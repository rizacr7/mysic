<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finger extends CI_Controller {

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
		$this->load->model('m_finger');
		$this->load->model('func_global');
		$this->db_hrdonline = $this->load->database("hrdonline", TRUE);

		if($this->session->userdata('username') == ""){
			redirect('Welcome/index');
		}
	}
	
	function view_dtfinger(){
		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('finger/data_absen');	
		$this->load->view('general/footer');	
	}
	
	function view_gps(){
		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('finger/vgps');	
		$this->load->view('general/footer');	
	}
	
	function daftar_faceid(){
		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('finger/vdaftar');	
		$this->load->view('general/footer');	
	}
	
	// data table ---
    function tab_finger_backup() {
		$username = $this->session->userdata('username');
		$bulan = $_POST['bulan'];
		$tahun = $_POST['tahun'];

		$paramdt= array();
		$paramdt['username'] = $username;
		$paramdt['bulan'] = $bulan;
		$paramdt['tahun'] = $tahun;
		
        echo "<table id='table_finger' class='table dt-responsive table-striped' width='100%'>
		<thead>
			<tr class='info'>
				<th>TANGGAL</th>
				<th>CHECK IN</th>
				<th>CHECK OUT</th>
			</tr>
		</thead>
		<tbody>";
        $no = 1;
        $data = $this->m_finger->get_data_finger("", "", "", 0, 0,$paramdt);
        foreach ($data->result_array() as $key => $value) {
            echo "
			<tr>
				<td>" . $this->func_global->dsql_tgl($value['tanggal']) . "</td>
				<td>" . $value['masuk'] . "</td>
				<td>" . $value['keluar'] . "</td>
			</tr>";
            $no++;
        }

        echo "</tbody>
		</table>
		<style>
			.even.selected td {
				background-color: #DCDCDC; !important;
			}
			.odd.selected td {
				background-color: #DCDCDC; !important;
			}

		</style>
		<script>
			$('#table_finger').dataTable({
				select: {style: 'single'}
			});
		</script>";
    }

	function tab_finger() {
		$no_peg = $this->session->userdata('username');
		$bulan = $_POST['bulan'];
		$tahun = $_POST['tahun'];

		$paramdt= array();
		$paramdt['username'] = $no_peg;
		$paramdt['bulan'] = $bulan;
		$paramdt['tahun'] = $tahun;

		$cekpegawai = "select a.*,b.nm_unit,b.kd_kantor,a.no_peg_lm from mas_peg_backup a left join m_unit b on a.kd_unit = b.kd_unit where a.no_peg = '$no_peg'";
		$rpeg = $this->db_hrdonline->query($cekpegawai)->result();
		$nama = $rpeg[0]->na_peg;
		$nm_unit = $rpeg[0]->nm_unit;
		$kd_kantor = $rpeg[0]->kd_kantor;
		$id_finger = $rpeg[0]->id_finger;
		$no_peg_lm = $rpeg[0]->no_peg_lm;
		$kd_kantor_in = "";

		
        echo "
			<table id='table_finger' class='table mb-0 table-striped table-bordered' width='100%'>
			<tr>
				<td colspan='7'><b>".$no_peg." - ".$nama."</b></td>
			</tr>
			<tr>
				<td colspan='7'><b>".$nm_unit."</b></td>
			</tr>
			<tr class='info'>
				<th style='background-color:#0C519D'><font color=white>Tanggal</font></th>
				<th style='background-color:#0C519D'><font color=white>Hari</font></th>
				<th style='background-color:#0C519D'><font color=white>Checkin</font></th>
				<th style='background-color:#0C519D'><font color=white>Checkout</font></th>
				<th style='background-color:#0C519D'><font color=white>Terlambat</font></th>
				<th style='background-color:#0C519D'><font color=white>Keterangan</font></th>
			</tr>
			<tbody>";
		
		$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
		for($i=1;$i<=$jumlah_hari;$i++){
			if($i<10){
				$i="0".$i;
			}
			$tanggal = $i."-".$bulan."-".$tahun;
			$tgl_dsql = $tahun."-".$bulan."-".$i;
			
			$daftar_hari = array(
			 'Sunday' => 'Minggu',
			 'Monday' => 'Senin',
			 'Tuesday' => 'Selasa',
			 'Wednesday' => 'Rabu',
			 'Thursday' => 'Kamis',
			 'Friday' => 'Jumat',
			 'Saturday' => 'Sabtu'
			);
		
			$namahari = date('l', strtotime($tgl_dsql));
			
			$cekfinger = "select * from t_finger_mobile where no_peg = '$no_peg' and tanggal = '$tgl_dsql'";
			
			$rdt = $this->db_hrdonline->query($cekfinger)->num_rows();
			if($rdt == 0){
				// --- cek sppd ---
				$l = $i+1;
				$tgl_spd = $tahun."-".$bulan."-".$l;

				if($no_peg_lm == ""){
					$qsppd = "SELECT bukti,SUBSTR(bukti,1,2) AS kode,TGL_AWAL,TGL_AKHIR,'' as kd_cuti FROM t_sppd WHERE no_peg = '$no_peg' AND HAPUS = 0 AND TGL_AWAL <= '$tgl_dsql' AND TGL_AKHIR >='$tgl_dsql'
					UNION
					SELECT no_bukti,SUBSTR(no_bukti,1,2) AS kode,tgl_awal,tgl_akhir,kd_cuti FROM t_cuti WHERE no_peg = '$no_peg' AND is_del = 0 AND tgl_awal <= '$tgl_dsql' AND tgl_akhir >='$tgl_dsql'";
				}
				else{
					$qsppd = "SELECT bukti,SUBSTR(bukti,1,2) AS kode,TGL_AWAL,TGL_AKHIR,'' as kd_cuti FROM t_sppd WHERE (no_peg = '$no_peg' OR no_peg = '$no_peg_lm') AND HAPUS = 0 AND TGL_AWAL <= '$tgl_dsql' AND TGL_AKHIR >='$tgl_dsql'
					UNION
					SELECT no_bukti,SUBSTR(no_bukti,1,2) AS kode,tgl_awal,tgl_akhir,kd_cuti FROM t_cuti WHERE (no_peg = '$no_peg' OR no_peg = '$no_peg_lm') AND is_del = 0 AND tgl_awal <= '$tgl_dsql' AND tgl_akhir >='$tgl_dsql'";
				}
				
				$rdt = $this->db_hrdonline->query($qsppd)->num_rows();
				if($rdt == 0){
					// --- cek hari libur nasional ---
					$tgl_libur = $tahun."-".$bulan."-".$i;

					$qlibur = "SELECT * FROM m_libur WHERE tgl_libur = '$tgl_libur' and is_del = 0";
					$rdata = $this->db_hrdonline->query($qlibur)->num_rows();
					if($rdata != 0){
						$dlibur = $this->db_hrdonline->query($qlibur)->result();
						$keteranganLibur = $dlibur[0]->keterangan;
						
						$chekcin = "";
						$chekcout = "";
						$terlambat = "";
						$libur = "1";
					}
					else{
						$qtukar = "SELECT * FROM t_tukar_libur WHERE tgl_ganti = '$tgl_libur' and no_peg = '$no_peg'";
						$rdata = $this->db_hrdonline->query($qtukar)->num_rows();
						if($rdata != 0){
							$dlibur = $this->db_hrdonline->query($qtukar)->result();
							$keteranganLibur = "TUKAR HARI LIBUR ".$dlibur[0]->tgl_libur;

							$chekcin = "";
							$chekcout = "";
							$terlambat = "";
							$libur = "2";
						}
						else{
							$chekcin = "";
							$chekcout = "";
							$terlambat = "";
							$libur = "0";
						}
						
					}
					
				}
				else{
					$dtFinger = $this->db_hrdonline->query($qsppd)->result();
					$kode = $dtFinger[0]->kode;
					$tgl_awal = $dtFinger[0]->TGL_AWAL;
					$tgl_akhir = $dtFinger[0]->TGL_AKHIR;

					if($kode == "SP"){
						$chekcin = "SPPD";
					}
					else{
						if($dtFinger[0]->kd_cuti == "DC"){
							$chekcin = "DC";
						}
						else if($dtFinger[0]->kd_cuti == "DP"){
							$chekcin = "DISPENSASI";
						}
						else{	
							$chekcin = "CUTI";
						}
						
					}	
					
					$chekcout = "";
					$terlambat = "";
				}
			}
			else{
				$l = $i+1;
				
				if($no_peg_lm == ""){
					$qsppd = "SELECT bukti,SUBSTR(bukti,1,2) AS kode,TGL_AWAL,TGL_AKHIR,'' as kd_cuti FROM t_sppd WHERE no_peg = '$no_peg' AND HAPUS = 0 AND TGL_AWAL <= '$tgl_dsql' AND TGL_AKHIR >='$tgl_dsql'
					UNION
					SELECT no_bukti,SUBSTR(no_bukti,1,2) AS kode,tgl_awal,tgl_akhir,kd_cuti FROM t_cuti WHERE no_peg = '$no_peg' AND is_del = 0 AND tgl_awal <= '$tgl_dsql' AND tgl_akhir >='$tgl_dsql'";
				}
				else{
					$qsppd = "SELECT bukti,SUBSTR(bukti,1,2) AS kode,TGL_AWAL,TGL_AKHIR,'' as kd_cuti FROM t_sppd WHERE (no_peg = '$no_peg' OR no_peg = '$no_peg_lm') AND HAPUS = 0 AND TGL_AWAL <= '$tgl_dsql' AND TGL_AKHIR >='$tgl_dsql'
					UNION
					SELECT no_bukti,SUBSTR(no_bukti,1,2) AS kode,tgl_awal,tgl_akhir,kd_cuti FROM t_cuti WHERE (no_peg = '$no_peg' OR no_peg = '$no_peg_lm') AND is_del = 0 AND tgl_awal <= '$tgl_dsql' AND tgl_akhir >='$tgl_dsql'";
				}
				$rdt = $this->db_hrdonline->query($qsppd)->num_rows();
				
				if($rdt == 0){
					// --- cek hari libur nasional ---
					$tgl_libur = $tahun."-".$bulan."-".$i;
					$qlibur = "SELECT * FROM m_libur WHERE tgl_libur = '$tgl_libur' and is_del = 0";
					$rdata = $this->db_hrdonline->query($qlibur)->num_rows();

					if($rdata == 0){ // --- jika tdk ada hari libur ---
						$dt = $this->db_hrdonline->query($cekfinger)->result();
						$chekcin = $dt[0]->masuk;
						$chekcout = $dt[0]->keluar;
						$terlambat = $dt[0]->terlambat;
						$kd_kantor_in = $dt[0]->kd_kantor_in;

						$lat_lon_in = $dt[0]->lat_lon_in;
						$lat_lon_out = $dt[0]->lat_lon_out;
						$lembur = 0;
					}
					else{ // --- jika hari libur nasional ---
						$qtukar = "SELECT * FROM t_tukar_libur WHERE tgl_libur = '$tgl_libur' AND no_peg = '$no_peg'";
						$rdata = $this->db_hrdonline->query($qtukar)->num_rows();
						if($rdata != 0){
							$dt = $this->db_hrdonline->query($cekfinger)->result();
							$chekcin = $dt[0]->masuk;
							$chekcout = $dt[0]->keluar;
							$terlambat = $dt[0]->terlambat;
							$kd_kantor_in = $dt[0]->kd_kantor_in;

							$lat_lon_in = $dt[0]->lat_lon_in;
							$lat_lon_out = $dt[0]->lat_lon_out;
							$lembur = 2;
						}
						else{
							$dt = $this->db_hrdonline->query($cekfinger)->result();
							$chekcin = $dt[0]->masuk;
							$chekcout = $dt[0]->keluar;
							$terlambat = '';
							$kd_kantor_in = $dt[0]->kd_kantor_in;

							$lat_lon_in = $dt[0]->lat_lon_in;
							$lat_lon_out = $dt[0]->lat_lon_out;
							$lembur = 1;
						}
					}
					
				}
				else{
					// -- sppd & cuti ---
					$dtFinger = $this->db_hrdonline->query($qsppd)->result();
					$kode = $dtFinger[0]->kode;
					$tgl_awal = $dtFinger[0]->TGL_AWAL;
					$tgl_akhir = $dtFinger[0]->TGL_AKHIR;
		
					if($kode == "SP"){
						$chekcin = "SPPD";
					}
					else{
						if($dtFinger[0]->kd_cuti == "DC"){
							$chekcin = "DC";
						}
						else if($dtFinger[0]->kd_cuti == "DP"){
							$chekcin = "DISPENSASI";
						}
						else{	
							$chekcin = "CUTI";
						}
					}	
					
					$chekcout = "";
					$terlambat = "";
				}
				
			}

			if($daftar_hari[$namahari] == "Minggu"){
				$keterangan = "LIBUR";
			}
			else{
				if($daftar_hari[$namahari] == "Sabtu" && $kd_kantor == "K0001" || $daftar_hari[$namahari] == "Sabtu" && $kd_kantor == "K0002"){
					$keterangan = "LIBUR";
				}
				else{
					if($chekcin == '' && $chekcout != ''){
						$keterangan = "TIDAK CHECKIN";
					}
					else if($chekcin != '' && $chekcout == ''){
						if($chekcin == "CUTI"){
							$keterangan = "CUTI";
						}
						else if($chekcin == "DISPENSASI"){
							$keterangan = "DISPENSASI";
						}
						else if($chekcin == "SPPD"){
							$keterangan = "SPPD";
						}
						else{
							$keterangan = "TIDAK CHECKOUT";
						}
					}
					else if($chekcin == '' && $chekcout == '' && $libur == '1'){
						$keterangan = $keteranganLibur;
					}
					else if($chekcin == '' && $chekcout == '' && $libur == '2'){
						$keterangan = $keteranganLibur;
					}
					else if($chekcin == '' && $chekcout == ''){
						$keterangan = "TIDAK ABSEN";
					}
					else{
						if($lembur == 1){
							$keterangan = "LEMBUR";
						}
						else{
							$keterangan = "";
						}
						
					}
					
				}
				
			}

			if($kd_kantor_in == ""){
				$kantor = $kd_kantor;
			}
			else{
				$kantor = $kd_kantor_in;
			}

			//---- cek kantor ---
			$qkantor = "select nm_kantor from m_kantor where kd_kantor = '$kantor'";
			$kd = $this->db_hrdonline->query($qkantor)->result();
			
			if($keterangan == "" || $keterangan == "TIDAK CHECKOUT" || $keterangan == "TIDAK CHECKIN"){
				$nmKantor = $kd[0]->nm_kantor;
			}
			else{
				$nmKantor = "";
			}

			if($keterangan == "TIDAK ABSEN"){
				$keterangan = "<span class='m-1 badge rounded-pill bg-danger'>Tidak Absen</span>";
			}
			else if($keterangan == "LIBUR"){
				$keterangan = "<span class='m-1 badge rounded-pill bg-info'>Libur</span>";
			}
			else if($keterangan == "CUTI"){
				$keterangan = "<span class='m-1 badge rounded-pill bg-warning'>Cuti</span>";
			}
			else if($keterangan == "SPPD"){
				$keterangan = "<span class='m-1 badge rounded-pill bg-primary'>Sppd</span>";
			}
			else if($keterangan == "TIDAK CHECKIN"){
				$keterangan = "<span class='m-1 badge rounded-pill bg-warning'>Tidak Checkin</span>";
			}
			else{
				$keterangan = "<span class='m-1 badge rounded-pill bg-success'>".$keterangan."</span>";
			}

			echo "
			<tr>
				<td align='center'>".$tanggal."</td>
				<td align='center'>".$daftar_hari[$namahari]."</td>
				<td>".$chekcin."</td>
				<td>".$chekcout."</td>
				<td>".$terlambat."</td>
				<td>".$keterangan."</td>
			</tr>";
		}
		
		 echo "</tbody>
		</table>";
    }
	

	// function cekin_finger(){
	// 	date_default_timezone_set('Asia/Jakarta');
	// 	$username = $this->session->userdata('username');
	// 	$kode = $this->session->userdata('kode_finger');
	// 	$tgl_now = date('Y-m-d');
	// 	$checkin = date('H:i:s');
	// 	$proyekcurah = 0;
	// 	$latitude = $_POST['lat'];
	// 	$longitude = $_POST['long'];
	// 	//$radius = 700;
	// 	$lat_lon_in = $latitude.",".$longitude;

	// 	$device_id = trim($this->input->post('device_id'));
	// 	// echo $device_id;

	// 	if (!$device_id) {
	// 		log_message('error', "Device ID kosong untuk user $username");
	// 		echo 7; // device tidak valid
	// 		return;
	// 	}

	// 	// cek apakah user sudah punya device
	// 	$sql_cek = "SELECT * FROM mas_peg_backup WHERE no_peg = '$username'";
	// 	$cekDevice = $this->db->query($sql_cek, [$username])->row();

	// 	if ($cekDevice) {
	// 		if (trim($cekDevice->device_id) != $device_id) {
	// 			log_message('error', "Device tidak sesuai: DB={$cekDevice->device_id} POST=$device_id");
	// 			echo 7; // device tidak sesuai
	// 			return;
	// 		}
	// 	} else {
	// 		// belum ada, simpan device baru
	// 		$sql_insert = "INSERT INTO mas_peg_backup (no_peg, device_id, created_at) VALUES (?, ?, ?)";
	// 		$this->db->query($sql_insert, [$username, $device_id, date('Y-m-d H:i:s')]);
	// 	}


		
	// 	$ipaddress = '-';
	// 	if (!empty($_SERVER['HTTP_CLIENT_IP'])){
	// 	$ipaddress=$_SERVER['HTTP_CLIENT_IP'];
	// 	}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	// 	$ipaddress=$_SERVER['HTTP_X_FORWARDED_FOR'];
	// 	}else{
	// 	$ipaddress=$_SERVER['REMOTE_ADDR'];
	// 	}

	// 	// --- cek lokasi kantor ---
	// 	$kd_kantor = $_POST['kd_kantor'];

	// 	if($username == ""){
	// 		echo 6;
	// 	}
	// 	else{
	// 	if($kd_kantor != ''){
	// 		$dKantor = "select * from m_kantor where kd_kantor = '$kd_kantor'";
	// 		$rdt = $this->db_hrdonline->query($dKantor)->result();
	// 		$koordinatKantor = $rdt[0]->koordinat_kantor;
	// 		$radius = $rdt[0]->radius;

	// 		// --- jab ---
	// 		$qmaspeg = "select kd_jab,kd_job,kd_unit from mas_peg_backup where no_peg = '$username'";
	// 		$qdt = $this->db_hrdonline->query($qmaspeg)->result();
	// 		$kd_jab = $qdt[0]->kd_jab;
	// 		$kd_job = $qdt[0]->kd_job;
			
	// 		if($username == "KW97011" || $username == "KW08013" || $username == "KW98105"){
	// 			$proyekcurah = 1;
	// 		}
	// 	}
	// 	else{
	// 		$qfinger = "select a.id_finger,b.kd_kantor,c.nm_kantor,c.koordinat_kantor,a.kd_jab,c.radius,a.kd_job from mas_peg_backup a 
	// 		left join m_unit b on a.kd_unit = b.kd_unit
	// 		left join m_kantor c on b.kd_kantor = c.kd_kantor
	// 		where a.no_peg = '$username'";
	// 		$rdt = $this->db_hrdonline->query($qfinger)->result();
	// 		$koordinatKantor = $rdt[0]->koordinat_kantor;
	// 		$kd_jab = $rdt[0]->kd_jab;
	// 		$radius = $rdt[0]->radius;
	// 		$kd_job = $rdt[0]->kd_job;
	// 	}
		
	// 	$str = explode(",", $koordinatKantor);
	// 	$lat1 = $str[0];
	// 	$lon1 = $str[1];

	// 	// --- koordinat absen ---
	// 	$lat2 = $latitude;
	// 	$lon2 = $longitude;

	// 	// if($username == "KW16004"){
	// 	// 	$lat1 =-6.392984937751868;
	// 	// 	$lon1 = 107.42884972412115;

	// 	// 	$lat2 = -6.3929788;
	// 	// 	$lon2 = 107.4288052;
	// 	// }

	// 	// --- rumus menghitung jarak ---
	// 	$theta = $lon1 - $lon2;
	// 	$miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
	// 	$miles = acos($miles);
	// 	$miles = rad2deg($miles);
	// 	$miles = $miles * 60 * 1.1515;
	// 	$feet = $miles * 5280;
	// 	$yards = $feet / 3;
	// 	$kilometers = round($miles * 1.609344,2);
	// 	$meters = $kilometers * 1000;

	// 	// --- khusus manager & kadiv---
	// 	if($kd_jab < 30 || $kd_jab == 102 || $proyekcurah == "1"){
	// 		$cekdt = "select COUNT(*) as dt_finger from t_finger_mobile where no_peg = '$username' and tanggal='$tgl_now'";
	// 		$rdt = $this->db_hrdonline->query($cekdt)->result();
	// 		$dt_finger = $rdt[0]->dt_finger;
	// 		if($dt_finger == 0){	
	// 			if($kd_kantor == 'K0001' || $kd_kantor == 'K0002' || $kd_kantor == 'K0025' || $kd_kantor == 'K0073'){
	// 				if($checkin > '07:31:00'){ 
	// 					$cekquery = "SELECT TIMEDIFF('$checkin', '07:31:00') AS terlambat";
	// 					$rdt = $this->db->query($cekquery)->result();
	// 					$terlambat = $rdt[0]->terlambat;

	// 					$insert = "insert into t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',masuk='$checkin',terlambat='$terlambat',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in'";
	// 					$this->db_hrdonline->query($insert);
	// 				}
	// 				else{
	// 					$insert = "insert into t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',masuk='$checkin',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in'";
	// 					$this->db_hrdonline->query($insert);
	// 				}
	// 			}
	// 			else{ // --- khusus pbb --
	// 				if($checkin > '08:01:00'){ 
	// 					$cekquery = "SELECT TIMEDIFF('$checkin', '08:01:00') AS terlambat";
	// 					$rdt = $this->db_hrdonline->query($cekquery)->result();
	// 					$terlambat = $rdt[0]->terlambat;

	// 					$insert = "insert into t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',masuk='$checkin',terlambat='$terlambat',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in'";
	// 					$this->db_hrdonline->query($insert);
	// 				}
	// 				else{
	// 					$insert = "insert into t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',masuk='$checkin',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in'";
	// 					$this->db_hrdonline->query($insert);
	// 				}
	// 			}
				
	// 			$this->db_hrdonline->close();
	// 			echo 1;
	// 		}
	// 		else{
	// 			echo 2;
	// 		}
	// 	}
	// 	else{
	// 		if($meters > $radius){
	// 			echo 5;
	// 		}
	// 		else{
	// 			$qbagian = "select b.kd_bagian from mas_peg_backup a left join m_unit b on a.kd_unit = b.kd_unit where a.no_peg= '$username'";
	// 			$rdt = $this->db_hrdonline->query($qbagian)->result();
	// 			$kdbagian = $rdt[0]->kd_bagian;
				
	// 			$cekdt = "select COUNT(*) as dt_finger from t_finger_mobile where no_peg = '$username' and tanggal='$tgl_now'";
	// 			$rdt = $this->db_hrdonline->query($cekdt)->result();
	// 			$dt_finger = $rdt[0]->dt_finger;

	// 			if($dt_finger == 0){	
	// 				// -- khusus satpam & eksp.cargo----
	// 				if($username == "KW98118" || $username == "KW16029" || $username == "KW99009" || $username == "KW98057" || $username == "KW97035" || $username == "KW97026" || $username == "KW04001" || $username == "KW94100" || $username == "KW16029" || $username == "KW98118" || $username == "KW00003" || $username == "TT13320" || $username == "OS25153" || $username == "KW02029" || $kd_job == "N12" || $kd_job == "B18" || $kd_kantor == "K0075" || $username == "KW99013" || $username == "TK25002"){
	// 					$insert = "insert into t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',masuk='$checkin',terlambat='$terlambat',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in'";
	// 					$this->db_hrdonline->query($insert);
	// 					echo 1;
	// 				}
	// 				else{
	// 					// --- insert hrd online ---
	// 					if($checkin > '18:00:00'){
	// 						echo 3;
	// 					}
	// 					else{
	// 						if($kd_kantor == 'K0001' || $kd_kantor == 'K0002' || $kd_kantor == 'K0025' || $kd_kantor == 'K0073'){
	// 							if($checkin > '07:31:00'){ 
	// 								$cekquery = "SELECT TIMEDIFF('$checkin', '07:31:00') AS terlambat";
	// 								$rdt = $this->db->query($cekquery)->result();
	// 								$terlambat = $rdt[0]->terlambat;
			
	// 								$insert = "insert into t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',masuk='$checkin',terlambat='$terlambat',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in'";
	// 								$this->db_hrdonline->query($insert);
	// 							}
	// 							else{
	// 								$insert = "insert into t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',masuk='$checkin',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in'";
	// 								$this->db_hrdonline->query($insert);
	// 							}
	// 						}
	// 						else{ // --- khusus pbb --
	// 							if($checkin > '08:01:00'){ 
	// 								$cekquery = "SELECT TIMEDIFF('$checkin', '08:01:00') AS terlambat";
	// 								$rdt = $this->db->query($cekquery)->result();
	// 								$terlambat = $rdt[0]->terlambat;
			
	// 								$insert = "insert into t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',masuk='$checkin',terlambat='$terlambat',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in'";
	// 								$this->db_hrdonline->query($insert);
	// 							}
	// 							else{
	// 								$insert = "insert into t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',masuk='$checkin',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in'";
	// 								$this->db_hrdonline->query($insert);
	// 							}
	// 						}
	// 						$this->db_hrdonline->close();
	// 						echo 1;
	// 					}
	// 				}
	// 			}
	// 			else{
	// 				echo 2;
	// 			}
	// 		}
	// 	}
	// 	}
	// }
	function cekin_finger() {
    date_default_timezone_set('Asia/Jakarta');

    $username = $this->session->userdata('username');
    $kode = $this->session->userdata('kode_finger');
    $tgl_now = date('Y-m-d');
    $checkin = date('H:i:s');
    $proyekcurah = 0;

    $latitude = $this->input->post('lat');
    $longitude = $this->input->post('long');
    $kd_kantor = $this->input->post('kd_kantor');
    $lat_lon_in = $latitude . "," . $longitude;

    // ambil device_id tapi tidak dicek
    $device_id = trim($this->input->post('device_id'));

	    if (!$device_id) {
            echo 7; // device tidak valid
            return;
        }

  	$sql = "SELECT * FROM mas_peg_backup WHERE no_peg = ? LIMIT 1";
    $cekDevice = $this->db->query($sql, array($username))->row();

	// $sqlInsert = "INSERT INTO mas_peg_backup (no_peg, device_id) VALUES (?, ?)";
    // $this->db->query($sqlInsert, array($username, $device_id));
	if ($cekDevice) {
		if (trim($cekDevice->device_id) == "") {
			// Update device_id lama dengan yang baru
			$sqlUpdate = "UPDATE mas_peg_backup SET device_id = ? WHERE no_peg = ?";
			$this->db->query($sqlUpdate, array($device_id, $username));
		} 
	} else {
		// Jika user belum ada sama sekali di tabel, buat baris baru
		$sqlUpdate = "UPDATE mas_peg_backup SET device_id = ? WHERE no_peg = ?";
		$this->db->query($sqlUpdate, array($device_id, $username));
	}

	
    // ambil IP
    $ipaddress = '-';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif (!empty($_SERVER['REMOTE_ADDR'])) $ipaddress = $_SERVER['REMOTE_ADDR'];

	if($cekDevice->device_id != $device_id){
		echo 7; // device tidak sesuai
		return;
	}

    if (!$username) {
        echo 6; // user tidak terdeteksi
        return;
    }
    // ambil info kantor
    if ($kd_kantor) {
        $dKantor = "SELECT koordinat_kantor, radius FROM m_kantor WHERE kd_kantor = ?";
        $rdt = $this->db_hrdonline->query($dKantor, array($kd_kantor))->row();
        $koordinatKantor = $rdt->koordinat_kantor;
        $radius = $rdt->radius;

        $qmaspeg = "SELECT kd_jab, kd_job, kd_unit FROM mas_peg_backup WHERE no_peg = ?";
        $qdt = $this->db_hrdonline->query($qmaspeg, array($username))->row();
        $kd_jab = $qdt->kd_jab;
        $kd_job = $qdt->kd_job;

        if (in_array($username, array("KW97011","KW08013","KW98105"))) {
            $proyekcurah = 1;
        }
    } else {
        $qfinger = "SELECT a.kd_jab, a.kd_job, c.koordinat_kantor, c.radius 
                    FROM mas_peg_backup a
                    LEFT JOIN m_unit b ON a.kd_unit = b.kd_unit
                    LEFT JOIN m_kantor c ON b.kd_kantor = c.kd_kantor
                    WHERE a.no_peg = ?";
        $rdt = $this->db_hrdonline->query($qfinger, array($username))->row();
        $koordinatKantor = $rdt->koordinat_kantor;
        $kd_jab = $rdt->kd_jab;
        $kd_job = $rdt->kd_job;
        $radius = $rdt->radius;
    }

    // Hitung jarak
    $arr = explode(",", $koordinatKantor);
    $lat1 = $arr[0];
    $lon1 = $arr[1];
    $lat2 = $latitude;
    $lon2 = $longitude;

    $theta = $lon1 - $lon2;
    $miles = acos(
        sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta))
    );
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $kilometers = $miles * 1.609344;
    $meters = $kilometers * 1000;

    // Cek apakah sudah absen
    $cekdt = "SELECT COUNT(*) AS dt_finger FROM t_finger_mobile WHERE no_peg = ? AND tanggal = ?";
    $rdt = $this->db_hrdonline->query($cekdt, array($username, $tgl_now))->row();
    $dt_finger = $rdt->dt_finger;

    if ($dt_finger > 0) {
        echo 2; // sudah check in
        return;
    }

    // Cek radius (manager/kadiv/proyekcurah bisa lewati)
    if (!($kd_jab < 30 || $kd_jab == 102 || $proyekcurah == 1)) {
        if ($meters > $radius) {
            echo 5; // di luar radius
            return;
        }
    }

    // Hitung terlambat
    $terlambat = null;
    if (($kd_kantor == 'K0001' || $kd_kantor == 'K0002' || $kd_kantor == 'K0025' || $kd_kantor == 'K0073') && $checkin > '07:31:00') {
        $cekquery = "SELECT TIMEDIFF(?, '07:31:00') AS terlambat";
        $rdt = $this->db->query($cekquery, array($checkin))->row();
        $terlambat = $rdt->terlambat;
    } elseif ($checkin > '08:01:00') {
        $cekquery = "SELECT TIMEDIFF(?, '08:01:00') AS terlambat";
        $rdt = $this->db->query($cekquery, array($checkin))->row();
        $terlambat = $rdt->terlambat;
    }

    // Insert absen
    $insert = "INSERT INTO t_finger_mobile 
        (no_peg, tanggal, masuk, terlambat, kode, ip_address_in, latitude, longitude, lat_lon_in,device_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $this->db_hrdonline->query($insert, array(
        $username, $tgl_now, $checkin, $terlambat, $kode, $ipaddress, $latitude, $longitude, $lat_lon_in, $device_id
    ));

    $this->db_hrdonline->close();
    echo 1; // sukses check in
}


	
	function cekout_finger(){
		date_default_timezone_set('Asia/Jakarta');
		$username = $this->session->userdata('username');
		$kode = $this->session->userdata('kode_finger');
		$tgl_now = date('Y-m-d');
		$jamnow = date('H:i:s');
		
		$ipaddress = '-';
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ipaddress=$_SERVER['HTTP_CLIENT_IP'];
		}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ipaddress=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
		$ipaddress=$_SERVER['REMOTE_ADDR'];
		}

		$latitude = $_POST['lat'];
		$longitude = $_POST['long'];
		//$radius = 700;
		$lat_lon_out = $latitude.",".$longitude;

		// --- cek lokasi kantor ---
		$kd_kantor = $_POST['kd_kantor'];
		if($kd_kantor != ''){
			$dKantor = "select * from m_kantor where kd_kantor = '$kd_kantor'";
			$rdt = $this->db_hrdonline->query($dKantor)->result();
			$koordinatKantor = $rdt[0]->koordinat_kantor;
			$radius = $rdt[0]->radius;

			// --- jab ---
			$qmaspeg = "select kd_jab,kd_job from mas_peg_backup where no_peg = '$username'";
			$qdt = $this->db_hrdonline->query($qmaspeg)->result();
			$kd_jab = $qdt[0]->kd_jab;
			$kd_job = $qdt[0]->kd_job;
		}
		else{
			$qfinger = "select a.id_finger,b.kd_kantor,c.nm_kantor,c.koordinat_kantor,a.kd_jab,c.radius,a.kd_job from mas_peg_backup a 
			left join m_unit b on a.kd_unit = b.kd_unit
			left join m_kantor c on b.kd_kantor = c.kd_kantor
			where a.no_peg = '$username'";
			$rdt = $this->db_hrdonline->query($qfinger)->result();
			$koordinatKantor = $rdt[0]->koordinat_kantor;
			$kd_jab = $rdt[0]->kd_jab;
			$radius = $rdt[0]->radius;
			$kd_job = $rdt[0]->kd_job;
		}
		
		$str = explode(",", $koordinatKantor);
		$lat1 = $str[0];
		$lon1 = $str[1];

		// --- koordinat absen ---
		$lat2 = $latitude;
		$lon2 = $longitude;

		// --- rumus menghitung jarak ---
		$theta = $lon1 - $lon2;
		$miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
		$miles = acos($miles);
		$miles = rad2deg($miles);
		$miles = $miles * 60 * 1.1515;
		$feet = $miles * 5280;
		$yards = $feet / 3;
		$kilometers = round($miles * 1.609344,2);
		$meters = $kilometers * 1000;
		
		$qbagian = "select b.kd_bagian from mas_peg_backup a left join m_unit b on a.kd_unit = b.kd_unit where a.no_peg= '$username'";
		$rdt = $this->db_hrdonline->query($qbagian)->result();
		$kdbagian = $rdt[0]->kd_bagian;
		
		$cek = "select * from t_finger_mobile where no_peg = '$username' and tanggal='$tgl_now'";
		$bcek = $this->db_hrdonline->query($cek)->num_rows();
		
		// --- khusus manager & kadiv---
		if($kd_jab < 30 || $kd_jab == 102 || $username == "KW97011" || $username == "KW08013" || $username == "KW98105"){
			if($bcek == 0){
				if($jamnow < '12:00:00'){
					echo 2;
				}
				else{
					$insert = "insert into t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',keluar='$jamnow',kode='$kode',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out'";
					$this->db_hrdonline->query($insert);
					$this->db_hrdonline->close();
					echo 1;
				}
			}
			else{
				if($jamnow < '12:00:00'){
					echo 3;
				}
				else{
					$update = "update t_finger_mobile set keluar='$jamnow',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out' where no_peg = '$username' and tanggal='$tgl_now'";
					$this->db_hrdonline->query($update);
					$this->db_hrdonline->close();
					echo 1;
				}
			}
		}
		else{
			if($meters > $radius){
				echo 5;
			}
			else{
				// -- khusus satpam ----
				if($username == "KW98118" || $username == "KW16029" || $username == "KW99009" || $username == "KW98057" || $username == "KW97026" || $username == "KW04001" || $username == "KW94100" || $username == "KW16029" || $username == "KW98118" || $username == "KW00003" || $username == "KW02029" || $username == "TT13320" || $kd_job == "N12" || $kd_kantor == "K0075" || $username == "KW99013" || $username == "TK25002" || $username == "OS25153" || $kd_job == "B18"){
					$cekdt = "select * from t_finger_mobile where no_peg = '$username' and tanggal='$tgl_now'";
					$rows = $this->db_hrdonline->query($cek)->num_rows();

					if($rows == 1){
						$update = "update t_finger_mobile set keluar='$jamnow',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out' where no_peg = '$username' and tanggal='$tgl_now'";
						$this->db_hrdonline->query($update);
						$this->db_hrdonline->close();
						echo 1;
					}
					else{
						$tgl_sebelumnya = date('Y-m-d', strtotime($tgl_now . ' -1 day'));

						$cekdt = "select * from t_finger_mobile where no_peg = '$username' and tanggal='$tgl_sebelumnya'";
						$rows = $this->db_hrdonline->query($cek)->num_rows();
						if($rows == 1){
							$val = $this->db->query($cekdt)->result();
							$keluar = $val[0]->keluar;

							if($keluar == "" || $keluar == "(NULL)"){
								$update = "update t_finger_mobile set keluar='$jamnow',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out' where no_peg = '$username' and tanggal='$tgl_sebelumnya'";
								$this->db_hrdonline->query($update);
								$this->db_hrdonline->close();
								echo 1;
							}
						}
						else{
							$insert = "insert into t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',keluar='$jamnow',kode='$kode',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out'";
							$this->db_hrdonline->query($insert);
							$this->db_hrdonline->close();
							echo 1;
						}
					}
				}
				else{
					if($bcek == 0){
						if($jamnow < '12:00:00'){
							echo 2;
						}
						else{
							$insert = "insert into t_finger_mobile set no_peg = '$username',tanggal='$tgl_now',keluar='$jamnow',kode='$kode',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out'";
							$this->db_hrdonline->query($insert);
							$this->db_hrdonline->close();
							echo 1;
						}
					}
					else{
						if($jamnow < '12:00:00'){
							echo 3;
						}
						else{
						$update = "update t_finger_mobile set keluar='$jamnow',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out' where no_peg = '$username' and tanggal='$tgl_now'";
						$this->db_hrdonline->query($update);
						$this->db_hrdonline->close();
						echo 1;
						}
					}
				}
			}
		}
	}
	

	function combo_kantor(){
		$username = $this->session->userdata('username');

		$value         = isset($_REQUEST['value']) ? $_REQUEST['value'] : "";
        $q             = isset($_REQUEST['q']) ? $_REQUEST['q'] : $value;
        $cari['value'] = $q;

		$cekdt = "select * from office_employees where no_peg = '$username'";
		$rdt = $this->db_hrdonline->query($cekdt)->num_rows();
		if($rdt == 0){
			$sql = "select a.*,b.nm_kantor from office_employees a left join m_kantor b on a.kd_kantor = b.kd_kantor where a.no_peg = '$username'";
		}
		else{
			$sql = "SELECT b.kd_kantor,c.nm_kantor FROM mas_peg_backup a 
			LEFT JOIN m_unit b ON a.kd_unit = b.kd_unit
			LEFT JOIN m_kantor c ON b.kd_kantor = c.kd_kantor
			WHERE a.no_peg = '$username'";
		}
		
		$data = $this->db_hrdonline->query($sql)->result_array();
        foreach ($data as $key => $value) {
            $value['id']   = $value['kd_kantor'];
            $value['text'] = $value['nm_kantor'];
            $arrData['results'][] = $value;
        }

        echo json_encode($arrData);
	}
	
}
