<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approve extends CI_Controller {

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
		$this->load->model('sdm_model');
		$this->load->model('func_global');
		$this->db_hrdonline = $this->load->database("hrdonline", TRUE);
		$this->db_pbb = $this->load->database("pbb", TRUE);

		if($this->session->userdata('username') == ""){
			redirect('Welcome/index');
		}
	}
	
	public function app_sppd()
	{
		$no_peg = $this->session->userdata('username');
		// $no_peg = "KW98051";

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('approve/data_app_sppd',$Param);	
		$this->load->view('general/footer');	
	}

	public function app_pjksppd()
	{
		$no_peg = $this->session->userdata('username');
		// $no_peg = "KW98051";

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('approve/data_app_pjksppd',$Param);	
		$this->load->view('general/footer');	
	}

	public function app_cuti()
	{
		$no_peg = $this->session->userdata('username');
		// $no_peg = "KW98051";

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('approve/data_app_cuti',$Param);	
		$this->load->view('general/footer');	
	}

	public function app_mutasi()
	{
		$no_peg = $this->session->userdata('username');
		$jab = $this->session->userdata('jab');
		// $no_peg = "KW98051";

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		if($jab == "KADIV" || $jab == "MR" || $jab == "PENGURUS"){
			$this->load->view('approve/data_app_mutasi',$Param);	
		}
		else{
			$this->load->view('not_found',$Param);
		}
		
		$this->load->view('general/footer');	
	}

	public function app_request()
	{
		$no_peg = $this->session->userdata('username');
		$jab = $this->session->userdata('jab');
		// $jab = "KADIV";
		// $no_peg = "KW00025";

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		
		if($jab == "KADIV" || $jab == "MR" || $jab == "PENGURUS"){
			if($jab == "MR" && $Datapeg[0]->kd_unit != "90C0"){
				$this->load->view('approve/data_app_request_unit',$Param);
			}
			else if($jab == "KADIV"){
				$this->load->view('approve/data_app_request_unit',$Param);
			}
			else{
				$this->load->view('approve/data_app_request',$Param);	
			}
		}
		else{
			$this->load->view('not_found',$Param);
		}
		
		$this->load->view('general/footer');	
	}

	public function view_app_request()
	{
		$no_peg = $this->session->userdata('username');
		$jab = $this->session->userdata('jab');
		// $jab="KADIV";
		// $no_peg = "KW00025";

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		if($jab == "KADIV" || $jab == "MR" || $jab == "PENGURUS"){
			$this->load->view('approve/view_app_request',$Param);	
		}
		else{
			$this->load->view('not_found',$Param);
		}
		
		$this->load->view('general/footer');	
	}

	public function view_app_mutasi()
	{
		$no_peg = $this->session->userdata('username');
		$jab = $this->session->userdata('jab');
		// $no_peg = "KW98051";

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		if($jab == "KADIV" || $jab == "MR" || $jab == "PENGURUS"){
			$this->load->view('approve/view_app_mutasi',$Param);	
		}
		else{
			$this->load->view('not_found',$Param);
		}
		
		$this->load->view('general/footer');	
	}

	public function view_app_sppd()
	{
		$no_peg = $this->session->userdata('username');
		// $no_peg = "KW98051";

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('approve/view_app_sppd',$Param);	
		$this->load->view('general/footer');	
	}

	public function view_app_cuti()
	{
		$no_peg = $this->session->userdata('username');
		// $no_peg = "KW98051";

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('approve/view_app_cuti',$Param);	
		$this->load->view('general/footer');	
	}

	public function view_app_pjksppd()
	{
		$no_peg = $this->session->userdata('username');
		// $no_peg = "KW98051";

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('approve/view_app_pjksppd',$Param);	
		$this->load->view('general/footer');	
	}

	public function approve_multi_sppd()
	{
		// ambil user login
		$username = $this->session->userdata('username');
		// sementara (debug)
		// $username = "KW98051";

		$ids = $this->input->post('ids');

		if (empty($ids) || !is_array($ids)) {
			echo json_encode(['status' => false, 'message' => 'Data kosong']);
			return;
		}

		// === DATA USER APPROVER ===
		$rdt = $this->db
			->where('no_peg', $username)
			->get('mas_peg')
			->row();

		if (!$rdt) {
			echo json_encode(['status' => false, 'message' => 'User tidak valid']);
			return;
		}

		$kd_jab   = $rdt->kd_jab;
		$kd_level = $rdt->kd_level;
		$kd_unit  = $rdt->kd_unit;

		$approved = [];
		$rejected = [];

		foreach ($ids as $id_sppd) {

			// === CEK DATA SPPD ===
			$dt = $this->db
				->where('ID', $id_sppd)
				->get('t_sppd')
				->row();

			if (!$dt || $dt->APPROVE == 1) {
				$rejected[] = $id_sppd;
				continue;
			}

			$tk_jabatan = $dt->TK_JABATAN;
			$bolehApprove = false;

			// ===== LOGIKA APPROVE (ASLI, DISAMAKAN) =====

			// Kadiv
			if ($tk_jabatan == 10 || $tk_jabatan == "102" || $tk_jabatan == "26") {
				if (in_array($kd_jab, ['01', '02', '04'])) {
					$bolehApprove = true;
				}
			}
			// Manager
			else if ($tk_jabatan <= 28) {
				if ($kd_unit == "90AD") {
					$bolehApprove = true;
				} else if (in_array($kd_jab, ['01','02','04','10','102','26'])) {
					$bolehApprove = true;
				}
			}
			// BM - MO
			else if (($tk_jabatan > 28 && $tk_jabatan < 40) || $kd_level == 2) {
				if ($kd_jab <= 28 || $kd_level == 2) {
					$bolehApprove = true;
				}
			}
			// SPV - STAFF
			else {
				if ($kd_jab < 36) {
					$bolehApprove = true;
				}
			}

			// ===== EKSEKUSI UPDATE =====
			if ($bolehApprove) {
				$this->db->where('ID', $id_sppd)
					->update('t_sppd', [
						'APPROVE'  => 1,
						'USER_APP' => $username,
						'TGL_APP'  => date('Y-m-d H:i:s')
					]);

				$approved[] = $id_sppd;
			} else {
				$rejected[] = $id_sppd;
			}
		}

		echo json_encode([
			'status'    => true,
			'approved'  => count($approved),
			'rejected'  => count($rejected),
			'detail'    => [
				'approved_ids' => $approved,
				'rejected_ids' => $rejected
			]
		]);
	}

	function approve_sppd(){
		$username = $this->session->userdata('username');
		// $username = "KW98051";

		$cekdt = "select * from mas_peg where no_peg = '$username'";
		$rdt = $this->db->query($cekdt)->result();
		$kd_jab = $rdt[0]->kd_jab;
		$kd_level = $rdt[0]->kd_level;
		$kd_unit = $rdt[0]->kd_unit;

		// --- cek sppd approve ---
		$qcek = "select * from t_sppd where id = '".$_POST['id_sppd']."'";
		$dt = $this->db->query($qcek)->result();
		$tk_jabatan = $dt[0]->TK_JABATAN;

		if($tk_jabatan == 10 || $tk_jabatan == "102" || $tk_jabatan == "26"){ // -- kadiv ---
			if($kd_jab == '01' || $kd_jab == '02' || $kd_jab == '04'){
				$update = "update t_sppd set approve = 1,USER_APP='$username',TGL_APP=NOW() where id = '".$_POST['id_sppd']."'";
				$this->db->query($update);
				echo json_encode(['status' => true]);
			}
			else{
				echo json_encode(['status' => false, 'message' => 'Anda Tidak Berhak Approve']);
			}
		}
		else if($tk_jabatan <= 28){ // --- manager ---
			//---khusus btp ---
			if($kd_unit == "90AD"){
				$update = "update t_sppd set approve = 1,USER_APP='$username',TGL_APP=NOW() where id = '".$_POST['id_sppd']."'";
				$this->db->query($update);
				echo json_encode(['status' => true]);
			}
			else{
				if($kd_jab == '01' || $kd_jab == '02' || $kd_jab == '04' || $kd_jab == '10' || $kd_jab == "102" || $kd_jab == "26"){
					$update = "update t_sppd set approve = 1,USER_APP='$username',TGL_APP=NOW() where id = '".$_POST['id_sppd']."'";
					$this->db->query($update);
					echo json_encode(['status' => true]);
				}
				else{
					echo json_encode(['status' => false, 'message' => 'Anda Tidak Berhak Approve']);
				}
			}
		}
		else if($tk_jabatan > 28 && $tk_jabatan < 40 || $kd_level == 2){ // BM - MO
			if($kd_jab <= '28' || $kd_level == 2){
				$update = "update t_sppd set approve = 1,USER_APP='$username',TGL_APP=NOW() where id = '".$_POST['id_sppd']."'";
				$this->db->query($update);
				echo json_encode(['status' => true]);
			}
			else{
				echo json_encode(['status' => false, 'message' => 'Anda Tidak Berhak Approve']);
			}
		}
		else{ // --- spv - staff --
			if($kd_jab < 36){
				$update = "update t_sppd set approve = 1,USER_APP='$username',TGL_APP=NOW() where id = '".$_POST['id_sppd']."'";
				$this->db->query($update);
				echo json_encode(['status' => true]);
			}
			else{
				echo json_encode(['status' => false, 'message' => 'Anda Tidak Berhak Approve']);
			}
		}
		
	}

	function bataldtsppd(){
		$hapus = "update t_sppd set APPROVE = 0 where id = '".$_POST['id_sppd']."'";
		$this->db->query($hapus);
		echo json_encode(['status' => true]);
	}


	public function approve_multi_pjksppd()
	{
		$username     = $this->session->userdata('username');
		$status_level = $this->session->userdata('jab');
		$ids          = $this->input->post('ids');

		if (!is_array($ids) || count($ids) == 0) {
			echo json_encode([
				'status' => false,
				'message' => 'Data tidak valid'
			]);
			return;
		}

		// data user
		$peg = $this->db->get_where('mas_peg', ['no_peg' => $username])->row();
		if (!$peg) {
			echo json_encode(['status' => false, 'message' => 'User tidak valid']);
			return;
		}

		$kd_jab   = $peg->kd_jab;
		$kd_level = $peg->kd_level;
		$kd_unit  = $peg->kd_unit;

		$approved = 0;
		$rejected = 0;

		foreach ($ids as $id_pjk) {

			// =========================
			// AMBIL DATA PJK
			// =========================
			$dt = $this->db
			->select('t_pjk.*, mas_peg.na_peg, mas_peg.kd_jab, mas_peg.kd_unit, SUBSTR(t_pjk.KA,1,1) AS kdKA')
			->from('t_pjk')
			->join('mas_peg', 'mas_peg.no_peg = t_pjk.NO_PEG', 'left')
			->where('t_pjk.id', $id_pjk)
			->get()
			->row();

			if (!$dt) {
				$rejected++;
				continue;
			}

			$tk_jabatan   = $dt->TK_JABATAN;
			$koor_anggaran= $dt->KA;
			$kdKA         = $dt->kdKA;
			$KET_KAS      = $dt->KET_KAS;
			$bukti_pjk    = $dt->BUKTI_PJK;
			$beban        = $dt->BEBAN;
			$no_peg       = $dt->NO_PEG;
			$uang_pjk     = $dt->U_PJK;
			$na_peg       = $dt->na_peg;
			$tanggal=date("Y-m-d");
			$flag_ppl = "0";
			if($beban == "6180" || $beban == "6181"){
				$flag_ppl = "1";
			}
			
			// =========================
			// CEK HAK APPROVE (RINGKAS)
			// =========================
			$bolehApprove = false;

			if ($status_level != 'STAFF') {
				$bolehApprove = true;
			}

			if (!$bolehApprove) {
				$rejected++;
				continue;
			}

			// =========================
			// UPDATE APPROVE
			// =========================
			$this->db->where('id', $id_pjk)
			->update('t_pjk', [
				'APPROVE_ATASAN' => 1,
				'TGL_APP'         => date("Y-m-d H:i:s"),
				'KD_PPU' => 1,
				'FLAG_PPL' => $flag_ppl,
				'USER_APPROVE'   => $username
			]);

			// =========================
			// LANJUTKAN LOGIKA ANDA
			// (kas, trackdoc, btp, dll)
			// =========================
			// ⚠️ SEMUA LOGIKA PANJANG YANG SUDAH ADA
			// TINGGAL DIPINDAHKAN KE SINI
			// TANPA $_POST['id_pjk']
			// GANTI DENGAN $id_pjk

			if($KET_KAS == 1){
				//---kas kantor pusat ---
				$qdata = "DELETE from db_fina.t_track_doc where no_document = '$bukti_pjk'";
				$rdt = $this->db->query($qdata);
				
				// --- insert t_trackdoc sifina ---
				$insert = "insert ignore into db_fina.t_track_doc set bukti_ph='$bukti_pjk',no_document = '$bukti_pjk',ajuan_dari='SDM',DoUval='1',tglDoUval=NOW()";
				$result = $this->db->query($insert);
				
			}
			else{
				//--- kas unit ---
				if($koor_anggaran == "2102"){
					//---unit pemeliharaan---
					$thn = substr(date('Y'),2,2);
					$bukti = "KK".date('m').$thn;

					$queryBukti = "SELECT SUBSTRING(BUKTI, 8, 6) urut FROM db_pemeliharaan.t_kasbank WHERE BUKTI LIKE '$bukti%' 
					ORDER BY id DESC LIMIT 1";
					$rbukti = $this->db->query($queryBukti)->result();
					$urut = $rbukti[0]->urut;

					$urut += 1;
					if($urut < 10){
						$urut = "000".$urut;
					}else if($urut < 100){
						$urut = "00".$urut;
					}else if($urut < 1000){
						$urut = "0".$urut;
					}
					$buktiKB = $bukti.$urut;

					$Qcek = "select * from db_pemeliharaan.t_kasbank where NO_REF = '$bukti_pjk' and  status_hapus = 0";
					$rdt = $this->db->query($Qcek)->num_rows();
					if($rdt == 0){
						$Qins = "insert into db_pemeliharaan.t_kasbank set BUKTI = '$buktiKB',TANGGAL = NOW(),KD_KB='1200',NM_KB='KAS KECIL',KD_CB='2651',NM_CB='UANG HARIAN (SPPD)',JM_HRG='$uang_pjk',NO_REF='$bukti_pjk',KETERANGAN='BIAYA HARIAN DAN PENGINAPAN $bukti_pjk',KA='$koor_anggaran',BB='$koor_anggaran',PB='$koor_anggaran'";
						$this->db->query($Qins);
					}
				}
				else if($koor_anggaran == "2100" || $koor_anggaran == "2101" || $koor_anggaran == "2000"){
					$thn = substr(date('Y'),2,2);
					$bukti = "KK".date('m').$thn;

					$queryBukti = "SELECT SUBSTRING(bukti_kb, 8, 6) urut FROM db_expgab.t_kasbank WHERE bukti_kb LIKE '$bukti%' 
					ORDER BY id DESC LIMIT 1";
					$rbukti = $this->db->query($queryBukti)->result();
					$urut = $rbukti[0]->urut;

					$urut += 1;
					if($urut < 10){
						$urut = "000".$urut;
					}else if($urut < 100){
						$urut = "00".$urut;
					}else if($urut < 1000){
						$urut = "0".$urut;
					}
					
					$buktiKB = $bukti.$urut;
					$Qcek = "select * from db_expgab.t_kasbank where no_ref = '$bukti_pjk' and  status_hapus = 0";
					$rdt = $this->db->query($Qcek)->num_rows();
					if($rdt == 0){
						$Qins = "insert into db_expgab.t_kasbank set kd_unit='$koor_anggaran',bukti_kb = '$buktiKB',tgl_kb = NOW(),kd_kb='1200',nm_kb='KAS KECIL',kd_cb='2651',nm_cb='UANG HARIAN (SPPD)',jml_kb='$uang_pjk',no_ref='$bukti_pjk',keterangan='BIAYA HARIAN DAN PENGINAPAN $bukti_pjk',ka='$koor_anggaran',bb='$koor_anggaran',pb='$koor_anggaran'";
						$this->db->query($Qins);
					}
				}
				else{
					//---khusus pbb---
					if($kdKA == 1){
						$queryBukti = "select SUBSTRING(BUKTI, 7, 6) urut from db_pbb_kwsg.no_bukti where BUKTI like 'KK%' and STATUS='2' 
						order by id desc limit 1";
						$rbukti = $this->db->query($queryBukti)->result();
						$urut = $rbukti[0]->urut;
						
						$urut += 1;
						if($urut < 10){
							$urut = "00000".$urut;
						}else if($urut < 100){
							$urut = "0000".$urut;
						}else if($urut < 1000){
							$urut = "000".$urut;
						}else if($urut < 10000){
							$urut = "00".$urut;
						}else if($urut < 100000){
							$urut = "0".$urut;
						}
						
						$bukti = "KK".date('m').date('y').$urut;
						$queryInsertBukti = "insert into db_pbb_kwsg.no_bukti(BUKTI, IP, USER_ID, STATUS) values('".$bukti."',
						'','$username','2')";
						$this->db->query($queryInsertBukti);

						$Qcek = "select * from db_pbb_kwsg.t_kasbank where no_ref = '$bukti_pjk' and  status_hapus = 0";
						$rdt = $this->db_pbb->query($Qcek)->num_rows();
						if($rdt == 0){
							$querykb = "insert into db_pbb_kwsg.t_kasbank(TANGGAL, BUKTI, KD_KB, NM_KB, KD_CB, NM_CB, NO_REF, JM_HRG,KD_LANG,NM_LANG, KETERANGAN, KA, PB, BB, STATUS_BUKTI, USER_ID, IP_ADDRESS,STATUS_ASAL) values('$tanggal', 
							'".$bukti."','1200','KAS KECIL','2651','UANG HARIAN (SPPD)','$bukti_pjk',
							".$uang_pjk.",'$no_peg','$na_peg','BIAYA HARIAN DAN PENGINAPAN $bukti_pjk','$koor_anggaran','$koor_anggaran','$beban','1','$no_peg','','FPJK')";
							$this->db_pbb->query($querykb);
							
						}
					}
				}
			}

			$approved++;
		}

		echo json_encode([
			'status'   => true,
			'approved' => $approved,
			'rejected' => $rejected
		]);
	}

	function bataldtpjksppd(){
		$id_pjk = $_POST['id_pjk'];
		$dt = $this->db
			->select("*, SUBSTR(KA,1,1) AS kdKA")
			->get_where('t_pjk', ['id' => $id_pjk])
			->row();

		$tk_jabatan   = $dt->TK_JABATAN;
		$koor_anggaran= $dt->KA;
		$kdKA         = $dt->kdKA;
		$KET_KAS      = $dt->KET_KAS;
		$bukti_pjk    = $dt->BUKTI_PJK;
		$beban        = $dt->BEBAN;

		if($KET_KAS == 1){
			//---kas kantor pusat ---
			$hapus = "DELETE from db_fina.t_track_doc where no_document = '$bukti_pjk'";
			$this->db->query($hapus);
		}
		else{
			//--- kas unit ---
			if($koor_anggaran == "2102"){
				//---unit pemeliharaan---
				$hapus = "DELETE from db_pemeliharaan.t_kasbank where no_ref = '$bukti_pjk'";
				$this->db->query($hapus);
			}
			else if($koor_anggaran == "2100" || $koor_anggaran == "2101" || $koor_anggaran == "2000"){
				$hapus = "DELETE from db_expgab.t_kasbank where no_ref = '$bukti_pjk'";
				$this->db->query($hapus);
			}
			else{
				//---khusus pbb---
				if($kdKA == 1){
					$hapus = "DELETE from db_pbb_kwsg.t_kasbank where no_ref = '$bukti_pjk'";
					$this->db_pbb->query($hapus);
				}
			}
		}	
		
		$hapus = "update t_pjk set APPROVE_ATASAN = 0,TGL_APP = NULL where id = '".$_POST['id_pjk']."'";
		$this->db->query($hapus);
		echo json_encode(['status' => true]);
	}

	public function approve_multi_izin()
	{
		// ambil user login
		$username = $this->session->userdata('username');
		// $username = "KW98051";
		$ids = $this->input->post('ids');

		if (empty($ids) || !is_array($ids)) {
			echo json_encode(['status' => false, 'message' => 'Data kosong']);
			return;
		}

		// === DATA USER APPROVER ===
		$rdt = $this->db
			->where('no_peg', $username)
			->get('mas_peg')
			->row();

		if (!$rdt) {
			echo json_encode(['status' => false, 'message' => 'User tidak valid']);
			return;
		}

		$kd_jab   = $rdt->kd_jab;
		$kd_level = $rdt->kd_level;
		$kd_unit  = $rdt->kd_unit;

		$approved = [];
		$rejected = [];

		foreach ($ids as $id_izin) {

			// === CEK DATA SPPD ===
			$dt = $this->db
				->where('id_izin', $id_izin)
				->get('t_izin')
				->row();

			if (!$dt || $dt->flag_app == 1) {
				$rejected[] = $id_izin;
				continue;
			}

			$tk_jabatan = $dt->kd_jab;
			$bolehApprove = false;

			// ===== LOGIKA APPROVE (ASLI, DISAMAKAN) =====

			// Kadiv
			if ($tk_jabatan == 10 || $tk_jabatan == "102" || $tk_jabatan == "26") {
				if (in_array($kd_jab, ['01', '02', '04'])) {
					$bolehApprove = true;
				}
			}
			// Manager
			else if ($tk_jabatan <= 28) {
				if ($kd_unit == "90AD") {
					$bolehApprove = true;
				} else if (in_array($kd_jab, ['01','02','04','10','102','26'])) {
					$bolehApprove = true;
				}
			}
			// BM - MO
			else if (($tk_jabatan > 28 && $tk_jabatan < 40) || $kd_level == 2) {
				if ($kd_jab <= 28 || $kd_level == 2) {
					$bolehApprove = true;
				}
			}
			// SPV - STAFF
			else {
				if ($kd_jab < 36) {
					$bolehApprove = true;
				}
			}

			// ===== EKSEKUSI UPDATE =====
			if ($bolehApprove) {
				$this->db->where('id_izin', $id_izin)
					->update('t_izin', [
						'flag_app'  => 1,
						'user_app' => $username,
						'tgl_app'  => date('Y-m-d H:i:s')
					]);

				$approved[] = $id_izin;
			} else {
				$rejected[] = $id_izin;
			}
		}

		echo json_encode([
			'status'    => true,
			'approved'  => count($approved),
			'rejected'  => count($rejected),
			'detail'    => [
			'approved_ids' => $approved,
			'rejected_ids' => $rejected
			]
		]);
	}

	public function approve_multi_cuti()
	{
		// ambil user login
		$username = $this->session->userdata('username');
		// $username = "KW98051";
		$ids = $this->input->post('ids');

		if (empty($ids) || !is_array($ids)) {
			echo json_encode(['status' => false, 'message' => 'Data kosong']);
			return;
		}

		// === DATA USER APPROVER ===
		$rdt = $this->db
			->where('no_peg', $username)
			->get('mas_peg')
			->row();

		if (!$rdt) {
			echo json_encode(['status' => false, 'message' => 'User tidak valid']);
			return;
		}

		$kd_jab   = $rdt->kd_jab;
		$kd_level = $rdt->kd_level;
		$kd_unit  = $rdt->kd_unit;

		$approved = [];
		$rejected = [];

		foreach ($ids as $id_cuti) {

			// === CEK DATA CUTI ===
			$dt = $this->db
				->select('t_cuti.*, mas_peg.kd_jab')
				->from('t_cuti')
				->join('mas_peg', 'mas_peg.no_peg = t_cuti.no_peg', 'left')
				->where('t_cuti.id', $id_cuti)
				->get()
				->row();

			if (!$dt || $dt->status_approve == 1) {
				$rejected[] = $id_cuti;
				continue;
			}

			$tk_jabatan = $dt->kd_jab;
			$bolehApprove = false;

			// ===== LOGIKA APPROVE (ASLI, DISAMAKAN) =====

			// Kadiv
			if ($tk_jabatan == 10 || $tk_jabatan == "102" || $tk_jabatan == "26") {
				if (in_array($kd_jab, ['01', '02', '04'])) {
					$bolehApprove = true;
				}
			}
			// Manager
			else if ($tk_jabatan <= 28) {
				if ($kd_unit == "90AD") {
					$bolehApprove = true;
				} else if (in_array($kd_jab, ['01','02','04','10','102','26'])) {
					$bolehApprove = true;
				}
			}
			// BM - MO
			else if (($tk_jabatan > 28 && $tk_jabatan < 40) || $kd_level == 2) {
				if ($kd_jab <= 28 || $kd_level == 2) {
					$bolehApprove = true;
				}
			}
			// SPV - STAFF
			else {
				if ($kd_jab < 36) {
					$bolehApprove = true;
				}
			}

			// ===== EKSEKUSI UPDATE =====
			if ($bolehApprove) {
				$this->db->where('id', $id_cuti)
					->update('t_cuti', [
						'status_approve'  => 1,
						'tgl_approve'  => date('Y-m-d H:i:s')
					]);

				$approved[] = $id_cuti;
			} else {
				$rejected[] = $id_cuti;
			}
		}

		echo json_encode([
			'status'    => true,
			'approved'  => count($approved),
			'rejected'  => count($rejected),
			'detail'    => [
			'approved_ids' => $approved,
			'rejected_ids' => $rejected
			]
		]);
	}

	public function approve_multi_mutasi()
	{
		// ambil user login
		$username = $this->session->userdata('username');
		$jab = $this->session->userdata('jab');
		// $username = "KW98051";
		$ids = $this->input->post('ids');
		$today = date('Y-m-d H:i:s');

		if (empty($ids) || !is_array($ids)) {
			echo json_encode(['status' => false, 'message' => 'Data kosong']);
			return;
		}

		// === DATA USER APPROVER ===
		$rdt = $this->db
			->where('no_peg', $username)
			->get('mas_peg')
			->row();

		if (!$rdt) {
			echo json_encode(['status' => false, 'message' => 'User tidak valid']);
			return;
		}

		$kd_jab   = $rdt->kd_jab;
		$kd_level = $rdt->kd_level;
		$kd_unit  = $rdt->kd_unit;

		$approved = [];
		$rejected = [];

		foreach ($ids as $id_mutasi) {

			$bolehApprove = false;
			// ===== LOGIKA APPROVE (ASLI, DISAMAKAN) =====

			// Kadiv
			if ($jab == "KADIV" || $jab == "MR" || $jab == "PENGURUS") {
				$bolehApprove = true;
			}
			
			// ===== EKSEKUSI UPDATE =====
			if ($bolehApprove) {
				if($jab == "MR"){
					if($kd_unit == "90C0"){
						$qmutasi = "SELECT a.*,b.na_peg,c.nm_job,d.nm_jab,f.kd_bagian AS bagianasal,h.kd_bagian AS bagiantujuan FROM db_hrd.t_pengajuan_mutasi a 
						LEFT JOIN db_hrd.mas_peg b ON a.`no_peg` = b.`no_peg` 
						LEFT JOIN db_hrd.m_jobdesc c ON a.kd_job = c.kd_job
						LEFT JOIN db_hrd.m_jabatan d ON a.kd_jab = d.kd_jab
						LEFT JOIN db_hrd.m_unit e ON a.kd_unit_asal = e.kd_unit
						LEFT JOIN db_hrd.m_bagian f ON e.kd_bagian = f.kd_bagian
						LEFT JOIN db_hrd.m_unit g ON a.`kd_unit_tujuan` =  g.`kd_unit`
						LEFT JOIN db_hrd.m_bagian h ON g.kd_bagian = h.kd_bagian
						WHERE a.is_del = 0 AND a.flag_app_sdm = 0 and a.id_mutasi = '$id_mutasi'";
						$val = $this->db->query($qmutasi)->result();

						$update = "update db_hrd.t_pengajuan_mutasi set flag_app_sdm=1,tgl_app_sdm='$today',user_app_sdm='$username' where id_mutasi = '$id_mutasi'";
						$result = $this->db->query($update);
					}
					else{

						$qmutasi = "SELECT a.*,b.na_peg,c.nm_job,d.nm_jab,f.kd_bagian AS bagianasal,h.kd_bagian AS bagiantujuan FROM db_hrd.t_pengajuan_mutasi a 
						LEFT JOIN db_hrd.mas_peg b ON a.`no_peg` = b.`no_peg` 
						LEFT JOIN db_hrd.m_jobdesc c ON a.kd_job = c.kd_job
						LEFT JOIN db_hrd.m_jabatan d ON a.kd_jab = d.kd_jab
						LEFT JOIN db_hrd.m_unit e ON a.kd_unit_asal = e.kd_unit
						LEFT JOIN db_hrd.m_bagian f ON e.kd_bagian = f.kd_bagian
						LEFT JOIN db_hrd.m_unit g ON a.`kd_unit_tujuan` =  g.`kd_unit`
						LEFT JOIN db_hrd.m_bagian h ON g.kd_bagian = h.kd_bagian
						WHERE a.is_del = 0 AND a.flag_app_sdm = 1 AND a.flag_app_kadiv=0 AND a.id_mutasi = '$id_mutasi'";

						$val = $this->db->query($qmutasi)->result();
						$bagianasal = $val[0]->bagianasal;
						$bagiantujuan = $val[0]->bagiantujuan;

						if($bagianasal == $bagiantujuan){
							$update = "update db_hrd.t_pengajuan_mutasi set flag_app_unit=1,tgl_app_unit='$today',user_app_unit='$username',flag_app_tujuan=1,tgl_app_tujuan='$today',user_app_tujuan='$username' where id_mutasi = '$id_mutasi'";
							$result = $this->db->query($update);
						}
						else{
							if($bagianasal == $kd_unit){
								$update = "update db_hrd.t_pengajuan_mutasi set flag_app_unit=1,tgl_app_unit='$today',user_app_unit='$username' where id_mutasi = '$id_mutasi'";
								$result = $this->db->query($update);
							}
							else if($bagiantujuan == $kd_unit){
								$update = "update db_hrd.t_pengajuan_mutasi set flag_app_tujuan=1,tgl_app_tujuan='$today',user_app_tujuan='$username' where id_mutasi = '$id_mutasi'";
								$result = $this->db->query($update);
							}
						}
					}

				}
				else if($jab == "KADIV"){
					$update = "update db_hrd.t_pengajuan_mutasi set flag_app_kadiv=1,tgl_app_kadiv='$today',user_app_kadiv='$username' where id_mutasi = '$id_mutasi'";
					$result = $this->db->query($update);

					$qmutasi = "SELECT a.*,b.na_peg,c.nm_jab,d.nm_job FROM db_hrd.t_pengajuan_mutasi a 
					LEFT JOIN db_hrd.mas_peg b ON a.no_peg = b.`no_peg`
					LEFT JOIN db_hrd.m_jabatan c ON a.kd_jab = c.kd_jab
					LEFT JOIN db_hrd.m_jobdesc d ON a.`kd_job` = d.kd_job
					WHERE a.id_mutasi = '$id_mutasi'";

					$val = $this->db->query($qmutasi)->result();
					$na_peg = $val[0]->na_peg;
					$jns_perubahan = $val[0]->jns_perubahan;

					if($jns_perubahan == 1 || $jns_perubahan == 6){ // -- mutasi --
						$jns_bm = "MUTASI";
						$keterangan_bm = $val[0]->no_peg."-".$na_peg." (".$val[0]->nm_unit_asal." Ke ".$val[0]->nm_unit_tujuan." )";
					}
					else if($jns_perubahan == 2){
						// --- promosi ---
						$jns_bm = "PROMOSI";
						$keterangan_bm = $val[0]->no_peg."-".$na_peg." (".$val[0]->nm_jab." Ke ".$val[0]->nm_jab_baru." )";
					}
					else if($jns_perubahan == 3){
						// --- promosi ---
						$jns_bm = "DEMOSI";
						$keterangan_bm = $val[0]->no_peg."-".$na_peg." (".$val[0]->nm_jab." Ke ".$val[0]->nm_jab_baru." )";
					}
					else{
						$jns_bm = "PENUGASAN";
						$keterangan_bm = $val[0]->no_peg."-".$na_peg." (".$val[0]->nm_job." Ke ".$val[0]->nm_job_baru." )";
					}

					//---insert broadcast ----
					$ins = "insert into db_hrd.broadcast set jns_bm = '$jns_bm', message = '$keterangan_bm'";
					$this->db->query($ins);
				}
				else if($jab == "PENGURUS"){
					if($kd_jab == "02"){ // === sekretaris ===
						$update = "update db_hrd.t_pengajuan_mutasi set flag_app_sekretaris=1,tgl_app_sekretaris='$today',user_app_sekretaris='$username' where id_mutasi = '$id_mutasi'";
						$result = $this->db->query($update);
					}
					else if($kd_jab == "04"){ // === bendahara ===
						$update = "update db_hrd.t_pengajuan_mutasi set flag_app_bendahara=1,tgl_app_bendahara='$today',user_app_bendahara='$username' where id_mutasi = '$id_mutasi'";
						$result = $this->db->query($update);
					}
					else{
						$update = "update db_hrd.t_pengajuan_mutasi set flag_app_pengurus=1,tgl_app_pengurus='$today',user_app_pengurus='$username' where id_mutasi = '$id_mutasi'";
						$result = $this->db->query($update);
					}
				}

				$approved[] = $id_mutasi;
			} else {
				$rejected[] = $id_mutasi;
			}
		}

		echo json_encode([
			'status'    => true,
			'approved'  => count($approved),
			'rejected'  => count($rejected),
			'detail'    => [
			'approved_ids' => $approved,
			'rejected_ids' => $rejected
			]
		]);
	}

	function save_signature(){
		$username = $this->session->userdata('username');
		header('Content-Type: application/json');
		$today = date("Y-m-d");

		$raw = file_get_contents("php://input");
		$data = json_decode($raw, true);

		// fallback jika JSON kosong
		if (!$data) {
			$data = $this->input->post();
		}

		// GANTI bagian ini (hapus tanda ??)
		$id_request = isset($data['id_request']) ? $data['id_request'] : '';
		$image      = isset($data['image']) ? $data['image'] : '';

		if ($id_request == '' || $image == '') {
			echo json_encode(array('success' => false, 'error' => 'Data tidak lengkap'));
			return;
		}

		if (!preg_match('/^data:image\/png;base64,/', $image)) {
			echo json_encode(array('success' => false, 'error' => 'Format gambar salah'));
			return;
		}

		//---cek status pengajuan---
		$queryCek = "SELECT a.*,c.`kd_divisi` FROM db_hrd.t_request_pegawai a 
		LEFT JOIN db_hrd.`m_unit` b ON a.`kd_unit` = b.`kd_unit`
		LEFT JOIN db_hrd.`m_bagian` c ON b.`kd_bagian` = c.`kd_bagian`
		WHERE a.id_req = '$id_request'";
		$value = $this->db_hrdonline->query($queryCek)->result();
		$flag_app_unit = $value[0]->flag_app_unit;
		$flag_app_sdm = $value[0]->flag_app_sdm;
		$flag_app_kadiv = $value[0]->flag_app_kadiv;
		$kd_divisi = $value[0]->kd_divisi;

		if($flag_app_unit == 0 && $flag_app_sdm == 0 && $flag_app_kadiv == 0){
			$ttd = $id_request."mr";
			$jenisttd = "signmr";
		}
		else{
			if($flag_app_sdm == 0 && $flag_app_unit == 1 && $flag_app_kadiv == 0){
				$ttd = $id_request."hr";
				$jenisttd = "signhr";
			}
			else{
				if($flag_app_kadiv == 0){
					$ttd = $id_request."kadiv";
					$jenisttd = "signkadiv";
				}
			}
		}

		$base64 = substr($image, strpos($image, ',') + 1);
		$decoded = base64_decode($base64);
		if (!$decoded) {
			echo json_encode(array('success' => false, 'error' => 'Gagal decode base64'));
			return;
		}

		// buat folder uploads/signatures/
		$dir = FCPATH . 'uploads/signature/';
		if (!is_dir($dir)) mkdir($dir, 0755, true);
		$filename = 'ttd_' . $ttd . '_' . date('Ymd_His') . '.png';
		$final_file_path = $dir . $filename;

		if (!file_put_contents($final_file_path, $decoded)) {
			echo json_encode(array('success' => false, 'error' => 'Gagal menyimpan file'));
			return;
		}

		// $final_file_path = 'ttd_' . $id_request . '_' . date('Ymd_His') . '.png';

		// === Kirim file ke SIPANDU ===
		$cfile = new CURLFile($final_file_path, mime_content_type($final_file_path), basename($final_file_path));
		$curl  = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL            => 'https://sipandu.kwsg.co.id/upload/do_upload_signature',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 60,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => [
				'userfile'     => $cfile,
				'kd_pengajuan' => $id_request,
				'jenis'        => $jenisttd,
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

		// contoh update ke database (opsional)
		if($flag_app_unit == 0 && $flag_app_sdm == 0 && $flag_app_kadiv == 0){
			$updatequery = "Update db_hrd.t_request_pegawai set flag_app_unit = 1,tgl_app_unit='$today',user_app_unit='$username',file_app_unit='$response' where id_req = '$id_request'";
		}
		else{
			if($flag_app_sdm == 0 && $flag_app_unit == 1 && $flag_app_kadiv == 0){
				if($kd_divisi == ""){
					$updatequery = "Update db_hrd.t_request_pegawai set flag_app_sdm = 1,tgl_app_sdm='$today',user_app_sdm='$username',file_app_sdm='$response',flag_app_kadiv = 1,tgl_app_kadiv='$today' where id_req = '$id_request' and flag_app_unit = 1";

					$query = "SELECT a.*,b.nm_unit FROM db_hrd.t_request_pegawai a 
					LEFT JOIN db_hrd.m_unit b ON a.kd_unit = b.kd_unit WHERE a.id_req = '$id_request'";
					$val = $this->db_hrdonline->query($query)->result();

					$jns_bm = "PENGAJUAN PEGAWAI";
					$keterangan_bm = $val[0]->nm_unit." (".$val[0]->job_desc." )";

					//---insert broadcast ----
					$data = array(
						"jns_bm" => $jns_bm,
						"message" => $keterangan_bm
					);
					$this->db_hrdonline->insert("db_hrd.broadcast",$data);
				}
				else{
					$updatequery = "Update db_hrd.t_request_pegawai set flag_app_sdm = 1,tgl_app_sdm='$today',user_app_sdm='$username',file_app_sdm='$response' where id_req = '$id_request' and flag_app_unit = 1";
				}
			}
			else{
				if($flag_app_kadiv == 0){
					$updatequery = "Update db_hrd.t_request_pegawai set flag_app_kadiv = 1,tgl_app_kadiv='$today',user_app_kadiv='$username',file_app_kadiv='$response' where id_req = '$id_request' and flag_app_sdm = 1";

					// $param = array();
					// $param['id_request'] = $id_request;
					// $databroadcast = $this->sdm_model->get_broadcast_request($param);

					$query = "SELECT a.*,b.nm_unit FROM db_hrd.t_request_pegawai a 
					LEFT JOIN db_hrd.m_unit b ON a.kd_unit = b.kd_unit WHERE a.id_req = '$id_request'";
					$val = $this->db_hrdonline->query($query)->result();

					$jns_bm = "PENGAJUAN PEGAWAI";
					$keterangan_bm = $val[0]->nm_unit." (".$val[0]->job_desc." )";

					//---insert broadcast ----
					$data = array(
						"jns_bm" => $jns_bm,
						"message" => $keterangan_bm
					);
					$this->db_hrdonline->insert("db_hrd.broadcast",$data);
				}
			}
		}
		
		$this->db_hrdonline->query($updatequery);

		echo json_encode(array(
			'success' => true,
			'file' => base_url('uploads/signature/' . $filename),
			'message' => 'Tanda tangan tersimpan'
		));
	}

	function approve_multi_request(){
		// ambil user login
		$username = $this->session->userdata('username');
		$jab = $this->session->userdata('jab');
		// sementara (debug)
		// $username = "KW98051";

		$ids = $this->input->post('ids');

		if (empty($ids) || !is_array($ids)) {
			echo json_encode(['status' => false, 'message' => 'Data kosong']);
			return;
		}

		// === DATA USER APPROVER ===
		$rdt = $this->db
			->where('no_peg', $username)
			->get('mas_peg')
			->row();

		if (!$rdt) {
			echo json_encode(['status' => false, 'message' => 'User tidak valid']);
			return;
		}

		$kd_jab   = $rdt->kd_jab;
		$kd_level = $rdt->kd_level;
		$kd_unit  = $rdt->kd_unit;

		$approved = [];
		$rejected = [];

		foreach ($ids as $id_req) {

			$bolehApprove = false;

			// ===== LOGIKA APPROVE (ASLI, DISAMAKAN) =====
			if($jab == "MR" || $jab == "PENGURUS"){
				$bolehApprove = true;
			}
			// ===== EKSEKUSI UPDATE =====
			if ($bolehApprove) {
				if($kd_jab == "02" && $jab == "PENGURUS"){ 
					$this->db->where('id_req', $id_req)
						->update('t_request_pegawai', [
							'flag_app_sekrtrs'  => 1,
							'user_app_sekrtrs' => $username,
							'tgl_app_sekrtrs'  => date('Y-m-d H:i:s')
						]);
				}
				else if($kd_jab == "04" && $jab == "PENGURUS"){ 
					$this->db->where('id_req', $id_req)
						->update('t_request_pegawai', [
							'flag_app_bendahara'  => 1,
							'user_app_bendahara' => $username,
							'tgl_app_bendahara'  => date('Y-m-d H:i:s')
						]);
				}
				else if($kd_jab == "01" && $jab == "PENGURUS"){ 
					$this->db->where('id_req', $id_req)
						->update('t_request_pegawai', [
							'flag_app_ketua'  => 1,
							'user_app_ketua' => $username,
							'tgl_app_ketua'  => date('Y-m-d H:i:s')
						]);
				}
				else{
					$this->db->where('id_req', $id_req)
					->update('t_request_pegawai', [
						'flag_app_sdm'  => 1,
						'user_app_sdm' => $username,
						'tgl_app_sdm'  => date('Y-m-d H:i:s')
					]);
				}
				$approved[] = $id_req;
			} else {
				$rejected[] = $id_req;
			}
		}

		echo json_encode([
			'status'    => true,
			'approved'  => count($approved),
			'rejected'  => count($rejected),
			'detail'    => [
				'approved_ids' => $approved,
				'rejected_ids' => $rejected
			]
		]);
	}
}
