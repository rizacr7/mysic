<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_Controller {

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
		$this->load->model('m_finger');
		$this->load->model('func_global');
		$this->load->model('view_model');

		if($this->session->userdata('username') == ""){
			redirect('Welcome/index');
		}
	}

	private function getAkomodasi($val)
	{
		switch ($val) {
			case '1': return 'Hotel';
			case '2': return 'Luar';
			case '3': return 'Mess';
			default:  return $val;
		}
	}

	private function getKendaraan($val)
	{
		switch ($val) {
			case '1': return 'Dinas';
			case '2': return 'Kereta Api';
			case '3': return 'Bus';
			case '4': return 'Kapal';
			case '5': return 'Pesawat';
			case '6': return 'Lain-lain';
			default:  return '-';
		}
	}
	
	public function view_pjk()
	{
		$id = $this->input->post('id_pjk');

		$pjk = $this->view_model->getPjkById($id);

		if (!$pjk) {
			echo "<div class='alert alert-danger'>Data tidak ditemukan</div>";
			return;
		}

		echo "
		<table class='table table-sm table-bordered'>
			<tr>
				<th width='30%'>No Bukti</th>
				<td>{$pjk->BUKTI_PJK}</td>
			</tr>
			<tr>
				<th>No.Pegawai</th>
				<td>{$pjk->NO_PEG}</td>
			</tr>
			<tr>
				<th>Nama</th>
				<td>{$pjk->na_peg}</td>
			</tr>
			<tr>
				<th>Unit</th>
				<td>{$pjk->nm_unit}</td>
			</tr>
			<tr>
				<th>Tanggal SPPD</th>
				<td>{$pjk->AWAL_TUGAS} s.d {$pjk->AKIR_TUGAS}</td>
			</tr>
			<tr>
				<th>Tujuan</th>
				<td>{$pjk->TUJUAN}</td>
			</tr>
			<tr>
				<th>Dalam Rangka</th>
				<td>{$pjk->DALAM_RANGKA}</td>
			</tr>
			<tr>
				<th>Akomodasi</th>
				<td>{$this->getAkomodasi($pjk->AKOMODASI)}</td>
			</tr>
			<tr>
				<th>Kendaraan</th>
				<td>{$this->getKendaraan($pjk->KENDARAAN)}</td>
			</tr>
			<tr>
				<th>Beban</th>
				<td>{$pjk->BEBAN}</td>
			</tr>
			<tr>
				<th>KA Biaya</th>
				<td>{$pjk->KA}</td>
			</tr>
		</table>
		";

		$uang_harian = $pjk->PJK_HARIAN;
		$uang_inap = $pjk->PJK_INAP;
		$uang_taksi = $pjk->PJK_TAXI;
		$total_pjk = $uang_harian + $uang_inap + $uang_taksi;
		$jm_inap = $pjk->JM_INAP;
		$persen_inap = $pjk->PERSEN_INAP;

		// --- uang harian
		if($pjk->AWAL_TUGAS == $pjk->AKIR_TUGAS){
			$lama = 1;
			$uangharian = $uang_harian/$lama;
		}
		else{
			$lama = (((abs(strtotime ($pjk->AWAL_TUGAS) - strtotime ($pjk->AKIR_TUGAS)))/(60*60*24))) + 1;
			$uangharian = $uang_harian/$lama;
		}

		$cektarif = "SELECT a.kd_jab,b.* FROM mas_peg a 
		LEFT JOIN m_tarif_sppd b ON a.kd_jab = b.kd_jab
		WHERE a.no_peg = '".$pjk->NO_PEG."'";
		$rtarif = $this->db->query($cektarif)->result();
		$tarif_inap = $rtarif[0]->uang_penginapan;
		

		if($jm_inap == 1){
			$uanggantiinap = $this->func_global->duit($tarif_inap);
			$uang_penginapan = "(1 x $uanggantiinap) x $persen_inap %";
		}
		else if($jm_inap == 0){
			$uang_penginapan = "";
		}
		else{
			if($persen_inap == 100){
				$uanggantiinap = $this->func_global->duit($tarif_inap);
				$uang_penginapan = "($jm_inap x $uanggantiinap) x $persen_inap %";
			}
			else{
				$jminap = $jm_inap - 1;
				$uang_penginapan = "(($jminap x $tarif_inap) x 100 %) + ((1 x $tarif_inap) x $persen_inap %)";
			}
		}

		echo "
		<table width='100%' border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse;font-size:13px'>
			<tr style='background:#f2f2f2'>
				<td colspan='4' align='center'><b>RINCIAN BIAYA PJK</b></td>
			</tr>
			<tr>
				<td width='35%'>Taksi</td>
				<td width='25%'></td>
				<td width='5%' align='center'>=</td>
				<td width='35%' align='right'>".$this->func_global->duit($uang_taksi)."</td>
			</tr>
			<tr>
				<td>Uang Harian</td>
				<td>($lama x ".$this->func_global->duit($uangharian).")</td>
				<td align='center'>=</td>
				<td align='right'>".$this->func_global->duit($uang_harian)."</td>
			</tr>
			<tr>
				<td>Uang Ganti Penginapan</td>
				<td>$uang_penginapan</td>
				<td align='center'>=</td>
				<td align='right'>".$this->func_global->duit($uang_inap)."</td>
			</tr>
			<tr style='background:#f9f9f9'>
				<td><b>TOTAL</b></td>
				<td></td>
				<td></td>
				<td align='right'><b>".$this->func_global->duit($total_pjk)."</b></td>
			</tr>
		</table>
		";

	}
	
}
