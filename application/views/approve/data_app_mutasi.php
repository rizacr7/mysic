
<div class="page-content-wrapper py-4">
  <div class="container" style="max-width: 720px;">
    
    <!-- Top Action & Navigation Bar -->
    <div class="d-flex align-items-center justify-content-between mb-3">
      <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-pill" onclick="kembali()" id="btnsimpan">
        <i class="bi bi-arrow-left me-1"></i> Kembali
      </button>
      <div id="loading" class="spinner-border spinner-border-sm text-primary" role="status" style="display:none;">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- Main Container Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
      <div class="card-body p-4">
        
        <!-- Header Title -->
        <div class="mb-4">
          <h5 class="fw-bold text-dark mb-1">Pengajuan Mutasi & Promosi</h5>
          <p class="text-muted small mb-0">Kelola dan verifikasi persetujuan pengajuan pegawai</p>
        </div>

        <!-- Bulk Action Bar -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-3 bg-light rounded-3 mb-4">
          <div class="form-check mb-0">
            <input class="form-check-input" type="checkbox" id="checkAll">
            <label class="form-check-label fw-semibold text-secondary small user-select-none" for="checkAll">
              Pilih Semua Pengajuan
            </label>
          </div>
          <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center gap-1" onclick="approveSelected()">
            <i class="bi bi-check2-circle"></i>
            <span>Approve Terpilih</span>
          </button>
        </div>

        <!-- List Pengajuan -->
        <div class="d-flex flex-column gap-3">
          <?php 
          $param = [
            'no_peg'    => $Datapeg[0]->no_peg,
            'kd_unit'   => $Datapeg[0]->kd_unit,
            'kd_bagian' => $Datapeg[0]->kd_bagian,
            'kd_jab'    => $Datapeg[0]->kd_jab,
            'kd_level'  => $Datapeg[0]->kd_level
          ];

          $dataresultapp = $this->sdm_model->appmutasipegawai($param);

          if (!empty($dataresultapp) && is_array($dataresultapp)) {
            foreach ($dataresultapp as $val) { 

              if($val['jns_perubahan'] == 1){
                $jnsperubahan = "<span class='badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1 rounded-pill'><font color='white'>Mutasi</font></span>";
                $perubahan = "<div class='text-secondary small d-flex align-items-center gap-1'><i class='bi bi-building'></i> ".$val['nm_unit_asal']." <i class='bi bi-arrow-right text-muted'></i> ".$val['nm_unit_tujuan']."</div>";
              }
              else if($val['jns_perubahan'] == 6){
                $jnsperubahan = "<span class='badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 rounded-pill'><font color='white'>Mutasi & Promosi</font></span>";
                $perubahan = "<div class='text-secondary small d-flex flex-wrap align-items-center gap-2'><span><i class='bi bi-building'></i> ".$val['nm_unit_asal']." <i class='bi bi-arrow-right text-muted'></i> ".$val['nm_unit_tujuan']."</span><span class='text-muted'>•</span><span><i class='bi bi-graph-up-arrow'></i> ".$val['jobgrade_awal']." <i class='bi bi-arrow-right text-muted'></i> ".$val['nm_jobgrade_baru']."</span></div>";
              }
              else if($val['jns_perubahan'] == 2){
                $jnsperubahan = "<span class='badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill'><font color='white'>Promosi</font></span>";
                $perubahan = "<div class='text-secondary small d-flex align-items-center gap-1'><i class='bi bi-graph-up-arrow'></i> ".$val['jobgrade_awal']." <i class='bi bi-arrow-right text-muted'></i> ".$val['nm_jobgrade_baru']."</div>";
              }
              else if($val['jns_perubahan'] == 3){
                $jnsperubahan = "<span class='badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill'><font color='white'>Demosi</font></span>";
                $perubahan = "<div class='text-secondary small d-flex align-items-center gap-1'><i class='bi bi-graph-down-arrow'></i> ".$val['jobgrade_awal']." <i class='bi bi-arrow-right text-muted'></i> ".$val['nm_jobgrade_baru']."</div>";
              }
              else if($val['jns_perubahan'] == 4){
                $jnsperubahan = "<span class='badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1 rounded-pill'><font color='white'>Penugasan</font></span>";
                $perubahan = "";
              }
              else if($val['jns_perubahan'] == 5){
                $jnsperubahan = "<span class='badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1 rounded-pill'><font color='white'>Status Karyawan</font></span>";
                $perubahan = "";
              }
          ?>

            <!-- Item Card -->
            <div class="card border rounded-4 shadow-none hover-shadow transition">
              <div class="card-body p-3 p-md-4">
                
                <!-- Item Header (Checkbox, Pegawai & Badge Status) -->
                <div class="d-flex align-items-start justify-content-between gap-2 mb-3 pb-3 border-bottom">
                  <div class="d-flex align-items-center gap-3">
                    <input type="checkbox" class="form-check-input checkItem mt-0" style="width: 1.25rem; height: 1.25rem;" value="<?php echo $val['id_mutasi']; ?>">
                    <div>
                      <h6 class="fw-bold mb-0 text-dark"><?php echo $val['na_peg']?></h6>
                      <small class="text-muted"><i class="bi bi-person-badge me-1"></i><?php echo $val['no_peg']?></small>
                    </div>
                  </div>
                  <div>
                    <?php echo $jnsperubahan ?>
                  </div>
                </div>

                <!-- Info Perubahan Unit/Grade -->
                <?php if(!empty($perubahan)): ?>
                  <div class="p-2 px-3 bg-light rounded-3 mb-3">
                    <?php echo $perubahan ?>
                  </div>
                <?php endif; ?>

                <!-- Log Approval Details -->
                <div class="row g-2 pt-1 text-muted" style="font-size: 0.85rem;">
                  <div class="col-sm-6 d-flex justify-content-between border-bottom border-light pb-1">
                    <span>Tgl. App MR Asal</span>
                    <span class="fw-medium text-dark"><?php echo $this->func_global->dsql_tgl($val['tgl_app_unit']) ?: '-' ?></span>
                  </div>
                  <div class="col-sm-6 d-flex justify-content-between border-bottom border-light pb-1">
                    <span>Tgl. App MR Tujuan</span>
                    <span class="fw-medium text-dark"><?php echo $this->func_global->dsql_tgl($val['tgl_app_tujuan']) ?: '-' ?></span>
                  </div>
                  <div class="col-sm-6 d-flex justify-content-between border-bottom border-light pb-1">
                    <span>Tgl. App Kadiv</span>
                    <span class="fw-medium text-dark"><?php echo $this->func_global->dsql_tgl($val['tgl_app_kadiv']) ?: '-' ?></span>
                  </div>
                  <div class="col-sm-6 d-flex justify-content-between border-bottom border-light pb-1">
                    <span>Tgl. App Ketua Pengurus</span>
                    <span class="fw-medium text-dark"><?php echo $this->func_global->dsql_tgl($val['tgl_app_pengurus']) ?: '-' ?></span>
                  </div>
                  <div class="col-sm-6 d-flex justify-content-between border-bottom border-light pb-1">
                    <span>Tgl. App Bendahara</span>
                    <span class="fw-medium text-dark"><?php echo $this->func_global->dsql_tgl($val['tgl_app_bendahara']) ?: '-' ?></span>
                  </div>
                  <div class="col-sm-6 d-flex justify-content-between border-bottom border-light pb-1">
                    <span>Tgl. App Sekretaris</span>
                    <span class="fw-medium text-dark"><?php echo $this->func_global->dsql_tgl($val['tgl_app_sekretaris']) ?: '-' ?></span>
                  </div>
                </div>

              </div>
            </div>

          <?php 
            }
          } else { 
          ?>
            <!-- Empty State -->
            <div class="text-center py-5">
              <div class="display-6 text-muted mb-3"><i class="bi bi-folder2-open"></i></div>
              <h6 class="fw-semibold text-secondary">Tidak Ada Pengajuan</h6>
              <p class="text-muted small mb-0">Saat ini belum ada data pengajuan mutasi & promosi pegawai.</p>
            </div>
          <?php } ?>
        </div>

      </div>
    </div>

  </div>
</div>

<script type="text/javascript">

document.getElementById('checkAll').addEventListener('change', function () {
    const isChecked = this.checked;
    document.querySelectorAll('.checkItem').forEach(cb => {
      cb.checked = isChecked;
    });
  });

  // AUTO UPDATE CHECK ALL JIKA ADA YANG DICENTANG MANUAL
  document.querySelectorAll('.checkItem').forEach(cb => {
    cb.addEventListener('change', function () {
      const total = document.querySelectorAll('.checkItem').length;
      const checked = document.querySelectorAll('.checkItem:checked').length;
      document.getElementById('checkAll').checked = (total === checked);
    });
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
        title: 'Approve Mutasi & Promosi Pegawai?',
        text: 'Data terpilih akan disetujui',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Approve'
    }).then((result) => {
        if (result.isConfirmed) {
           // ✅ Tampilkan Loading
            Swal.fire({
                title: "Sedang Proses...",
                text: "Mohon tunggu sebentar",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/approve/approve_multi_mutasi",
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

function kembali(){
   window.location.href='<?php echo base_url(); ?>index.php/sdm/pageapprove';
}
</script>
	