
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

        <div class="spinner-border text-primary mb-3"
            role="status" style="display:none" id="loading">
          <span class="visually-hidden">Loading...</span>
        </div>

        <?php 
          $no_peg = $Datapeg[0]->no_peg;
          $param['no_peg'] = $no_peg;
          $dataresult = $this->sdm_model->cutipegawai($param);

          foreach($dataresult as $val){

            $tgl_input_date = new DateTime($val['tgl_update']);
            $tgl_sekarang   = new DateTime(date('Y-m-d'));

            $btnaction = ($tgl_sekarang <= $tgl_input_date->modify('+3 days'))
              ? "<button class='btn btn-sm btn-danger'
                    onclick=\"hapuscuti('{$val['id']}')\">
                    <i class='bi bi-trash'></i> Hapus
                </button>"
              : "";
        ?>

        <!-- CARD ITEM -->
        <div class="border rounded p-3 mb-3 bg-light">

          <!-- HEADER -->
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <strong><?php echo $val['no_bukti']; ?></strong><br>
              <small class="text-muted"><?php echo $val['na_peg']; ?></small>
            </div>
          </div>

          <!-- BODY -->
          <div class="row small mb-2">
            <div class="col-12 mb-2">
              <span class="text-muted">Tanggal Cuti</span><br>
              <span class="fw-medium">
                <?php echo $val['tgl_awal']." s.d ".$val['tgl_akhir']; ?>
              </span>
            </div>

            <div class="col-6 mb-2">
              <span class="text-muted">Lama</span><br>
              <span class="fw-medium"><?php echo $val['lama']; ?> hari</span>
            </div>

            <div class="col-12 mb-2">
              <span class="text-muted">Keterangan</span><br>
              <span class="fw-medium"><?php echo $val['keterangan']; ?></span>
            </div>
          </div>

          <!-- FOOTER -->
          <?php if($btnaction != ""){ ?>
          <div class="text-end">
            <?php echo $btnaction; ?>
          </div>
          <?php } ?>

        </div>
        <!-- END CARD ITEM -->

        <?php } ?>

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
	