<?php
    date_default_timezone_set("Asia/Jakarta");
?>
<style>
/* Spinner besar saat menunggu kamera */
#video-loading {
    width: 60px;
    height: 60px;
    border: 6px solid #ccc;
    border-top-color: #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 20px auto;
}

#video-loading-out {
    width: 60px;
    height: 60px;
    border: 6px solid #ccc;
    border-top-color: #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 20px auto;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Spinner kecil saat deteksi wajah */
#detect-loading {
    width: 25px;
    height: 25px;
    border: 3px solid #ccc;
    border-top-color: #e67e22;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    display: none;
    margin-left: 10px;
}


/* Spinner kecil saat deteksi wajah */
#detect-loading-out {
    width: 25px;
    height: 25px;
    border: 3px solid #ccc;
    border-top-color: #e67e22;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    display: none;
    margin-left: 10px;
}

</style>
<script src="<?php echo base_url('assets/js/face-api.min.js'); ?>"></script>

  <div class="page-content-wrapper">
    <!-- Welcome Toast -->
    <div class="toast toast-autohide custom-toast-1 toast-primary home-page-toast shadow" role="alert" aria-live="assertive" aria-atomic="true"
    data-bs-delay="60000" data-bs-autohide="true" id="installWrap">
      <div class="toast-body p-4">
        <div class="toast-text me-2">
          <h6 class="text-white">Welcome to MySIC</h6>
          <span class="d-block mb-2">Click the <strong>Install Now</strong> button & enjoy it just like an
            app.</span>
          <button id="installApp" class="btn btn-sm btn-warning">Install Now</button>
        </div>
      </div>
      <button class="btn btn-close btn-close-white position-absolute p-2" type="button" data-bs-dismiss="toast"
        aria-label="Close"></button>
    </div>

    <!-- Tiny Slider One Wrapper -->
    <div class="tiny-slider-one-wrapper">
      <div class="tiny-slider-one">
        <!-- Single Hero Slide -->
        <div>
          <div class="single-hero-slide bg-overlay" style="background-image: url('<?php echo base_url()?>assets/img/bg-img/sicprofile.jpg')">
            <div class="h-100 d-flex align-items-center text-center">
              <div class="container">
                <h3 class="text-white mb-1"><?php echo $this->session->userdata('nama')?></h3>
                <h5 class="text-white mb-4"><?php echo $this->session->userdata('username')?></h5>
                <h6 class="text-white mb-4"><?php echo $this->session->userdata('nm_unit')?></h6>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>

    <div class="pt-3"></div>

    <div class="top-products-area">
      <div class="container">
        <div class="row g-3">

          <!-- Single Top Product Card -->
          <div class="col-12 col-sm-12 col-lg-12">
            <div class="card single-product-card" style="background-color:#EE3643">
              <div class="card-body p-3">
                <div class="element-heading-wrapper">
                  <i class="bi bi-calendar2"></i>
                  <div class="heading-text">
                    <h6 class="mb-1"><font color="white"><?php echo $hari?> <?php echo $tglnow?></font></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-12 col-lg-12">
            <div class="card single-product-card" style="background-color:#0C519D">
              <div class="card-body p-3">
                <div class="element-heading-wrapper">
                  <i class="bi bi-clock"></i>
                  <div class="heading-text">
                    <span style="color:#FFFFFF;font-size:17px; font-weight:bold;" id="jam"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <p></p>
    
    <div class="container direction-rtl">
      <div class="card mb-3">
        <div class="card-body">
              <label class="form-label" for="exampleInputText3"><span style="color:#0C519D;font-size:14px; font-weight:bold;">Lokasi Kantor</span></label>
              <select class="pe-4 form-select form-select" name="kd_kantor" id="kd_kantor">
                  <?php
                      $data = $this->m_finger->get_kantor();
                      foreach ($data as $key => $value) {
                          echo "<option value=\"".$value['kd_kantor']."\">".$value['nm_kantor']."</option>";
                      }
                  ?>
              </select>
              <p></p>
              <div id="loading" style="display:none;">
                  <span class="spinner-border spinner-border-sm text-primary"></span> Mengambil Koordinat...
              </div>
              <label class="form-label" for="exampleInputText3">
                <!-- <p id="demo"></p> -->
                <span style="color:#0C519D;font-size:14px; font-weight:bold;" id="demo"></span>
              </label>

              <button class="btn btn-outline-danger w-100" onclick="daftarfaceid()">Daftar Face Id</button>
        </div>
      </div>
    </div>
    

    <div class="top-products-area">
      <div class="container">
        <div class="row g-3">

          <!-- Single Top Product Card -->
          <div class="col-6 col-sm-4 col-lg-3">
            <div class="card single-product-card" onclick="cek_in()" style="background-color:#0C519D">
              <div class="card-body p-3">
                <div class="element-heading-wrapper">
                  <i class="bi bi-box-arrow-in-up"></i>
                  <div class="heading-text">
                    <h6 class="mb-1"><font color="white">Check In</font></h6>
                    <span><font color="white">Absen Masuk</font></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Single Top Product Card -->
          <div class="col-6 col-sm-4 col-lg-3">
            <div class="card single-product-card" onclick="cek_out()" style="background-color:#EE3643">
              <div class="card-body p-3">
                <div class="element-heading-wrapper">
                  <i class="bi bi-box-arrow-up"></i>
                  <div class="heading-text">
                    <h6 class="mb-1"><font color="white">Check Out</font></h6>
                    <span><font color="white">Absen Pulang</font></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Single Top Product Card -->
          <div class="col-6 col-sm-4 col-lg-3">
            <div class="card single-product-card" onclick="view_dt()">
              <div class="card-body p-3">
                <div class="element-heading-wrapper">
                  <i class="bi bi-calendar2"></i>
                  <div class="heading-text">
                    <h6 class="mb-1">View</h6>
                    <span>Data Absen</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Single Top Product Card -->
          <div class="col-6 col-sm-4 col-lg-3">
            <div class="card single-product-card" onclick="view_gps()">
              <div class="card-body p-3">
                <div class="element-heading-wrapper">
                  <i class="bi bi-pin-map"></i>
                  <div class="heading-text">
                    <h6 class="mb-1">Maps</h6>
                    <span>Koordinat</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="row" style="display:none">
        <div class="col text-center">
            <input type="text" name="lat" id="lat">
            <input type="text" name="long" id="long">
        </div>
    </div>

    <!-- Modal Check-in -->
    <div id="modalCheckin" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.7); justify-content:center; align-items:center; z-index:9999;">
        <div style="background:#fff; padding:20px; border-radius:10px; text-align:center; width:360px; position:relative;">
            <h5>Ambil Foto untuk Check In</h5>
            <div style="position:relative; display:inline-block;">
                <div id="video-loading"></div>
              
                <div id="video-wrap" style="position:relative; width:320px; height:255px;">
                    <video id="video" width="320" height="255" autoplay muted
                        style="display:none; border-radius:3px; border:1px solid #ccc;"></video>
                    <canvas id="overlay" width="320" height="255"
                        style="position:absolute; top:0; left:0; pointer-events:none;"></canvas>
                </div>
                
                <div style="display:flex; align-items:center; justify-content:center;">
                    <div id="detect-loading"></div>
                </div>
            </div>
            <p id="msg" style="color:red; min-height:20px; margin-top:5px;"></p>
            <div style="margin-top:10px; display:flex; justify-content:center; gap:10px;">
                <button id="btn-checkin" style="flex:1; padding:8px; border:none; background-color:#0C519D; color:white; border-radius:5px; cursor:pointer;">
                    CheckIn
                </button>
                <button id="btnCancel" style="flex:1; padding:8px; border:none; background-color:#EE3643; color:white; border-radius:5px; cursor:pointer;">
                    Batal
                </button>
            </div>
            <div id="status" style="margin-top: 10px; font-weight: bold;">Memuat Model AI Face Detection</div>
        </div>
    </div>

    <!-- Modal Check-out -->
    <div id="modalCheckout" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.7); justify-content:center; align-items:center; z-index:9999;">
        <div style="background:#fff; padding:20px; border-radius:10px; text-align:center; width:360px; position:relative;">
            <h5>Ambil Foto untuk Check Out</h5>
            <div style="position:relative; display:inline-block;">
                <div id="video-loading-out"></div>
                
                <div id="video-wrap" style="position:relative; width:320px; height:255px;">
                    <video id="videoout" width="320" height="255" autoplay muted
                        style="display:none; border-radius:3px; border:1px solid #ccc;"></video>
                    <canvas id="overlayout" width="320" height="255"
                        style="position:absolute; top:0; left:0; pointer-events:none;"></canvas>
                </div>
                
                <div style="display:flex; align-items:center; justify-content:center;">
                    <div id="detect-loading-out"></div>
                </div>
            </div>
            <p id="msg" style="color:red; min-height:20px; margin-top:5px;"></p>
            <div style="margin-top:10px; display:flex; justify-content:center; gap:10px;">
                <button id="btn-checkout" style="flex:1; padding:8px; border:none; background-color:#0C519D; color:white; border-radius:5px; cursor:pointer;">
                    CheckOut
                </button>
                <button id="btnCancelout" style="flex:1; padding:8px; border:none; background-color:#EE3643; color:white; border-radius:5px; cursor:pointer;">
                    Batal
                </button>
            </div>
            <div id="statusout" style="margin-top: 10px; font-weight: bold;">Memuat Model AI Face Detection</div>
        </div>
    </div>

    <div class="pb-3"></div>
  </div>

  <script type="text/javascript">

  const video = document.getElementById('video');
  const btn = document.getElementById('btn-checkin');
  const status = document.getElementById('status');

  const videoout = document.getElementById('videoout');
  const btnout = document.getElementById('btn-checkout');
  const statusout = document.getElementById('statusout');

  const spinVideo = document.getElementById('video-loading');
  const spinDetect = document.getElementById('detect-loading');

  const spinVideoOut = document.getElementById('video-loading-out');
  const spinDetectOut = document.getElementById('detect-loading-out');

  let videoStream = null;
  let detectionInterval = null;

  let videoStreamout = null;
  let detectionIntervalOut = null;

  btn.disabled = true;
  btnout.disabled = true;

  const MODEL_URL = '<?php echo base_url("assets/models"); ?>';
  const UPLOAD_URL = '<?php echo base_url("index.php/absensi/proses_checkin"); ?>';
  const UPLOAD_URL_OUT = '<?php echo base_url("index.php/absensi/proses_checkout"); ?>';

  window.onload = function() {
      jam();
      getLocation();
      loadModels();
  };

function jam() {
    let e = document.getElementById('jam'), d = new Date();
    let h = d.getHours(), m = set(d.getMinutes()), s = set(d.getSeconds());
    e.innerHTML = h + ':' + m + ':' + s;
    setTimeout(jam, 1000);
}
function set(e) { return e < 10 ? '0' + e : e; }

// ===== GEOLOCATION =====
let x = document.getElementById("demo");
function getLocation() {
    var x = document.getElementById("demo");
    var loading = document.getElementById("loading");

    // tampilkan spinner
    loading.style.display = "block";
    x.innerHTML = "";
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        loading.style.display = "none";
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}


function showPosition(position) {
    var x = document.getElementById("demo");
    var loading = document.getElementById("loading");
    loading.style.display = "none";
    x.innerHTML = "Koordinat Absensi: " + position.coords.latitude + "," + position.coords.longitude;
    $("#lat").val(position.coords.latitude);
    $("#long").val(position.coords.longitude);
}

getLocation();
jam();


function daftarfaceid() {
    window.location.href = "<?php echo base_url('index.php/finger/daftar_faceid'); ?>";
}
function view_dt(){ window.location.href='<?php echo base_url(); ?>index.php/finger/view_dtfinger'; }

async function loadModels() {
    await Promise.all([
        faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL),
        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
    ]);
}

function cek_in(){
    document.getElementById('modalCheckin').style.display = 'flex'; startVideo(); 

    // Load model hanya sekali
    loadModels();
    startVideo();
}
document.getElementById('btnCancel').addEventListener('click', closeModal);
function closeModal() { document.getElementById('modalCheckin').style.display = 'none'; stopVideo(); document.getElementById('msg').innerText = ''; }


function startVideo() {
    navigator.mediaDevices.getUserMedia({ video: {} })
        .then(stream => {
            videoStream = stream;
            video.srcObject = stream;

            video.onloadeddata = () => {
                spinVideo.style.display = "none";
                video.style.display = "block";
                status.innerText = "Model siap. Silakan hadapkan wajah ke kamera.";

                // Set canvas overlay mengikuti video
                overlay.width = video.videoWidth;
                overlay.height = video.videoHeight;

                // Mulai deteksi wajah 
                mulaiDeteksiWajah();
            };
        })
        .catch(err => console.error(err));
}


function stopVideo() {
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;
    }

    if (detectionInterval) {
        clearInterval(detectionInterval);
        detectionInterval = null;
    }

    video.srcObject = null; // bersihkan tampilan video
}

btn.addEventListener('click', async () => {
    btn.disabled = true;                   // 🔥 disable tombol
    // btn.innerText = "Memproses...";        // ubah teks tombol

    status.innerText = "Mendeteksi wajah...";
    spinDetect.style.display = "inline-block"; // tampilkan spinner kecil

    const detection = await faceapi.detectSingleFace(video)
        .withFaceLandmarks()
        .withFaceDescriptor();
    spinDetect.style.display = "none"; // sembunyikan spinner setelah proses selesai

    if (detection) {
        const descriptorArray = Array.from(detection.descriptor);
        kirimKeServer(descriptorArray);
    } else {
        status.innerText = "Wajah tidak terdeteksi! Pastikan pencahayaan cukup.";
        btn.disabled = false;              // 🔥 aktifkan lagi tombol
    }
});

function kirimKeServer(faceVector) {
    const lat = document.getElementById("lat").value;
    const long = document.getElementById("long").value;
    const kd_kantor = document.getElementById("kd_kantor").value;
    
    status.innerText = "Mengirim data...";
    btn.disabled = true;

    fetch(UPLOAD_URL, {
        method: 'POST',
        headers: {'Content-Type': 'application/json' },
        body: JSON.stringify({
            face_vector: faceVector,
            lat: lat,
            long: long,
            kd_kantor: kd_kantor,
            type: 'absenmasuk'
        })
    })
    .then(async res => {
        let data;
        try {
            data = await res.json();
        } catch (e) {
            throw new Error("Respons server tidak valid");
        }
        return data;
    })
    .then(data => {

        // Hanya tutup modal jika sukses
        if (data.status === "success") {
            closeModal();
            stopVideo();

            Swal.fire({
                title: "Berhasil!",
                text: data.message,
                icon: "success",
                confirmButtonText: "OK"
            });

           
        }
        else{
            closeModal();
            stopVideo();

            Swal.fire({
                title: "Error!",
                text: data.message,
                icon: "error",
                confirmButtonText: "OK"
            });
        }

        btn.disabled = false;
    })
    .catch(err => {
        console.error(err);

        Swal.fire({
            title: "Error!",
            text: "Terjadi kesalahan koneksi.",
            icon: "error",
            confirmButtonText: "OK"
        });

        status.innerText = "Terjadi kesalahan koneksi.";
        btn.disabled = false;
    });
}

function view_gps(){
    window.location.href='<?php echo base_url(); ?>index.php/finger/view_gps?loop=1';
}


const overlay = document.getElementById('overlay');
const ctx = overlay.getContext('2d');

function syncOverlayWithVideo() {
  // ukuran tampilan (CSS pixels)
  const displayWidth = video.clientWidth;
  const displayHeight = video.clientHeight;

  // ukuran internal canvas (accounting for devicePixelRatio untuk ketajaman)
  const dpr = window.devicePixelRatio || 1;
  overlay.style.width = displayWidth + 'px';
  overlay.style.height = displayHeight + 'px';
  overlay.width = Math.round(displayWidth * dpr);
  overlay.height = Math.round(displayHeight * dpr);

  // sesuaikan transformasi context agar 1 unit = 1 CSS pixel
  ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
}

// panggil setelah video siap / saat ukuran berubah
video.addEventListener('loadedmetadata', () => {
  // tampilkan video jika ingin
  video.style.display = 'block';
  // sinkronkan overlay
  syncOverlayWithVideo();
});

// Jika layout bisa berubah (responsif), panggil saat resize
window.addEventListener('resize', () => {
  if (video.srcObject) syncOverlayWithVideo();
});

function prepareFaceapiDisplay() {
  const displaySize = { width: video.clientWidth, height: video.clientHeight };
  faceapi.matchDimensions(overlay, displaySize);
  return displaySize;
}

let displaySize = null;

async function mulaiDeteksiWajah() {
  // pastikan overlay dan video sudah disinkron
  syncOverlayWithVideo();
  displaySize = prepareFaceapiDisplay();

  detectionInterval = setInterval( async () => {
    const detection = await faceapi
      .detectSingleFace(video)
      .withFaceLandmarks()
      .withFaceDescriptor();

    // bersihkan overlay (digambar dalam CSS pixels, ctx sudah distretched sesuai DPR)
    ctx.clearRect(0, 0, overlay.width, overlay.height);

    if (detection) {
      status.innerText = "Wajah terdeteksi ✔️";

      // resize hasil deteksi ke ukuran tampilan
      const resizedDet = faceapi.resizeResults(detection, displaySize);

      // gunakan resizedDet.detection.box yang sudah sesuai posisi di layar
      const box = resizedDet.detection.box;
      ctx.lineWidth = 3;
      ctx.strokeStyle = "#00e676";
      ctx.strokeRect(box.x, box.y, box.width, box.height);

      // gambar landmarks (opsional)
    //   const landmarks = resizedDet.landmarks;
    //   ctx.fillStyle = "#ff1744";
    //   landmarks.positions.forEach(pt => {
    //     ctx.beginPath();
    //     ctx.arc(pt.x, pt.y, 2, 0, Math.PI * 2);
    //     ctx.fill();
    //   });

      btn.disabled = false;
    } else {
      status.innerText = "Tidak ada wajah. Arahkan wajah ke kamera...";
      btn.disabled = true;
    }

  }, 200);
}

//--- checkout ---
const overlayout = document.getElementById('overlayout');
const ctxout = overlayout.getContext('2d');

function cek_out(){
    document.getElementById('modalCheckout').style.display = 'flex'; startVideOut(); 

    loadModels();
    // startVideo();
    startVideOut();
}
document.getElementById('btnCancelout').addEventListener('click', closeModalout);
function closeModalout() { document.getElementById('modalCheckout').style.display = 'none'; stopVideoOut(); document.getElementById('msg').innerText = ''; }

function startVideOut() {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {

            videoStreamout = stream;
            videoout.srcObject = stream;

            videoout.onloadedmetadata = () => {

                videoout.style.display = "block";
                spinVideoOut.style.display = "none";
                // Pastikan ukuran valid
                if (videoout.videoWidth === 0 || videoout.videoHeight === 0) {
                    console.log("Ukuran video masih 0, ulangi 200ms...");
                    return setTimeout(videoout.onloadedmetadata, 200);
                }

                // Sinkronkan overlay
                syncOverlayWithVideoout();

                // Set displaySizeOut
                displaySizeOut = prepareFaceapiDisplayOut();

                mulaiDeteksiWajahOut();
            };
        })
        .catch(err => console.error("Kamera error:", err));
}

function stopVideoOut() {

    // hentikan interval deteksi wajah
    if (detectionIntervalOut) {
        clearInterval(detectionIntervalOut);
        detectionIntervalOut = null;
    }

    // hentikan stream kamera
    if (videoStreamout) {
        videoStreamout.getTracks().forEach(track => {
            track.stop();
        });
        videoStreamout = null;
    }

    // hentikan video element (INI KUNCI)
    const videoOut = document.getElementById('videoout');
    if (videoOut) {
        videoOut.pause();
        videoOut.srcObject = null;
        videoOut.load();
        videoOut.style.display = 'none';
    }

    // bersihkan canvas overlay
    const canvas = document.getElementById('overlayout');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }
}


function syncOverlayWithVideoout() {
  // ukuran tampilan (CSS pixels)
  const displayWidth = videoout.clientWidth;
  const displayHeight = videoout.clientHeight;

  // ukuran internal canvas (accounting for devicePixelRatio untuk ketajaman)
  const dpr = window.devicePixelRatio || 1;
  overlayout.style.width = displayWidth + 'px';
  overlayout.style.height = displayHeight + 'px';
  overlayout.width = Math.round(displayWidth * dpr);
  overlayout.height = Math.round(displayHeight * dpr);

  // sesuaikan transformasi context agar 1 unit = 1 CSS pixel
  ctxout.setTransform(dpr, 0, 0, dpr, 0, 0);
}


// panggil setelah video siap / saat ukuran berubah
videoout.addEventListener('loadedmetadata', () => {
  // tampilkan video jika ingin
  videoout.style.display = 'block';
  // sinkronkan overlay
  syncOverlayWithVideoout();
});

// Jika layout bisa berubah (responsif), panggil saat resize
window.addEventListener('resize', () => {
  if (videoout.srcObject) syncOverlayWithVideoout();
});

function prepareFaceapiDisplayOut() {
  const displaySizeOut = { width: videoout.clientWidth, height: videoout.clientHeight };
  faceapi.matchDimensions(overlayout, displaySizeOut);
  return displaySizeOut;
}

let displaySizeOut = null;

async function mulaiDeteksiWajahOut() {
  detectionIntervalOut = setInterval(async () => {

    const detection = await faceapi
      .detectSingleFace(videoout)
      .withFaceLandmarks()
      .withFaceDescriptor();

    ctxout.clearRect(0, 0, overlayout.width, overlayout.height);

    if (detection && displaySizeOut.width > 0) {

      statusout.innerText = "Wajah terdeteksi ✔️";

      const resized = faceapi.resizeResults(detection, displaySizeOut);
      const box = resized.detection.box;

      ctxout.beginPath();
      ctxout.lineWidth = 3;
      ctxout.strokeStyle = "#00e676";
      ctxout.strokeRect(box.x, box.y, box.width, box.height);

      btnout.disabled = false;

    } else {
      statusout.innerText = "Wajah Tidak Terdeteksi. Arahkan wajah ke kamera...";
      btnout.disabled = true;
    }

  }, 200);
}


btnout.addEventListener('click', async () => {
    btnout.disabled = true;                   // 🔥 disable tombol
    // btn.innerText = "Memproses...";        // ubah teks tombol

    statusout.innerText = "Mendeteksi wajah...";
    spinDetectOut.style.display = "inline-block"; // tampilkan spinner kecil

    const detection = await faceapi.detectSingleFace(videoout)
        .withFaceLandmarks()
        .withFaceDescriptor();
    spinDetectOut.style.display = "none"; // sembunyikan spinner setelah proses selesai

    if (detection) {
        const descriptorArray = Array.from(detection.descriptor);
        kirimKeServerOut(descriptorArray);
    } else {
        // spinDetectOut.innerText = "Wajah tidak terdeteksi! Pastikan pencahayaan cukup.";
        statusout.innerText = "Wajah tidak terdeteksi! Pastikan pencahayaan cukup.";
        btnout.disabled = false;              // 🔥 aktifkan lagi tombol
    }
});


function kirimKeServerOut(faceVector) {
    const lat = document.getElementById("lat").value;
    const long = document.getElementById("long").value;
    const kd_kantor = document.getElementById("kd_kantor").value;
    
    statusout.innerText = "Mengirim data...";
    btnout.disabled = true;

    fetch(UPLOAD_URL_OUT, {
        method: 'POST',
        headers: {'Content-Type': 'application/json' },
        body: JSON.stringify({
            face_vector: faceVector,
            lat: lat,
            long: long,
            kd_kantor: kd_kantor,
            type: 'absenkeluar'
        })
    })
    .then(async res => {
        let data;
        try {
            data = await res.json();
        } catch (e) {
            throw new Error("Respons server tidak valid");
        }
        return data;
    })
    .then(data => {
        // Hanya tutup modal jika sukses
        if (data.status === "success") {
            Swal.fire({
                title: "Berhasil!",
                text: data.message,
                icon: "success",
                confirmButtonText: "OK"
            });
           
        }
        else{
            Swal.fire({
                title: "Error!",
                text: data.message,
                icon: "error",
                confirmButtonText: "OK"
            });
        }

        closeModalout();
        // stopVideoOut();

        btnout.disabled = false;
    })
    .catch(err => {
        console.error(err);

        Swal.fire({
            title: "Error!",
            text: "Terjadi kesalahan koneksi.",
            icon: "error",
            confirmButtonText: "OK"
        });

        statusout.innerText = "Terjadi kesalahan koneksi.";
        btnout.disabled = false;
    });
}

</script>

<script>
let deferredPrompt = null;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    document.getElementById('installApp').style.display = 'block';
});

document.getElementById('installApp').addEventListener('click', async () => {
    if (!deferredPrompt) return;

    deferredPrompt.prompt();
    await deferredPrompt.userChoice;
    deferredPrompt = null;
});
</script>
