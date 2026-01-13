<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends CI_Controller {

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
	public function checkout()
	{
		$this->load->view('absensi_checkout');
	}

	public function proses_daftar_bkp() {
		$username = $this->session->userdata('username');

		// Ambil JSON dari request
		$input = json_decode(file_get_contents('php://input'), true);

		if (!$username) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Session Habis, Silakan Login Ulang!'
			]);
			return;
		}

		if (!isset($input['face_vector']) || !isset($input['image'])) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Data tidak lengkap!'
			]);
			return;
		}

		$vector_baru = $input['face_vector']; // array 128 float
		$imageBase64 = $input['image'];

		/* =========================
		PROSES SIMPAN GAMBAR
		========================== */
		// bersihkan base64
		$imageBase64 = str_replace('data:image/jpeg;base64,', '', $imageBase64);
		$imageBase64 = str_replace(' ', '+', $imageBase64);
		$imageBinary = base64_decode($imageBase64);
		
		// folder upload
		$path = FCPATH . 'uploads/';
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}

		// nama file
		$filename = 'face_' . $username . '_' . time() . '.jpg';
		$filePath = $path . $filename;
		// simpan file
		file_put_contents($filePath, $imageBinary);

		/* =========================
		SIMPAN KE DATABASE
		========================== */
		
		$this->db->where('no_peg', $username);
		$this->db->update('db_hrd.mas_peg_backup', [
			'face_id'   => json_encode($vector_baru),
			'face_img'  => $filename // kolom gambar (jika ada)
		]);

		echo json_encode([
			'status'  => 'success',
			'message' => 'Pendaftaran Face ID Berhasil Disimpan!',
			'image'   => $filename
		]);
	}

	public function proses_daftar() {

		$username = $this->session->userdata('username');
		if (!$username) {
			echo json_encode(['status'=>'error','message'=>'Session habis']);
			return;
		}

		$input = json_decode(file_get_contents('php://input'), true);
		if (!isset($input['face_vector'])) {
			echo json_encode(['status'=>'error','message'=>'Vector kosong']);
			return;
		}

		$vector_baru = $input['face_vector'];
		$imageBase64 = $input['image'];

		/* =========================
		PROSES SIMPAN GAMBAR
		========================== */
		// bersihkan base64
		$imageBase64 = str_replace('data:image/jpeg;base64,', '', $imageBase64);
		$imageBase64 = str_replace(' ', '+', $imageBase64);
		$imageBinary = base64_decode($imageBase64);
		
		// folder upload
		$path = FCPATH . 'uploads/';
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}

		// nama file
		$filename = 'face_' . $username . '_' . time() . '.jpg';
		$filePath = $path . $filename;
		// simpan file
		file_put_contents($filePath, $imageBinary);
		$dim = count($vector_baru);

		// ambil face user
		$row = $this->db
			->select('face_id')
			->where('no_peg', $username)
			->get('db_hrd.mas_peg_backup')
			->row();

		if (!$row) {
			echo json_encode(['status'=>'error','message'=>'Pegawai tidak ditemukan']);
			return;
		}

		// ===============================
		// FACE DUPLICATE CHECK (1:N)
		// ===============================
		$minDistance = 999;
		$foundDuplicate = false;
		$pegawaiDuplicate = null;
		$thresholdDuplicate = 0.4;

		$res = $this->db
			->select('no_peg,na_peg,face_id')
			->where('face_id IS NOT NULL', null, false)
			->where('no_peg !=', $username) // penting!
			->where('flag_keluar !=', 1)
			->get('db_hrd.mas_peg_backup')
			->result();

		if (empty($res)) {
			// === Kirim file ke SIPANDU ===
			$cfile = new CURLFile($filePath, mime_content_type($filePath), basename($filePath));
			$curl  = curl_init();

			curl_setopt_array($curl, [
				CURLOPT_URL            => 'https://sipandu.kwsg.co.id/upload/do_upload_faceid',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT        => 60,
				CURLOPT_POST           => true,
				CURLOPT_POSTFIELDS     => [
					'userfile'     => $cfile,
					'nopeg'        => $username,
				],
				CURLOPT_HTTPHEADER     => [
					'Authorization: jKwYn8kknmd21HzHhGfT',
				],
			]);

			$response = curl_exec($curl);

			if (curl_errno($curl)) {
				throw new Exception('CURL ERROR: ' . curl_error($curl));
			}

			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);

			log_message('debug', "Response SIPANDU (HTTP {$http_code}): " . $response);
			// -- end of kirim file ---
			
			// langsung simpan face baru
			$this->db->where('no_peg', $username)
				->update('db_hrd.mas_peg_backup', [
					'reg_faceid' => 1,
					'face_id' => json_encode($vector_baru),
					'face_img'  => $response // kolom gambar (jika ada)
				]);

			echo json_encode([
				'status' => 'success',
				'message' => 'Face ID berhasil disimpan'
			]);
			return;
		}

		/* ===============================
		ADA DATA → LAKUKAN MATCHING
		================================ */
		foreach ($res as $r) {
			$dbVector = json_decode($r->face_id, true);
			if (!$dbVector) continue;

			$distance = $this->euclideanDistance($vector_baru, $dbVector);

			if ($distance < $minDistance) {
				$minDistance = $distance;
				$pegawaiDuplicate = $r;
			}

			// DETEKSI DUPLIKAT
			if ($distance < $thresholdDuplicate) {
				$foundDuplicate = true;
				break; // cukup 1 saja
			}
		}

		/* ===============================
		KEPUTUSAN AKHIR
		================================ */
		if ($foundDuplicate) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Pendaftaran Face ID ditolak. Wajah mirip dengan pegawai lain.'
			]);
			return;
		}

		// AMAN → SIMPAN FACE ID

		// === Kirim file ke SIPANDU ===
		$cfile = new CURLFile($filePath, mime_content_type($filePath), basename($filePath));
		$curl  = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL            => 'https://sipandu.kwsg.co.id/upload/do_upload_faceid',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 60,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => [
				'userfile'     => $cfile,
				'nopeg'        => $username,
			],
			CURLOPT_HTTPHEADER     => [
				'Authorization: jKwYn8kknmd21HzHhGfT',
			],
		]);

		$response = curl_exec($curl);

		if (curl_errno($curl)) {
			throw new Exception('CURL ERROR: ' . curl_error($curl));
		}

		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		log_message('debug', "Response SIPANDU (HTTP {$http_code}): " . $response);
		// -- end of kirim file ---
		
		$this->db->where('no_peg', $username)
				->update('db_hrd.mas_peg_backup', [
					'reg_faceid' => 1,
					'face_id' => json_encode($vector_baru),
					'face_img'  => $response // kolom gambar (jika ada)
				]);

		echo json_encode([
			'status' => 'success',
			'message' => 'Face ID berhasil didaftarkan'
		]);
	}

	function euclideanDistance($v1, $v2) {
		$sum = 0;
		for ($i = 0; $i < count($v1); $i++) {
			$sum += pow($v1[$i] - $v2[$i], 2);
		}
		return sqrt($sum);
	}



	public function proses_daftar_backup() {
		$username = $this->session->userdata('username');
	    // Ambil data JSON dari JS
		$input = json_decode(file_get_contents('php://input'), true);
	    $vector_baru = $input['face_vector']; // Ini array 128 angka
	    
		if($username != ""){
			// Simpan vector sebagai text JSON di kolom face_id di tabel mas_peg
			$queryUpdate = "update db_hrd.mas_peg_backup set face_id = '".json_encode($vector_baru)."' where no_peg = '".$username."'";
			$this->db->query($queryUpdate);
			echo json_encode([
				'status' => 'success', 
				'message' => 'Pendaftaran Face ID Berhasil Disimpan!'
			]);
		}
		else{
			echo json_encode([
				'status' => 'error', 
				'message' => 'Session Habis, Silakan Login Ulang!'
			]);
			return;
		}
	}
	
	function proses_checkin_bkp(){
		date_default_timezone_set('Asia/Jakarta');
		$username = $this->session->userdata('username');
		$kode = $this->session->userdata('kode_finger');
		 // Ambil data JSON dari JS
	    $input = json_decode(file_get_contents('php://input'), true);
	    $vector_baru = $input['face_vector']; // Ini array 128 angka

		$tgl_now = date('Y-m-d');
		$checkin = date('H:i:s');
		$latitude = $input['lat'];
		$longitude = $input['long'];
		$kd_kantor = $input['kd_kantor'];
		$lat_lon_in = $latitude . "," . $longitude;

		if($kd_kantor != ''){
			$dKantor = "select * from m_kantor where kd_kantor = '$kd_kantor'";
			$rdt = $this->db_hrdonline->query($dKantor)->result();
			$koordinatKantor = $rdt[0]->koordinat_kantor;
			$radius = $rdt[0]->radius;
			
			// --- jab ---
			$qmaspeg = "select kd_jab,kd_job,kd_unit from mas_peg where no_peg = '$username'";
			$qdt = $this->db_hrdonline->query($qmaspeg)->result();
			$kd_jab = $qdt[0]->kd_jab;
			$kd_job = $qdt[0]->kd_job;
			
			if($username == "KW97011" || $username == "KW08013" || $username == "KW98105"){
				$proyekcurah = 1;
			}
		}
		else{
			$qfinger = "select a.id_finger,b.kd_kantor,c.nm_kantor,c.koordinat_kantor,a.kd_jab,c.radius,a.kd_job from mas_peg a 
			left join m_unit b on a.kd_unit = b.kd_unit
			left join m_kantor c on b.kd_kantor = c.kd_kantor
			where a.no_peg = '$username'";
			$rdt = $this->db_hrdonline->query($qfinger)->result();
			$koordinatKantor = $rdt[0]->koordinat_kantor;
			$kd_jab = $rdt[0]->kd_jab;
			$radius = $rdt[0]->radius;
			$kd_job = $rdt[0]->kd_job;
			
		}
		
		$param = array();
		$param['koordinatKantor'] = $koordinatKantor;
		$param['latitude'] = $latitude;
		$param['longitude'] = $longitude;
		$HitungJarak = $this->absensi_model->hitungradius($param);
		
		if($username != ""){
			if($HitungJarak > $radius){
				echo json_encode([
					'status' => 'error', 
					'message' => 'Lokasi Anda Diluar Radius Absensi '.$HitungJarak.''
				]);
			}
			else{
				// 1. Ambil data face_id dari maspeg
				$qmaspeg = "SELECT face_id FROM db_hrd.mas_peg_backup WHERE no_peg = '$username'";
				$rdata = $this->db->query($qmaspeg)->result();
				$face_id = $rdata[0]->face_id;

				if($face_id == ""){
					echo json_encode([
						'status' => 'error', 
						'message' => 'Silahkan Melakukan Pendaftaran Face Id Terlebih Dahulu!'
					]);
					return;
				}
				else{
					// 2. Decode vector lama dari database
					$vector_lama = json_decode($face_id);
					// 3. Bandingkan Vector Lama vs Vector Baru (Fungsi matematika di bawah)
					$jarak = $this->hitung_jarak_euclidean($vector_lama, $vector_baru);
					// 4. Tentukan Kemiripan
					// Threshold face-api.js biasanya 0.6 (semakin kecil semakin mirip)
					// 0.0 = Kembar Identik
					// 0.4 = Mirip Sekali
					// 0.6 = Batas ambang orang berbeda
					
					// Konversi ke Persentase (Logika kasar: 1 - jarak)
					$kemiripan = (1 - $jarak) * 100;
					
					// Kita set toleransi. Jika jarak < 0.5 (atau kemiripan > 50-60%) dianggap valid.
					// Anda minta 70%, berarti jarak harus sangat dekat (sekitar 0.3).
					if ($kemiripan >= 60) { // Saya sarankan 60% untuk start, 70% agak ketat
						
						$queryInsert = "insert into db_hrd.t_finger_mobile_dummy set no_peg='$username',face_id_in='".json_encode($vector_baru)."',similarity_score='$kemiripan',tanggal='$tgl_now',masuk='$checkin'";
						$this->db->query($queryInsert);
						echo json_encode([
							'status' => 'success', 
							'message' =>'Checkin Berhasil! Kemiripan:'.round($kemiripan).'%'
						]);
					} else {
						echo json_encode([
							'status' => 'error', 
							'message' => 'Wajah Tidak Cocok! Kemiripan hanya:'.round($kemiripan).'%'
						]);
					}
				}
			}
		}
		else{
			echo json_encode([
				'status' => 'error', 
				'message' => 'Session Habis, Silakan Login Ulang!'
			]);
			
		}
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

		$vector_baru = $input['face_vector'];
		$latitude    = $input['lat'];
		$longitude   = $input['long'];
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
		$qmaspeg = "select kd_jab,kd_job,kd_unit from mas_peg where no_peg = '$username'";
		$qdt = $this->db_hrdonline->query($qmaspeg)->result();
		$kd_jab = $qdt[0]->kd_jab;
		$kd_job = $qdt[0]->kd_job;
		if($username == "KW97011" || $username == "KW08013" || $username == "KW98105"){
			$proyekcurah = 1;
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

		// =======================
		// Ambil FaceID dari database
		// =======================
		$qmaspeg = $this->db->query("SELECT face_id FROM db_hrd.mas_peg_backup WHERE no_peg = '$username'");
		if ($qmaspeg->num_rows() == 0) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Face ID belum terdaftar, silakan melakukan pendaftaran!'
			]);
			exit;
		}

		$face_id = $qmaspeg->row()->face_id;
		if (!$face_id) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Face ID belum terdaftar, silakan melakukan pendaftaran!'
			]);
			exit;
		}

		$vector_lama = json_decode($face_id);
		$jarak       = $this->hitung_jarak_euclidean($vector_lama, $vector_baru);
		$kemiripan   = (1 - $jarak) * 100;

		// =======================
		// Validasi Kemiripan
		// =======================
		if ($kemiripan >= 60) {
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

			$cekdt = "SELECT COUNT(*) as dt_finger from t_finger_mobile_dummy WHERE no_peg = '$username' and tanggal='$tgl_now'";
			$rdt = $this->db_hrdonline->query($cekdt)->result();
			$dt_finger = $rdt[0]->dt_finger;
			if($dt_finger == 0){
				if($kd_job == "N12" || $kd_job == "B18" || $kd_kantor == "K0075"){
					//---khusus satpam dan expedisi cargo---
					// --- insert t_finger_mobile ---
					$insert = "insert into db_hrd.t_finger_mobile_dummy set no_peg = '$username',tanggal='$tgl_now',masuk='$checkin',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in',kd_kantor_in='$kd_kantor',face_id_in='".json_encode($vector_baru)."',similarity_score='$kemiripan'";
					$this->db_hrdonline->query($insert);

					echo json_encode([
						'status' => 'success',
						'message' => 'Checkin Berhasil! Kemiripan: '.round($kemiripan).'%'
					]);
					exit;
				}
				else{
					if($checkin > '09:00:00'){
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

						// --- insert t_finger_mobile ---
						$insert = "insert into db_hrd.t_finger_mobile_dummy set no_peg = '$username',tanggal='$tgl_now',shift_time_in='$jam_masuk',shift_time_out='$jam_pulang',masuk='$checkin',terlambat='$terlambat',kode='$kode',ip_address_in='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_in='$lat_lon_in',kd_kantor_in='$kd_kantor',face_id_in='".json_encode($vector_baru)."',similarity_score='$kemiripan'";
						$this->db_hrdonline->query($insert);

						echo json_encode([
							'status' => 'success',
							'message' => 'Checkin Berhasil! Kemiripan: '.round($kemiripan).'%'
						]);
						exit;
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
		else{
			echo json_encode([
				'status' => 'error',
				'message' => 'Wajah Tidak Cocok! Kemiripan hanya: '.round($kemiripan).'%'
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

		$vector_baru = $input['face_vector'];
		$latitude    = $input['lat'];
		$longitude   = $input['long'];
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
		// Ambil FaceID dari database
		// =======================
		$qmaspeg = $this->db->query("SELECT face_id FROM db_hrd.mas_peg_backup WHERE no_peg = '$username'");
		if ($qmaspeg->num_rows() == 0) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Face ID belum terdaftar, silakan melakukan pendaftaran!'
			]);
			exit;
		}

		$face_id = $qmaspeg->row()->face_id;
		if (!$face_id) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Face ID belum terdaftar, silakan melakukan pendaftaran!'
			]);
			exit;
		}

		$vector_lama = json_decode($face_id);
		$jarak       = $this->hitung_jarak_euclidean($vector_lama, $vector_baru);
		$kemiripan   = (1 - $jarak) * 100;

		// =======================
		// Validasi Kemiripan
		// =======================
		if ($kemiripan >= 60) {
			if($kd_job == "N12" || $kd_job == "B18" || $kd_kantor == "K0075"){
				//---khusus satpam dan expedisi cargo---
				$cekdt = "select * from t_finger_mobile_dummy where no_peg = '$username' and tanggal='$tgl_now'";
				$rows = $this->db_hrdonline->query($cek)->num_rows();
				if($rows == 1){
					$update = "update t_finger_mobile_dummy set keluar='$checkout',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out'face_id_out='".json_encode($vector_baru)."',
					similarity_out='$kemiripan' where no_peg = '$username' and tanggal='$tgl_now'";
					$this->db_hrdonline->query($update);

					echo json_encode([
						'status' => 'success',
						'message' => 'Checkout Berhasil! Kemiripan: '.round($kemiripan).'%'
					]);
					exit;
				}
				else{
					$tgl_sebelumnya = date('Y-m-d', strtotime($tgl_now . ' -1 day'));
					$cekdt = "select * from t_finger_mobile_dummy where no_peg = '$username' and tanggal='$tgl_sebelumnya'";
					$rows = $this->db_hrdonline->query($cek)->num_rows();
					if($rows == 1){
						$val = $this->db->query($cekdt)->result();
						$keluar = $val[0]->keluar;
						if($keluar == "" || $keluar == "(NULL)"){
							$update = "update t_finger_mobile_dummy set keluar='$checkout',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out'face_id_out='".json_encode($vector_baru)."',
							similarity_out='$kemiripan' where no_peg = '$username' and tanggal='$tgl_sebelumnya'";
							$this->db_hrdonline->query($update);
						}
					}
					else{
						// --- insert t_finger_mobile ---
						$insert = "insert into db_hrd.t_finger_mobile_dummy set no_peg = '$username',tanggal='$tgl_now',keluar='$checkout',kode='$kode',ip_address_out='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_out='$lat_lon_out',kd_kantor_out='$kd_kantor',face_id_out='".json_encode($vector_baru)."',similarity_out='$kemiripan'";
						$this->db_hrdonline->query($insert);
					}

					echo json_encode([
						'status' => 'success',
						'message' => 'Checkout Berhasil! Kemiripan: '.round($kemiripan).'%'
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
					
					$cekdt = "SELECT COUNT(*) as dt_finger from t_finger_mobile_dummy WHERE no_peg = '$username' and tanggal='$tgl_now'";
					$rdt = $this->db_hrdonline->query($cekdt)->result();
					$dt_finger = $rdt[0]->dt_finger;
					if($dt_finger == 0){
						// --- insert t_finger_mobile ---
						$insert = "insert into db_hrd.t_finger_mobile_dummy set no_peg = '$username',tanggal='$tgl_now',shift_time_in='$jam_masuk',shift_time_out='$jam_pulang',keluar='$checkout',kode='$kode',ip_address_out='$ipaddress',latitude='$latitude',longitude='$longitude',lat_lon_out='$lat_lon_out',kd_kantor_out='$kd_kantor',plg_cepat='$pulangcepat',lembur='$lembur',face_id_out='".json_encode($vector_baru)."',similarity_out='$kemiripan'";
						$this->db_hrdonline->query($insert);
					}
					else{
						$update = "update t_finger_mobile_dummy set keluar='$checkout',ip_address_out='$ipaddress',lat_lon_out='$lat_lon_out'face_id_out='".json_encode($vector_baru)."',
						similarity_out='$kemiripan',plg_cepat='$pulangcepat',lembur='$lembur' where no_peg = '$username' and tanggal='$tgl_now'";
						$this->db_hrdonline->query($update);
					}

					echo json_encode([
						'status' => 'success',
						'message' => 'Checkout Berhasil! Kemiripan: '.round($kemiripan).'%'
					]);
					exit;
				}
			}
			
		} else {

			echo json_encode([
				'status' => 'error',
				'message' => 'Wajah Tidak Cocok! Kemiripan hanya: '.round($kemiripan).'%'
			]);
			exit;
		}
	}


	public function proses_absen() {
	    // Ambil data JSON dari JS
	    $input = json_decode(file_get_contents('php://input'), true);
	    $vector_baru = $input['face_vector']; // Ini array 128 angka
	    $tipe = $input['type'];
	    
	    if ($tipe == 'checkin') {
	        // --- LOGIKA CHECKIN ---
	        // Simpan array angka ini ke database sebagai JSON string
	        $data_simpan = [
	            'user_id' => 123, // Sesuaikan session
	            'jam_masuk' => date('Y-m-d H:i:s'),
	            // Simpan vector sebagai text JSON
	            'face_vector_masuk' => json_encode($vector_baru) 
	        ];
	        
	        $this->db->insert('db_hrd.t_absensi_tes', $data_simpan);
	        echo json_encode(['message' => 'Checkin Berhasil Disimpan!']);

	    } else {
	        // --- LOGIKA CHECKOUT ---
	        // 1. Ambil data checkin hari ini dari DB
	        $absen_hari_ini = $this->db->get_where('db_hrd.t_absensi_tes', ['user_id' => 123, 'jam_keluar' => NULL])->row();
	        
	        if (!$absen_hari_ini) {
	             echo json_encode(['message' => 'Anda belum checkin!']);
	             return;
	        }

	        // 2. Decode vector lama dari database
	        $vector_lama = json_decode($absen_hari_ini->face_vector_masuk);

	        // 3. Bandingkan Vector Lama vs Vector Baru (Fungsi matematika di bawah)
	        $jarak = $this->hitung_jarak_euclidean($vector_lama, $vector_baru);
	        
	        // 4. Tentukan Kemiripan
	        // Threshold face-api.js biasanya 0.6 (semakin kecil semakin mirip)
	        // 0.0 = Kembar Identik
	        // 0.4 = Mirip Sekali
	        // 0.6 = Batas ambang orang berbeda
	        
	        // Konversi ke Persentase (Logika kasar: 1 - jarak)
	        $kemiripan = (1 - $jarak) * 100;
	        
	        // Kita set toleransi. Jika jarak < 0.5 (atau kemiripan > 50-60%) dianggap valid.
	        // Anda minta 70%, berarti jarak harus sangat dekat (sekitar 0.3).
	        if ($kemiripan >= 60) { // Saya sarankan 60% untuk start, 70% agak ketat
	            
	            // Update DB Checkout
	            $this->db->where('id', $absen_hari_ini->id);
	            $this->db->update('db_hrd.t_absensi_tes', ['jam_keluar' => date('Y-m-d H:i:s'), 'similarity_score' => $kemiripan, 'face_vector_keluar' => json_encode($vector_baru) ]);
	            
	            echo json_encode([
	                'status' => 'success', 
	                'message' => 'Checkout Berhasil! Kemiripan: '.round($kemiripan).'%'
	            ]);
	        } else {
	            echo json_encode([
	                'status' => 'error', 
	                'message' => 'Wajah Tidak Cocok! Kemiripan hanya: '.round($kemiripan).'%'
	            ]);
	        }
	    }
	}

	// --- FUNGSI MATEMATIKA PHP (Pengganti Python) ---
	private function hitung_jarak_euclidean($vektor1, $vektor2) {
	    if (count($vektor1) != count($vektor2)) {
	        return 1.0; // Error panjang data beda
	    }

	    $sum = 0;
	    for ($i = 0; $i < count($vektor1); $i++) {
	        // Rumus: (a - b)^2
	        $diff = $vektor1[$i] - $vektor2[$i];
	        $sum += $diff * $diff;
	    }

	    // Akar kuadrat dari total
	    return sqrt($sum);
	}
}
