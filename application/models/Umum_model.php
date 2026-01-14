<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Umum_model extends CI_Model{
	function __construct(){
		$this->load->database();
		$this->db_hrdonline = $this->load->database("hrdonline", TRUE);
	}
	
	public function inp_odkendaraan($data){
		$username = $this->session->userdata('username');
		
		$day = date('Y-m-d');
		$jam = gmdate("H:i:s", time()+60*60*7);
		
		$no_peg = $data['no_peg'];
		$kd_unit = $data['kd_unit'];
		$tujuan = strtoupper($data['tujuan']);
		$jmlh_penumpang=$data['jmlh_penumpang'];
		$keperluan = $data['keperluan'];
		$tmpt_berangkat = strtoupper($data['tmpt_berangkat']);
		$keterangan = strtoupper($data['keterangan']);
		$tgl_berangkat=$data['tgl_berangkat'];
		$tgl_tiba=$data['tgl_tiba'];

		$jam_berangkat = $data['jam_berangkat'];
		$jam_tiba = $data['jam_tiba'];
		$no_od = $this->bukti_order('OK');
		
		$time1=strtotime($day);
		$time2=strtotime($tgl_berangkat);
		$selisih=($time2-$time1)/(60*60*45);
		
		$pecah1 = explode("-", $day);
		$date1 = $pecah1[2];
		$month1 = $pecah1[1];
		$year1 = $pecah1[0];
		 
		$pecah2 = explode("-", $tgl_berangkat);
		$date2 = $pecah2[2];
		$month2 = $pecah2[1];
		$year2 =  $pecah2[0];
		$jd1 = GregorianToJD($month1, $date1, $year1);
		$jd2 = GregorianToJD($month2, $date2, $year2);
		
		$selisihhari = $jd2 - $jd1;
		
		if ($tgl_tiba < $tgl_berangkat){
			echo 2;
		}
		else if($tgl_berangkat == $day){
			$insert = "insert into db_umumkendaraan.t_od_kendaraan set no_od='$no_od',tgl_od=NOW(),no_peg='$no_peg',kd_unit='$kd_unit',tujuan='$tujuan',
			keperluan='$keperluan',jmlh_penumpang=$jmlh_penumpang,keterangan='$keterangan',tmpt_berangkat='$tmpt_berangkat',tgl_berangkat='$tgl_berangkat',
			tgl_tiba='$tgl_tiba',jam_berangkat='$jam_berangkat',jam_tiba='$jam_tiba'";
			$result = $this->db->query($insert);
			return 1;
		}
		else if($tgl_berangkat != $day){
			if($selisihhari > 1){
				$insert = "insert into db_umumkendaraan.t_od_kendaraan set no_od='$no_od',tgl_od=NOW(),no_peg='$no_peg',kd_unit='$kd_unit',tujuan='$tujuan',
				keperluan='$keperluan',jmlh_penumpang=$jmlh_penumpang,keterangan='$keterangan',tmpt_berangkat='$tmpt_berangkat',tgl_berangkat='$tgl_berangkat',
				tgl_tiba='$tgl_tiba',jam_berangkat='$jam_berangkat',jam_tiba='$jam_tiba'";
				$result = $this->db->query($insert);
				return 1;
			}
			else{
				if($jam < '15:00:00'){
					$insert = "insert into db_umumkendaraan.t_od_kendaraan set no_od='$no_od',tgl_od=NOW(),no_peg='$no_peg',kd_unit='$kd_unit',tujuan='$tujuan',
					keperluan='$keperluan',jmlh_penumpang=$jmlh_penumpang,keterangan='$keterangan',tmpt_berangkat='$tmpt_berangkat',tgl_berangkat='$tgl_berangkat',
					tgl_tiba='$tgl_tiba',jam_berangkat='$jam_berangkat',jam_tiba='$jam_tiba'";
					$result = $this->db->query($insert);
					return 1;
				}
				else{
					return 4;
				}
			}
			
		}
		
	}

	function bukti_order($data){
		$tanggal = date('d-m-Y');
		
		$str = explode("-", $tanggal);
		$tgl = $str[0];
		$bln = $str[1];
		$thn = $str[2];
		$tahun = substr($thn,2,2);
	
		$kd = 'OK';
		$kode = $kd.$bln.$tahun;
		$sql = "SELECT MAX(no_od) AS maxID FROM db_umumkendaraan.t_od_kendaraan WHERE no_od like '$kode%'";	

		$result = $this->db->query($sql)->result();
		$noUrut = (int) substr($result[0]->maxID, -4);
		$noUrut++;
		$newId = sprintf("%04s", $noUrut);
		$id= $kd.$bln.$tahun.$newId;
		return $id;
		
	}

	function viewkendaraan($data){
		$no_peg = $data['no_peg'];
		$kd_unit = $data['kd_unit'];
		$kd_bagian = $data['kd_bagian'];

		$year = date("Y");
		
		$query = "SELECT a.*, b.na_peg
		FROM db_umumkendaraan.t_od_kendaraan a
		LEFT JOIN mas_peg b ON a.no_peg = b.no_peg
		LEFT JOIN m_unit c ON a.kd_unit = c.kd_unit
		WHERE c.kd_bagian = '$kd_bagian'
		AND a.is_del = 0
		AND a.tgl_od >= DATE_FORMAT(
				DATE_SUB(CURDATE(), INTERVAL 4 MONTH),
				'%Y-%m-01'
			)
		ORDER BY a.tgl_od DESC;";
		$result = $this->db_hrdonline->query($query)->result_array();
		return $result;  
	}
}
?>