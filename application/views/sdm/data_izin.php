
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data Izin Pegawai</h6>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-body">

        <div class="spinner-border text-primary mb-3" role="status"
            style="display:none" id="loading">
          <span class="visually-hidden">Loading...</span>
        </div>

        <?php 
          $no_peg = $Datapeg[0]->no_peg;
          $param['no_peg'] = $no_peg;
          $dataresult = $this->sdm_model->izinpegawai($param);

          foreach($dataresult as $val){

            $tgl_input = new DateTime($val['tgl_input']);
            $tgl_sekarang = new DateTime(date('Y-m-d'));

            $kdizin = ($val['kdizin'] == 1) ? "Terlambat" : "Pulang Cepat";

            if($val['flag_app'] == 1){
              $status = "<span class='badge rounded-pill bg-primary'>Approved</span>";
              $btnaction = "";
            } else {
              $status = "<span class='badge rounded-pill bg-warning'>Pending</span>";

              if ($tgl_sekarang <= $tgl_input->modify('+3 days')) {
                $btnaction = "
                  <button class='btn btn-sm btn-danger'
                    onclick=\"hapusizin('{$val['id_izin']}')\">
                    <i class='bi bi-trash'></i>
                  </button>
                ";
              } else {
                $btnaction = "";
              }
            }
        ?>

        <div class="border rounded p-3 mb-3 bg-light">

          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <strong><?php echo $val['na_peg']; ?></strong><br>
              <small class="text-muted"><?php echo $this->func_global->dsql_tgl($val['tgl_izin']); ?></small>
            </div>
            <?php echo $status; ?>
          </div>

          <div class="row small mb-2">
            <div class="col-6">
              <span class="text-muted">Jenis</span><br>
              <span class="fw-medium"><?php echo $kdizin; ?></span>
            </div>
            <div class="col-6">
              <span class="text-muted">Keterangan</span><br>
              <span class="fw-medium"><?php echo $val['keterangan']; ?></span>
            </div>
          </div>

          <?php if($btnaction != ""){ ?>
          <div class="text-end">
            <?php echo $btnaction; ?>
          </div>
          <?php } ?>

        </div>

        <?php } ?>

      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
	

function hapusizin(id_izin) {
  Swal.fire({
      title: 'Hapus data izin?',
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
              url: "<?php echo base_url(); ?>index.php/sdm/hapusdtizin",
              type: "POST",
              data: { id_izin: id_izin },
              dataType: "json",
              success: function(res) {
                  if (res.status === true) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Berhasil',
                          text: 'Data berhasil dihapus',
                          timer: 1500,
                          showConfirmButton: false
                      });
                      // reload DataTable
                      window.location.href='<?php echo base_url(); ?>index.php/sdm/dataizin';
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
	