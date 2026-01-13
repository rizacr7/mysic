
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>View Approve Cuti Pegawai</h6>
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
                  <th>Bukti</th>
                  <th>Nama</th>
                  <th>Tgl.Cuti</th>
                  <th>Lama</th>
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
                $dataresultapp = $this->sdm_model->viewappcutipegawai($param);

                foreach($dataresultapp as $key => $val){

                  $tgl_input = substr($val['tgl_approve'],1,10);
                  $id_cuti = $val['id'];

                  $tgl_input_date = new DateTime($tgl_input);
                  $tgl_sekarang   = new DateTime(date('Y-m-d'));

                  $selisih = $tgl_input_date->diff($tgl_sekarang)->days;

                  if ($tgl_sekarang <= $tgl_input_date->modify('+3 days')) {
                      $btnaction = "<button class='btn m-1 btn-sm btn-warning'
                        onclick=\"batalappcuti('{$val['id']}')\">
                        <i class='bi bi-arrow-clockwise'></i>
                      </button>";
                  } else {
                      $btnaction = ""; // kosongkan
                  }

                  if($val['status_approve'] == '1'){
                    $label_app = "<span class='m-1 badge rounded-pill bg-primary'>Approved</span>";
                  }
                  else{
                    $label_app = "<span class='m-1 badge rounded-pill bg-danger'>Pending</span>";
                  }
                  

                  echo "
                  <tr>
                    <td>".$val['no_bukti']."</td>
                    <td>".$val['na_peg']."</td>
                    <td>".$val['tgl_awal']." s.d ".$val['tgl_akhir']."</td>
                    <td>".$val['lama']."</td>
                    <td>".$val['keterangan']."</td>
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
	

function batalappcuti(id_cuti) {
  Swal.fire({
      title: 'Membatalkan data cuti?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, batalkan!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/approve/bataldtcuti",
              type: "POST",
              data: { id_cuti: id_cuti },
              dataType: "json",
              success: function(res) {
                  if (res.status === true) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Berhasil',
                          text: 'Data berhasil dibatalkan',
                          timer: 1500,
                          showConfirmButton: false
                      });
                      // reload DataTable
                      window.location.href='<?php echo base_url(); ?>index.php/approve/view_app_cuti';
                      // $('#dataTable').DataTable().ajax.reload(null, false);
                  } else {
                      Swal.fire('Gagal', res.message ?? 'Gagal menghapus data', 'error');
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
	