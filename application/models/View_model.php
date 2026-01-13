<?php defined('BASEPATH') OR exit('No direct script access allowed');

class view_model extends CI_Model{
	function __construct(){
		$this->load->database();
		$this->db_hrdonline = $this->load->database("hrdonline", TRUE);
	}
	
	public function getPjkById($id)
	{
		return $this->db
        ->select('t_pjk.*, mas_peg.na_peg,m_unit.nm_unit,t_sppd.DALAM_RANGKA')
        ->from('t_pjk')
        ->join('mas_peg', 'mas_peg.no_peg = t_pjk.no_peg', 'left')
		->join('m_unit', 'm_unit.kd_unit = t_pjk.UNIT', 'left')
		->join('t_sppd', 't_pjk.bukti = t_sppd.bukti', 'left')
        ->where('t_pjk.ID', $id)
        ->get()
        ->row();
	}
}
?>