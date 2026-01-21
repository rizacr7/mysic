<style>
canvas { border:1px solid #ccc; border-radius:4px; touch-action: none; }
.controls { margin-top:8px; display:flex; gap:8px; flex-wrap:wrap; }
#preview { margin-top:12px; max-width:100%; height:auto; border:1px dashed #ccc; padding:6px; display:none; }
</style>
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data Pengajuan Mutasi & Promosi Pegawai</h6>
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
            <div class="col-12">

              <!-- ACTION BUTTON -->
              <div class="d-flex gap-2 mb-3 flex-wrap">
                <!-- <button class="btn btn-secondary btn-sm" onclick="toggleSelectAll()">
                  <i class="bi bi-check-all"></i> Select All
                </button>

                <button class="btn btn-primary btn-sm" onclick="approveSelected()">
                  <i class="bi bi-check2-square"></i> Approve PJK SPPD
                </button> -->

                <!-- <button class="btn btn-dark btn-sm" onclick="viewpjk()">
                  <i class="bi bi-search"></i> View PJK SPPD
                </button> -->
              </div>

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

              ?>

              <div class="col-12 mb-3">
                <div class="card shadow-sm border-0">
                  <div class="card-body bg-light">

                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <div>
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

                    <!-- ACTION BUTTON -->
                    <div class="d-flex justify-content-end gap-2">
                      <button class="btn btn-outline-dark btn-sm"
                        onclick="approverequest('<?= $val['id_req'] ?>','<?= $val['job_desc'] ?>')">
                        <i class="bi bi-check"></i> Approve
                      </button>
                    </div>

                  </div>
                </div>
              </div>
              <?php 
                  }
                } else {
                  echo "<div class='text-center text-muted'>Data tidak ditemukan</div>";
                }
              ?>

            </div>
          </div>

          <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h6 class="modal-title" id="exampleModalLabel">Form Signature</h6>
                  <button class="btn btn-close p-1 ms-auto" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formModal" onsubmit="return false">
                      <div class="row">
                          <div class="col-md-12 col-sm-12 col-xs-12">
                              <div class="form-group" style="display:none;">
                                  <label>Id Req</label><br>
                                  <input type="text" id="id_request" name="id_request" readonly class="form-control" onkeyup="uppercase(this)">
                              </div>

                              <div class="form-group">
                                  <label>Job Desc</label><br>
                                  <input type="text" id="job" name="job" readonly class="form-control" onkeyup="uppercase(this)">
                              </div>

                              <p>Silakan tanda tangani di area di bawah (sentuh / klik & geser untuk menulis):</p>

                              <canvas id="sigCanvas" width="340" height="200"></canvas>

                              <div class="controls">
                              <button id="clearBtn" class="btn btn-sm btn-danger" >Bersihkan</button>
                              <button id="undoBtn" class="btn btn-sm btn-warning" >Undo</button>
                              <!-- <button id="saveBtn" type="button">Simpan Tanda Tangan</button> -->
                              </div>

                              <img id="preview" alt="Preview tanda tangan">

                              <p id="message" style="color:green;"></p>
                          </div>
                      </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                  <button class="btn btn-success" type="button" onclick="simpan()">Save</button>
                </div>
              </div>
            </div>
          </div>
          
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

function approverequest(idreq,jobdesc){
    // Tampilkan modal
    $('#myModal').modal('show');
    // Set nilai id_request di dalam modal
    $('#id_request').val(idreq);
    $('#job').val(jobdesc);

    // Inisialisasi signature pad
    // initSignaturePad();
}

$('#myModal').on('shown.bs.modal', function () {
    // Ambil canvas
    const canvas = document.getElementById('sigCanvas');
    const ctx = canvas.getContext('2d');

    // Reset ukuran agar canvas aktif penuh
    const rect = canvas.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = rect.height;

    // Bersihkan area gambar
    ctx.clearRect(0, 0, canvas.width, canvas.height);
});

const canvas = document.getElementById('sigCanvas');
    const ctx = canvas.getContext('2d');
    let drawing = false;
    let last = {x:0, y:0};
    let strokes = [];
    let currentStroke = [];

    // --- event dasar untuk menggambar di canvas ---
    function getPoint(e) {
    const rect = canvas.getBoundingClientRect();
    const x = (e.clientX ?? e.touches?.[0]?.clientX) - rect.left;
    const y = (e.clientY ?? e.touches?.[0]?.clientY) - rect.top;
    return {x, y};
    }

    function startDraw(e) {
    e.preventDefault();
    drawing = true;
    currentStroke = [];
    const p = getPoint(e);
    last = p;
    currentStroke.push(p);
    }

    function moveDraw(e) {
    if (!drawing) return;
    e.preventDefault();
    const p = getPoint(e);
    ctx.lineJoin = 'round';
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2.5;
    ctx.beginPath();
    ctx.moveTo(last.x, last.y);
    ctx.lineTo(p.x, p.y);
    ctx.stroke();
    last = p;
    currentStroke.push(p);
    }

    function endDraw() {
    if (!drawing) return;
    drawing = false;
    strokes.push(currentStroke.slice());
    currentStroke = [];
    }

    // --- Event listeners ---
    canvas.addEventListener('pointerdown', startDraw);
    canvas.addEventListener('pointermove', moveDraw);
    canvas.addEventListener('pointerup', endDraw);
    canvas.addEventListener('pointerout', endDraw);
    canvas.addEventListener('pointercancel', endDraw);

    // Tombol Bersihkan & Undo
    document.getElementById('clearBtn').addEventListener('click', () => {
    ctx.clearRect(0,0,canvas.width,canvas.height);
    strokes = [];
    document.getElementById('preview').style.display = 'none';
    document.getElementById('message').textContent = '';
    });

    document.getElementById('undoBtn').addEventListener('click', () => {
    strokes.pop();
    redraw();
    });

    function redraw() {
    ctx.clearRect(0,0,canvas.width,canvas.height);
    ctx.lineJoin = 'round';
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2.5;
    for (const stroke of strokes) {
        if (stroke.length < 2) continue;
        ctx.beginPath();
        ctx.moveTo(stroke[0].x, stroke[0].y);
        for (let i=1;i<stroke.length;i++){
        ctx.lineTo(stroke[i].x, stroke[i].y);
        }
        ctx.stroke();
    }
  }

   async function simpan() {
      const id_request = document.getElementById('id_request').value.trim();

      if (!id_request) {
          Swal.fire('Info', 'Pilih minimal satu data', 'info');
          return;
      }

      if (strokes.length === 0) {
          Swal.fire('Info', 'Silakan buat tanda tangan terlebih dahulu.', 'info');
          return;
      }

      const dataURL = canvas.toDataURL('image/png');

      // 🔍 Tambahkan ini untuk debug
      console.log('Kirim data:', { id_request, image: dataURL.substring(0,50) + '...' });

      try {
          const res = await fetch('<?php echo base_url(); ?>index.php/approve/save_signature', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id_request, image: dataURL })
          });

          const result = await res.json();
          console.log('Respon server:', result); // 🔍 lihat hasilnya di console browser

          if (result.success) {
              document.getElementById('message').textContent = 'Tanda tangan berhasil disimpan.';
              document.getElementById('message').style.color = 'green';
              setTimeout(() => {
                  $('#myModal').modal('hide');
              }, 2000);
              location.reload();
          } else {
              document.getElementById('message').textContent = 'Gagal menyimpan: ' + result.error;
              document.getElementById('message').style.color = 'red';
              location.reload();
          }
      } catch (err) {
          console.error(err);
          Swal.fire('Error', 'Terjadi kesalahan saat menyimpan tanda tangan.', 'error');
      }
  }

function kembali(){
   window.location.href='<?php echo base_url(); ?>index.php/sdm/pageapprove';
}
</script>
	