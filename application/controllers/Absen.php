<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absen extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){
		parent::__construct();		
		$this->load->library('session');
		$this->load->model('m_login');
		$this->load->model('absensi_model');
		$this->load->model('func_global');
		$this->db_hrdonline = $this->load->database("hrdonline", TRUE);

		if($this->session->userdata('username') == ""){
			redirect('Welcome/index');
		}
	}
	
	public function index()
	{
		$this->load->view('absensi_view');
	}
	

	public function proses_checkin()
	{
		header('Content-Type: application/json');  
		date_default_timezone_set('Asia/Jakarta');
		
		$username = $this->session->userdata('username');
		$kode = $this->session->userdata('kode_finger');

		if (!$username) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Session Habis, Silakan Login Ulang!'
			]);
			exit;
		}

		// Ambil data JSON
		$input = json_decode(file_get_contents('php://input'), true);
		if (!$input) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Input tidak valid'
			]);
			exit;
		}

		$device_id = $input['device_id'];
		$fingerprint = $input['fingerprint'];
		$latitude    = $input['latitude'];
		$longitude   = $input['longitude'];
		$kd_kantor   = $input['kd_kantor'];
		$lat_lon_in = $latitude.",".$longitude;
		$proyekcurah = 0;
		$cekjarak = 1;

		$tgl_now = date('Y-m-d');
		$checkin = date('H:i:s');

		if ($latitude == "" || $longitude == "") {
			echo json_encode([
				'status' => 'error',
				'message' => 'Data koordinat Absensi tidak terdeteksi'
			]);
			exit;
		}

		// =======================
		// Ambil data kantor
		// =======================
		if($kd_kantor != ''){
			$dKantor = $this->db_hrdonline->query("SELECT * FROM m_kantor WHERE kd_kantor = '$kd_kantor'");
		} else {
			$dKantor = $this->db_hrdonline->query("
				SELECT c.koordinat_kantor, c.radius 
				FROM mas_peg a 
				LEFT JOIN m_unit b ON a.kd_unit = b.kd_unit
				LEFT JOIN m_kantor c ON b.kd_kantor = c.kd_kantor
				WHERE a.no_peg = '$username'
			");
		}

		// --- jab ---
		$qmaspeg = "select kd_jab,kd_job,kd_unit,device_id from mas_peg where no_peg = '$username'";
		$qdt = $this->db_hrdonline->query($qmaspeg)->result();
		$kd_jab = $qdt[0]->kd_jab;
		$kd_job = $qdt[0]->kd_job;
		$deviceid_maspeg = $qdt[0]->device_id;

		if($username == "KW97011" || $username == "KW08013" || $username == "KW98105" || $username == "KW16004" || $username == "OS25132"){
			$proyekcurah = 1;
		}

		if($deviceid_maspeg == ""  || $deviceid_maspeg == NULL){
			$queryUpdate="update mas_peg set device_id='".$device_id."' where no_peg = '$username'";
			$this->db->query($queryUpdate);
		}

		if ($dKantor->num_rows() == 0) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Data kantor tidak ditemukan'
			]);
			exit;
		}

		$rdt = $dKantor->row();
		$koordinatKantor = $rdt->koordinat_kantor;
		$radius          = $rdt->radius;

		$param = array();
		$param['koordinatKantor'] = $koordinatKantor;
		$param['latitude'] = $latitude;
		$param['longitude'] = $longitude;
		$meters = $this->absensi_model->hitungradius($param);

		//---khusus manager kadiv dan curah ----
		if($kd_jab < 30 || $kd_jab == 102 || $proyekcurah == "1"){
			$cekjarak = 0;
		}

		if ($meters > $radius && $cekjarak == 1) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Lokasi Anda di luar radius absensi: '.round($meters).' meter'
			]);
			exit;
		}

		$proyekcurah = 0;
		$ipaddress = '-';
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ipaddress=$_SERVER['HTTP_CLIENT_IP'];
		}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ipaddress=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ipaddress=$_SERVER['REMOTE_ADDR'];
		}
		
		if($username == "KW97011" || $username == "KW08013" || $username == "KW98105"){
			$proyekcurah = 1;
		}

		$cekdt = "SELECT COUNT(*) as dt_finger from t_finger_mobile WHERE no_peg = '$username' and tanggal='$tgl_now'";
		$rdt = $this->db_hrdonline->query($cekdt)->result();
		$dt_finger = $rdt[0]->dt_finger;
		if($dt_finger == 0){
			if($kd_job == "N12" || $kd_job == "B18" || $kd_job== "D42" || $kd_job == "C24" || $kd_job == "N11" || $kd_kantor == "K0075"){
				//---khusus satpam dan expedisi cargo---
				// --- insert t_finger_mobile ---
				$cekdevice = "SELECT COUNT(*) as jml_device from t_finger_mobile WHERE tanggal='$tgl_now' and device_id = '".$device_id."' and fingerjs='$fingerprint'";
				$rdt = $this->db_hrdonline->query($cekdevice)->result();
				$jml_device = $rdt[0]->jml_device;
				if($jml_device > 0){
					echo json_encode([
						'status' => 'error',
						'message' => 'Device ini sudah digunakan untuk absensi hari ini'
					]);
					exit;
				}
				else{
					$insert = "insert into db_hrd.t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',masuk='$checkin',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in',kd_kantor_in='$kd_kantor',device_id='".$device_id."',fingerjs='$fingerprint'";
					$this->db_hrdonline->query($insert);

					echo json_encode([
						'status' => 'success',
						'message' => 'Checkin Berhasil!'
					]);
					exit;
				}
			}
			else{
				if($checkin > '10:00:00'){
					echo json_encode([
						'status' => 'error',
						'message' => 'Absen Masuk Maksimal Jam 09.00'
					]);
					exit;
				}
				else{
					if($kd_kantor == 'K0001' || $kd_kantor == 'K0002' || $kd_kantor == 'K0025' || $kd_kantor == 'K0073'){
						$jam_masuk = "07:30:00";
						$jam_pulang = "16:30:00";
					}
					else{
						$jam_masuk = "08:00:00";
						$jam_pulang = "16:00:00";
					}

					if($checkin > $jam_masuk){
						$cekquery = "SELECT TIMEDIFF('$checkin', '$jam_masuk') AS terlambat";
						$rdt = $this->db->query($cekquery)->result();
						$terlambat = $rdt[0]->terlambat;
					}
					else{
						$terlambat = "";
					}

					//---cek device id ---
					$cekdevice = "SELECT COUNT(*) as jml_device from t_finger_mobile WHERE tanggal='$tgl_now' and device_id = '".$device_id."' and fingerjs='$fingerprint'";
					$rdt = $this->db_hrdonline->query($cekdevice)->result();
					$jml_device = $rdt[0]->jml_device;
					if($jml_device > 0){
						echo json_encode([
							'status' => 'error',
							'message' => 'Device ini sudah digunakan untuk absensi hari ini'
						]);
						exit;
					}
					else{
						// --- insert t_finger_mobile ---
						$insert = "insert into db_hrd.t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',shift_time_in='$jam_masuk',shift_time_out='$jam_pulang',masuk='$checkin',terlambat='$terlambat',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in',kd_kantor_in='$kd_kantor',device_id='".$device_id."',fingerjs='$fingerprint'";
						$this->db_hrdonline->query($insert);

						echo json_encode([
							'status' => 'success',
							'message' => 'Checkin Berhasil!'
						]);
						exit;
					}
				}
			}
			
		}
		else{
			echo json_encode([
				'status' => 'error',
				'message' => 'Anda Sudah Absen Masuk Hari Ini'
			]);
			exit;
		}
		
	}

	public function proses_checkout()
	{
		header('Content-Type: application/json');  
		date_default_timezone_set('Asia/Jakarta');
		
		$username = $this->session->userdata('username');
		$kode = $this->session->userdata('kode_finger');
		if (!$username) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Session Habis, Silakan Login Ulang!'
			]);
			exit;
		}

		// Ambil data JSON
		$input = json_decode(file_get_contents('php://input'), true);
		if (!$input) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Input tidak valid'
			]);
			exit;
		}

		$device_id = $input['device_id'];
		$fingerprint = $input['fingerprint'];
		$latitude    = $input['latitude'];
		$longitude   = $input['longitude'];
		$kd_kantor   = $input['kd_kantor'];
		$lat_lon_out = $latitude.",".$longitude;
		$cekjarak = 1;
		$proyekcurah=0;

		$ipaddress = '-';
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ipaddress=$_SERVER['HTTP_CLIENT_IP'];
		}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ipaddress=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
		$ipaddress=$_SERVER['REMOTE_ADDR'];
		}

		$tgl_now = date('Y-m-d');
		$checkout = date('H:i:s');

		if ($latitude == "" || $longitude == "") {
			echo json_encode([
				'status' => 'error',
				'message' => 'Data koordinat Absensi tidak terdeteksi'
			]);
			exit;
		}

		// =======================
		// Ambil data kantor
		// =======================
		if($kd_kantor != ''){
			$dKantor = $this->db_hrdonline->query("SELECT * FROM m_kantor WHERE kd_kantor = '$kd_kantor'");
		} else {
			$dKantor = $this->db_hrdonline->query("
				SELECT c.koordinat_kantor, c.radius 
				FROM mas_peg a 
				LEFT JOIN m_unit b ON a.kd_unit = b.kd_unit
				LEFT JOIN m_kantor c ON b.kd_kantor = c.kd_kantor
				WHERE a.no_peg = '$username'
			");
		}

		if ($dKantor->num_rows() == 0) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Data kantor tidak ditemukan'
			]);
			exit;
		}

		$rdt = $dKantor->row();
		$koordinatKantor = $rdt->koordinat_kantor;
		$radius          = $rdt->radius;

		// --- jab ---
		$qmaspeg = "select kd_jab,kd_job,kd_unit from mas_peg where no_peg = '$username'";
		$qdt = $this->db_hrdonline->query($qmaspeg)->result();
		$kd_jab = $qdt[0]->kd_jab;
		$kd_job = $qdt[0]->kd_job;
		if($username == "KW97011" || $username == "KW08013" || $username == "KW98105"){
			$proyekcurah = 1;
		}

		$param = array();
		$param['koordinatKantor'] = $koordinatKantor;
		$param['latitude'] = $latitude;
		$param['longitude'] = $longitude;
		$meters = $this->absensi_model->hitungradius($param);
		//---khusus manager kadiv dan curah ----
		if($kd_jab < 30 || $kd_jab == 102 || $proyekcurah == "1"){
			$cekjarak = 0;
		}

		if ($meters > $radius && $cekjarak == 1) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Lokasi Anda di luar radius absensi: '.round($meters).' meter'
			]);
			exit;
		}

		

		// =======================
		// Validasi Kemiripan
		// =======================
	
		if($kd_job == "N12" || $kd_job == "B18" || $kd_job== "D42" || $kd_job == "C24" || $kd_job == "N11" || $kd_kantor == "K0075"){
			//---khusus satpam dan expedisi cargo---
			$cekdt = "select * from t_finger_mobile where no_peg = '$username' and tanggal='$tgl_now'";
			$rows = $this->db_hrdonline->query($cek)->num_rows();
			if($rows == 1){
				$update = "update t_finger_mobile set keluar='$checkout',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out',device_id_out='".$device_id."',
				fingerjs_out='$fingerprint' where no_peg = '$username' and tanggal='$tgl_now'";
				$this->db_hrdonline->query($update);

				echo json_encode([
					'status' => 'success',
					'message' => 'Checkout Berhasil!'
				]);
				exit;
			}
			else{
				$tgl_sebelumnya = date('Y-m-d', strtotime($tgl_now . ' -1 day'));
				$cekdt = "select * from t_finger_mobile where no_peg = '$username' and tanggal='$tgl_sebelumnya'";
				$rows = $this->db_hrdonline->query($cek)->num_rows();
				if($rows == 1){
					$val = $this->db->query($cekdt)->result();
					$keluar = $val[0]->keluar;
					if($keluar == "" || $keluar == "(NULL)"){
						$update = "update t_finger_mobile set keluar='$checkout',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out',device_id_out='".$device_id."',
						fingerjs_out='$fingerprint' where no_peg = '$username' and tanggal='$tgl_sebelumnya'";
						$this->db_hrdonline->query($update);
					}
				}
				else{
					// --- insert t_finger_mobile ---
					$insert = "insert into db_hrd.t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',keluar='$checkout',kode='$kode',ip_address_out='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_out='$lat_lon_out',kd_kantor_out='$kd_kantor',device_id_out='".$device_id."',
					fingerjs_out='$fingerprint'";
					$this->db_hrdonline->query($insert);
				}

				echo json_encode([
					'status' => 'success',
					'message' => 'Checkout Berhasil!'
				]);
				exit;
			}
		}
		else{
			if($checkout < '12:00:00'){
				echo json_encode([
					'status' => 'error',
					'message' => 'Absen Keluar Harus Diatas Jam 12.00'
				]);
				exit;
			}
			else{
				if($kd_kantor == 'K0001' || $kd_kantor == 'K0002' || $kd_kantor == 'K0025' || $kd_kantor == 'K0073'){
					$jam_masuk = "07:30:00";
					$jam_pulang = "16:30:00";
				}
				else{
					$jam_masuk = "08:00:00";
					$jam_pulang = "16:00:00";
				}

				if($checkout > $jam_pulang){
					$cekquery = "SELECT TIMEDIFF('$checkout', '$jam_pulang') AS lembur";
					$rdt = $this->db->query($cekquery)->result();
					$lembur = $rdt[0]->lembur;
					$pulangcepat = "";
				}
				else{
					$cekquery = "SELECT TIMEDIFF('$jam_pulang', '$checkout') AS pulangcepat";
					$rdt = $this->db->query($cekquery)->result();
					$pulangcepat = $rdt[0]->pulangcepat;
					$lembur = "";
				}
				
				$cekdt = "SELECT COUNT(*) as dt_finger from t_finger_mobile WHERE no_peg = '$username' and tanggal='$tgl_now'";
				$rdt = $this->db_hrdonline->query($cekdt)->result();
				$dt_finger = $rdt[0]->dt_finger;
				if($dt_finger == 0){
					// --- insert t_finger_mobile ---
					//---cek device id ---
					$cekdevice = "SELECT COUNT(*) as jml_device from t_finger_mobile WHERE tanggal='$tgl_now' and device_id = '".$device_id."' and fingerjs='$fingerprint'";
					$rdt = $this->db_hrdonline->query($cekdevice)->result();
					$jml_device = $rdt[0]->jml_device;
					if($jml_device > 0){
						echo json_encode([
							'status' => 'error',
							'message' => 'Device ini sudah digunakan untuk absensi hari ini'
						]);
						exit;
					}
					else{
						$insert = "insert into db_hrd.t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',shift_time_in='$jam_masuk',shift_time_out='$jam_pulang',keluar='$checkout',kode='$kode',ip_address_out='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_out='$lat_lon_out',kd_kantor_out='$kd_kantor',plg_cepat='$pulangcepat',lembur='$lembur',device_id_out='".$device_id."',
						fingerjs_out='$fingerprint'";
						$this->db_hrdonline->query($insert);
							echo json_encode([
							'status' => 'success',
							'message' => 'Checkout Berhasil!'
						]);
						exit;
					}
				}
				else{
					// $cekdevice = "SELECT COUNT(*) as jml_device from t_finger_mobile WHERE tanggal='$tgl_now' and device_id_out = '".$device_id."' and fingerjs_out='$fingerprint'";
					// $rdt = $this->db_hrdonline->query($cekdevice)->result();
					// $jml_device = $rdt[0]->jml_device;
					// if($jml_device > 0){
					// 	echo json_encode([
					// 		'status' => 'error',
					// 		'message' => 'Device ini sudah digunakan untuk absensi hari ini'
					// 	]);
					// 	exit;
					// }
					// else{
						$update = "update t_finger_mobile set keluar='$checkout',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out',device_id_out='".$device_id."',
						fingerjs_out='$fingerprint',plg_cepat='$pulangcepat',lembur='$lembur' where no_peg = '$username' and tanggal='$tgl_now'";
						$this->db_hrdonline->query($update);
							echo json_encode([
							'status' => 'success',
							'message' => 'Checkout Berhasil!'
						]);
						exit;
					// }
				}
			}
		}
			
		
	}


	
}
