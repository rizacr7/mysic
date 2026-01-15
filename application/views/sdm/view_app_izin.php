
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>View Approve Pengajuan Izin Pegawai</h6>
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

              $dataresultapp = $this->sdm_model->viewappizinpegawai($param);

              foreach($dataresultapp as $val){

                $batalBtn = "";

                if ($val['flag_app'] == 1 && !empty($val['tgl_app'])) {

                  $tglApprove   = new DateTime(date('Y-m-d', strtotime($val['tgl_app'])));
                  $tglSekarang  = new DateTime(date('Y-m-d'));
                  $selisihHari  = $tglApprove->diff($tglSekarang)->days;

                  if ($tglSekarang >= $tglApprove && $selisihHari <= 3) {
                    $batalBtn = "
                      <button type='button'
                        class='btn btn-sm btn-danger mt-2 w-100'
                        onclick=\"batalizin('{$val['id_izin']}')\">
                        <i class='bi bi-x-circle'></i> Batal Approve
                      </button>
                    ";
                  }
                }

                $kdizin = ($val['kdizin'] == 1) ? "Terlambat" : "Pulang Cepat";

                if($val['flag_app'] == 1){
                  $status = "<span class='badge bg-primary'>Approved</span>";
                  $checkbox = "";
                } else {
                  $status = "<span class='badge bg-warning text-dark'>Pending</span>";
                  $checkbox = "<input type='checkbox' class='form-check-input checkItem' value='{$val['id_izin']}'>";
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
                      <i class="bi bi-calendar-event"></i> <?= $val['tgl_izin'] ?>
                    </div>

                    <!-- DETAIL -->
                    <ul class="list-unstyled small mb-0">
                      <li><b>Jenis Izin:</b> <?= $kdizin ?></li>
                      <li><b>Keterangan:</b> <?= $val['keterangan'] ?></li>
                    </ul>
                    <?= $batalBtn ?>

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
	

function batalizin(id_izin) {
  Swal.fire({
      title: 'Membatalkan data izin?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, batalkan!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/sdm/bataldtizin",
              type: "POST",
              data: { id_izin: id_izin },
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
                      window.location.href='<?php echo base_url(); ?>index.php/sdm/view_app_izin';
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
	