
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data Pengajuan Pegawai baru</h6>
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
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="checkAll">
                <label class="form-check-label fw-semibold" for="checkAll">
                  Pilih Semua Pengajuan
                </label>
              </div>

              <!-- BUTTON MULTI APPROVE -->
              <button class="btn btn-primary btn-sm mb-2" onclick="approveSelected()">
                <i class="bi bi-check2-square"></i> Approve Pengajuan Terpilih
              </button>

               <?php 
                    $param = [
                      'no_peg'    => $Datapeg[0]->no_peg,
                      'kd_unit'   => $Datapeg[0]->kd_unit,
                      'kd_bagian' => $Datapeg[0]->kd_bagian,
                      'kd_jab'    => $Datapeg[0]->kd_jab,
                      'kd_level'  => $Datapeg[0]->kd_level
                    ];
                    $dataresultapp = $this->sdm_model->app_request_unit($param);

                    if (!empty($dataresultapp)) {
                      foreach ($dataresultapp as $val) {
                      $checkbox = "<input type='checkbox' class='form-check-input checkItem' value='{$val['id_req']}'>";
                  ?>

                  <div class="col-12 mb-3">
                    <div class="card shadow-sm border-0">
                      <div class="card-body bg-light">

                        <!-- HEADER -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                          <div>
                            <?= $checkbox ?>
                            <strong class="ms-1"><?= $val['job_desc'] ?> - <?= $val['no_bukti'] ?></strong>
                          </div>
                        </div>

                        <!-- TANGGAL -->
                        <div class="small text-muted mb-2">
                          <i class="bi bi-calendar-range"></i>
                          <?= $this->func_global->dsql_tgl($val['tanggal']) ?>
                        </div>
                        <!-- DETAIL -->
                        <ul class="list-unstyled small mb-0">
                          <li><b>Unit:</b> <?= $val['nm_unit'] ?></li>
                          <li><b>JobDesc:</b> <?= $val['job_desc'] ?></li>
                          <li><b>Jumlah:</b> <?= $val['jumlah'] ?></li>
                          <li><b>Jenis Kelamin:</b> <?= $val['jns_kel'] ?></li>
                          <li><b>Pendidikan:</b> <?= $val['pendidikan'] ?> <?= $val['jurusan'] ?></li>
                          <li><b>Kompetansi:</b> <?= $val['kompetensi_khusus'] ?></li>
                          <li><b>Gambaran Pekerjaan:</b> <?= $val['gambaran_pekerjaan'] ?></li>
                        </ul>

                      </div>
                    </div>
                  </div>

                  <?php }
                  } else {
                    echo "<p>Tidak ada data pengajuan pegawai baru.</p>";
                  }
                  ?>
              
            </div>
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
        title: 'Approve Pengajuan Pegawai?',
        text: 'Data terpilih akan disetujui',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Approve'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/approve/approve_multi_request",
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
	