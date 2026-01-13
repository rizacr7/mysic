
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data Cuti Pegawai</h6>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-body">
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
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php 
                $no_peg = $Datapeg[0]->no_peg;
                $param = array();
                $param['no_peg'] = $no_peg;
                $dataresult = $this->sdm_model->cutipegawai($param);

                foreach($dataresult as $key => $val){

                  $tgl_input = $val['tgl_update'];
                  $id_cuti = $val['id'];

                  $tgl_input_date = new DateTime($tgl_input);
                  $tgl_sekarang   = new DateTime(date('Y-m-d'));

                  $selisih = $tgl_input_date->diff($tgl_sekarang)->days;

                  if ($tgl_sekarang <= $tgl_input_date->modify('+3 days')) {
                      $btnaction = "<button class='btn m-1 btn-sm btn-danger'
                        onclick=\"hapuscuti('{$val['id']}')\">
                        <i class='bi bi-trash'></i>
                      </button>";
                  } else {
                      $btnaction = ""; // kosongkan
                  }

                  echo "
                  <tr>
                    <td>".$val['no_bukti']."</td>
                    <td>".$val['na_peg']."</td>
                    <td>".$val['tgl_awal']." s.d ".$val['tgl_akhir']."</td>
                    <td>".$val['lama']."</td>
                    <td>".$val['keterangan']."</td>
                    <td>".$btnaction."</td>
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
	
// $('#dataTable').DataTable({
//     scrollX: true,
//     autoWidth: false
// });

function hapuscuti(id_cuti) {
  Swal.fire({
      title: 'Hapus data cuti?',
      text: 'Data yang dihapus tidak bisa dikembalikan!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/sdm/hapusdtcuti",
              type: "POST",
              data: { id_cuti: id_cuti },
              dataType: "json",
              success: function(res) {
                  if (res.status === true) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Berhasil',
                          text: 'Data cuti berhasil dihapus',
                          timer: 1500,
                          showConfirmButton: false
                      });
                      // reload DataTable
                      window.location.href='<?php echo base_url(); ?>index.php/sdm/datacuti';
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
</script>
	