
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data Pengajuan Kendaraan Pool</h6>
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

               <?php 
                $param = [
                  'no_peg'    => $Datapeg[0]->no_peg,
                  'kd_unit'   => $Datapeg[0]->kd_unit,
                  'kd_bagian' => $Datapeg[0]->kd_bagian,
                  'kd_jab'    => $Datapeg[0]->kd_jab,
                  'kd_level'  => $Datapeg[0]->kd_level
                ];

               
                $dataresultapp = $this->umum_model->viewkendaraan($param);

                if (!empty($dataresultapp) && is_array($dataresultapp)) {
                  foreach ($dataresultapp as $val) { 
                  
                  $id_od = $val['id_od'];

                  if($val['approve_mo'] == 0){
                    $btnaction = " <button type='button' class='btn btn-danger w-100' onclick='hapusod($id_od)'><i class='bi bi-trash'></i> Delete</button>";
                  }
                  else{
                    $btnaction = "";
                  }
                ?>
                    <!-- Element Heading -->
                    <p></p>
                    <div class="container">
                      <div class="card shadow-sm" style="background-color:#f7f7f7;">
                        <div class="card-body">

                          <h6 class="fw-semibold mb-3">
                            <i class="bi bi-car-front"></i> <?php echo $val['no_peg'] ?> - <?php echo $val['na_peg'] ?>
                          </h6>

                          <div class="row small">

                            <div class="col-12 col-md-6 mb-2">
                              <span class="text-muted">Tujuan</span><br>
                              <span class="fw-medium"><?php echo $val['tujuan'] ?></span>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                              <span class="text-muted">Lokasi Jemput</span><br>
                              <span class="fw-medium"><?php echo $val['tmpt_berangkat'] ?></span>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                              <span class="text-muted">Jumlah Penumpang</span><br>
                              <span class="fw-medium"><?php echo $val['jmlh_penumpang'] ?></span>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                              <span class="text-muted">Keperluan</span><br>
                              <span class="fw-medium"><?php echo $val['keperluan'] ?></span>
                            </div>

                            <div class="col-6 mb-2">
                              <span class="text-muted">Tgl. Berangkat</span><br>
                              <span class="fw-medium">
                                <?php echo $this->func_global->dsql_tgl($val['tgl_berangkat']) ?>
                              </span>
                            </div>

                            <div class="col-6 mb-2">
                              <span class="text-muted">Jam Berangkat</span><br>
                              <span class="fw-medium"><?php echo $val['jam_berangkat'] ?></span>
                            </div>

                            <div class="col-6 mb-2">
                              <span class="text-muted">Tgl. Kedatangan</span><br>
                              <span class="fw-medium">
                                <?php echo $this->func_global->dsql_tgl($val['tgl_tiba']) ?>
                              </span>
                            </div>

                            <div class="col-6 mb-2">
                              <span class="text-muted">Jam Kedatangan</span><br>
                              <span class="fw-medium"><?php echo $val['jam_tiba'] ?></span>
                            </div>

                            <div class="col-12 mb-2">
                              <span class="text-muted">Keterangan</span><br>
                              <span class="fw-medium"><?php echo $val['keterangan'] ?></span>
                            </div>

                            <div class="col-6 mb-2">
                              <span class="text-muted">Nopol</span><br>
                              <span class="fw-medium"><?php echo $val['nopol'] ?></span>
                            </div>

                            <div class="col-6 mb-2">
                              <span class="text-muted">Driver</span><br>
                              <span class="fw-medium"><?php echo $val['driver'] ?></span>
                            </div>

                            <div class="col-12 mb-2">
                              <span class="text-muted">Action</span><br>
                              <span class="fw-medium"><?php echo $btnaction?></span>
                            </div>

                          </div>

                        </div>
                      </div>
                    </div>


                  <?php }
                  } else {
                    echo "<p>Tidak ada data pengajuan.</p>";
                  }
                  ?>
              
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">


function hapusod(id_od) {
  Swal.fire({
      title: 'Hapus data pengajuan kendaraan?',
      text: 'Data yang dihapus tidak bisa dikembalikan!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/umum/hapusdtodkendaraan",
              type: "POST",
              data: { id_od: id_od },
              dataType: "json",
              success: function(res) {
                  if (res.status === true) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Berhasil',
                          text: 'Data berhasil dihapus',
                          timer: 1500,
                          showConfirmButton: false
                      });
                      // reload DataTable
                      window.location.href='<?php echo base_url(); ?>index.php/umum/datarequest_kendaraan';
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
   window.location.href='<?php echo base_url(); ?>index.php/sdm/pagemenu';
}
</script>
	