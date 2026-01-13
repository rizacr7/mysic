
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>View Approve Pengajuan Izin Pegawai</h6>
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
                  <th>Tgl.Izin</th>
                  <th>Nama</th>
                  <th>Jenis</th>
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
                $dataresultapp = $this->sdm_model->viewappizinpegawai($param);

                foreach($dataresultapp as $key => $val){

                  $tgl_input = $val['tgl_input'];
                  $tgl_app = $val['tgl_app'];
                  $id_izin = $val['id_izin'];

                  if($val['kdizin'] == 1){
                    $kdizin = "Terlambat";
                  }
                  else{
                    $kdizin = "Pulang Cepat";
                  }

                  $tgl_input_date = new DateTime($tgl_app);
                  $tgl_sekarang   = new DateTime(date('Y-m-d'));

                  $selisih = $tgl_input_date->diff($tgl_sekarang)->days;
                

                  if($val['flag_app'] == 1){
                    if ($tgl_sekarang <= $tgl_input_date->modify('+3 days')) {
                        $btnaction = "<button class='btn m-1 btn-sm btn-warning'
                          onclick=\"batalizin('{$val['id_izin']}')\">
                         <i class='bi bi-arrow-clockwise'></i>
                        </button>";
                    } else {
                        $btnaction = ""; // kosongkan
                    }
                    $status = "<span class='m-1 badge rounded-pill bg-primary'>Approved</span>";
                  }
                  else{
                    $status = "<span class='m-1 badge rounded-pill bg-warning'>Pending</span>";
                  }

                  echo "
                  <tr>
                    <td>".$btnaction."</td>
                    <td>".$val['tgl_izin']."</td>
                    <td>".$val['na_peg']."</td>
                    <td>".$kdizin."</td>
                    <td>".$val['keterangan']."</td>
                    <td>".$status."</td>
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
	

function batalizin(id_izin) {
  Swal.fire({
      title: 'Membatalkan data izin?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, batalkan!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/sdm/bataldtizin",
              type: "POST",
              data: { id_izin: id_izin },
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
                      window.location.href='<?php echo base_url(); ?>index.php/sdm/view_app_izin';
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
	