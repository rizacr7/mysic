<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
		$this->db_hrdonline = $this->load->database("hrdonline", TRUE);
// 		$this->db_undian = $this->load->database("db_undian", TRUE);
	}
	
	public function index()
	{
		$this->load->view('vlogin');
	}
	
	function proses_login() {
        $usr = $this->input->post('username');
		$psw = $this->input->post('password');

		$usr = str_replace("'","",$usr);
		$psw = str_replace("'","",$psw);

		if($usr == "" || $psw == ""){
			$this->session->set_flashdata('result_login', '<br>Username atau Password tidak boleh kosong.');
			redirect();
		}
		
		//$p = md5(mysql_escape_string($psw));
		$cek = $this->m_login->masuk($usr, $psw);
		if ($cek->num_rows() > 0) {
			foreach ($cek->result() as $qad) {
				
				// --- cek kode finger ---
				$qfinger = "select a.id_finger,b.kd_kantor,c.nm_kantor,b.nm_unit,a.kd_level from mas_peg a 
				left join m_unit b on a.kd_unit = b.kd_unit
				left join m_kantor c on b.kd_kantor = c.kd_kantor
				where a.no_peg = '$usr'";
				$rdt = $this->db->query($qfinger)->result();
				$kode_finger = $rdt[0]->id_finger;
				$nm_kantor = $rdt[0]->nm_kantor;
				$nm_unit = $rdt[0]->nm_unit;
				$kd_level = $rdt[0]->kd_level;
				$kdjab = substr($qad->kd_jab,0,1);

				if($qad->kd_jab == "01" || $qad->kd_jab == "02" || $qad->kd_jab == "04"){
					$jab = "PENGURUS";
				}
				else if($qad->kd_jab == "10" || $qad->kd_jab == "26" || $qad->kd_jab == "102"){
					$jab = "KADIV";
				}
				else if($kdjab == "2"){
					$jab = "MR";
				}
				else if($kdjab == "3" || $kd_level == "2"){
					$jab = "MO";
				}
				else if($kdjab == "4"){
					$jab = "SPV";
				}
				else{
					$jab = "STAFF";
				}

				
				$sess_data['nama'] = $qad->nama;
                $sess_data['username'] = $qad->username;
				$sess_data['kd_jab'] = $qad->kd_jab;
				$sess_data['kantor'] = $nm_kantor;
				$sess_data['kode_finger'] = $kode_finger;
				$sess_data['nm_unit'] = $nm_unit;
				$sess_data['jab'] = $jab;
				
				$this->session->set_userdata($sess_data);
			}
			redirect('Welcome/sukses');
		}
		else{
			$this->session->set_flashdata('result_login', '<br>Username atau Password yang anda masukkan salah.');
            redirect();
		}
    }
	
	function sukses(){
	    // Set timezone ke UTC+7 (Jakarta)
        date_default_timezone_set('Asia/Jakarta');
    
		$tglNow = $this->func_global->dsql_tglfull(date("Y-m-d"));
		$hari = date("D");
		$today = date("Y-m-d");
		$username = $this->session->userdata('username');

		if($hari == "Sun"){
			$hari_ini = "Minggu";
		}
		else if($hari == "Mon"){
			$hari_ini = "Senin";
		}
		else if($hari == "Tue"){
			$hari_ini = "Selasa";
		}
		else if($hari == "Wed"){
			$hari_ini = "Rabu";
		}
		else if($hari == "Thu"){
			$hari_ini = "Kamis";
		}
		else if($hari == "Fri"){
			$hari_ini = "Jum'at";
		}
		else if($hari == "Sat"){
			$hari_ini = "Sabtu";
		}


		

		
		
		if($this->session->userdata('username') == ""){
			redirect('Welcome/index');
		}
		else{

			$qmaspeg = "SELECT a.kd_jab,a.kd_job,a.kd_unit,a.device_id,a.check_all,c.`ket_bagian`,c.`flag_wfh`,a.flagwfo,b.kd_bagian FROM mas_peg a
			LEFT JOIN m_unit b ON a.`kd_unit` = b.kd_unit
			LEFT JOIN m_bagian c ON b.`kd_bagian` = c.`kd_bagian`
			WHERE a.no_peg = '".$username."'";
			
			$qdt = $this->db_hrdonline->query($qmaspeg)->result();
			$kd_jab = $qdt[0]->kd_jab;
			$kd_job = $qdt[0]->kd_job;
			$deviceid_maspeg = $qdt[0]->device_id;
			$check_all = $qdt[0]->check_all;
			$ket_bagian = $qdt[0]->ket_bagian;
			$flag_wfh = $qdt[0]->flag_wfh;
			$flagwfo = $qdt[0]->flagwfo;
			$kd_bagian = $qdt[0]->kd_bagian;

			
			$data = array(
				'username' => $this->session->userdata('username'),
				'nama' => $this->session->userdata('nama'),
				'kantor' => $this->session->userdata('kantor'),
				'nm_unit' => $this->session->userdata('nm_unit'),
				'tglnow' => $tglNow,
				'hari' => $hari_ini,
				'flag_wfh' => $flag_wfh,
				'ket_bagian' => $ket_bagian,
				'flagwfo' => $flagwfo,
				'kd_bagian' => $kd_bagian,
			);
			
			$this->load->view('general/header');	
			$this->load->view('general/sidebar');	
			$this->load->view('home',$data);	
			$this->load->view('general/footer');	
		}
	}

	function logout(){
		$this->session->sess_destroy();
		redirect('Welcome/index');
	}

// 	function undangansic(){
// 		$data = array(
// 			'username' => $this->session->userdata('username'),
// 			'nama' => $this->session->userdata('nama'),
// 			'kantor' => $this->session->userdata('kantor'),
// 			'nm_unit' => $this->session->userdata('nm_unit')
// 		);

// 		$this->load->view('general/header');	
// 		$this->load->view('general/sidebar');	
// 		$this->load->view('general/undangansic',$data);	
// 		$this->load->view('general/footer');
// 	}

// 	function tukarhadiah(){
// 		$data = array(
// 			'username' => $this->session->userdata('username'),
// 			'nama' => $this->session->userdata('nama'),
// 			'kantor' => $this->session->userdata('kantor'),
// 			'nm_unit' => $this->session->userdata('nm_unit')
// 		);

// 		$this->load->view('general/header');	
// 		$this->load->view('general/sidebar');	
// 		$this->load->view('general/tukarhadiah',$data);	
// 		$this->load->view('general/footer');
// 	}
}
