
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

.btn-flat {
    padding: 6px 14px;
    font-size: 13px;
    border: 1px solid #0C519D;
    background: #0C519D;
    color: #fff;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.2s;
}

.btn-flat:disabled {
    background: #bdc3c7;
    border-color: #bdc3c7;
    cursor: not-allowed;
}

.btn-flat:hover:not(:disabled) {
    background: #2980b9;
}
</style>
<script src="<?php echo base_url('assets/js/face-api.min.js'); ?>"></script>

<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Daftar Face Id</h6>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-body">
        
          <div class="form-group">
            <label class="form-label" for="exampleTextarea1">Silakan Menghadap Kamera</label>
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
          <div style="text-align:center; margin-top:15px;">
              <button id="btn-checkin" class="btn-flat" disabled>Ambil Data Wajah</button>
              <div id="status" style="margin-top: 10px; font-weight: bold;">Memuat Model AI...</div>
          </div>
        
      </div>
    </div>
  </div>
</div>


<script>
const video = document.getElementById('video');
const btn = document.getElementById('btn-checkin');
const status = document.getElementById('status');
const spinVideo = document.getElementById('video-loading');
const spinDetect = document.getElementById('detect-loading');

btn.disabled = true;

const MODEL_URL = '<?php echo base_url("assets/models"); ?>';
const UPLOAD_URL = '<?php echo base_url("index.php/absensi/proses_daftar"); ?>';

// Load Model AI
Promise.all([
    faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL),
    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
]).then(startVideo);

let videoStream = null;
let detectionInterval = null;

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
        videoStream.getTracks().forEach(t => t.stop());
        videoStream = null;
    }

    if (detectionInterval) {
        clearInterval(detectionInterval);
        detectionInterval = null;
    }

    ctx = overlay.getContext("2d");
    ctx.clearRect(0, 0, overlay.width, overlay.height);

    video.srcObject = null;
}

// function startVideo() {
//     navigator.mediaDevices.getUserMedia({ video: {} })
//         .then(stream => {
//             video.srcObject = stream;

//             // ketika kamera sudah siap
//             video.onloadeddata = () => {
//                 spinVideo.style.display = "none";
//                 video.style.display = "block";
//                 status.innerText = "Sistem sudah siap.";
//                 btn.disabled = false;
//             };
//         })
//         .catch(err => console.error(err));
// }


// btn.addEventListener('click', async () => {
//     status.innerText = "Mendeteksi wajah...";
//     spinDetect.style.display = "inline-block"; // tampilkan spinner kecil

//     const detection = await faceapi.detectSingleFace(video)
//         .withFaceLandmarks()
//         .withFaceDescriptor();

//     spinDetect.style.display = "none"; // sembunyikan spinner setelah proses selesai

//     if (detection) {
//         const descriptorArray = Array.from(detection.descriptor);
//         kirimKeServer(descriptorArray);
//     } else {
//         status.innerText = "Wajah tidak terdeteksi! Pastikan pencahayaan cukup.";
//     }
// });

function captureImageFromVideo(video) {
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    // hasil base64
    return canvas.toDataURL('image/jpeg', 0.9);
}

btn.addEventListener('click', async () => {
    status.innerText = "Mendeteksi wajah...";
    spinDetect.style.display = "inline-block";

    const detection = await faceapi
        .detectSingleFace(video)
        .withFaceLandmarks()
        .withFaceDescriptor();

    spinDetect.style.display = "none";

    if (detection) {
        const descriptorArray = Array.from(detection.descriptor);
        // 🔴 ambil gambar dari video
        const imageBase64 = captureImageFromVideo(video);
        kirimKeServer(descriptorArray, imageBase64);
    } else {
        status.innerText = "Wajah tidak terdeteksi! Pastikan pencahayaan cukup.";
    }
});

function kirimKeServer(faceVector,imageBase64) {
    status.innerText = "Mengirim data...";

    fetch(UPLOAD_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            face_vector: faceVector,
            image: imageBase64,
            type: 'daftar'
        })
    })
        .then(res => res.json())
        .then(data => {
            // Tampilkan sweetalert sukses atau gagal
            Swal.fire({
                title: data.status === "success" ? "Berhasil!" : "Gagal!",
                text: data.message,
                icon: data.status === "success" ? "success" : "error",
                confirmButtonText: "OK"
            });
            status.innerText = data.message;
            window.location.href = "<?php echo base_url('index.php/welcome/sukses'); ?>";
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
        });
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
      /*
      const landmarks = resizedDet.landmarks;
      ctx.fillStyle = "#ff1744";
      landmarks.positions.forEach(pt => {
        ctx.beginPath();
        ctx.arc(pt.x, pt.y, 2, 0, Math.PI * 2);
        ctx.fill();
      });
      */

      btn.disabled = false;
    } else {
      status.innerText = "Tidak ada wajah. Arahkan wajah ke kamera...";
      btn.disabled = true;
    }

  }, 200);
}
</script>