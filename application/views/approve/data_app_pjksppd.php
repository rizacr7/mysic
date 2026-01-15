
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data Pengajuan PJK SPPD Pegawai</h6>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-body">

          <button type="button" class="btn btn-danger w-100" onclick="kembali()" id="btnsimpan"><i class='bi bi-arrow-left-circle'></i> Back</button>
          <p></p>
          <div class="spinner-border text-primary" role="status" style="display:none" align="center" id="loading">
            <span class="visually-hidden">Loading...</span>
          </div>

          <div class="row">
            <div class="col-12">

              <!-- ACTION BUTTON -->
              <div class="d-flex gap-2 mb-3 flex-wrap">
                <button class="btn btn-secondary btn-sm" onclick="toggleSelectAll()">
                  <i class="bi bi-check-all"></i> Select All
                </button>

                <button class="btn btn-primary btn-sm" onclick="approveSelected()">
                  <i class="bi bi-check2-square"></i> Approve PJK SPPD
                </button>

                <!-- <button class="btn btn-dark btn-sm" onclick="viewpjk()">
                  <i class="bi bi-search"></i> View PJK SPPD
                </button> -->
              </div>

              <?php 
                $param = [
                  'no_peg'    => $Datapeg[0]->no_peg,
                  'kd_unit'   => $Datapeg[0]->kd_unit,
                  'kd_bagian' => $Datapeg[0]->kd_bagian,
                  'kd_jab'    => $Datapeg[0]->kd_jab,
                  'kd_level'  => $Datapeg[0]->kd_level
                ];

                $dataresultapp = $this->sdm_model->apppjksppdpegawai($param);

                if (!empty($dataresultapp)) {
                  foreach ($dataresultapp as $val) {

                    // STATUS & CHECKBOX
                    if ($val['APPROVE_ATASAN'] == '0') {
                      $checkbox = "<input type='checkbox' class='form-check-input checkItem' value='{$val['ID']}'>";
                      $status   = "<span class='badge bg-danger'>Pending</span>";
                    } else {
                      $checkbox = "";
                      $status   = "<span class='badge bg-primary'>Approved</span>";
                    }

                    // AKOMODASI
                    // AKOMODASI
                    switch ($val['AKOMODASI']) {
                      case '1': $akomodasi = 'Hotel'; break;
                      case '2': $akomodasi = 'Luar'; break;
                      case '3': $akomodasi = 'Mess'; break;
                      default:  $akomodasi = '-';
                    }

                    // === KENDARAAN ===
                    switch ($val['KENDARAAN']) {
                      case '1': $kendaraan = 'Dinas'; break;
                      case '2': $kendaraan = 'Kereta Api'; break;
                      case '3': $kendaraan = 'Bus'; break;
                      case '4': $kendaraan = 'Kapal'; break;
                      case '5': $kendaraan = 'Pesawat'; break;
                      case '6': $kendaraan = 'Lain-lain'; break;
                      default:  $kendaraan = '-';
                    }
              ?>

              <div class="col-12 mb-3">
                <div class="card shadow-sm border-0">
                  <div class="card-body bg-light">

                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <div>
                        <?= $checkbox ?>
                        <strong class="ms-1"><?= $val['na_peg'] ?></strong>
                      </div>
                      <?= $status ?>
                    </div>

                    <!-- TANGGAL -->
                    <div class="small text-muted mb-2">
                      <i class="bi bi-calendar-range"></i>
                      <?= $val['AWAL_TUGAS'] ?> s.d <?= $val['AKIR_TUGAS'] ?>
                    </div>

                    <!-- DETAIL -->
                    <ul class="list-unstyled small mb-0">
                      <li><b>Bukti:</b> <?= $val['BUKTI_PJK'] ?></li>
                      <li><b>Tujuan:</b> <?= $val['TUJUAN'] ?></li>
                      <li><b>Dalam Rangka:</b> <?= $val['DALAM_RANGKA'] ?></li>
                      <li><b>Akomodasi:</b> <?= $akomodasi ?></li>
                      <li><b>Kendaraan:</b> <?= $kendaraan ?></li>
                      <li><b>Beban:</b> <?= $val['BEBAN'] ?></li>
                      <li><b>KA Biaya:</b> <?= $val['KA'] ?></li>
                    </ul>

                    <!-- ACTION BUTTON -->
                    <div class="d-flex justify-content-end gap-2">
                      <button class="btn btn-outline-dark btn-sm"
                        onclick="viewpjk('<?= $val['ID'] ?>')">
                        <i class="bi bi-search"></i> View PJK
                      </button>
                    </div>

                  </div>
                </div>
              </div>

              <?php 
                  }
                } else {
                  echo "<div class='text-center text-muted'>Data tidak ditemukan</div>";
                }
              ?>

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
  </div>
</div>

<script type="text/javascript">

 document.addEventListener("DOMContentLoaded", function () {

    // 1. INIT VANILLA DATATABLE
    const dataTable = new DataTable("#dataTable", {
        searchable: true,
        fixedHeight: true,
        perPage: 10
    });

    // 2. LOGIKA SINGLE SELECT (Hanya boleh 1)
    document.addEventListener("change", function (e) {
        // Cek apakah yang diklik adalah checkbox dengan class 'checkItem'
        if (e.target.classList.contains("checkItem")) {
            
            if (e.target.checked) {
                // Cari semua checkbox lain dan uncheck
                document.querySelectorAll(".checkItem").forEach(cb => {
                    if (cb !== e.target) {
                        cb.checked = false;
                    }
                });
            }
        }
    });

    // Catatan: Anda bisa menghapus event listener "checkAll" di HTML/JS 
    // karena fungsinya bertolak belakang dengan sistem single-select.
});

let isAllSelected = false;

function toggleSelectAll() {
  const items = document.querySelectorAll('.checkItem');
  isAllSelected = !isAllSelected;

  items.forEach(item => {
    item.checked = isAllSelected;
  });
}

function approveSelected() {
    let ids = [];

    $('.checkItem:checked').each(function () {
        ids.push($(this).val());
    });

    if (ids.length === 0) {
        Swal.fire('Info', 'Pilih minimal satu data', 'info');
        return;
    }

    Swal.fire({
        title: 'Approve PJK SPPD?',
        text: 'Data terpilih akan disetujui',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Approve'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/approve/approve_multi_pjksppd",
                type: "POST",
                data: { ids: ids },
                dataType: "json",
                success: function (res) {
                    if (res.status) {
                        Swal.fire(
                            'Selesai',
                            `Approved: ${res.approved}, Ditolak: ${res.rejected}`,
                            'success'
                        ).then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Kesalahan server', 'error');
                }
            });
        }
    });
}


function viewpjk(id_pjk) {
    let ids = id_pjk;

   
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
            id_pjk: ids
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
	