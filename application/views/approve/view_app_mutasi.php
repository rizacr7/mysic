
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>View Approve Pengajuan Mutasi & Promosi Pegawai</h6>
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
              
              <!-- <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="checkAll">
                <label class="form-check-label fw-semibold" for="checkAll">
                  Pilih Semua Pengajuan
                </label>
              </div> -->

              <!-- BUTTON MULTI APPROVE -->
              <!-- <button class="btn btn-primary btn-sm mb-2" onclick="approveSelected()">
                <i class="bi bi-check2-square"></i> Approve Pengajuan Mutasi & Promosi Terpilih
              </button> -->

               <?php 
                $param = [
                  'no_peg'    => $Datapeg[0]->no_peg,
                  'kd_unit'   => $Datapeg[0]->kd_unit,
                  'kd_bagian' => $Datapeg[0]->kd_bagian,
                  'kd_jab'    => $Datapeg[0]->kd_jab,
                  'kd_level'  => $Datapeg[0]->kd_level
                ];

               
                $dataresultapp = $this->sdm_model->viewappmutasipegawai($param);

                if (!empty($dataresultapp) && is_array($dataresultapp)) {
                  foreach ($dataresultapp as $val) { 

                  if($val['jns_perubahan'] == 1){
                    $jnsperubahan = "<span class='badge bg-info'>Mutasi</span>";
                    $perubahan = "<span class='label label-default' style='font-size: 85%;'>".$val['nm_unit_asal']." ke ".$val['nm_unit_tujuan']."</span>";
                  
                  }
                  else if($val['jns_perubahan'] == 6){
                    $jnsperubahan = "<span class='badge bg-primary'>Mutasi & Promosi</span>";
                    $perubahan = "<span class='label label-warning' style='font-size: 85%;'>".$val['nm_unit_asal']." ke ".$val['nm_unit_tujuan']."</span> | <span class='label label-default' style='font-size: 85%;'>".$val['nm_jab']." ke ".$val['nm_jab_baru']."</span>";
                  }
                  else if($val['jns_perubahan'] == 2){
                    $jnsperubahan = "<span class='badge bg-success'>Promosi</span>";
                    $perubahan = "<span class='label label-default' style='font-size: 85%;'>".$val['nm_jab']." ke ".$val['nm_jab_baru']."</span>";
                    
                  }
                  else if($val['jns_perubahan'] == 3){
                    $jnsperubahan = "<span class='badge bg-danger'>Demosi</span>";
                    $perubahan = "<span class='label label-default' style='font-size: 85%;'>".$val['nm_jab']." KE ".$val['nm_jab_baru']."</span>";
                  }
                  else if($val['jns_perubahan'] == 4){
                    $jnsperubahan = "Penugasan";
                    $perubahan = "";
                  }
                  else if($val['jns_perubahan'] == 5){
                    $jnsperubahan = "Status Karyawan";
                    $perubahan = "";
                  }
                
                ?>
                    <!-- Element Heading -->
                    <div class="container">
                      <div class="element-heading mt-3">
                        <h6> 
                        <!-- <input type="checkbox" class="form-check-input checkItem" value="<?php echo $val['id_mutasi']; ?>">  -->
                        <?php echo $val['no_peg']?> - <?php echo $val['na_peg']?></h6>
                      </div>
                    </div>

                    <div class="container">

                      <div class="card shadow-sm" style="background-color:#f7f7f7;">
                         
                        <div class="card-body">
                          
                          <h5 class="mb-2 fw-semibold"><?php echo $jnsperubahan ?></h5>
                          <h6 class="mb-4 text-muted"><?php echo $perubahan ?></h6>

                          <table class="table table-sm table-borderless mb-0">
                            <tbody>
                              <tr>
                                <td class="text-muted" style="width:40%">Tgl. App MR</td>
                                <td class="fw-medium">: <?php echo $this->func_global->dsql_tgl($val['tgl_app_unit']) ?></td>
                              </tr>
                              <tr>
                                <td class="text-muted">Tgl. App Kadiv</td>
                                <td class="fw-medium">: <?php echo $this->func_global->dsql_tgl($val['tgl_app_kadiv']) ?></td>
                              </tr>
                              <tr>
                                <td class="text-muted">Tgl. App Ketua Pengurus</td>
                                <td class="fw-medium">: <?php echo $this->func_global->dsql_tgl($val['tgl_app_pengurus']) ?></td>
                              </tr>
                              <tr>
                                <td class="text-muted">Tgl. App Bendahara Pengurus</td>
                                <td class="fw-medium">: <?php echo $this->func_global->dsql_tgl($val['tgl_app_bendahara']) ?></td>
                              </tr>
                              <tr>
                                <td class="text-muted">Tgl. App Sekretaris Pengurus</td>
                                <td class="fw-medium">: <?php echo $this->func_global->dsql_tgl($val['tgl_app_sekretaris']) ?></td>
                              </tr>
                            </tbody>
                          </table>

                        </div>
                      </div>
                    </div>

                  <?php }
                  } else {
                    echo "<p>Tidak ada data pengajuan mutasi & promosi pegawai.</p>";
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
        title: 'Approve Mutasi & Promosi Pegawai?',
        text: 'Data terpilih akan disetujui',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Approve'
    }).then((result) => {
        if (result.isConfirmed) {
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
	