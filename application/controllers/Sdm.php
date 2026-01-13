<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sdm extends CI_Controller {

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

		if($this->session->userdata('username') == ""){
			redirect('Welcome/index');
		}
	}
	
	public function inpcuti()
	{
		$no_peg = $this->session->userdata('username');

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('sdm/inp_cuti',$Param);	
		$this->load->view('general/footer');	
	}

	public function inpizin()
	{
		$no_peg = $this->session->userdata('username');

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('sdm/inp_izin',$Param);	
		$this->load->view('general/footer');	
	}

	public function datacuti()
	{
		$no_peg = $this->session->userdata('username');

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('sdm/data_cuti',$Param);	
		$this->load->view('general/footer');	
	}

	public function dataizin()
	{
		$no_peg = $this->session->userdata('username');

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('sdm/data_izin',$Param);	
		$this->load->view('general/footer');	
	}

	public function inpsppd()
	{
		$no_peg = $this->session->userdata('username');

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('sdm/inp_sppd',$Param);	
		$this->load->view('general/footer');	
	}

	public function inp_pjksppd()
	{
		$no_peg = $this->session->userdata('username');

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('sdm/inp_pjksppd',$Param);	
		$this->load->view('general/footer');	
	}

	public function datasppd()
	{
		$no_peg = $this->session->userdata('username');
		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('sdm/data_sppd',$Param);	
		$this->load->view('general/footer');	
	}

	public function datapjksppd()
	{
		$no_peg = $this->session->userdata('username');
		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('sdm/data_pjksppd',$Param);	
		$this->load->view('general/footer');	
	}

	public function pageapprove()
	{
		$no_peg = $this->session->userdata('username');
		$jab = $this->session->userdata('jab');

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	

		if($no_peg == "KW16004"){
			$this->load->view('sdm/page_approve',$Param);	
		}
		else if($jab == "STAFF" || $jab == "SPV"){
			$this->load->view('not_found',$Param);
		}	
		else{
			$this->load->view('sdm/page_approve',$Param);	
		}
		$this->load->view('general/footer');	
	}

	public function pagemenu()
	{
		$no_peg = $this->session->userdata('username');
		$jab = $this->session->userdata('jab');

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('page_menu',$Param);	
		$this->load->view('general/footer');	
	}

	public function app_izin()
	{
		$no_peg = $this->session->userdata('username');
		// $no_peg = "KW98051";

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('sdm/data_app_izin',$Param);	
		$this->load->view('general/footer');	
	}

	public function view_app_izin()
	{
		$no_peg = $this->session->userdata('username');
		// $no_peg = "KW98051";

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('sdm/view_app_izin',$Param);	
		$this->load->view('general/footer');	
	}

	function ajaxdtsaldocuti(){
		$no_peg = $_GET['no_peg'];
		$tglnow = date("Y-m-d");
		$tahun = date('Y');
		//$query = "SELECT (saldo_cuti+(IF(tgl_exp_ctb>=NOW(),saldo_cuti_besar,0))) AS sisa_cuti FROM saldo_cuti WHERE no_peg = '$no_peg'";
		$query = "select * from saldo_cuti where no_peg = '$no_peg'";
		$r_dt = $this->db_hrdonline->query($query)->num_rows();

		if($r_dt == 0){
			$query = "SELECT 12 AS sisa_cuti";
			$result = $this->ddb_hrdonlineb->query($query)->row();
			$sisa_cuti = 12;
		}
		else{
			$result = $this->db_hrdonline->query($query)->result();
			$tgl_exp_ctb = $result[0]->tgl_exp_ctb;
			$cuti = $result[0]->cuti;
			$batal = $result[0]->batal;
			$saldo_cuti = $result[0]->saldo_cuti;
			$saldo_cuti_besar = $result[0]->saldo_cuti_besar;
			
			if($saldo_cuti_besar <> 0 && $tglnow <= $tgl_exp_ctb){
				$saldo_ctb = $saldo_cuti_besar;
				$saldoctthn = $saldo_cuti - $cuti + $batal;
				
				if($saldoctthn < 0){
					$saldoct_peg = 0;
					$saldoctb_peg = $saldo_ctb + $saldoctthn;
				}
				else{
					$saldoctb_peg = $saldo_ctb;
					$saldoct_peg = $saldoctthn;
				}
			}
			else{
				$saldo_ctb = 0;
				$saldoctb_peg = 0;
				$saldoct_peg = $saldo_cuti - $cuti + $batal;
			}
			$sisa_cuti = $saldoct_peg + $saldoctb_peg;
			
			
			//$result = $this->db->query($query)->row();
		}
		echo $sisa_cuti;
	}

	function ins_cutipegawai(){
		$username = $this->session->userdata('username');
		$tanggal = date("Y-m-d");
		$no_peg = $_POST['no_peg'];
		$kd_unit = $_POST['kd_unit'];
		$kdcuti = $_POST['kdcuti'];
		$tgl_awal = $_POST['tgl_cuti_awal'];
		$tgl_akhir =$_POST['tgl_cuti_akhir'];
		$keterangan = $_POST['keterangan'];
		$saldo_cuti = $_POST['saldo_cuti'];
		$jmlama=0;
		$jmlama_libur=0;
		
		// --- cek kd job ---
		$query="SELECT a.*,b.hari_kerja FROM mas_peg a LEFT JOIN m_unit b ON a.kd_unit = b.kd_unit WHERE a.no_peg = '$no_peg'";
		$dt = $this->db_hrdonline->query($query)->result();
		$kd_job = $dt[0]->kd_job;
		$hari_kerja = $dt[0]->hari_kerja;
		
		if($tgl_awal == $tgl_akhir){
			$lama = 1;
		}
		else{
			$lama = (((abs(strtotime ($tgl_awal) - strtotime ($tgl_akhir)))/(60*60*24))) + 1;
		}

		if($kd_job == "N11" || $kd_job == "N12" || $kd_job == "D42"){ // --- khusus satpam ---
			if($lama > 1){
				for ($x = 1; $x < $lama; $x++) {
					$tgl1 = $tgl_awal;// pendefinisian tanggal awal
					$tgl2 = date('Y-m-d', strtotime('+'.$x.'days', strtotime($tgl1)));
	
					$cekdtLibur = "select * from t_shift where tanggal = '$tgl2' and shift = 'off' and no_peg = '$no_peg'";
					$rdt = $this->db_hrdonline->query($cekdtLibur)->num_rows();
					if($rdt > 0){
						$jmlama++;
					}
				}
			}
		}
		else{
			if($lama > 1){
				for ($x = 1; $x < $lama; $x++) {
					$tgl1 = $tgl_awal;// pendefinisian tanggal awal
					$tgl2 = date('Y-m-d', strtotime('+'.$x.'days', strtotime($tgl1)));
	
					$hari = date("D",strtotime($tgl2));
					
					if($hari_kerja == 5){
						if($hari == "Sun" || $hari == "Sat"){
							$jmlama++;
						}
					}
					else{
						if($hari == "Sun"){
							$jmlama++;
						}
					}

					//---cek libur nasional---
					$queryLibur = "select * from m_libur where tgl_libur = '$tgl2'";
					$rdt = $this->db_hrdonline->query($queryLibur)->num_rows();
					if($rdt > 0){
						$jmlama_libur++;
					}
					
				}
			}
		}
		
		$lama = $lama -  $jmlama - $jmlama_libur;
		
		$paramkb= array();
		$paramkb['kd'] = 'CT';
		$paramkb['tanggal'] = $tanggal;
		$DataResult=$this->sdm_model->get_bukti($paramkb);
		$tahun = $this->func_global->year($tgl_awal);
		
		if($kdcuti == 'CT'){
			$sisa_saldo = $saldo_cuti - $lama;
		}
		else{
			$sisa_saldo  = $saldo_cuti;
		}
		
		if($sisa_saldo < 0){
			echo 2;
		}
		else{
			// --- saldo cuti --
			$ceksaldo = "SELECT * FROM saldo_cuti WHERE no_peg = '$no_peg'";
			$r_dt = $this->db_hrdonline->query($ceksaldo)->num_rows();
			if($r_dt == 0){
				if($kdcuti == 'CT'){
					$ins = "insert into saldo_cuti set no_peg = '$no_peg',cuti='$lama',saldo_cuti='$sisa_saldo'";
					$r_ins = $this->db_hrdonline->query($ins);
				}
			}
			else{
				$rdt = $this->db->query($ceksaldo)->result();
				$saldo_akhir = $rdt[0]->saldo_cuti;
				$saldo_ctb = $rdt[0]->saldo_cuti_besar;
				$cuti = $rdt[0]->cuti;
				$batal = $rdt[0]->batal;
				$tgl_exp_ctb = $rdt[0]->tgl_exp_ctb;
				$updatect = $cuti + $lama;
				
				if($saldo_ctb <> 0 && $tgl_awal<= $tgl_exp_ctb){
					$updatesaldo = $saldo_ctb - $lama;
					if($kdcuti == 'CT'){
						$update = "update saldo_cuti set cuti='$updatect' WHERE no_peg = '$no_peg'";
						$r_update = $this->db_hrdonline->query($update);
					}
				}
				else{
					$updatesaldo = $saldo_akhir - $lama;
					if($kdcuti == 'CT'){
						$update = "update saldo_cuti set cuti='$updatect' WHERE no_peg = '$no_peg'";
						$r_update = $this->db_hrdonline->query($update);
					}
				}
			}
			
			$inscuti = "insert into t_cuti set tanggal = '$tanggal',no_bukti='$DataResult',no_peg='$no_peg',kd_unit='$kd_unit',kd_cuti='$kdcuti',tgl_awal='$tgl_awal',tgl_akhir='$tgl_akhir',keterangan='$keterangan',user='$username',lama='$lama',sisa_saldo='$sisa_saldo'";
			$result = $this->db_hrdonline->query($inscuti);
			echo 1;
		}
	}

	function hapusdtcuti(){
		$param = array();
		$param['id_cuti'] = $_POST['id_cuti'];
		$DataResult = $this->sdm_model->hapusCuti($param);

		echo json_encode(['status' => true]);
	}

	function ins_izinpegawai(){
		$username = $this->session->userdata('username');
		$tanggal = date("Y-m-d");
		$no_peg = $_POST['no_peg'];
		$kd_unit = $_POST['kd_unit'];
		$kd_jab = $_POST['kd_jab'];
		$kdizin = $_POST['kdizin'];
		$tgl_izin = $_POST['tgl_izin'];
		$keterangan = $_POST['keterangan'];

		$paramkb= array();
		$paramkb['kd'] = 'IZN';
		$paramkb['tanggal'] = $tanggal;
		$DataResult=$this->sdm_model->get_bukti($paramkb);

		//---cek data ---
		$query = "SELECT * FROM t_izin WHERE no_peg = '$no_peg' and tgl_izin = '$tgl_izin' and status_hapus = 0";
		$rdt = $this->db->query($query)->num_rows();
		if($rdt == 0){
			$data = array(
				'no_peg' => $no_peg,
				'no_bukti' => $DataResult,
				'kd_unit' => $kd_unit,
				'kd_jab' => $kd_jab,
				'tgl_izin' => $tgl_izin,
				'keterangan' => $keterangan,
				'kdizin' => $kdizin
			);
			$this->db_hrdonline->insert('t_izin', $data);
			echo 1;
		}
		else{
			echo 2;
		}
	}

	function hapusdtizin(){
		$id_izin = $_POST['id_izin'];

		//--cek data---
		$query = "Select flag_app FROM t_izin WHERE id_izin = '$id_izin'";
		$rdt = $this->db->query($query)->result();
		$flag_app = $rdt[0]->flag_app;

		if($flag_app == 0){
			$update = "update t_izin set status_hapus = 1 where id_izin = '$id_izin'";
			$this->db->query($update);
			echo json_encode(['status' => true]);
		}
		else{
			echo json_encode(['status' => false, 'message' => 'Gagal hapus data']);
		}
		
	}

	function appdtizin(){
		$username = $this->session->userdata('username');
		$id_izin = $_POST['id_izin'];
		$update = "update t_izin set flag_app = 1,tgl_app=NOW(),user_app='$username' where id_izin = '$id_izin'";
		$this->db->query($update);
		echo json_encode(['status' => true]);
	}

	function bataldtizin(){
		$username = $this->session->userdata('username');
		$id_izin = $_POST['id_izin'];
		$update = "update t_izin set flag_app = 0,tgl_app='(NULL)',user_app='(NULL)' where id_izin = '$id_izin'";
		$this->db->query($update);
		echo json_encode(['status' => true]);
	}
	

	function ins_sppdpegawai(){
		$username = $this->session->userdata('username');
		$tanggal = date("Y-m-d");
		$no_peg = $_POST['no_peg'];
		$kd_unit = $_POST['kd_unit'];
		$beban = $_POST['beban'];
		$kd_jab = $_POST['kd_jab'];
		$tgl_awal = $_POST['tgl_awal'];
		$tgl_akhir = $_POST['tgl_akhir'];
		$keterangan = str_replace("'","",strtoupper($_POST['keterangan']));
		$tujuan = strtoupper($_POST['tujuan']);
		$kendaraan = $_POST['kendaraan'];
		$keperluan = $_POST['keperluan'];
		$akomodasi = $_POST['akomodasi'];

		if($tgl_akhir < $tgl_awal){
			echo 2;
		}
		else{
			$paramkb= array();
			$paramkb['kd'] = 'SPD';
			$paramkb['tanggal'] = $tanggal;
			$DataResult=$this->sdm_model->get_bukti($paramkb);

			$insert = "insert into t_sppd set tanggal = '$tanggal',bukti='$DataResult',no_peg='$no_peg',unit='$kd_unit',tk_jabatan='$kd_jab',tujuan='$tujuan',dalam_rangka='$keterangan',beban='$beban',unit_asli = '$kd_unit',keperluan='$keperluan',kendaraan='$kendaraan',tgl_awal='$tgl_awal',tgl_akhir='$tgl_akhir',jns_sppd='$keperluan',khusus='0',user_id='$username',akomodasi='$akomodasi'";
			
			$this->db->query($insert);
			
			echo 1;
		}
	}

	function hapusdtsppd(){
		$username = $this->session->userdata('username');
		$id_sppd = $_POST['id_sppd'];
		$cekdt = "select * from t_sppd where id= '".$_POST['id_sppd']."'";
		$rdt = $this->db->query($cekdt)->result();
		$approve = $rdt[0]->APPROVE;
		
		if($approve == 0){
			$hapus = "update t_sppd set hapus = 1,TGL_HAPUS = NOW(),USER_HAPUS='$username' where id = '".$_POST['id_sppd']."'";
			$this->db->query($hapus);
			echo json_encode(['status' => true]);
		}
		else{
			echo json_encode(['status' => false, 'message' => 'SPPD Sudah di Approve']);
		}
	}

	function getdatasppd(){
		$query = "SELECT a.*,b.na_peg,c.nm_unit,d.nm_jab,DATE_FORMAT(a.TGL_AWAL,'%d-%m-%Y') AS tglawal,DATE_FORMAT(a.TGL_AKHIR,'%d-%m-%Y') AS tglakhir,IF(a.AKOMODASI='1','hotel',IF(a.AKOMODASI='2','luar','mess')) AS penginapan  FROM t_sppd a 
		LEFT JOIN mas_peg b ON a.NO_PEG = b.no_peg 
		LEFT JOIN m_unit c ON b.kd_unit = c.kd_unit
		LEFT JOIN m_jabatan d on a.TK_JABATAN = d.kd_jab
		WHERE a.BUKTI = '".$_GET['bukti_sppd']."'";
		$result = $this->db->query($query)->row();
		echo json_encode($result);
	}

	function ins_ppdpegawai(){
		$username = $this->session->userdata('username');
		$tanggal = date("Y-m-d");
		$bukti_sppd = $_POST['bukti_sppd'];
		$no_peg = $_POST['no_peg'];
		$kd_unit = $_POST['kd_unit'];
		$beban = $_POST['beban'];
		$kd_jab = $_POST['kd_jab'];
		$tgl_awal = $_POST['tgl_awal'];
		$tgl_akhir = $_POST['tgl_akhir'];
		$keterangan = str_replace("'","",strtoupper($_POST['keterangan']));
		$tujuan = strtoupper($_POST['tujuan']);
		$kendaraan = $_POST['kendaraan'];
		$keperluan = $_POST['keperluan'];
		$akomodasi = $_POST['akomodasi'];
		$jam_berangkat = $_POST['jamberangkat'];
		$jam_pulang = $_POST['jampulang'];
		$khusus=0;
		$kas_keluar = $_POST['kas_keluar'];
		$taksi = 0;

		$qsppd = "SELECT AKOMODASI,KENDARAAN FROM t_sppd WHERE bukti = '$bukti_sppd'";
		$query = $this->db->query($qsppd)->result();
		$akomodasiSppd = $query[0]->AKOMODASI;
		$kendaraanSppd = $query[0]->KENDARAAN;

		if($_POST['khusus'] == '1'){
			$khusus = 1;
		}
		else{
			$khusus = 0;
		}

		if($kas_keluar == 1){
			$k_biaya = '';
		}
		else{
			$k_biaya = $_POST['ka_biaya'];
		}

		if($khusus == 'true'){
			$khusus = 1;
		}
		else{
			$khusus = 0;
		}

		if($tgl_akhir < $tgl_awal){
			echo 2;
		}
		else if($akomodasiSppd != $akomodasi){
			echo 4;
		}
		else{
			if($akomodasi == 'hotel' || $akomodasi == 1){
				// --- cek booking ---
				$qcek = "select * from db_booking.t_booking_hotel where bukti_sppd = '$bukti_sppd' and hapus = 0";
				$rdt = $this->db_hrdonline->query($qcek)->num_rows();
				if($rdt == 0){
					echo 3;
				}
				else{
					$paramkb= array();
					$paramkb['kd'] = 'PPD';
					$paramkb['tanggal'] = $tanggal;
					$DataResult=$this->sdm_model->get_bukti($paramkb);
					
					// --- hitung uang harian ---
					$param = array();
					$param['tgl_awal'] = $tgl_awal;
					$param['tgl_akhir'] = $tgl_akhir;
					$param['khusus'] = $khusus;
					$param['no_peg'] = $no_peg;
					$DataUangHarian=$this->sdm_model->get_uangharian($param);
					
					$strpulang = explode(":",$jam_pulang);
					$jampulang = $strpulang[0];
					$mntpulang = $strpulang[1];
						
					if((($jampulang == "24" || $jampulang == "00" || $jampulang == "0" || $jampulang == "01" || $jampulang == "1") && $mntpulang > 0) || $jam_pulang == "01:00"  || $jam_pulang == "1:00" || $jam_pulang == "1:0" || $jam_pulang == "02:00"  || $jam_pulang == "2:00" || $jam_pulang == "2:0"){
						$persen_inap = 25;
					}
					else if((($jampulang == "02" || $jampulang == "03" || $jampulang == "3") && $mntpulang > 0) || $jam_pulang == "03:00"  || $jam_pulang == "3:00" || $jam_pulang == "3:0" || $jam_pulang == "04:00"  || $jam_pulang == "4:00" || $jam_pulang == "4:0"){
						$persen_inap = 50;
					}
					else if(($jampulang == "04" && $mntpulang > 0) || $jam_pulang == "05:00"  || $jam_pulang == "5:00" || $jam_pulang == "5:0"){
						$persen_inap = 75;
					}
					else {
						$persen_inap = 100;
					}

					// --- hitung uang inap ---
					if($akomodasi <> "hotel"){
						$param = array();
						$param['tgl_awal'] = $tgl_awal;
						$param['tgl_akhir'] = $tgl_akhir;
						$param['no_peg'] = $no_peg;
						$param['jam_pulang'] = $jam_pulang;
						$DataUangInap=$this->sdm_model->get_uanginap($param);
						
						$lamainap = (((abs(strtotime ($tgl_awal) - strtotime ($tgl_akhir)))/(60*60*24)));
					}
					else{
						// --- cek booking ---
						$qbooking = "SELECT SUM(jml_hari_inap) AS jminap FROM db_booking.t_booking_hotel WHERE bukti_sppd = '$bukti_sppd' AND hapus = 0 AND harga <> 0";
						$rbk = $this->db->query($qbooking)->result();
						$lamainaphotel= $rbk[0]->jminap;
						$lamasppd = (((abs(strtotime ($tgl_awal) - strtotime ($tgl_akhir)))/(60*60*24)));
						
						$lamainap = $lamasppd - $lamainaphotel;
						
						$param = array();
						$param['tgl_awal'] = $tgl_awal;
						$param['tgl_akhir'] = $tgl_akhir;
						$param['no_peg'] = $no_peg;
						$param['jam_pulang'] = $jam_pulang;
						$param['lamainaphotel'] = $lamainaphotel;
						$DataUangInap=$this->sdm_model->get_uanginap_hotel($param);
					}
					
					$uang_pjk = $DataUangHarian + $DataUangInap;
					$insert = "insert into t_pjk set tgl_pjk = '$tanggal',bukti_pjk='$DataResult',bukti_ppl='$DataResult',bukti='$bukti_sppd',no_peg='$no_peg',unit='$kd_unit',tk_jabatan='$kd_jab',tujuan='$tujuan',beban='$beban',unit_asli = '$kd_unit',keperluan='$keperluan',kendaraan='$kendaraan',awal_tugas='$tgl_awal',akir_tugas='$tgl_akhir',jam_awal='$jam_berangkat',jam_akhir='$jam_pulang',jns_sppd='$keperluan',KA='$k_biaya',khusus='$khusus',user_id='$username',akomodasi='$akomodasi',pjk_inap='$DataUangInap',pjk_harian='$DataUangHarian',u_pjk='$uang_pjk',ket_kas='$kas_keluar',JM_INAP='$lamainap',PERSEN_INAP='$persen_inap',pjk_taxi='$taksi'";
					$this->db->query($insert);
					
					// -- update sppd --
					$update = "update t_sppd set STS_PJK = 1 where BUKTI = '$bukti_sppd'";
					$this->db->query($update);

					echo 1;
				}
			}
			else{
				$paramkb= array();
				$paramkb['kd'] = 'PPD';
				$paramkb['tanggal'] = $tanggal;
				$DataResult=$this->sdm_model->get_bukti($paramkb);

				// --- hitung uang harian ---
				$param = array();
				$param['tgl_awal'] = $tgl_awal;
				$param['tgl_akhir'] = $tgl_akhir;
				$param['khusus'] = $khusus;
				$param['no_peg'] = $no_peg;
				$DataUangHarian=$this->sdm_model->get_uangharian($param);
				
				$strpulang = explode(":",$jam_pulang);
				$jampulang = $strpulang[0];
				$mntpulang = $strpulang[1];

				if((($jampulang == "24" || $jampulang == "00" || $jampulang == "0" || $jampulang == "01" || $jampulang == "1") && $mntpulang > 0) || $jam_pulang == "01:00"  || $jam_pulang == "1:00" || $jam_pulang == "1:0" || $jam_pulang == "02:00"  || $jam_pulang == "2:00" || $jam_pulang == "2:0"){
					$persen_inap = 25;
				}
				else if((($jampulang == "02" || $jampulang == "03" || $jampulang == "3") && $mntpulang > 0) || $jam_pulang == "03:00"  || $jam_pulang == "3:00" || $jam_pulang == "3:0" || $jam_pulang == "04:00"  || $jam_pulang == "4:00" || $jam_pulang == "4:0"){
					$persen_inap = 50;
				}
				else if(($jampulang == "04" && $mntpulang > 0) || $jam_pulang == "05:00"  || $jam_pulang == "5:00" || $jam_pulang == "5:0"){
					$persen_inap = 75;
				}
				else {
					$persen_inap = 100;
				}

				if($akomodasi <> "hotel"){
					$param = array();
					$param['tgl_awal'] = $tgl_awal;
					$param['tgl_akhir'] = $tgl_akhir;
					$param['no_peg'] = $no_peg;
					$param['jam_pulang'] = $jam_pulang;
					$DataUangInap=$this->sdm_model->get_uanginap($param);
					
					$lamainap = (((abs(strtotime ($tgl_awal) - strtotime ($tgl_akhir)))/(60*60*24)));
				}
				else{
					// --- cek booking ---
					$qbooking = "SELECT SUM(jml_hari_inap) AS jminap FROM db_booking.t_booking_hotel WHERE bukti_sppd = '$bukti_sppd' AND hapus = 0";
					$rbk = $this->db->query($qbooking)->result();
					$lamainaphotel= $rbk[0]->jminap;
					$lamasppd = (((abs(strtotime ($tgl_awal) - strtotime ($tgl_akhir)))/(60*60*24)));
					$lamainap = $lamasppd - $lamainaphotel;
					
					$param = array();
					$param['tgl_awal'] = $tgl_awal;
					$param['tgl_akhir'] = $tgl_akhir;
					$param['no_peg'] = $no_peg;
					$param['jam_pulang'] = $jam_pulang;
					$param['lamainaphotel'] = $lamainaphotel;
					$DataUangInap=$this->sdm_model->get_uanginap_hotel($param);
				}
				
				$uang_pjk = $DataUangHarian + $DataUangInap;
				$insert = "insert into t_pjk set tgl_pjk = '$tanggal',bukti_pjk='$DataResult',bukti_ppl='$DataResult',bukti='$bukti_sppd',no_peg='$no_peg',unit='$kd_unit',tk_jabatan='$kd_jab',tujuan='$tujuan',beban='$beban',unit_asli = '$kd_unit',keperluan='$keperluan',kendaraan='$kendaraan',awal_tugas='$tgl_awal',akir_tugas='$tgl_akhir',jam_awal='$jam_berangkat',jam_akhir='$jam_pulang',jns_sppd='$keperluan',KA='$k_biaya',khusus='$khusus',user_id='$username',akomodasi='$akomodasi',pjk_inap='$DataUangInap',pjk_harian='$DataUangHarian',pjk_taxi='$taksi',u_pjk='$uang_pjk',ket_kas='$kas_keluar',JM_INAP='$lamainap',PERSEN_INAP='$persen_inap'";
				$this->db->query($insert);
				
				// -- update sppd --
				$update = "update t_sppd set STS_PJK = 1 where BUKTI = '$bukti_sppd'";
				$this->db->query($update);
				
				echo 1;
			}
		}
	}

	function hapusdtpjksppd(){
		$username = $this->session->userdata('username');

		$cekdt = "select * from t_pjk where id= '".$_POST['id_pjk']."'";
		$rdt = $this->db->query($cekdt)->result();
		$approve = $rdt[0]->APPROVE_ATASAN;
		$bukti_sppd = $rdt[0]->BUKTI;
		$bukti_pjk = $rdt[0]->BUKTI_PJK;

		$hapus = "update t_pjk set HAPUS = 1,TGL_HAPUS=NOW(),USER_HAPUS='$username' where id = '".$_POST['id_pjk']."'";
		$this->db->query($hapus);
		
		// --- update sppd ===
		$update = "update t_sppd set STS_PJK = 0 where BUKTI = '$bukti_sppd'";
		$this->db->query($update);
		echo json_encode(['status' => true]);
	}
}
