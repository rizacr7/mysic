
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data Pengajuan SPPD Pegawai</h6>
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
              <!-- BUTTON MULTI APPROVE -->
              <button class="btn btn-primary btn-sm mb-2" onclick="approveSelected()">
                <i class="bi bi-check2-square"></i> Approve SPPD
              </button>

              <div class="table-responsive">
                <table id="dataTable" class="display nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>
                        <input type="checkbox" id="checkAll">
                      </th>
                      <th>Bukti</th>
                      <th>Nama</th>
                      <th>Tgl. SPPD</th>
                      <th>Tujuan</th>
                      <th>Dalam Rangka</th>
                      <th>Akomodasi</th>
                      <th>Kendaraan</th>
                      <th>Status</th>
                    </tr>
                  </thead>

                  <tbody>
                  <?php 
                    $param = [
                      'no_peg'    => $Datapeg[0]->no_peg,
                      'kd_unit'   => $Datapeg[0]->kd_unit,
                      'kd_bagian' => $Datapeg[0]->kd_bagian,
                      'kd_jab'    => $Datapeg[0]->kd_jab,
                      'kd_level'  => $Datapeg[0]->kd_level
                    ];

                    $dataresultapp = $this->sdm_model->appsppdpegawai($param);

                    if (!empty($dataresultapp) && is_array($dataresultapp)) {
                      foreach ($dataresultapp as $val) {

                        // === ACTION CHECKBOX ===
                        if ($val['APPROVE'] == '0') {
                          $action = "<input type='checkbox' class='checkItem' value='{$val['ID']}'>";
                          $status = "<span class='badge rounded-pill bg-danger'>Pending</span>";
                        } else {
                          $action = "";
                          $status = "<span class='badge rounded-pill bg-primary'>Approved</span>";
                        }

                        // === AKOMODASI ===
                        switch ($val['AKOMODASI']) {
                          case '1': $akomodasi = 'Hotel'; break;
                          case '2': $akomodasi = 'Luar'; break;
                          case '3': $akomodasi = 'Mess'; break;
                          default:  $akomodasi = '-';
                        }

                        // === KENDARAAN ===
                        switch ($val['KENDARAAN']) {
                          case '1': $kendaraan = 'Dinas'; break;
                          case '2': $kendaraan = 'Kereta Api'; break;
                          case '3': $kendaraan = 'Bus'; break;
                          case '4': $kendaraan = 'Kapal'; break;
                          case '5': $kendaraan = 'Pesawat'; break;
                          case '6': $kendaraan = 'Lain-lain'; break;
                          default:  $kendaraan = '-';
                        }

                        echo "
                        <tr>
                          <td>{$action}</td>
                          <td>{$val['BUKTI']}</td>
                          <td>{$val['na_peg']}</td>
                          <td>{$val['TGL_AWAL']} s.d {$val['TGL_AKHIR']}</td>
                          <td>{$val['TUJUAN']}</td>
                          <td>{$val['DALAM_RANGKA']}</td>
                          <td>{$akomodasi}</td>
                          <td>{$kendaraan}</td>
                          <td>{$status}</td>
                        </tr>";
                      }
                    } else {
                      echo "<tr><td colspan='9' class='text-center'>Data tidak ditemukan</td></tr>";
                    }
                  ?>
                  </tbody>
                </table>
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
        title: 'Approve SPPD?',
        text: 'Data terpilih akan disetujui',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Approve'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/approve/approve_multi_sppd",
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
	

function approvesppd(id_sppd) {
  Swal.fire({
      title: 'Menyetujui data sppd?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, izinkan!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "<?php echo base_url(); ?>index.php/approve/approve_sppd",
              type: "POST",
              data: { id_sppd: id_sppd },
              dataType: "json",
              success: function(res) {
                  if (res.status === true) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Berhasil',
                          text: 'Data berhasil disetujui',
                          timer: 1500,
                          showConfirmButton: false
                      });
                      // reload DataTable
                      window.location.href='<?php echo base_url(); ?>index.php/approve/app_sppd';
                      // $('#dataTable').DataTable().ajax.reload(null, false);
                  } else {
                      Swal.fire('Gagal', res.message ?? 'Gagal approve data', 'error');
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
	