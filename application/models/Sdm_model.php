<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sdm_model extends CI_Model{
	function __construct(){
		$this->load->database();
		$this->db_hrdonline = $this->load->database("hrdonline", TRUE);
	}
	
	function get_bukti($data){
		$kd = $data['kd'];
		$tanggal = $data['tanggal'];
		$str = explode("-", $tanggal);
		$thn = $str[0];
		$bln = $str[1];
		$day = $str[2];
		$tahun = substr($thn,2,2);
		
		if($kd == 'CT'){
			$code = 'CT';
			$kode = $code.$bln.$tahun;
			$sql = "SELECT MAX(no_bukti) AS maxID FROM t_cuti WHERE no_bukti like '$kode%'";
		}
		else if($kd == 'IZN'){
			$code = 'IZN';
			$kode = $code.$bln.$tahun;
			$sql = "SELECT MAX(no_bukti) AS maxID FROM t_izin WHERE no_bukti like '$kode%'";
		}
		else if($kd == 'SPD'){
			$code = 'SPD';
			$kode = $code.$bln.$tahun;
			$sql = "SELECT MAX(bukti) AS maxID FROM t_sppd WHERE bukti like '$kode%'";
		}
		else if($kd == 'PPD'){
			$code = 'PPD';
			$kode = $code.$bln.$tahun;
			$sql = "SELECT MAX(bukti_pjk) AS maxID FROM t_pjk WHERE bukti_pjk like '$kode%'";
		}
		else if($kd == 'SPL'){
			$code = 'SPL';
			$kode = $code.$bln.$tahun;
			$sql = "SELECT MAX(no_bukti) AS maxID FROM t_lembur WHERE no_bukti like '$kode%'";
		}
		
		$result = $this->db_hrdonline->query($sql)->result();
		$noUrut = (int) substr($result[0]->maxID, -4);
		$noUrut++;
		$newId = sprintf("%04s", $noUrut);
		$id= $kode.$newId;
		return $id;
	}

	function cutipegawai($data){
		$no_peg = $data['no_peg'];
		$year = date("Y");
		$lastyear = $year-1;

		$query = "Select a.*,b.na_peg from t_cuti a 
		left join mas_peg b on a.no_peg = b.no_peg 
		where a.no_peg = '$no_peg' and a.is_del = 0 AND YEAR(a.tgl_awal) BETWEEN $lastyear AND $year order by tgl_awal desc;";
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result;  
	}

	function hapusCuti($data){
		$id_cuti = $data['id_cuti'];

		$del = "update t_cuti set is_del = 1 where id = '$id_cuti'";
        $result = $this->db_hrdonline->query($del);
		
		$cekdt = "select * from t_cuti where id = '$id_cuti'";
		$rdt = $this->db_hrdonline->query($cekdt)->result();
		$status_approve = $rdt[0]->status_approve;
		$lama = $rdt[0]->lama;
		$no_peg = $rdt[0]->no_peg;
		$tgl_awal = $rdt[0]->tgl_awal;
		$kd_cuti = $rdt[0]->kd_cuti;
		$tahun = $this->func_global->year($tgl_awal);
		
		  //--- saldo cuti ---
		$ceksaldo = "SELECT * FROM saldo_cuti WHERE no_peg = '$no_peg'";
		$rdt = $this->db_hrdonline->query($ceksaldo)->result();
		$cuti = $rdt[0]->cuti;

		if($kd_cuti == "CT"){
			$update_cuti = $cuti - $lama;
			$update = "update saldo_cuti set cuti = '$update_cuti' where no_peg = '$no_peg'";
			$r_update = $this->db_hrdonline->query($update);
		}
	}

	function izinpegawai($data){
		$no_peg = $data['no_peg'];
		$year = date("Y");
		$lastyear = $year-1;

		$query = "Select a.*,b.na_peg from t_izin a 
		left join mas_peg b on a.no_peg = b.no_peg 
		where a.no_peg = '$no_peg' and a.status_hapus = 0 AND a.tgl_izin >= DATE_FORMAT(
			DATE_SUB(CURDATE(), INTERVAL 3 MONTH),
			'%Y-%m-01'
		) order by tgl_izin desc;";
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result;  
	}

	function appizinpegawai($data){
		$no_peg = $data['no_peg'];
		$kd_unit = $data['kd_unit'];
		$kd_jab = $data['kd_jab'];
		$kd_level = $data['kd_level'];

		$year = date("Y");
		$lastyear = $year-1;

		$kdjab = substr($kd_jab,0,1);
		$whereunit = "";

		if($kd_jab == "10" || $kd_jab == "26" || $kd_jab == "102"){
			//---kd jab divisi---
			$whereunit = " AND d.kd_divisi = '".$kd_unit."' AND SUBSTR(a.kd_jab,1,1) >= '2' AND a.no_peg NOT IN ('$no_peg')";
		}
		else if($kdjab == "2"){
			//--manager---
			$whereunit = " AND c.kd_bagian = '".$kd_unit."' AND SUBSTR(a.kd_jab,1,1) >= '3' AND a.no_peg NOT IN ('$no_peg')";
		}
		else if($kdjab == "3" || $kd_level == "2"){
			//--ass.manager---
			$whereunit = " AND c.kd_unit = '".$kd_unit."' AND SUBSTR(a.kd_jab,1,1) >= '4' AND a.no_peg NOT IN ('$no_peg')";
		}

		$query = "Select a.*,b.na_peg from t_izin a 
		left join mas_peg b on a.no_peg = b.no_peg 
		LEFT JOIN m_unit c ON a.kd_unit = c.kd_unit
		LEFT JOIN m_bagian d ON c.kd_bagian = d.kd_bagian
		where a.flag_app=0 and a.status_hapus = 0 ".$whereunit." AND YEAR(a.tgl_izin) BETWEEN $lastyear AND $year order by tgl_izin desc;";
		
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result; 
	}

	function viewappizinpegawai($data){
		$no_peg = $data['no_peg'];
		$kd_unit = $data['kd_unit'];
		$kd_jab = $data['kd_jab'];
		$kd_level = $data['kd_level'];

		$year = date("Y");
		$lastyear = $year-1;

		$kdjab = substr($kd_jab,0,1);
		$whereunit = "AND c.kd_unit = '".$kd_unit."'";

		if($kd_jab == "10" || $kd_jab == "26" || $kd_jab == "102"){
			//---kd jab divisi---
			$whereunit = " AND d.kd_divisi = '".$kd_unit."' AND SUBSTR(a.kd_jab,1,1) >= '2' AND a.no_peg NOT IN ('$no_peg')";
		}
		else if($kdjab == "2"){
			//--manager---
			$whereunit = " AND c.kd_bagian = '".$kd_unit."' AND SUBSTR(a.kd_jab,1,1) >= '3' AND a.no_peg NOT IN ('$no_peg')";
		}
		else if($kdjab == "3" || $kd_level == "2"){
			//--ass.manager---
			$whereunit = " AND c.kd_unit = '".$kd_unit."' AND SUBSTR(a.kd_jab,1,1) >= '4' AND a.no_peg NOT IN ('$no_peg')";
		}

		$query = "Select a.*,b.na_peg from t_izin a 
		left join mas_peg b on a.no_peg = b.no_peg 
		LEFT JOIN m_unit c ON a.kd_unit = c.kd_unit
		LEFT JOIN m_bagian d ON c.kd_bagian = d.kd_bagian
		where a.flag_app=1 and a.status_hapus = 0 ".$whereunit." AND a.tgl_izin >= DATE_FORMAT(
			DATE_SUB(CURDATE(), INTERVAL 4 MONTH),
			'%Y-%m-01'
		)
		order by tgl_izin desc;";
		
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result; 
	}


	function sppdpegawai($data){
		$no_peg = $data['no_peg'];
		$year = date("Y");
		$lastyear = $year-1;

		$query = "SELECT a.*, b.na_peg
		FROM t_sppd a
		LEFT JOIN mas_peg b ON a.no_peg = b.no_peg
		WHERE a.no_peg = '$no_peg'
		AND a.HAPUS = 0
		AND a.tgl_awal >= DATE_FORMAT(
				DATE_SUB(CURDATE(), INTERVAL 4 MONTH),
				'%Y-%m-01'
			)
		ORDER BY a.tgl_awal DESC;";
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result;  
	}

	function pjksppdpegawai($data){
		$no_peg = $data['no_peg'];
		$year = date("Y");
		$lastyear = $year-1;

		$query = "SELECT a.*, b.na_peg, c.DALAM_RANGKA
		FROM t_pjk a
		LEFT JOIN mas_peg b ON a.no_peg = b.no_peg
		LEFT JOIN t_sppd c ON a.BUKTI = c.BUKTI
		WHERE a.no_peg = '$no_peg'
		AND a.HAPUS = 0
		AND a.tgl_update >= DATE_FORMAT(
				DATE_SUB(CURDATE(), INTERVAL 4 MONTH),
				'%Y-%m-01'
			)
		ORDER BY a.tgl_update DESC;";
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result;  
	}

	function appsppdpegawai($data){
		$no_peg = $data['no_peg'];
		$kd_unit = $data['kd_unit'];
		$kd_jab = $data['kd_jab'];
		$kd_level = $data['kd_level'];

		$year = date("Y");
		$lastyear = $year-1;

		$kdjab = substr($kd_jab,0,1);
		$whereunit = " AND c.kd_unit = '".$kd_unit."' AND a.no_peg NOT IN ('$no_peg')";

		if($kd_jab == "01" || $kd_jab == "02" || $kd_jab == "04"){
			$qdivisi = "select * from m_divisi where kd_pengurus = '$kd_jab'";
			$rdt = $this->db->query($qdivisi)->num_rows();
			if($rdt == 0){
				//---kd jab pengurus---
				$whereunit = " AND d.kd_pengurus = '".$kd_jab."' AND a.TK_JABATAN <= 28 AND a.no_peg NOT IN ('$no_peg')";
			}
			else{
				//---kd jab pengurus---
				$whereunit = " AND d.kd_pengurus = '".$kd_jab."' AND a.TK_JABATAN IN ('26','10','102') AND a.no_peg NOT IN ('$no_peg')";
			}
		}
		else if($kd_jab == "10" || $kd_jab == "26" || $kd_jab == "102"){
			if($kd_unit == "90AA"){
				//---khusus pbb--
				//---kd jab divisi---
				$whereunit = " AND d.kd_divisi = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) = '2' AND a.no_peg NOT IN ('$no_peg')";
			}
			else{
				//---kd jab divisi---
				$whereunit = " AND d.kd_divisi = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) >= '2' AND a.no_peg NOT IN ('$no_peg')";
			}
			
		}
		else if($kdjab == "2"){
			//--manager---
			$whereunit = " AND c.kd_bagian = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) >= '3' AND a.no_peg NOT IN ('$no_peg')";
		}
		else if($kdjab == "3" || $kd_level == "2"){
			//--ass.manager---
			$whereunit = " AND c.kd_unit = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) >= '4' AND a.no_peg NOT IN ('$no_peg')";
		}

		$query = "Select a.*,b.na_peg from t_sppd a 
		left join mas_peg b on a.no_peg = b.no_peg 
		LEFT JOIN m_unit c ON a.UNIT = c.kd_unit
		LEFT JOIN m_bagian d ON c.kd_bagian = d.kd_bagian
		where a.APPROVE =0 and a.HAPUS = 0 ".$whereunit." order by a.TGL_AWAL desc;";
		
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result; 
	}

	function appcutipegawai($data){
		$no_peg = $data['no_peg'];
		$kd_unit = $data['kd_unit'];
		$kd_jab = $data['kd_jab'];
		$kd_level = $data['kd_level'];

		$year = date("Y");
		$lastyear = $year-1;

		$kdjab = substr($kd_jab,0,1);
		$whereunit = " AND c.kd_unit = '".$kd_unit."' AND a.no_peg NOT IN ('$no_peg')";

		if($kd_jab == "01" || $kd_jab == "02" || $kd_jab == "04"){
			$qdivisi = "select * from m_divisi where kd_pengurus = '$kd_jab'";
			$rdt = $this->db->query($qdivisi)->num_rows();
			if($rdt == 0){
				//---kd jab pengurus---
				$whereunit = " AND d.kd_pengurus = '".$kd_jab."' AND b.kd_jab <= 28 AND a.no_peg NOT IN ('$no_peg')";
			}
			else{
				//---kd jab pengurus---
				$whereunit = " AND d.kd_pengurus = '".$kd_jab."' AND b.kd_jab IN ('26','10','102') AND a.no_peg NOT IN ('$no_peg')";
			}
		}
		else if($kd_jab == "10" || $kd_jab == "26" || $kd_jab == "102"){
			//---kd jab divisi---
			$whereunit = " AND d.kd_divisi = '".$kd_unit."' AND SUBSTR(b.kd_jab,1,1) >= '2' AND a.no_peg NOT IN ('$no_peg')";
		}
		else if($kdjab == "2"){
			//--manager---
			$whereunit = " AND c.kd_bagian = '".$kd_unit."' AND SUBSTR(b.kd_jab,1,1) >= '3' AND a.no_peg NOT IN ('$no_peg')";
		}
		else if($kdjab == "3" || $kd_level == "2"){
			//--ass.manager---
			$whereunit = " AND c.kd_unit = '".$kd_unit."' AND SUBSTR(b.kd_jab,1,1) >= '4' AND a.no_peg NOT IN ('$no_peg')";
		}

		$query = "Select a.*,b.na_peg from t_cuti a 
		left join mas_peg b on a.no_peg = b.no_peg 
		LEFT JOIN m_unit c ON a.kd_unit = c.kd_unit
		LEFT JOIN m_bagian d ON c.kd_bagian = d.kd_bagian
		where a.status_approve =0 and a.is_del = 0 and a.cuti_bersama = 0 ".$whereunit." AND a.tanggal >= DATE_FORMAT(
				DATE_SUB(CURDATE(), INTERVAL 4 MONTH),
				'%Y-%m-01'
			) order by a.tanggal desc;";
		
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result; 
	}

	function viewappcutipegawai($data){
		$no_peg = $data['no_peg'];
		$kd_unit = $data['kd_unit'];
		$kd_jab = $data['kd_jab'];
		$kd_level = $data['kd_level'];

		$year = date("Y");
		$lastyear = $year-1;

		$kdjab = substr($kd_jab,0,1);
		$whereunit = " AND c.kd_unit = '".$kd_unit."' AND a.no_peg NOT IN ('$no_peg')";

		if($kd_jab == "01" || $kd_jab == "02" || $kd_jab == "04"){
			$qdivisi = "select * from m_divisi where kd_pengurus = '$kd_jab'";
			$rdt = $this->db->query($qdivisi)->num_rows();
			if($rdt == 0){
				//---kd jab pengurus---
				$whereunit = " AND d.kd_pengurus = '".$kd_jab."' AND b.kd_jab <= 28 AND a.no_peg NOT IN ('$no_peg')";
			}
			else{
				//---kd jab pengurus---
				$whereunit = " AND d.kd_pengurus = '".$kd_jab."' AND b.kd_jab IN ('26','10','102') AND a.no_peg NOT IN ('$no_peg')";
			}
		}
		else if($kd_jab == "10" || $kd_jab == "26" || $kd_jab == "102"){
			//---kd jab divisi---
			$whereunit = " AND d.kd_divisi = '".$kd_unit."' AND SUBSTR(b.kd_jab,1,1) >= '2' AND a.no_peg NOT IN ('$no_peg')";
		}
		else if($kdjab == "2"){
			//--manager---
			$whereunit = " AND c.kd_bagian = '".$kd_unit."' AND SUBSTR(b.kd_jab,1,1) >= '3' AND a.no_peg NOT IN ('$no_peg')";
		}
		else if($kdjab == "3" || $kd_level == "2"){
			//--ass.manager---
			$whereunit = " AND c.kd_unit = '".$kd_unit."' AND SUBSTR(b.kd_jab,1,1) >= '4' AND a.no_peg NOT IN ('$no_peg')";
		}

		$query = "Select a.*,b.na_peg from t_cuti a 
		left join mas_peg b on a.no_peg = b.no_peg 
		LEFT JOIN m_unit c ON a.kd_unit = c.kd_unit
		LEFT JOIN m_bagian d ON c.kd_bagian = d.kd_bagian
		where a.status_approve =1 and a.is_del = 0 and a.cuti_bersama = 0 ".$whereunit." AND a.tanggal >= DATE_FORMAT(
				DATE_SUB(CURDATE(), INTERVAL 4 MONTH),
				'%Y-%m-01'
			) order by a.tanggal desc;";
		
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result; 
	}

	function viewappsppdpegawai($data){
		$no_peg = $data['no_peg'];
		$kd_unit = $data['kd_unit'];
		$kd_jab = $data['kd_jab'];
		$kd_level = $data['kd_level'];

		$year = date("Y");
		$lastyear = $year-1;

		$kdjab = substr($kd_jab,0,1);
		$whereunit = "AND c.kd_unit = '".$kd_unit."'";

		if($kd_jab == "01" || $kd_jab == "02" || $kd_jab == "04"){
			$qdivisi = "select * from m_divisi where kd_pengurus = '$kd_jab'";
			$rdt = $this->db->query($qdivisi)->num_rows();
			if($rdt == 0){
				//---kd jab pengurus---
				$whereunit = " AND d.kd_pengurus = '".$kd_jab."' AND a.TK_JABATAN <= 28 AND a.no_peg NOT IN ('$no_peg')";
			}
			else{
				//---kd jab pengurus---
				$whereunit = " AND d.kd_pengurus = '".$kd_jab."' AND a.TK_JABATAN IN ('26','10','102') AND a.no_peg NOT IN ('$no_peg')";
			}
		}
		else if($kd_jab == "10" || $kd_jab == "26" || $kd_jab == "102"){
			//---kd jab divisi---
			if($kd_unit == "90AA"){
				$whereunit = " AND d.kd_divisi = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) = '2' AND a.no_peg NOT IN ('$no_peg')";
			}
			else{
				$whereunit = " AND d.kd_divisi = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) >= '2' AND a.no_peg NOT IN ('$no_peg')";
			}
			
		}
		else if($kdjab == "2"){
			//--manager---
			$whereunit = " AND c.kd_bagian = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) >= '3' AND a.no_peg NOT IN ('$no_peg')";
		}
		else if($kdjab == "3" || $kd_level == "2"){
			//--ass.manager---
			$whereunit = " AND c.kd_unit = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) >= '4' AND a.no_peg NOT IN ('$no_peg')";
		}

		$query = "Select a.*,b.na_peg,SUBSTR(a.TGL_APP,1,10) as tglapp from t_sppd a 
		left join mas_peg b on a.no_peg = b.no_peg 
		LEFT JOIN m_unit c ON a.UNIT = c.kd_unit
		LEFT JOIN m_bagian d ON c.kd_bagian = d.kd_bagian
		where a.APPROVE =1 and a.HAPUS = 0 ".$whereunit." AND a.TGL_APP >= DATE_FORMAT(
			DATE_SUB(CURDATE(), INTERVAL 4 MONTH),
			'%Y-%m-01'
		) order by a.TGL_APP desc;";
		
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result; 
	}

	function get_uangharian($data){
		$tgl_awal = $data['tgl_awal'];
		$tgl_akhir = $data['tgl_akhir'];
		$khusus = $data['khusus'];
		$no_peg = $data['no_peg'];
		
		$cektarif = "SELECT a.kd_jab,b.* FROM mas_peg a 
		LEFT JOIN m_tarif_sppd b ON a.kd_jab = b.kd_jab
		WHERE a.no_peg = '$no_peg'";
		$rtarif = $this->db->query($cektarif)->result();
		$uang_umum = $rtarif[0]->uang_umum;
		$uang_khusus = $rtarif[0]->uang_khusus;
		$kd_jab = $rtarif[0]->kd_jab;
		
		if($tgl_awal == $tgl_akhir){
			if($kd_jab == '01' || $kd_jab == '02' || $kd_jab == '03' || $kd_jab == '04'){
				$lama = 1;
			}
			else{
				$lama = 1;
			}
			
		}
		else{
			$lama = (((abs(strtotime ($tgl_awal) - strtotime ($tgl_akhir)))/(60*60*24))) + 1;
		}
		
		if($khusus == 1){
			$uangharian = $uang_khusus*$lama;
		}
		else{
			$uangharian = $uang_umum*$lama;
		}
		return $uangharian;
	}

	function get_uanginap($data){
		$tgl_awal = $data['tgl_awal'];
		$tgl_akhir = $data['tgl_akhir'];
		$no_peg = $data['no_peg'];
		$jam_pulang = $data['jam_pulang'];
		
		$cektarif = "SELECT a.kd_jab,b.* FROM mas_peg a 
		LEFT JOIN m_tarif_sppd b ON a.kd_jab = b.kd_jab
		WHERE a.no_peg = '$no_peg'";
		$rtarif = $this->db->query($cektarif)->result();
		$uang_penginapan = $rtarif[0]->uang_penginapan;
		
		$strpulang = explode(":",$jam_pulang);
		$jampulang = $strpulang[0];
		$mntpulang = $strpulang[1];
			
		if((($jampulang == "24" || $jampulang == "00" || $jampulang == "0" || $jampulang == "01" || $jampulang == "1") && $mntpulang > 0) || $jam_pulang == "01:00"  || $jam_pulang == "1:00" || $jam_pulang == "1:0" || $jam_pulang == "02:00"  || $jam_pulang == "2:00" || $jam_pulang == "2:0"){
			$persen = 25;
		}
		else if((($jampulang == "02" || $jampulang == "03" || $jampulang == "3") && $mntpulang > 0) || $jam_pulang == "03:00"  || $jam_pulang == "3:00" || $jam_pulang == "3:0" || $jam_pulang == "04:00"  || $jam_pulang == "4:00" || $jam_pulang == "4:0"){
			$persen = 50;
		}
		else if(($jampulang == "04" && $mntpulang > 0) || $jam_pulang == "05:00"  || $jam_pulang == "5:00" || $jam_pulang == "5:0"){
			$persen = 75;
		}
		else{
			$persen = 100;
		}
	
		// --- jam kepulangan ---
		/*
		if($jam_pulang >= '0:01:00' && $jam_pulang <= '2:00:00'){
			$persen = 25;
		}
		else if($jam_pulang >= '2:01:00' && $jam_pulang <= '4:00:00'){
			$persen = 50;
		}
		else if($jam_pulang >= '4:01:00' && $jam_pulang <= '5:00:00'){
			$persen = 75;
		}
		else if($jam_pulang >= '5:01:00'){
			$persen = 100;
		}
		*/
		//$persen = 100;
		
		if($tgl_awal == $tgl_akhir){
			$lama = 0;
			$uanginap=0;
		}
		else{
			$lama = (((abs(strtotime ($tgl_awal) - strtotime ($tgl_akhir)))/(60*60*24)));
			if($lama == 1){
				$uanginap = ($uang_penginapan*($persen/100));
			}
			else{
				$lamainap = $lama - 1;
			
				$uanginap1 = $uang_penginapan*$lamainap;
				$uanginap2 = ($uang_penginapan*($persen/100));
				
				$uanginap = $uanginap1 + $uanginap2;
			}
		}
		
		return $uanginap;
	}

	function get_uanginap_hotel($data){
		$tgl_awal = $data['tgl_awal'];
		$tgl_akhir = $data['tgl_akhir'];
		$lamainaphotel = $data['lamainaphotel'];
		$no_peg = $data['no_peg'];
		$jam_pulang = $data['jam_pulang'];
		
		$cektarif = "SELECT a.kd_jab,b.* FROM mas_peg a 
		LEFT JOIN m_tarif_sppd b ON a.kd_jab = b.kd_jab
		WHERE a.no_peg = '$no_peg'";
		$rtarif = $this->db->query($cektarif)->result();
		$uang_penginapan = $rtarif[0]->uang_penginapan;
		
		$strpulang = explode(":",$jam_pulang);
		$jampulang = $strpulang[0];
		$mntpulang = $strpulang[1];
			
		if((($jampulang == "24" || $jampulang == "00" || $jampulang == "0" || $jampulang == "01" || $jampulang == "1") && $mntpulang > 0) || $jam_pulang == "01:00"  || $jam_pulang == "1:00" || $jam_pulang == "1:0" || $jam_pulang == "02:00"  || $jam_pulang == "2:00" || $jam_pulang == "2:0"){
			$persen = 25;
		}
		else if((($jampulang == "02" || $jampulang == "03" || $jampulang == "3") && $mntpulang > 0) || $jam_pulang == "03:00"  || $jam_pulang == "3:00" || $jam_pulang == "3:0" || $jam_pulang == "04:00"  || $jam_pulang == "4:00" || $jam_pulang == "4:0"){
			$persen = 50;
		}
		else if(($jampulang == "04" && $mntpulang > 0) || $jam_pulang == "05:00"  || $jam_pulang == "5:00" || $jam_pulang == "5:0"){
			$persen = 75;
		}
		else{
			$persen = 100;
		}
		
		if($tgl_awal == $tgl_akhir){
			$lama = 0;
			$uanginap=0;
		}
		else{
			$lamasppd = (((abs(strtotime ($tgl_awal) - strtotime ($tgl_akhir)))/(60*60*24)));
			
			$lama = $lamasppd - $lamainaphotel;
			
			if($lama == 1){
				$uanginap = ($uang_penginapan*($persen/100));
			}
			else{
				$lamainap = $lama - 1;
			
				$uanginap1 = $uang_penginapan*$lamainap;
				$uanginap2 = ($uang_penginapan*($persen/100));
				
				$uanginap = $uanginap1 + $uanginap2;
			}
		}
		
		return $uanginap;
	}

	function apppjksppdpegawai($data){
		$no_peg = $data['no_peg'];
		$kd_unit = $data['kd_unit'];
		$kd_jab = $data['kd_jab'];
		$kd_level = $data['kd_level'];

		$year = date("Y");
		$lastyear = $year-1;

		$kdjab = substr($kd_jab,0,1);
		$whereunit = " AND c.kd_unit = '".$kd_unit."' AND a.no_peg NOT IN ('$no_peg')";

		if($kd_jab == "01" || $kd_jab == "02" || $kd_jab == "04"){
			$qdivisi = "select * from m_divisi where kd_pengurus = '$kd_jab'";
			$rdt = $this->db->query($qdivisi)->num_rows();
			if($rdt == 0){
				//---kd jab pengurus---
				$whereunit = " AND d.kd_pengurus = '".$kd_jab."' AND a.TK_JABATAN <= 28 AND a.no_peg NOT IN ('$no_peg')";
			}
			else{
				//---kd jab pengurus---
				$whereunit = " AND d.kd_pengurus = '".$kd_jab."' AND a.TK_JABATAN IN ('26','10','102') AND a.no_peg NOT IN ('$no_peg')";
			}
		}
		else if($kd_jab == "10" || $kd_jab == "26" || $kd_jab == "102"){
			//---kd jab divisi---
			if($kd_unit == "90AA"){
				//---khusus pbb--
				$whereunit = " AND d.kd_divisi = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) = 2 AND a.no_peg NOT IN ('$no_peg')";
			}
			else{
				$whereunit = " AND d.kd_divisi = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) >= 2 AND a.no_peg NOT IN ('$no_peg')";
			}
		}
		else if($kdjab == "2"){
			//--manager---
			$whereunit = " AND c.kd_bagian = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) >= 3 AND a.no_peg NOT IN ('$no_peg')";
		}
		else if($kdjab == "3" || $kd_level == "2"){
			//--ass.manager---
			$whereunit = " AND c.kd_unit = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) >= 4 AND a.no_peg NOT IN ('$no_peg')";
		}

		$query = "Select a.*,b.na_peg,e.DALAM_RANGKA from t_pjk a 
		left join mas_peg b on a.no_peg = b.no_peg 
		LEFT JOIN m_unit c ON a.UNIT = c.kd_unit
		LEFT JOIN m_bagian d ON c.kd_bagian = d.kd_bagian
		LEFT JOIN t_sppd e ON a.BUKTI = e.BUKTI
		where a.APPROVE_ATASAN =0 and a.HAPUS = 0 ".$whereunit." order by a.AWAL_TUGAS desc;";
		
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result; 
	}

	function viewapppjksppdpegawai($data){
		$no_peg = $data['no_peg'];
		$kd_unit = $data['kd_unit'];
		$kd_jab = $data['kd_jab'];
		$kd_level = $data['kd_level'];

		$year = date("Y");
		$lastyear = $year-1;

		$kdjab = substr($kd_jab,0,1);
		$whereunit = " AND c.kd_unit = '".$kd_unit."' AND a.no_peg NOT IN ('$no_peg')";

		if($kd_jab == "01" || $kd_jab == "02" || $kd_jab == "04"){
			$qdivisi = "select * from m_divisi where kd_pengurus = '$kd_jab'";
			$rdt = $this->db->query($qdivisi)->num_rows();
			if($rdt == 0){
				//---kd jab pengurus---
				$whereunit = " AND d.kd_pengurus = '".$kd_jab."' AND a.TK_JABATAN <= 28 AND a.no_peg NOT IN ('$no_peg')";
			}
			else{
				//---kd jab pengurus---
				$whereunit = " AND d.kd_pengurus = '".$kd_jab."' AND a.TK_JABATAN IN ('26','10','102') AND a.no_peg NOT IN ('$no_peg')";
			}
		}
		else if($kd_jab == "10" || $kd_jab == "26" || $kd_jab == "102"){
			//---kd jab divisi---
			if($kd_unit == "90AA"){
				$whereunit = " AND d.kd_divisi = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) = 2 AND a.no_peg NOT IN ('$no_peg')";
			}
			else{
				$whereunit = " AND d.kd_divisi = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) >= 2 AND a.no_peg NOT IN ('$no_peg')";
			}
			
		}
		else if($kdjab == "2"){
			//--manager---
			$whereunit = " AND c.kd_bagian = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) >= 3 AND a.no_peg NOT IN ('$no_peg')";
		}
		else if($kdjab == "3" || $kd_level == "2"){
			//--ass.manager---
			$whereunit = " AND c.kd_unit = '".$kd_unit."' AND SUBSTR(a.TK_JABATAN,1,1) >= 4 AND a.no_peg NOT IN ('$no_peg')";
		}

		$query = "Select a.*,b.na_peg,e.DALAM_RANGKA from t_pjk a 
		left join mas_peg b on a.no_peg = b.no_peg 
		LEFT JOIN m_unit c ON a.UNIT = c.kd_unit
		LEFT JOIN m_bagian d ON c.kd_bagian = d.kd_bagian
		LEFT JOIN t_sppd e ON a.BUKTI = e.BUKTI
		where a.APPROVE_ATASAN =1 and a.HAPUS = 0 ".$whereunit." order by a.AWAL_TUGAS desc;";
		
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result; 
	}

	function inshrddbtp($data){
        $bukti_pjk = $data['bukti_pjk'];
        $beban = $data['beban'];

        if($beban == '6180'){
            $beban = '1000';
        }
        else if($beban == '6181'){
            $beban = '2000';
        }

        $qHapusFina = "DELETE FROM db_fina_konsolidasi.t_track_doc WHERE no_document = '$bukti_pjk'";
        $this->db_hrdbtp->query($qHapusFina);

        $qHapus = "DELETE FROM db_hrd.t_pjk WHERE BUKTI_PJK = '$bukti_pjk'";
        $query = $this->db_finabtp->query($qHapus);

        $insert = "insert ignore into db_fina_konsolidasi.t_track_doc set bukti_ph='$bukti_pjk',no_document = '$bukti_pjk',ajuan_dari='SDM',DoUval='1',tglDoUval=NOW(),kd_unit='$beban'";
        $result = $this->db_finabtp->query($insert);

        //---insert t_pjk_sppd ---
        $queryPjk = "SELECT * FROM t_pjk WHERE BUKTI_PJK = '$bukti_pjk'";
        $val = $this->db->query($queryPjk)->result();

        $dataDtl = array(
            'TGL_PJK' => $val[0]->TGL_PJK,
            'BUKTI_PJK' => $val[0]->BUKTI_PJK,
            'BUKTI' => $val[0]->BUKTI,
            'BUKTI_PPL' => $val[0]->BUKTI_PPL,
            'NO_PEG' => $val[0]->NO_PEG,
            'UNIT' => $val[0]->UNIT,
            'TK_JABATAN' => $val[0]->TK_JABATAN,
            'TUJUAN' => $val[0]->TUJUAN,
            'BEBAN' => $val[0]->BEBAN,
            'UNIT_ASLI' => $val[0]->UNIT_ASLI,
            'KEPERLUAN' => $val[0]->KEPERLUAN,
            'AKOMODASI' => $val[0]->AKOMODASI,
            'KENDARAAN' => $val[0]->KENDARAAN,
            'AWAL_TUGAS' => $val[0]->AWAL_TUGAS,
            'JAM_AWAL' => $val[0]->JAM_AWAL,
            'JAM_AKHIR' => $val[0]->JAM_AKHIR,
            'AKIR_TUGAS' => $val[0]->AKIR_TUGAS,
            'KHUSUS' => $val[0]->KHUSUS,
            'U_PJK' => $val[0]->U_PJK,
            'PJK_HARIAN' => $val[0]->PJK_HARIAN,
            'JM_INAP' => $val[0]->JM_INAP,
            'PJK_INAP' => $val[0]->PJK_INAP,
            'PERSEN_INAP' => $val[0]->PERSEN_INAP,
            'APPROVE_ATASAN' => $val[0]->APPROVE_ATASAN,
            'USER_APPROVE' => $val[0]->USER_APPROVE,
            'JNS_SPPD' => $val[0]->JNS_SPPD,
            'KET_KAS' => $val[0]->KET_KAS,
            );
        $this->db_hrdbtp->insert('t_pjk',$dataDtl);
    }
	
	function appmutasipegawai($data){
		$status_user = $this->session->userdata('jab');
		$no_peg = $data['no_peg'];
		$kd_unit = $data['kd_unit'];
		$kd_jab = $data['kd_jab'];
		$kd_level = $data['kd_level'];

		$whereapp = "AND a.flag_app_sdm = 1 AND a.flag_app_sdm2 = 0 ";

		if($status_user == "PENGURUS"){
            if($kd_jab == "02"){ // === sekretaris ===
                $whereapp = "AND flag_app_unit = 1 AND flag_app_kadiv = 1 AND a.flag_app_sekretaris=0";
            }
            else if($kd_jab == "04"){ // === bendahara ===
                $whereapp = "AND flag_app_unit = 1 AND flag_app_kadiv = 1 AND a.flag_app_bendahara=0";
            }
            else{
                $whereapp = "AND flag_app_unit = 1 AND flag_app_kadiv = 1 AND a.flag_app_pengurus=0";
            }
        }
        else if($status_user == "KADIV"){
            if($kd_unit == "90AA"){
                //--kadiv bisnis---
                $whereapp = "AND flag_app_unit = 1 AND a.flag_app_kadiv=0 AND f.kd_divisi = '$kd_unit'";
            }
            else if($kd_unit == "90AB"){
                //---kadiv keuangan---
                $whereapp = "AND flag_app_unit = 1 AND a.flag_app_kadiv=0 AND f.kd_divisi = '$kd_unit'";
            }
            else if($kd_unit == "90AC"){
                //---kadiv b2b
                $whereapp = "AND flag_app_unit = 1 AND a.flag_app_kadiv=0 AND f.kd_divisi = '$kd_unit'";
            }
        }
        else if($status_user == "MR"){
            $whereapp = "AND a.flag_app_sdm = 1 AND a.flag_app_kadiv=0 AND (f.kd_bagian = '$kd_unit' OR h.kd_bagian='$kd_unit')";
        }

		$query = "SELECT a.*,b.na_peg,c.nm_job,d.nm_jab FROM db_hrd.t_pengajuan_mutasi a 
        LEFT JOIN db_hrd.mas_peg b ON a.`no_peg` = b.`no_peg` 
        LEFT join db_hrd.m_jobdesc c on a.kd_job = c.kd_job
        LEFT join db_hrd.m_jabatan d on a.kd_jab = d.kd_jab
        LEFT join db_hrd.m_unit e on a.kd_unit_asal = e.kd_unit
        LEFT join db_hrd.m_bagian f on e.kd_bagian = f.kd_bagian
        LEFT JOIN db_hrd.m_unit g ON a.`kd_unit_tujuan` =  g.`kd_unit`
        LEFT JOIN db_hrd.m_bagian h ON g.kd_bagian = h.kd_bagian
        WHERE a.is_del = 0 ".$whereapp." ORDER BY a.tgl_pengajuan DESC;";
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result; 
	}

	function viewappmutasipegawai($data){
		$status_user = $this->session->userdata('jab');
		$no_peg = $data['no_peg'];
		$kd_unit = $data['kd_unit'];
		$kd_jab = $data['kd_jab'];
		$kd_level = $data['kd_level'];

		$whereapp = "AND a.flag_app_sdm = 1 AND a.flag_app_sdm2 = 0 ";

		if($status_user == "PENGURUS"){
            if($kd_jab == "02"){ // === sekretaris ===
                $whereapp = "AND flag_app_unit = 1 AND flag_app_kadiv = 1 AND a.flag_app_sekretaris=1";
            }
            else if($kd_jab == "04"){ // === bendahara ===
                $whereapp = "AND flag_app_unit = 1 AND flag_app_kadiv = 1 AND a.flag_app_bendahara=1";
            }
            else{
                $whereapp = "AND flag_app_unit = 1 AND flag_app_kadiv = 1 AND a.flag_app_pengurus=1";
            }
        }
        else if($status_user == "KADIV"){
            if($kd_unit == "90AA"){
                //--kadiv bisnis---
                $whereapp = "AND flag_app_unit = 1 AND a.flag_app_kadiv=1 AND f.kd_divisi = '$kd_unit'";
            }
            else if($kd_unit == "90AB"){
                //---kadiv keuangan---
                $whereapp = "AND flag_app_unit = 1 AND a.flag_app_kadiv=1 AND f.kd_divisi = '$kd_unit'";
            }
            else if($kd_unit == "90AC"){
                //---kadiv b2b
                $whereapp = "AND flag_app_unit = 1 AND a.flag_app_kadiv=1 AND f.kd_divisi = '$kd_unit'";
            }
        }
        else if($status_user == "MR"){
            $whereapp = "AND a.flag_app_sdm = 1 AND flag_app_unit = 1 AND (f.kd_bagian = '$kd_unit' OR h.kd_bagian='$kd_unit')";
        }

		$query = "SELECT a.*,b.na_peg,c.nm_job,d.nm_jab FROM db_hrd.t_pengajuan_mutasi a 
        LEFT JOIN db_hrd.mas_peg b ON a.`no_peg` = b.`no_peg` 
        LEFT join db_hrd.m_jobdesc c on a.kd_job = c.kd_job
        LEFT join db_hrd.m_jabatan d on a.kd_jab = d.kd_jab
        LEFT join db_hrd.m_unit e on a.kd_unit_asal = e.kd_unit
        LEFT join db_hrd.m_bagian f on e.kd_bagian = f.kd_bagian
        LEFT JOIN db_hrd.m_unit g ON a.`kd_unit_tujuan` =  g.`kd_unit`
        LEFT JOIN db_hrd.m_bagian h ON g.kd_bagian = h.kd_bagian
        WHERE a.is_del = 0 ".$whereapp." ORDER BY a.tgl_pengajuan DESC LIMIT 30";
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result; 
	}
}
?>