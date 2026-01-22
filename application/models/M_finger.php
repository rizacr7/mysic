<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_finger extends CI_Model{
	function __construct(){
		$this->load->database();
		$this->db_hrdonline = $this->load->database("hrdonline", TRUE);
	}
	
	// --- data vendor ---
	function get_data_finger($cari = "", $sort = "", $order = "", $offset = "0", $limit = "",$paramdt ,$numrows = 0) {
		$username = $paramdt['username'];
		$bulan = $paramdt['bulan'];
		$tahun = $paramdt['tahun'];
		// $bulan = date('m');
		// $tahun = date('Y');
		
        $query_select = ($numrows) ? " count(*) numrows " : " a.*,b.na_peg ";

        if (is_array($cari) and $cari['value'] != "") {
            $cari_field = isset($cari['field']) ? $cari['field'] : array("a.no_peg");

            $isi_where = implode(" like '%" . $cari['value'] . "%' or ", $cari_field);

            $query_where = " and (" . $isi_where . " like '%" . $cari['value'] . "%' ) ";
        } else {
            $query_where = "";
        }

        $query_sort = ($sort) ? " order by " . $sort . " " . $order : "ORDER BY a.tanggal desc";

        $query_limit = ($limit) ? " limit " . $offset . ", " . $limit : "";
		
		
		$query = "select " . $query_select . " FROM t_finger_mobile a 
		left join mas_peg b on a.no_peg = b.no_peg
		where a.no_peg = '$username' and month(a.tanggal) = '$bulan' and year(a.tanggal) = $tahun
		" . $query_where . " " . $query_sort . " " . $query_limit;
		
		
        return $this->db->query($query);
    }
	
	function get_kantor(){
		$username = $this->session->userdata('username');

		$cekdt = "select * from office_employees where no_peg = '$username'";
		$rdt = $this->db_hrdonline->query($cekdt)->num_rows();
		if($rdt > 0){
			// $sql = "select a.*,b.nm_kantor from office_employees a left join m_kantor b on a.kd_kantor = b.kd_kantor where a.no_peg = '$username'";

			$sql = "SELECT m.* FROM (SELECT a.kd_kantor,b.nm_kantor FROM office_employees a 
			LEFT JOIN m_kantor b ON a.kd_kantor = b.kd_kantor 
			WHERE a.no_peg = '$username'
			UNION
			SELECT b.kd_kantor,c.nm_kantor FROM mas_peg a 
			LEFT JOIN m_unit b ON a.kd_unit = b.kd_unit
			LEFT JOIN m_kantor c ON b.kd_kantor = c.kd_kantor
			WHERE a.no_peg = '$username') m GROUP BY m.kd_kantor";
		}
		else{
			$sql = "SELECT b.kd_kantor,c.nm_kantor FROM mas_peg a 
			LEFT JOIN m_unit b ON a.kd_unit = b.kd_unit
			LEFT JOIN m_kantor c ON b.kd_kantor = c.kd_kantor
			WHERE a.no_peg = '$username'";
		}
        $result = $this->db_hrdonline->query($sql);
		return $result->result_array();
	}
	
	
}
?>