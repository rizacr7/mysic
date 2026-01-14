
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data SPPD Pegawai</h6>
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
          $dataresult = $this->sdm_model->sppdpegawai($param);

          foreach($dataresult as $val){

            /* ===== Mapping ===== */
            if($val['AKOMODASI'] == 1){
              $akomodasi = "Hotel";
            }
            else if($val['AKOMODASI'] == 2){
              $akomodasi = "Luar";
            }
            else if($val['AKOMODASI'] == 3){
              $akomodasi = "Mess";
            }
            else{
              $akomodasi = "-";
            }
            
            if($val['KENDARAAN'] == 1){
              $kendaraan = "Dinas";
            }
            else if($val['KENDARAAN'] == 2){
              $kendaraan = "Kereta Api";
            }
            else if($val['KENDARAAN'] == 3){
              $kendaraan = "Bus";
            }
            else if($val['KENDARAAN'] == 4){
              $kendaraan = "Kapal";
            }
            else if($val['KENDARAAN'] == 5){
              $kendaraan = "Pesawat";
            }
            else{
              $kendaraan = "Lain-Lain";
            }


            $label_app = ($val['APPROVE'] == '1')
              ? "<span class='badge rounded-pill bg-primary'>Approved</span>"
              : "<span class='badge rounded-pill bg-danger'>Pending</span>";

            $label_pjk = ($val['STS_PJK'] == '1')
              ? "<span class='badge rounded-pill bg-success'><i class='bi bi-check2-circle'></i> PJK</span>"
              : "<span class='badge rounded-pill bg-danger'><i class='bi bi-dash-circle'></i> PJK</span>";

            $btnaction = ($val['APPROVE'] == '0')
              ? "<button class='btn btn-sm btn-danger'
                  onclick=\"hapussppd('{$val['ID']}')\">
                  <i class='bi bi-trash'></i> Hapus
                </button>"
              : "";
        ?>

        <div class="border rounded p-3 mb-3 bg-light">

          <!-- HEADER -->
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <strong><?php echo $val['na_peg']; ?></strong><br>
              <small class="text-muted">
                <?php echo $val['TGL_AWAL']." s.d ".$val['TGL_AKHIR']; ?>
              </small>
            </div>
            <?php echo $label_app; ?>
          </div>

          <!-- BODY -->
          <div class="row small mb-2">
            <div class="col-6 mb-2">
              <span class="text-muted">Bukti</span><br>
              <span class="fw-medium"><?php echo $val['BUKTI']; ?></span>
            </div>
            <div class="col-6 mb-2">
              <span class="text-muted">Tujuan</span><br>
              <span class="fw-medium"><?php echo $val['TUJUAN']; ?></span>
            </div>

            <div class="col-12 mb-2">
              <span class="text-muted">Dalam Rangka</span><br>
              <span class="fw-medium"><?php echo $val['DALAM_RANGKA']; ?></span>
            </div>

            <div class="col-6 mb-2">
              <span class="text-muted">Akomodasi</span><br>
              <span class="fw-medium"><?php echo $akomodasi; ?></span>
            </div>

            <div class="col-6 mb-2">
              <span class="text-muted">Kendaraan</span><br>
              <span class="fw-medium"><?php echo $kendaraan; ?></span>
            </div>
          </div>

          <!-- FOOTER -->
          <div class="d-flex justify-content-between align-items-center">
            <?php echo $label_pjk; ?>
            <?php echo $btnaction; ?>
          </div>

        </div>

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

function hapussppd(id_sppd) {
  Swal.fire({
      title: 'Hapus data sppd?',
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
              url: "<?php echo base_url(); ?>index.php/sdm/hapusdtsppd",
              type: "POST",
              data: { id_sppd: id_sppd },
              dataType: "json",
              success: function(res) {
                  if (res.status === true) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Berhasil',
                          text: 'Data sppd berhasil dihapus',
                          timer: 1500,
                          showConfirmButton: false
                      });
                      // reload DataTable
                      window.location.href='<?php echo base_url(); ?>index.php/sdm/datasppd';
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
	