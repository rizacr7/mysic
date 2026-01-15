
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>View Approve SPPD Pegawai</h6>
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
            $param = array(
              'no_peg'     => $no_peg,
              'kd_unit'    => $Datapeg[0]->kd_unit,
              'kd_bagian'  => $Datapeg[0]->kd_bagian,
              'kd_jab'     => $Datapeg[0]->kd_jab,
              'kd_level'   => $Datapeg[0]->kd_level
            );

            $dataresultapp = $this->sdm_model->viewappsppdpegawai($param);

            foreach($dataresultapp as $val){

              $tgl_input = substr($val['TGL_APP'],1,10);
              $tgl_input_date = new DateTime($tgl_input);
              $tgl_sekarang   = new DateTime(date('Y-m-d'));

              if ($tgl_sekarang <= $tgl_input_date->modify('+7 days') && $val['STS_PJK'] == '0') {
                $btnaction = "<button class='btn btn-sm btn-warning'
                  onclick=\"batalappsppd('{$val['ID']}')\">
                  <i class='bi bi-arrow-clockwise'></i> Batalkan
                </button>";
              } else {
                $btnaction = "";
              }

              $label_app = ($val['APPROVE'] == '1')
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
              <div class="card shadow-sm">
                <div class="card-body  bg-light">

                  <div class="d-flex justify-content-between mb-2">
                    <strong><?= $val['na_peg'] ?></strong>
                    <?= $label_app ?>
                  </div>

                  <div class="small text-muted mb-2">
                    <?= $val['TGL_AWAL'] ?> s.d <?= $val['TGL_AKHIR'] ?>
                  </div>

                  <ul class="list-unstyled mb-3">
                    <li><b>Tujuan:</b> <?= $val['TUJUAN'] ?></li>
                    <li><b>Dalam Rangka:</b> <?= $val['DALAM_RANGKA'] ?></li>
                    <li><b>Akomodasi:</b> <?= $akomodasi ?></li>
                    <li><b>Kendaraan:</b> <?= $kendaraan ?></li>
                  </ul>

                  <div class="d-flex justify-content-between align-items-center">
                    <div><?= $btnaction ?></div>
                    <small class="text-muted">Bukti: <?= $val['BUKTI'] ?></small>
                  </div>

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
	

function batalappsppd(id_sppd) {
  Swal.fire({
      title: 'Membatalkan data sppd?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, batalkan!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/approve/bataldtsppd",
              type: "POST",
              data: { id_sppd: id_sppd },
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
                      window.location.href='<?php echo base_url(); ?>index.php/approve/view_app_sppd';
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
	