
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>View Approve PJK SPPD Pegawai</h6>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-body">

        <!-- BACK BUTTON -->
        <button type="button" class="btn btn-danger w-100 mb-3" onclick="kembali()" id="btnsimpan">
          <i class="bi bi-arrow-left-circle"></i> Back
        </button>

        <!-- LOADING -->
        <div class="text-center mb-3">
          <div class="spinner-border text-primary" role="status" style="display:none" id="loading">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>

        <!-- VIEW PJK -->
        <button class="btn btn-dark btn-sm mb-3 w-100" onclick="viewpjkdata()" style="display:none">
          <i class="bi bi-search"></i> View PJK SPPD
        </button>

        <!-- LIST CARD -->
        <div class="row">
        <?php
          $no_peg = $Datapeg[0]->no_peg;
          $param = [
            'no_peg'     => $no_peg,
            'kd_unit'    => $Datapeg[0]->kd_unit,
            'kd_bagian'  => $Datapeg[0]->kd_bagian,
            'kd_jab'     => $Datapeg[0]->kd_jab,
            'kd_level'   => $Datapeg[0]->kd_level
          ];

          $dataresultapp = $this->sdm_model->viewapppjksppdpegawai($param);

          foreach ($dataresultapp as $val) {

            $tgl_input = substr($val['TGL_APP'], 1, 10);
            $tgl_input_date = $tgl_input ? new DateTime($tgl_input) : null;
            $tgl_sekarang = new DateTime(date('Y-m-d'));

            if ($tgl_input_date &&
                $tgl_sekarang <= $tgl_input_date->modify('+4 days') &&
                $val['APPROVE_ATASAN'] == '1') {
              $btnaction = "<button class='btn btn-sm btn-warning'
                onclick=\"batalapppjksppd('{$val['ID']}')\">
                <i class='bi bi-arrow-clockwise'></i> Batalkan
              </button>";
            } else {
              $btnaction = "";
            }

            $viewaction = "
              <button class='btn btn-sm btn-dark'
                onclick=\"viewpjk('{$val['ID']}')\">
                <i class='bi bi-search'></i> View
              </button>
            ";

            $label_app = ($val['APPROVE_ATASAN'] == '1')
              ? "<span class='badge bg-primary'>Approved</span>"
              : "<span class='badge bg-danger'>Pending</span>";

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
        ?>

          <div class="col-12 mb-3">
            <div class="card shadow-sm border-0">
              <div class="card-body bg-light">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <input type="checkbox" class="form-check-input me-2 checkItem" value="<?= $val['ID'] ?>">
                    <strong><?= $val['na_peg'] ?></strong>
                  </div>
                  <?= $label_app ?>
                </div>

                <!-- DATE -->
                <div class="small text-muted mb-2">
                  <?= $val['AWAL_TUGAS'] ?> s.d <?= $val['AKIR_TUGAS'] ?>
                </div>

                <!-- CONTENT -->
                <ul class="list-unstyled small mb-3">
                  <li><b>Tujuan:</b> <?= $val['TUJUAN'] ?></li>
                  <li><b>Dalam Rangka:</b> <?= $val['DALAM_RANGKA'] ?></li>
                  <li><b>Akomodasi:</b> <?= $akomodasi ?></li>
                  <li><b>Kendaraan:</b> <?= $kendaraan ?></li>
                  <li><b>Beban:</b> <?= $val['BEBAN'] ?></li>
                  <li><b>Bukti:</b> <?= $val['BUKTI_PJK'] ?></li>
                </ul>

                <!-- ACTION -->
                <div class="d-flex justify-content-between align-items-center">
                  <?= $viewaction ?>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <?= $btnaction ?>
                  <small class="text-muted" style='display:none'>ID: <?= $val['ID'] ?></small>
                </div>

              </div>
            </div>
          </div>

        <?php } ?>
        </div>

        <!-- MODAL VIEW PJK -->
        <div class="modal fade" id="fullscreenModal" tabindex="-1" aria-labelledby="fullscreenModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-fullscreen-md-down">
            <div class="modal-content">
              <div class="modal-header">
                <h6 class="modal-title" id="fullscreenModalLabel">View PJK SPPD</h6>
                <button class="btn btn-close" type="button" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <!-- content from viewpjk() -->
              </div>
              <div class="modal-footer">
                <button class="btn btn-sm btn-danger" type="button" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	
  document.addEventListener("DOMContentLoaded", function () {

    // INIT VANILLA DATATABLE
    const dataTable = new DataTable("#dataTable", {
        searchable: true,
        fixedHeight: true,
        perPage: 10
    });

    // CHECK ALL
    document.getElementById("checkAll").addEventListener("change", function () {
        const checked = this.checked;
        document.querySelectorAll(".checkItem").forEach(cb => {
            cb.checked = checked;
        });
    });

    // UNCHECK HEADER JIKA SALAH SATU DILEPAS
    document.addEventListener("change", function (e) {
        if (e.target.classList.contains("checkItem") && !e.target.checked) {
            document.getElementById("checkAll").checked = false;
        }
    });

});

function batalapppjksppd(id_pjk) {
  Swal.fire({
      title: 'Membatalkan data pjk sppd?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, batalkan!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/approve/bataldtpjksppd",
              type: "POST",
              data: { id_pjk: id_pjk },
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
                      window.location.href='<?php echo base_url(); ?>index.php/approve/view_app_pjksppd';
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


function viewpjkdata() {
    let ids = [];

    $('.checkItem:checked').each(function () {
        ids.push($(this).val());
    });

    // validasi
    if (ids.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Pilih satu data PJK terlebih dahulu'
        });
        return;
    }

    if (ids.length > 1) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Hanya boleh memilih satu data'
        });
        return;
    }

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
            id_pjk: ids[0]
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
   window.location.href='<?php echo base_url(); ?>index.php/sdm/pageapprove';
}
</script>
	