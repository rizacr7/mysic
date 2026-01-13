
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data Pengajuan SPPD Pegawai</h6>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-body">

          <button type="button" class="btn btn-danger w-100" onclick="kembali()" id="btnsimpan"><i class='bi bi-arrow-left-circle'></i> Back</button>

          <div class="spinner-border text-primary" role="status" style="display:none" align="center" id="loading">
            <span class="visually-hidden">Loading...</span>
          </div>

          <div class="row">
            <div class="col-12 col-md-12">
              <div class="table-responsive">
              <table class="display nowrap" style="width:100%" id="dataTable">
              <thead>
                <tr>
                  <th>Action</th>
                  <th>Bukti</th>
                  <th>Nama</th>
                  <th>Tgl.SPPD</th>
                  <th>Tujuan</th>
                  <th>Akomodasi</th>
                  <th>Kendaraan</th>
                  <th>Keterangan</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
              <?php 
                $no_peg = $Datapeg[0]->no_peg;
                $param = array();
                $param['no_peg'] = $no_peg;
                $param['kd_unit'] = $Datapeg[0]->kd_unit;
                $param['kd_bagian'] = $Datapeg[0]->kd_bagian;
                $param['kd_jab'] = $Datapeg[0]->kd_jab;
                $param['kd_level'] = $Datapeg[0]->kd_level;
                $dataresultapp = $this->sdm_model->appsppdpegawai($param);

                foreach($dataresultapp as $key => $val){

                  $tgl_input = $val['TGL_UPDATE'];
                  $id_sppd = $val['ID'];

                  $tgl_input_date = new DateTime($tgl_input);
                  $tgl_sekarang   = new DateTime(date('Y-m-d'));

                  $selisih = $tgl_input_date->diff($tgl_sekarang)->days;

                  if ($val['APPROVE'] == '0') {
                      $btnaction = "<button class='btn m-1 btn-sm btn-info'
                        onclick=\"approvesppd('{$val['ID']}')\">
                        <i class='bi bi-check-square'></i>
                      </button>";
                  } else {
                      $btnaction = ""; // kosongkan
                  }

                  if($val['APPROVE'] == '1'){
                    $label_app = "<span class='m-1 badge rounded-pill bg-primary'>Approved</span>";
                  }
                  else{
                    $label_app = "<span class='m-1 badge rounded-pill bg-danger'>Pending</span>";
                  }
                  
                  if($val['STS_PJK'] == '1'){
                    $label_pjk = "<span class='m-1 badge rounded-pill bg-success'><i class='bi bi-check2-circle'></i></span>";
                  }
                  else{
                    $label_pjk = "<span class='m-1 badge rounded-pill bg-danger'><i class='bi bi-dash-circle'></i></span>";
                  }

                  if($val['AKOMODASI'] == '1'){
                    $akomodasi = "Hotel";
                  }
                  else if($val['AKOMODASI'] == '2'){
                    $akomodasi = "Luar";
                  }
                  else if($val['AKOMODASI'] == '3'){
                    $akomodasi = "Mess";
                  }
                  else{
                    $akomodasi = "-";
                  }

                  if($val['KENDARAAN'] == '1'){
                    $kendaraan = "Dinas";
                  }
                  else if($val['KENDARAAN'] == '2'){
                    $kendaraan = "Kereta Api";
                  }
                  else if($val['KENDARAAN'] == '3'){
                    $kendaraan = "Bus";
                  }
                  else if($val['KENDARAAN'] == '4'){
                    $kendaraan = "Kapal";
                  }
                  else if($val['KENDARAAN'] == '5'){
                    $kendaraan = "Pesawat";
                  }
                  else if($val['KENDARAAN'] == '6'){
                    $kendaraan = "Lain-lain";
                  }
                  else{
                    $kendaraan = "-";
                  }

                  echo "
                  <tr>
                    <td>".$btnaction."</td>
                    <td>".$val['BUKTI']."</td>
                    <td>".$val['na_peg']."</td>
                    <td>".$val['TGL_AWAL']." s.d ".$val['TGL_AKHIR']."</td>
                    <td>".$val['TUJUAN']."</td>
                    <td>".$val['DALAM_RANGKA']."</td>
                    <td>".$akomodasi."</td>
                    <td>".$kendaraan."</td>
                    <td>".$label_app."</td>
                   
                  </tr>";
                }

              ?>
              </tbody>
            </table>
            </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	

function approvesppd(id_sppd) {
  Swal.fire({
      title: 'Menyetujui data sppd?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, izinkan!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/approve/approve_sppd",
              type: "POST",
              data: { id_sppd: id_sppd },
              dataType: "json",
              success: function(res) {
                  if (res.status === true) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Berhasil',
                          text: 'Data berhasil disetujui',
                          timer: 1500,
                          showConfirmButton: false
                      });
                      // reload DataTable
                      window.location.href='<?php echo base_url(); ?>index.php/approve/app_sppd';
                      // $('#dataTable').DataTable().ajax.reload(null, false);
                  } else {
                      Swal.fire('Gagal', res.message ?? 'Gagal approve data', 'error');
                  }
              },
              error: function() {
                  Swal.fire('Error', 'Terjadi kesalahan server', 'error');
              }
          });
      }
  });
}

function kembali(){
   window.location.href='<?php echo base_url(); ?>index.php/sdm/pageapprove';
}
</script>
	