
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data PJK SPPD Pegawai</h6>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-body">
        <button type="button" class="btn btn-danger w-100" onclick="kembali()" id="btnsimpan"><i class='bi bi-arrow-left-circle'></i> Back</button>
        <p></p>
        <div class="spinner-border text-primary mb-3"
            role="status" style="display:none" id="loading">
          <span class="visually-hidden">Loading...</span>
        </div>

        <?php 
          $no_peg = $Datapeg[0]->no_peg;
          $param['no_peg'] = $no_peg;
          $dataresult = $this->sdm_model->pjksppdpegawai($param);

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

            $label_app = ($val['APPROVE_ATASAN'] == '1')
              ? "<span class='badge rounded-pill bg-primary'>Approved</span>"
              : "<span class='badge rounded-pill bg-danger'>Pending</span>";

            $btnaction = ($val['APPROVE_ATASAN'] == '0')
              ? "<button class='btn btn-sm btn-danger'
                    onclick=\"hapuspjksppd('{$val['ID']}')\">
                    <i class='bi bi-trash'></i>
                </button>"
              : "";

            $viewaction = "
              <button class='btn btn-sm btn-dark'
                onclick=\"viewpjk('{$val['ID']}')\">
                <i class='bi bi-search'></i> View
              </button>
            ";
        ?>

        <!-- CARD ITEM -->
        <div class="border rounded p-3 mb-3 bg-light">

          <!-- HEADER -->
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <strong><?php echo $val['BUKTI_PJK']; ?></strong><br>
              <small class="text-muted">
                <?php echo $val['AWAL_TUGAS']." s.d ".$val['AKIR_TUGAS']; ?>
              </small>
            </div>
            <?php echo $label_app; ?>
          </div>

          <!-- BODY -->
          <div class="row small mb-2">
            <div class="col-12 mb-2">
              <span class="text-muted">Nama</span><br>
              <span class="fw-medium"><?php echo $val['na_peg']; ?></span>
            </div>

            <div class="col-12 mb-2">
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

            <div class="col-6 mb-2">
              <span class="text-muted">Beban</span><br>
              <span class="fw-medium"><?php echo $val['BEBAN']; ?></span>
            </div>

            <div class="col-6 mb-2">
              <span class="text-muted">Kode Biaya</span><br>
              <span class="fw-medium"><?php echo $val['KA']; ?></span>
            </div>
          </div>

          <!-- FOOTER -->
          <div class="d-flex justify-content-between align-items-center">
            <?php echo $viewaction; ?>
            <?php echo $btnaction; ?>
          </div>

        </div>
        <!-- END CARD ITEM -->

        <?php } ?>

      </div>
    </div>
    </div>

    <div class="modal fade" id="fullscreenModal" tabindex="-1" aria-labelledby="fullscreenModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen-md-down">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title" id="fullscreenModalLabel">View PJK SPPD</h6>
            <button class="btn btn-close p-1 ms-auto" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          
          </div>
          <div class="modal-footer">
            <button class="btn btn-sm btn-danger" type="button" data-bs-dismiss="modal">Close</button>
            <!-- <button class="btn btn-sm btn-success" type="button">Save</button> -->
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

function hapuspjksppd(id_pjk) {
  Swal.fire({
      title: 'Hapus data pjk sppd?',
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
              url: "<?php echo base_url(); ?>index.php/sdm/hapusdtpjksppd",
              type: "POST",
              data: { id_pjk: id_pjk },
              dataType: "json",
              success: function(res) {
                  if (res.status === true) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Berhasil',
                          text: 'Data pjk sppd berhasil dihapus',
                          timer: 1500,
                          showConfirmButton: false
                      });
                      // reload DataTable
                      window.location.href='<?php echo base_url(); ?>index.php/sdm/datapjksppd';
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



function viewpjk(id_pjk) {
    // buka modal
    $('#fullscreenModal').modal('show');
    $('#fullscreenModal .modal-body').html(`
        <div class="text-center p-4">
            <div class="spinner-border text-primary"></div>
            <div class="mt-2">Memuat data...</div>
        </div>
    `);

    // AJAX kirim ID
    $.ajax({
        url: "<?= base_url('index.php/view/view_pjk') ?>",
        type: "POST",
        data: {
            id_pjk: id_pjk
        },
        success: function (res) {
            $('#fullscreenModal .modal-body').html(res);
        },
        error: function () {
            $('#fullscreenModal .modal-body').html(`
                <div class="alert alert-danger">
                    Gagal memuat data PJK
                </div>
            `);
        }
    });
}

function kembali(){
   window.location.href='<?php echo base_url(); ?>index.php/sdm/pagemenu';
}
</script>
	