
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data Pengajuan Izin Pegawai</h6>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-body">

        <!-- BACK -->
        <button type="button" class="btn btn-danger w-100 mb-3"
                onclick="kembali()" id="btnsimpan">
          <i class="bi bi-arrow-left-circle"></i> Back
        </button>

        <!-- LOADING -->
        <div class="text-center mb-3">
          <div class="spinner-border text-primary"
              role="status"
              style="display:none"
              id="loading">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>

        <div class="d-flex gap-2 mb-3">
          <button type="button"
                  class="btn btn-outline-secondary btn-sm w-50"
                  onclick="selectAllMobile(true)">
            <i class="bi bi-check-square"></i> Select All
          </button>

          <button type="button"
                  class="btn btn-outline-secondary btn-sm w-50"
                  onclick="selectAllMobile(false)">
            <i class="bi bi-square"></i> Unselect All
          </button>
        </div>

        <!-- APPROVE BUTTON -->
        <button class="btn btn-primary btn-sm mb-3 w-100"
                onclick="approveSelected()">
          <i class="bi bi-check2-square"></i> Approve Izin Pegawai
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

          $dataresultapp = $this->sdm_model->appizinpegawai($param);

          foreach($dataresultapp as $val){

            $kdizin = ($val['kdizin'] == 1)
              ? "Terlambat"
              : "Pulang Cepat";

            if($val['flag_app'] == 1){
              $status = "<span class='badge bg-primary'>Approved</span>";
              $checkbox = "";
            } else {
              $status = "<span class='badge bg-warning text-dark'>Pending</span>";
              $checkbox = "<input type='checkbox'
                              class='form-check-input checkItem me-2'
                              value='{$val['id_izin']}'>";
            }
        ?>
          <div class="col-12 mb-3">
            <div class="card shadow-sm border-0">
              <div class="card-body bg-light">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <?= $checkbox ?>
                    <strong><?= $val['na_peg'] ?></strong>
                  </div>
                  <?= $status ?>
                </div>

                <!-- DATE -->
                <div class="small text-muted mb-2">
                  <i class="bi bi-calendar-event"></i>
                  <?= $val['tgl_izin'] ?>
                </div>

                <!-- DETAIL -->
                <ul class="list-unstyled small mb-0">
                  <li><b>Jenis Izin:</b> <?= $kdizin ?></li>
                  <li><b>Keterangan:</b> <?= $val['keterangan'] ?></li>
                </ul>

              </div>
            </div>
          </div>
        <?php } ?>
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

function selectAllMobile(status) {
  document.querySelectorAll('.checkItem').forEach(function(cb) {
    cb.checked = status;
  });
}

function approveSelected() {
    let ids = [];

    document.querySelectorAll('.checkItem:checked').forEach(function(cb) {
      ids.push(cb.value);
    });

    if (ids.length === 0) {
        Swal.fire('Info', 'Pilih minimal satu data', 'info');
        return;
    }

    Swal.fire({
        title: 'Approve Izin Pegawai?',
        text: 'Data terpilih akan disetujui',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Approve'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/approve/approve_multi_izin",
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
	

function appizin(id_izin) {
  Swal.fire({
      title: 'Menyetujui data izin?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, izinkan!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/sdm/appdtizin",
              type: "POST",
              data: { id_izin: id_izin },
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
                      window.location.href='<?php echo base_url(); ?>index.php/sdm/app_izin';
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
	