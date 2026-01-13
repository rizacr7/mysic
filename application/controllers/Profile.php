<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

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
		$this->load->model('diklat_model');

		if($this->session->userdata('username') == ""){
			redirect('Welcome/index');
		}
	}
	
	public function profilepegawai()
	{
		$no_peg = $this->session->userdata('username');

		$Datapeg = $this->m_login->profilepegawai($no_peg);
		$Param  = array();
		$Param['Datapeg']= $Datapeg;

		$this->load->view('general/header');	
		$this->load->view('general/sidebar');	
		$this->load->view('profile/vprofile',$Param);	
		$this->load->view('general/footer');	
	}

	function mapping_pegawai(){
		
		$no_peg = $this->session->userdata('username');
		

		//--cek data ---
		$qpegawai = "SELECT * FROM mas_peg WHERE no_peg = '$no_peg'";
		$dt = $this->db->query($qpegawai)->result();
		$nik = $dt[0]->no_ktp;
		$no_pph = $dt[0]->no_pph;
		
		echo "
		<table class='table mb-0 table-striped'>";

			$Datamutasi = $this->diklat_model->mutasipegawai($no_peg,$nik,$no_pph);
			$no=1;
			$jmData = $Datamutasi->num_rows();

			if($jmData > 0){
				foreach($Datamutasi->result_array() as $val){
					$file_sk = $val['file_sk'];
					$download="";

					if($file_sk != ""){
						$querySK = "SELECT * FROM sk_pegawai WHERE bukti = '".$file_sk."' AND status_hapus = 0";
						$dt = $this->db->query($querySK)->result();
						$linkdownload = $dt[0]->link_download;
						$link = "<a href='$linkdownload' target='_blank'>file sk</a>";
						$download = "<label class='label label-warning'>$link</label>";
					}
					
					echo "
					<tr>
						<td>".$no."</td>
						<td>".$this->func_global->dsql_tgl($val['tanggal'])."</td>
						<td>".$val['uraian']."</td>
						<td>".$download."</td>
					</tr>";
					$no++;
				}
			}
			else{
				echo "
				<tr>
					<td>Data Tidak ditemukan</td>
				</tr>";
			}
		echo "</table>";
	}

	function mapping_golongan(){

		$no_peg = $this->session->userdata('username');
		
		//--cek data ---
		$qpegawai = "SELECT * FROM mas_peg WHERE no_peg = '$no_peg'";
		$dt = $this->db->query($qpegawai)->result();
		$no_ktp = $dt[0]->no_ktp;
		$no_pph = $dt[0]->no_pph;
		
		$html='
		<table class="table mb-0 table-striped">
			<tr class="info">
				<th>Tanggal</th>
				<th>Keterangan</th>
			</tr>';

		$queryLama = "SELECT tgl_tran as tanggal,uraian as ket 
		FROM db_cuti.mem_peg WHERE no_peg IN (SELECT no_peg FROM mas_peg WHERE no_pph='$no_pph') AND hapus<>'1' 
		ORDER BY tgl_tran";
		$rdt = $this->db->query($queryLama)->num_rows();
		if($rdt > 0){
			$qresult = $this->db->query($queryLama);
			$rows = array();
			$l=0;

			foreach($qresult->result() as $dtlm){
				$rows[$l]['tanggal'] = $dtlm->tanggal;
				$rows[$l]['ket'] = $dtlm->ket;
	
				$html.='<tr>
					<td>'.$this->func_global->dsql_tgl($dtlm->tanggal).'</td>
					<td>'.$dtlm->ket.'</td>
				</tr>';
			}
		}
		else{
			$qbaru = "SELECT *,SUBSTR(no_peg,1,2) as kdpeg FROM mas_peg WHERE no_ktp = '$no_ktp' ORDER BY id_pegawai ASC";
			
			$qdata = $this->db->query($qbaru)->num_rows();
			$rows = array();
			$l=0;
			for($l=0;$l<$qdata;$l++){
				$qresult = $this->db->query($qbaru)->result();
				if($l==0){
					$html.='
					<tr>
						<td>'.$this->func_global->dsql_tgl($qresult[0]->tgl_masuk).'</td>
						<td>Mulai Bekerja</td>
					</tr>';
				}
				else{
					if($qresult[$l]->kdpeg != "KW"){
						$html.='
						<tr>
							<td>'.$this->func_global->dsql_tgl($qresult[$l]->tgl_masuk).'</td>
							<td>Pergantian Nomer pegawai '.$qresult[$l]->no_peg_lm.' Ke '.$qresult[$l]->no_peg.'</td>
						</tr>';
					}
				}
			}
			
		}

		$query = "SELECT m.* FROM (SELECT tgl_update AS tanggal,CONCAT(IF(keterangan IS NULL,'Perubahan Golongan ',keterangan),' ',kd_golongan_lama,' Ke ',kd_golongan_baru) AS ket,sk_golongan as sk FROM t_history_golongan 
		WHERE no_peg = '$no_peg' AND is_del = 0
		UNION
		SELECT a.tanggal AS tanggal,CONCAT('Perubahan dari ',ket_job_lm,' Ke ',ket_job_baru) AS ket, '' as sk 
		FROM t_jobdesc a 
		LEFT JOIN mas_peg b ON a.no_peg = b.no_peg 
		WHERE b.no_ktp = '$no_ktp'
		UNION
		SELECT tgl_update AS tanggal,CONCAT(IF(a.keterangan IS NULL,'Perubahan jabatan ',a.keterangan),b.`nm_jab`, ' Ke ', c.`nm_jab`) AS ket,sk_jabatan as sk FROM t_history_jab a 
		LEFT JOIN m_jabatan b ON a.`kd_jab_lama` = b.`kd_jab`
		LEFT JOIN m_jabatan c ON a.`kd_jab_baru` = c.`kd_jab`
		WHERE a.no_peg = '$no_peg' AND a.is_del = 0) m ORDER BY m.tanggal ASC";

		$qdata = $this->db->query($query);
		$rows = array();
		$i=0;
		foreach($qdata->result() as $dt){
			$rows[$i]['tanggal'] = $dt->tanggal;
			$rows[$i]['ket'] = $dt->ket;
			$rows[$i]['sk'] = $dt->sk;

			if($dt->sk == ""){
				$download="";
			}
			else{
				$querySK = "SELECT * FROM sk_pegawai WHERE bukti = '".$dt->sk."' AND status_hapus = 0";
				$val = $this->db->query($querySK)->result();
				$linkdownload = $val[0]->link_download;

				$link = "<a href='$linkdownload' target='_blank'>file sk</a>";
				$download = "<label class='label label-warning'>$link</label>";
			}

			$html.='<tr>
				<td>'.$this->func_global->dsql_tgl($dt->tanggal).'</td>
				<td>'.$dt->ket.' '.$download.'</td>
			</tr>';
		}

		$html.='</table>';

		echo $html;
	}
	
}
