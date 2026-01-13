
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

          <div class="spinner-border text-primary" role="status" style="display:none" align="center" id="loading">
            <span class="visually-hidden">Loading...</span>
          </div>

          <div class="row">
            <div class="col-12">
              <p></p>
              <!-- BUTTON MULTI APPROVE -->
              <button class="btn btn-primary btn-sm mb-2" onclick="approveSelected()">
                <i class="bi bi-check2-square"></i> Approve PJK SPPD
              </button>

              <button class="btn btn-dark btn-sm mb-2" onclick="viewpjk()">
                <i class="bi bi-search"></i> View PJK SPPD
              </button>

              <div class="table-responsive">
                <table id="dataTable" class="display nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>
                        <input type="checkbox" id="checkAll">
                      </th>
                      <th  style="display:none">Bukti</th>
                      <th>Nama</th>
                      <th>Tgl. SPPD</th>
                      <th>Tujuan</th>
                      <th>Dalam Rangka</th>
                      <th>Akomodasi</th>
                      <th>Kendaraan</th>
                      <th>Beban</th>
                      <th>Status</th>
                    </tr>
                  </thead>

                  <tbody>
                  <?php 
                    $param = [
                      'no_peg'    => $Datapeg[0]->no_peg,
                      'kd_unit'   => $Datapeg[0]->kd_unit,
                      'kd_bagian' => $Datapeg[0]->kd_bagian,
                      'kd_jab'    => $Datapeg[0]->kd_jab,
                      'kd_level'  => $Datapeg[0]->kd_level
                    ];

                    $dataresultapp = $this->sdm_model->apppjksppdpegawai($param);

                    if (!empty($dataresultapp) && is_array($dataresultapp)) {
                      foreach ($dataresultapp as $val) {

                        // === ACTION CHECKBOX ===
                        if ($val['APPROVE_ATASAN'] == '0') {
                          $action = "<input type='checkbox' class='checkItem' value='{$val['ID']}'>";
                          $status = "<span class='badge rounded-pill bg-danger'>Pending</span>";
                        } else {
                          $action = "";
                          $status = "<span class='badge rounded-pill bg-primary'>Approved</span>";
                        }

                        // === AKOMODASI ===
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

                        echo "
                        <tr>
                          <td>{$action}</td>
                          <td style='display:none'>{$val['BUKTI_PJK']}</td>
                          <td>{$val['na_peg']}</td>
                          <td>{$val['AWAL_TUGAS']} s.d {$val['AKIR_TUGAS']}</td>
                          <td>{$val['TUJUAN']}</td>
                          <td>{$val['DALAM_RANGKA']}</td>
                          <td>{$akomodasi}</td>
                          <td>{$kendaraan}</td>
                          <td>{$val['BEBAN']}</td>
                          <td>{$status}</td>
                        </tr>";
                      }
                    } else {
                      echo "<tr><td colspan='9' class='text-center'>Data tidak ditemukan</td></tr>";
                    }
                  ?>
                  </tbody>
                </table>
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


function viewpjk() {
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
	