<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_login extends CI_Model{
	function __construct(){
		$this->load->database();
	}
	
	function masuk($username, $password){
		$query = "select b.kd_unit, b.no_peg as username, b.na_peg as nama,b.kd_jab from db_cuti.m_pass a join db_hrd.mas_peg b on a.user_name=b.no_peg where a.user_name = '" . strtoupper($username) . "' and a.pass = md5(concat('AJWKXLAJSCLWLW', md5('" . strtoupper($password) . "'), 'AJWKXLAJSCLWLW'))";
		return $this->db->query($query);
		
	}
	
	function profilepegawai($no_peg){
        // $query = "select a.*,b.kd_bagian,b.nm_unit,c.nm_statpeg,d.nm_jab,b.kd_akun_unit,e.nm_job,FLOOR(DATEDIFF(CURDATE(), a.tgl_lahir) / 365) AS umur from mas_peg a 
		// left join m_unit b on a.kd_unit = b.kd_unit  
		// left join m_statuspegawai c on a.status_peg = c.kd_statpeg
		// left join m_jabatan d on a.kd_jab = d.kd_jab
		// left join m_jobdesc e on a.kd_job = e.kd_job
		// where a.no_peg = '".$no_peg."'";
		
		$query = "select a.*,b.kd_bagian,b.nm_unit,c.nm_statpeg,d.nm_jab,b.kd_akun_unit,IF(f.`nm_so` IS NULL,e.nm_job,f.`nm_job`) AS nm_job,FLOOR(DATEDIFF(CURDATE(), a.tgl_lahir) / 365) AS umur,f.`nm_job` AS nm_so,g.`personal`,h.`kd_eselon` from mas_peg a 
		left join m_unit b on a.kd_unit = b.kd_unit  
		left join m_statuspegawai c on a.status_peg = c.kd_statpeg
		left join m_jabatan d on a.kd_jab = d.kd_jab
		left join m_jobdesc e on a.kd_job = e.kd_job
        LEFT JOIN m_so f ON a.`kd_jobgrade` = f.`kd_job`
        LEFT JOIN m_person_grade g ON a.`kd_persongrade` = g.`kode`
        LEFT JOIN m_job_grade h ON f.kd_grade = h.`kode`
		where a.no_peg = '".$no_peg."'";
		$result = $this->db->query($query)->result();
        return $result;
    }
}
?>