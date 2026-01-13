
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data PJK SPPD Pegawai</h6>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-body">
          <div class="spinner-border text-primary" role="status" style="display:none" align="center" id="loading">
            <span class="visually-hidden">Loading...</span>
          </div>
          <div class="row">
            <div class="col-12 col-md-12">
              <div class="table-responsive">
              <table class="display nowrap" style="width:100%" id="dataTable">
              <thead>
                <tr>
                  <th>View</th>
                  <th style="display:none">Bukti</th>
                  <th>No.Ref</th>
                  <th>Nama</th>
                  <th>Tgl.SPPD</th>
                  <th>Tujuan</th>
                  <th>Akomodasi</th>
                  <th>Kendaraan</th>
                  <th>Keterangan</th>
                  <th>Beban</th>
                  <th>K.Biaya</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php 
                $no_peg = $Datapeg[0]->no_peg;
                $param = array();
                $param['no_peg'] = $no_peg;
                $dataresult = $this->sdm_model->pjksppdpegawai($param);

                foreach($dataresult as $key => $val){

                  $tgl_input = $val['TGL_UPDATE'];
                  $id_sppd = $val['ID'];

                  $tgl_input_date = new DateTime($tgl_input);
                  $tgl_sekarang   = new DateTime(date('Y-m-d'));

                  $selisih = $tgl_input_date->diff($tgl_sekarang)->days;

                  if ($val['APPROVE_ATASAN'] == '0') {
                      $btnaction = "<button class='btn m-1 btn-sm btn-danger'
                        onclick=\"hapuspjksppd('{$val['ID']}')\">
                        <i class='bi bi-trash'></i>
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

                   $viewaction = "<button class='btn m-1 btn-sm btn-dark'
                        onclick=\"viewpjk('{$val['ID']}')\">
                        <i class='bi bi-search'></i>
                      </button>";

                  echo "
                  <tr>
                    <td>$viewaction</td>
                    <td style='display:none'>".$val['BUKTI_PJK']."</td>
                    <td>".$val['BUKTI']."</td>
                    <td>".$val['na_peg']."</td>
                    <td>".$val['AWAL_TUGAS']." s.d ".$val['AKIR_TUGAS']."</td>
                    <td>".$val['TUJUAN']."</td>
                    <td>".$val['DALAM_RANGKA']."</td>
                    <td>".$akomodasi."</td>
                    <td>".$kendaraan."</td>
                    <td>".$val['BEBAN']."</td>
                    <td>".$val['KA']."</td>
                    <td>".$label_app."</td>
                    <td>".$btnaction."</td>
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
	
// $('#dataTable').DataTable({
//     scrollX: true,
//     autoWidth: false
// });

function hapuspjksppd(id_pjk) {
  Swal.fire({
      title: 'Hapus data pjk sppd?',
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
              url: "<?php echo base_url(); ?>index.php/sdm/hapusdtpjksppd",
              type: "POST",
              data: { id_pjk: id_pjk },
              dataType: "json",
              success: function(res) {
                  if (res.status === true) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Berhasil',
                          text: 'Data pjk sppd berhasil dihapus',
                          timer: 1500,
                          showConfirmButton: false
                      });
                      // reload DataTable
                      window.location.href='<?php echo base_url(); ?>index.php/sdm/datapjksppd';
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



function viewpjk(id_pjk) {
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
            id_pjk: id_pjk
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

</script>
	