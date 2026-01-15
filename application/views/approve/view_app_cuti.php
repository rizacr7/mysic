
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>View Approve Cuti Pegawai</h6>
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
            <?php 
              $no_peg = $Datapeg[0]->no_peg;
              $param = [
                'no_peg'     => $no_peg,
                'kd_unit'    => $Datapeg[0]->kd_unit,
                'kd_bagian'  => $Datapeg[0]->kd_bagian,
                'kd_jab'     => $Datapeg[0]->kd_jab,
                'kd_level'   => $Datapeg[0]->kd_level
              ];

              $dataresultapp = $this->sdm_model->viewappcutipegawai($param);

              foreach($dataresultapp as $val){

                $tgl_input = substr($val['tgl_approve'],1,10);
                $tgl_input_date = $tgl_input ? new DateTime($tgl_input) : null;
                $tgl_sekarang   = new DateTime(date('Y-m-d'));

                if ($tgl_input_date && $tgl_sekarang <= $tgl_input_date->modify('+3 days')) {
                  $btnaction = "<button class='btn btn-sm btn-warning'
                    onclick=\"batalappcuti('{$val['id']}')\">
                    <i class='bi bi-arrow-clockwise'></i> Batalkan
                  </button>";
                } else {
                  $btnaction = "";
                }

                $label_app = ($val['status_approve'] == '1')
                  ? "<span class='badge bg-primary'>Approved</span>"
                  : "<span class='badge bg-danger'>Pending</span>";
            ?>
              <div class="col-12 mb-3">
                <div class="card shadow-sm border-0">
                  <div class="card-body bg-light">

                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <div>
                        <strong><?= $val['na_peg'] ?></strong><br>
                        <small class="text-muted">Bukti: <?= $val['no_bukti'] ?></small>
                      </div>
                      <?= $label_app ?>
                    </div>

                    <!-- TANGGAL CUTI -->
                    <div class="small text-muted mb-2">
                      <?= $val['tgl_awal'] ?> s.d <?= $val['tgl_akhir'] ?>
                    </div>

                    <!-- DETAIL -->
                    <ul class="list-unstyled small mb-3">
                      <li><b>Lama Cuti:</b> <?= $val['lama'] ?> hari</li>
                      <li><b>Keterangan:</b> <?= $val['keterangan'] ?></li>
                    </ul>

                    <!-- ACTION -->
                    <?php if($btnaction != ""){ ?>
                    <div class="text-end">
                      <?= $btnaction ?>
                    </div>
                    <?php } ?>

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
	

function batalappcuti(id_cuti) {
  Swal.fire({
      title: 'Membatalkan data cuti?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, batalkan!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/approve/bataldtcuti",
              type: "POST",
              data: { id_cuti: id_cuti },
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
                      window.location.href='<?php echo base_url(); ?>index.php/approve/view_app_cuti';
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
	