
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data Pengajuan SPPD Pegawai</h6>
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
              <div class="d-flex gap-2 mb-3">
                <button class="btn btn-secondary btn-sm" onclick="toggleSelectAll()">
                  <i class="bi bi-check-all"></i> Select All
                </button>

                <button class="btn btn-primary btn-sm" onclick="approveSelected()">
                  <i class="bi bi-check2-square"></i> Approve SPPD
                </button>
              </div>

              <?php 
                $param = [
                  'no_peg'    => $Datapeg[0]->no_peg,
                  'kd_unit'   => $Datapeg[0]->kd_unit,
                  'kd_bagian' => $Datapeg[0]->kd_bagian,
                  'kd_jab'    => $Datapeg[0]->kd_jab,
                  'kd_level'  => $Datapeg[0]->kd_level
                ];

                $dataresultapp = $this->sdm_model->appsppdpegawai($param);

                if (!empty($dataresultapp)) {
                  foreach ($dataresultapp as $val) {

                    // STATUS & CHECKBOX
                    if ($val['APPROVE'] == '0') {
                      $checkbox = "<input type='checkbox' class='form-check-input checkItem' value='{$val['ID']}'>";
                      $status   = "<span class='badge bg-danger'>Pending</span>";
                    } else {
                      $checkbox = "";
                      $status   = "<span class='badge bg-primary'>Approved</span>";
                    }

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
                      <i class="bi bi-calendar-event"></i>
                      <?= $val['TGL_AWAL'] ?> s.d <?= $val['TGL_AKHIR'] ?>
                    </div>

                    <!-- DETAIL -->
                    <ul class="list-unstyled small mb-0">
                      <li><b>Bukti:</b> <?= $val['BUKTI'] ?></li>
                      <li><b>Tujuan:</b> <?= $val['TUJUAN'] ?></li>
                      <li><b>Dalam Rangka:</b> <?= $val['DALAM_RANGKA'] ?></li>
                      <li><b>Akomodasi:</b> <?= $akomodasi ?></li>
                      <li><b>Kendaraan:</b> <?= $kendaraan ?></li>
                    </ul>

                  </div>
                </div>
              </div>

              <?php 
                  }
                } else {
                  echo "<div class='col-12 text-center text-muted'>Data tidak ditemukan</div>";
                }
              ?>

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
        title: 'Approve SPPD?',
        text: 'Data terpilih akan disetujui',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Approve'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/approve/approve_multi_sppd",
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
	

function approvesppd(id_sppd) {
  Swal.fire({
      title: 'Menyetujui data sppd?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, izinkan!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/approve/approve_sppd",
              type: "POST",
              data: { id_sppd: id_sppd },
              dataType: "json",
              success: function(res) {
                  if (res.status === true) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Berhasil',
                          text: 'Data berhasil disetujui',
                          timer: 1500,
                          showConfirmButton: false
                      });
                      // reload DataTable
                      window.location.href='<?php echo base_url(); ?>index.php/approve/app_sppd';
                      // $('#dataTable').DataTable().ajax.reload(null, false);
                  } else {
                      Swal.fire('Gagal', res.message ?? 'Gagal approve data', 'error');
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
	