<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umum extends CI_Controller {

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
		$this->load->model('umum_model');
		$this->load->model('func_global');
		$this->db_hrdonline = $this->load->database("hrdonline", TRUE);
		
		if($this->session->userdata('username') == ""){
			redirect('Welcome/index');
		}
	}
	
	public function request_kendaraan()
	{
		$no_peg = $this->session->userdata('username');
	
		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('umum/inp_kendaraan',$Param);	
		$this->load->view('general/footer');	
	}

	public function datarequest_kendaraan()
	{
		$no_peg = $this->session->userdata('username');
	
		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('umum/view_kendaraan',$Param);	
		$this->load->view('general/footer');	
	}

	function ins_permintaankendaraan(){		
		$data=array(
			'no_peg'=>$this->input->post('no_peg'),
			'kd_unit'=>$this->input->post('kd_unit'),
			'tujuan'=>$this->input->post('tujuan'),
			'jmlh_penumpang'=>$this->input->post('jmlh_penumpang'),
			'tmpt_berangkat'=>$this->input->post('tmpt_berangkat'),
			'keperluan'=>$this->input->post('keperluan'),
			'keterangan'=>$this->input->post('keterangan'),
			'tgl_berangkat'=>$this->input->post('tgl_berangkat'),
			'tgl_tiba'=>$this->input->post('tgl_tiba'),
			'jam_berangkat'=>$this->input->post('jam_berangkat'),
			'jam_tiba'=>$this->input->post('jam_tiba')
		);
			
		$cek=$this->umum_model->inp_odkendaraan($data);
		if($cek == 1){
			echo 1;
		}else if ($cek == 2){
			echo 2;
		}else if ($cek == 3){
			echo 3;
		}else if($cek == 4){
			echo 4;
		}
	}

	function hapusdtodkendaraan(){
		$id_od = $_POST['id_od'];
		$update = "update db_umumkendaraan.t_od_kendaraan set is_del = 1 where id_od = '".$id_od."'";
		$this->db->query($update);
		echo json_encode(['status' => true]);
	}
}
