<style>
.list-group-item {
    text-align: left;
    font-size: 14px;
}
</style>
<div class="page-content-wrapper py-3">
    <div class="team-member-wrapper direction-rtl">
      <div class="container">
        <div class="row g-3">

          <!-- Single Team Member -->
          <div class="col-12">
            <div class="card team-member-card shadow">
              <div class="card-body">
                <!-- Member Image-->
                <?php
                $image_url ="https://hrkita.sic.co.id/foto_pegawai/" . $Datapeg[0]->foto_pegawai;
                if($Datapeg[0]->foto_pegawai == "") {
                    if($Datapeg[0]->sex == "P"){
                      $image_url = base_url() . "assets/img/bg-img/user2.png"; 
                    }
                    else{
                      $image_url = base_url() . "assets/img/bg-img/user1.png"; 
                    }
                }
                ?>
                <div class="team-member-img shadow-sm">
                  <img src="<?php echo $image_url; ?>" alt="">
                </div>
                <!-- Team Info-->
                <div class="team-info">
                  <h6 class="mb-1 fz-14"><?php echo $Datapeg[0]->na_peg?></h6>
                  <h7 class="mb-1 fz-14"><?php echo $Datapeg[0]->no_peg?></h7>
                  <p class="mb-0 fz-12"><?php echo $Datapeg[0]->nm_jab?></p>
                  <p class="mb-0 fz-12"><?php echo $Datapeg[0]->kd_golongan?></p>
                </div>
              </div>
              <!-- Contact Info-->
              <div class="contact-info bg-primary">
                <p class="mb-0 text-truncate"><?php echo $Datapeg[0]->nm_unit?></p>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="card team-member-card shadow">
                <div class="card-body">
                    <ul class="list-group list-group-flush text-left">
                        <li class="list-group-item">
                            <strong>Alamat</strong> : <?= $Datapeg[0]->alamat ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Tempat Lahir</strong> : <?= $Datapeg[0]->tmpt_lahir ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Tgl. Lahir</strong> :
                            <?= $this->func_global->dsql_tgl($Datapeg[0]->tgl_lahir) ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Umur</strong> : <?= $Datapeg[0]->umur ?>
                        </li>
                        <li class="list-group-item">
                            <strong>NIK</strong> : <?= $Datapeg[0]->no_ktp ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Agama</strong> : <?= $Datapeg[0]->agama ?>
                        </li>
                        <li class="list-group-item">
                            <strong>No.Hp</strong> : <?= $Datapeg[0]->no_hp ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Email</strong> : <?= $Datapeg[0]->email ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Pendidikan</strong> : <?= $Datapeg[0]->pendidikan ?> <?= $Datapeg[0]->ket_pendidikan ?>
                        </li>
                    </ul>
                </div>
            </div>
          </div>

          <div class="col-12">
            <div class="container">
              <div class="card">
                <div class="card-body">
                  <div class="accordion accordion-flush accordion-style-one" id="accordionStyle1">
                    <!-- Single Accordion -->
                    <div class="accordion-item">
                      <div class="accordion-header" id="accordionOne">
                        <h6 data-bs-toggle="collapse" data-bs-target="#accordionStyleOne" aria-expanded="true"
                          aria-controls="accordionStyleOne">Riwayat Mutasi / Pekerjaan SIC<i class="ti ti-chevron-down"></i></h6>
                      </div>
                      <div class="accordion-collapse collapse show" id="accordionStyleOne" aria-labelledby="accordionOne"
                        data-bs-parent="#accordionStyle1">
                        <div class="accordion-body">
                              <div class="table-responsive">
                                <div class="box-body" id="div_tabel_data_mapping"></div>
                              </div>
                        </div>
                      </div>
                    </div>

                    <!-- Single Accordion -->
                    <div class="accordion-item">
                      <div class="accordion-header" id="accordionTwo">
                        <h6 class="collapsed" data-bs-toggle="collapse" data-bs-target="#accordionStyleTwo"
                          aria-expanded="false" aria-controls="accordionStyleTwo">Riwayat Kenaikan Golongan<i
                            class="ti ti-chevron-down"></i></h6>
                      </div>
                      <div class="accordion-collapse collapse" id="accordionStyleTwo" aria-labelledby="accordionTwo"
                        data-bs-parent="#accordionStyle1">
                        <div class="accordion-body">
                            <div class="table-responsive">
                              <div class="box-body" id="div_tabel_golongan"></div>
                            </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function viewmapping() {
        $.ajax({
            url: '<?php echo base_url(); ?>index.php/profile/mapping_pegawai',
            success: function (res) {
                $("#div_tabel_data_mapping").html(res);
            }
        });
    }
	  viewmapping()

    function viewgolongan() {
        $.ajax({
            url: '<?php echo base_url(); ?>index.php/profile/mapping_golongan',
            success: function (res) {
                $("#div_tabel_golongan").html(res);
            }
        });
    }
	viewgolongan()
  </script>

