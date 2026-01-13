
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>View Approve PJK SPPD Pegawai</h6>
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
            <div class="col-12 col-md-12">
              <p></p>
              <button class="btn btn-dark btn-sm mb-2" onclick="viewpjk()">
                <i class="bi bi-search"></i> View PJK SPPD
              </button>
              <div class="table-responsive">
              <table class="display nowrap" style="width:100%" id="dataTable">
              <thead>
                <tr>
                  <th>
                    <input type="checkbox" id="checkAll">
                  </th>
                  <th>Action</th>
                  <th>Bukti</th>
                  <th>Nama</th>
                  <th>Tgl.SPPD</th>
                  <th>Tujuan</th>
                  <th>Keterangan</th>
                  <th>Akomodasi</th>
                  <th>Kendaraan</th>
                  <th>Beban</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
              <?php 
                $no_peg = $Datapeg[0]->no_peg;
                $param = array();
                $param['no_peg'] = $no_peg;
                $param['kd_unit'] = $Datapeg[0]->kd_unit;
                $param['kd_bagian'] = $Datapeg[0]->kd_bagian;
                $param['kd_jab'] = $Datapeg[0]->kd_jab;
                $param['kd_level'] = $Datapeg[0]->kd_level;
                $dataresultapp = $this->sdm_model->viewapppjksppdpegawai($param);

                foreach($dataresultapp as $key => $val){

                  $tgl_input = substr($val['TGL_APP'],1,10);
                  $id_sppd = $val['ID'];

                  $tgl_input_date = new DateTime($tgl_input);
                  $tgl_sekarang   = new DateTime(date('Y-m-d'));

                  $selisih = $tgl_input_date->diff($tgl_sekarang)->days;

                  if ($tgl_sekarang <= $tgl_input_date->modify('+4 days') && $val['APPROVE_ATASAN'] == '1' && $tgl_input != '') {
                      $btnaction = "<button class='btn m-1 btn-sm btn-warning'
                        onclick=\"batalapppjksppd('{$val['ID']}')\">
                        <i class='bi bi-arrow-clockwise'></i>
                      </button>";
                  } else {
                      $btnaction = ""; // kosongkan
                  }

                  if($val['APPROVE_ATASAN'] == '1'){
                    $label_app = "<span class='m-1 badge rounded-pill bg-primary'>Approved</span>";
                  }
                  else{
                    $label_app = "<span class='m-1 badge rounded-pill bg-danger'>Pending</span>";
                  }
                  
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

                  $action = "<input type='checkbox' class='checkItem' value='{$val['ID']}'>";

                  echo "
                  <tr>
                    <td>".$action."</td>
                    <td>".$btnaction."</td>
                    <td>".$val['BUKTI_PJK']."</td>
                    <td>".$val['na_peg']."</td>
                    <td>".$val['AWAL_TUGAS']." s.d ".$val['AKIR_TUGAS']."</td>
                    <td>".$val['TUJUAN']."</td>
                    <td>".$val['DALAM_RANGKA']."</td>
                    <td>".$akomodasi."</td>
                    <td>".$kendaraan."</td>
                    <td>".$val['BEBAN']."</td>
                    <td>".$label_app."</td>
                   
                  </tr>";
                }

              ?>
              </tbody>
            </table>
            </div>
            </div>
          </div>

          <div class="modal fade" id="fullscreenModal" tabindex="-1" aria-labelledby="fullscreenModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-md-down">
              <div class="modal-content">
                <div class="modal-header">
                  <h6 class="modal-title" id="fullscreenModalLabel">View PJK SPPD</h6>
                  <button class="btn btn-close p-1 ms-auto" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
               
                </div>
                <div class="modal-footer">
                  <button class="btn btn-sm btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                  <!-- <button class="btn btn-sm btn-success" type="button">Save</button> -->
                </div>
              </div>
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

function batalapppjksppd(id_pjk) {
  Swal.fire({
      title: 'Membatalkan data pjk sppd?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, batalkan!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/approve/bataldtpjksppd",
              type: "POST",
              data: { id_pjk: id_pjk },
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
                      window.location.href='<?php echo base_url(); ?>index.php/approve/view_app_pjksppd';
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


function viewpjk() {
    let ids = [];

    $('.checkItem:checked').each(function () {
        ids.push($(this).val());
    });

    // validasi
    if (ids.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Pilih satu data PJK terlebih dahulu'
        });
        return;
    }

    if (ids.length > 1) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Hanya boleh memilih satu data'
        });
        return;
    }

    // buka modal
    $('#fullscreenModal').modal('show');
    $('#fullscreenModal .modal-body').html(`
        <div class="text-center p-4">
            <div class="spinner-border text-primary"></div>
            <div class="mt-2">Memuat data...</div>
        </div>
    `);

    // AJAX kirim ID
    $.ajax({
        url: "<?= base_url('index.php/view/view_pjk') ?>",
        type: "POST",
        data: {
            id_pjk: ids[0]
        },
        success: function (res) {
            $('#fullscreenModal .modal-body').html(res);
        },
        error: function () {
            $('#fullscreenModal .modal-body').html(`
                <div class="alert alert-danger">
                    Gagal memuat data PJK
                </div>
            `);
        }
    });
}
	

function kembali(){
   window.location.href='<?php echo base_url(); ?>index.php/sdm/pageapprove';
}
</script>
	