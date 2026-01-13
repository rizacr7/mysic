<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Diklat_model extends CI_Model{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
    
    function get_pelatihan($data){
        $tahun = $data['tahun'];

        $query="SELECT a.*,e.`na_peg`,f.`nm_unit`,b.`KETERANGAN` AS provider,c.`KETERANGAN` AS jnsdiklat,d.`KETERANGAN` AS temadiklat FROM t_pelatihan a 
        LEFT JOIN tab_provider b ON a.`ID_PANITIA` = b.`ID_PANITIA` 
        LEFT JOIN tab_diklat c ON a.`ID_DIKLAT` = c.`ID_DIKLAT`
        LEFT JOIN tab_temadiklat d ON a.`ID_TEMA` = d.`ID_TEMA`
        LEFT JOIN mas_peg e ON a.`NO_PEG` = e.`no_peg`
        LEFT JOIN m_unit f ON e.`kd_unit` = f.`kd_unit`
        WHERE YEAR(tgl_mulai) = '$tahun' AND a.`HAPUS` = 0 HAVING na_peg is not null order by f.kd_unit";
        return $this->db->query($query);
    }

    function get_data_diklatpegawai($cari = "", $sort = "", $order = "", $offset = "0", $limit = "",$param, $numrows = 0) {
        $tahun = $param['tahun'];

        $query_select = ($numrows) ? " count(*) numrows " : " a.*,d.na_peg,b.KETERANGAN as provider,c.KETERANGAN as jnsdiklat ";

        if (is_array($cari) and $cari['value'] != "") {
            $cari_field = isset($cari['field']) ? $cari['field'] : array("a.KETERANGAN","a.NO_PEG","d.na_peg");

            $isi_where = implode(" like '%" . $cari['value'] . "%' or ", $cari_field);

            $query_where = " and (" . $isi_where . " like '%" . $cari['value'] . "%' ) ";
        } else {
            $query_where = "";
        }
		
        $query_sort = ($sort) ? " order by " . $sort . " " . $order : "order by a.ID_LATIH DESC";

        $query_limit = ($limit) ? " limit " . $offset . ", " . $limit : "";

		$query = "SELECT " . $query_select . " FROM t_pelatihan a 
        LEFT join mas_peg d on a.no_peg = d.no_peg 
        LEFT JOIN tab_provider b ON a.`ID_PANITIA` = b.`ID_PANITIA` 
        LEFT JOIN tab_diklat c ON a.`ID_DIKLAT` = c.`ID_DIKLAT`
        WHERE a.HAPUS = 0 AND YEAR(a.TGL_MULAI) = $tahun " . $query_where . " " . $query_sort . " " . $query_limit;
       
        return $this->db->query($query);
    }

    function get_data_prestasi($cari = "", $sort = "", $order = "", $offset = "0", $limit = "",$param, $numrows = 0) {
        $tahun = $param['tahun'];

        $query_select = ($numrows) ? " count(*) numrows " : " a.*,b.na_peg ";

        if (is_array($cari) and $cari['value'] != "") {
            $cari_field = isset($cari['field']) ? $cari['field'] : array("a.no_peg","b.na_peg");

            $isi_where = implode(" like '%" . $cari['value'] . "%' or ", $cari_field);

            $query_where = " and (" . $isi_where . " like '%" . $cari['value'] . "%' ) ";
        } else {
            $query_where = "";
        }
		
        $query_sort = ($sort) ? " order by " . $sort . " " . $order : "order by a.id_prestasi DESC";

        $query_limit = ($limit) ? " limit " . $offset . ", " . $limit : "";

		$query = "SELECT " . $query_select . " FROM t_prestasi a 
        LEFT join mas_peg b on a.no_peg = b.no_peg 
        WHERE a.flag_hapus = 0 AND a.tahun = $tahun " . $query_where . " " . $query_sort . " " . $query_limit;
       
        return $this->db->query($query);
    }
	
    function profilepegawai($no_peg){
        $query = "select a.*,b.nm_unit,c.nm_statpeg,d.nm_jab,b.kd_akun_unit,e.nm_job,FLOOR(DATEDIFF(CURDATE(), a.tgl_lahir) / 365) AS umur from mas_peg a 
		left join m_unit b on a.kd_unit = b.kd_unit  
		left join m_statuspegawai c on a.status_peg = c.kd_statpeg
		left join m_jabatan d on a.kd_jab = d.kd_jab
		left join m_jobdesc e on a.kd_job = e.kd_job
		where a.no_peg = '".$no_peg."'";
		$result = $this->db->query($query)->result();
        return $result;
    }

    function pelatihanpegawai($no_peg,$nik){
        $query = "SELECT a.*,b.`KETERANGAN` AS provider,c.`KETERANGAN` AS jnsdiklat,if(d.`KETERANGAN` is null,a.`KETERANGAN`,d.`KETERANGAN`) AS temadiklat FROM t_pelatihan a 
        LEFT JOIN tab_provider b ON a.`ID_PANITIA` = b.`ID_PANITIA` 
        LEFT JOIN tab_diklat c ON a.`ID_DIKLAT` = c.`ID_DIKLAT`
        LEFT JOIN tab_temadiklat d ON a.`ID_TEMA` = d.`ID_TEMA`
        WHERE (a.`NO_PEG` = '".$no_peg."' OR a.`NIK` = '".$nik."') AND a.`HAPUS` = 0 ";
        $result = $this->db->query($query);
        return $result;
    }

    function mutasipegawai($no_peg,$nik,$no_pph){
        $query = "SELECT tgl_tran AS tanggal,uraian, '' as file_sk
        FROM db_cuti.mem_peg WHERE no_peg IN (SELECT no_peg FROM mas_peg WHERE no_pph='".$no_pph."') AND hapus<>'1' and KD_TRAN = '02'
        union
        SELECT a.tgl_mutasi AS tanggal,CONCAT('Mutasi dari ',nm_unit_asal,' Ke ',nm_unit_tujuan) AS ket,sk_mutasi as file_sk
        FROM t_mutasi a 
        LEFT JOIN mas_peg b ON a.no_peg = b.no_peg 
        WHERE (b.no_ktp = '".$nik."' OR a.no_peg = '".$no_peg."') AND a.is_del = 0 order by tanggal asc";
        $result = $this->db->query($query);
        return $result;
    }

    function prestasipegawai($no_peg,$nik){
        $query = "SELECT a.* FROM t_prestasi a 
        WHERE (a.`no_peg` = '".$no_peg."' OR a.`nik` = '".$nik."') AND a.`flag_hapus` = 0 ";
        $result = $this->db->query($query);
        return $result;
    }

    function nppegawai($no_peg,$nik){
        $query = "SELECT a.*,ket_np FROM t_np_pegawai a 
        LEFT JOIN m_np b on a.kd_np = b.kd_np
        WHERE a.`no_peg` = '".$no_peg."' AND a.`status_hapus` = 0 ";
        $result = $this->db->query($query);
        return $result;
    }
}
?>