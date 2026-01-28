<?php
    date_default_timezone_set("Asia/Jakarta");
?>

  <div class="page-content-wrapper">
    <!-- Welcome Toast -->
    <!-- <div class="toast toast-autohide custom-toast-1 toast-primary home-page-toast shadow" role="alert" aria-live="assertive" aria-atomic="true"
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
    </div> -->

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
          
          <?php 
            $querypeserta = "SELECT * FROM peserta WHERE no_peg = '".$this->session->userdata('username')."'";
            $rdt = $this->db_undian->query($querypeserta)->num_rows();
            if($rdt != 0){
                $data = $this->db_undian->query($querypeserta)->result();
                $nopeg= $this->session->userdata('username');
               
          ?>
          <div class="col-12 col-sm-12 col-lg-12">


          <div class="card card-bg-img bg-img" onclick="undangansic('<?php echo $nopeg?>')" style="background-image: url('<?php echo base_url()?>assets/img/bg-img/undangangathering.jpeg')">
            <div class="card-body p-4 direction-rtl" style="height: 180px;">
              <h6 class="display-3 mb-4 fw-semibold"></h6>
            </div>
            <a class="btn btn-danger">UNDANGAN DIGITAL FAMILY GATHERING</a>
          </div>
          
          </div>
          <?php }?>
        
          <?php 
            $queryhadiah = "SELECT * FROM undian WHERE no_peg = '".$this->session->userdata('username')."' and is_ambil = 0";
            $rdt = $this->db_undian->query($queryhadiah)->num_rows();
            if($rdt != 0){
                $data = $this->db_undian->query($queryhadiah)->result();
                $nmhadiah = $data[0]->nama_hadiah;
                
          ?>
           <div class="col-12 col-sm-12 col-lg-12">
            <div class="card card-bg-img bg-img" onclick="tukarhadiah()" style="background-image: url('<?php echo base_url()?>assets/img/bg-img/doorprize.jpg')">
              <div class="card-body p-4 direction-rtl" style="height: 180px;">
                <h6 class="display-3 mb-4 fw-semibold"></h6>
              </div>
              <a class="btn btn-primary">TUKARKAN HADIAH</a>
            </div>
          </div>
          <?php 
            }
          ?>

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

              <!-- <button class="btn btn-outline-danger w-100" onclick="daftarfaceid()">Daftar Face Id</button> -->
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

          <div class="col-6 col-sm-4 col-lg-3">
            <div class="card single-product-card" onclick="page_menu()">
              <div class="card-body p-3">
                <div class="element-heading-wrapper">
                  <i class="bi bi-list-task"></i>
                  <div class="heading-text">
                    <h6 class="mb-1">All Menu</h6>
                    <span>Aplikasi</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-6 col-sm-4 col-lg-3">
            <div class="card single-product-card" onclick="page_app()">
              <div class="card-body p-3">
                <div class="element-heading-wrapper">
                  <i class="bi bi-card-checklist"></i>
                  <div class="heading-text">
                    <h6 class="mb-1">Approval</h6>
                    <span>Pengajuan</span>
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


    <div class="pb-3"></div>
  </div>

  <script type="text/javascript">

  document.addEventListener("DOMContentLoaded", () => {
    jam();
    getLocation();
  });

function jam() {
    let e = document.getElementById('jam'), d = new Date();
    let h = d.getHours(), m = set(d.getMinutes()), s = set(d.getSeconds());
    e.innerHTML = h + ':' + m + ':' + s;
    setTimeout(jam, 1000);
}
function set(e) { return e < 10 ? '0' + e : e; }

// ===== GEOLOCATION =====
let x = document.getElementById("demo");
// function getLocation() {
//     var x = document.getElementById("demo");
//     var loading = document.getElementById("loading");

//     // tampilkan spinner
//     loading.style.display = "block";
//     x.innerHTML = "";
//     if (navigator.geolocation) {
//         navigator.geolocation.getCurrentPosition(showPosition);
//     } else {
//         loading.style.display = "none";
//         x.innerHTML = "Geolocation is not supported by this browser.";
//     }
// }


// function showPosition(position) {
//     var x = document.getElementById("demo");
//     var loading = document.getElementById("loading");
//     loading.style.display = "none";
//     x.innerHTML = "Koordinat Absensi: " + position.coords.latitude + "," + position.coords.longitude;
//     $("#lat").val(position.coords.latitude);
//     $("#long").val(position.coords.longitude);
// }

let lastPosition = null;

function getLocation() {
    const x = document.getElementById("demo");
    const loading = document.getElementById("loading");

    loading.style.display = "block";
    x.innerHTML = "Mengambil lokasi...";

    if (!navigator.geolocation) {
        loading.style.display = "none";
        x.innerHTML = "Browser tidak mendukung GPS";
        return;
    }

    navigator.geolocation.getCurrentPosition(
        showPosition,
        showError,
        {
            enableHighAccuracy: true, // ⚡ lebih cepat
            timeout: 15000,             // ⏱ max 15 detik
            maximumAge: 60000          // ♻️ cache 1 menit
        }
    );
}

function showPosition(position) {
    const x = document.getElementById("demo");
    const loading = document.getElementById("loading");

    loading.style.display = "none";

    const lat = position.coords.latitude;
    const lng = position.coords.longitude;
    const acc = position.coords.accuracy;

    // x.innerHTML = `Koordinat Absensi: ${lat}, ${lng}<br>Akurasi: ±${Math.round(acc)} m`;
    x.innerHTML = `Koordinat Absensi: ${lat}, ${lng}`;

    document.getElementById("lat").value = lat;
    document.getElementById("long").value = lng;

    lastPosition = position;
}

function showError(error) {
    const x = document.getElementById("demo");
    const loading = document.getElementById("loading");

    loading.style.display = "none";

    let msg = "";
    switch (error.code) {
        case error.PERMISSION_DENIED:
            msg = "Izin lokasi ditolak";
            break;
        case error.POSITION_UNAVAILABLE:
            msg = "Lokasi tidak tersedia";
            break;
        case error.TIMEOUT:
            msg = "GPS timeout, coba lagi";
            break;
        default:
            msg = "Gagal mengambil lokasi";
    }

    x.innerHTML = msg;
}

// getLocation();
// jam();


function daftarfaceid() {
    window.location.href = "<?php echo base_url('index.php/finger/daftar_faceid'); ?>";
}
function view_dt(){ window.location.href='<?php echo base_url(); ?>index.php/finger/view_dtfinger'; }

function view_gps(){
    window.location.href='<?php echo base_url(); ?>index.php/finger/view_gps?loop=1';
}

function page_menu(){
    window.location.href='<?php echo base_url(); ?>index.php/sdm/pagemenu';
}

function page_app(){
    window.location.href='<?php echo base_url(); ?>index.php/sdm/pageapprove';
}

function tukarhadiah(){
    window.location.href='<?php echo base_url(); ?>index.php/welcome/tukarhadiah';
}


function undangansic(nopeg){
    window.open('https://undian2026.sic.co.id/undangan/?no_undian='+nopeg);
}


function generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

function getDeviceId() {
    let deviceId = localStorage.getItem('device_id');
    if (!deviceId) {
        deviceId = generateUUID();
        localStorage.setItem('device_id', deviceId);
    }
    return deviceId;
}

// =========================
// SHA-256 helper
// =========================
async function sha256(message) {
    const msgBuffer = new TextEncoder().encode(message);
    const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer);
    return Array.from(new Uint8Array(hashBuffer))
        .map(b => b.toString(16).padStart(2, '0'))
        .join('');
}

// =========================
// Device Fingerprint
// =========================
function getCanvasFingerprint() {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');

    ctx.textBaseline = "top";
    ctx.font = "14px Arial";
    ctx.fillStyle = "#f60";
    ctx.fillRect(125,1,62,20);
    ctx.fillStyle = "#069";
    ctx.fillText("cek-in-fingerprint", 2, 15);
    ctx.fillStyle = "rgba(102,204,0,0.7)";
    ctx.fillText("cek-in-fingerprint", 4, 17);

    return canvas.toDataURL();
}

function getFingerprintRaw() {
    return [
        navigator.userAgent,
        navigator.platform,
        navigator.language,
        screen.width + 'x' + screen.height,
        Intl.DateTimeFormat().resolvedOptions().timeZone,
        navigator.hardwareConcurrency || '',
        navigator.deviceMemory || '',
        getCanvasFingerprint()
    ].join('###');
}

async function cek_in() {
    const status = document.getElementById("status");
    const kd_kantor = document.getElementById("kd_kantor").value;
    const latitude = document.getElementById("lat").value;
    const longitude = document.getElementById("long").value;
    
    // ✅ DEVICE ID (LocalStorage)
    const device_id = getDeviceId();

    // 🔐 Fingerprint
    const raw = getFingerprintRaw();
    const fingerprint = await sha256(raw);

    fetch("<?php echo base_url("index.php/absen/proses_checkin"); ?>", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            device_id: device_id,      // ✅ dikirim
            fingerprint: fingerprint,  // ✅ dikirim
            latitude: latitude,
            longitude: longitude,
            kd_kantor: kd_kantor
        })
    })
    .then(res => res.json())
    .then(res => {
          if (res.status === "success") {
            Swal.fire({
                title: "Berhasil!",
                text: res.message,
                icon: "success",
                confirmButtonText: "OK"
            });
          
        }
        else{
            Swal.fire({
                title: "Error!",
                text: res.message,
                icon: "error",
                confirmButtonText: "OK"
            });
        }
    })
    .catch(() => {
          Swal.fire({
              title: "Error!",
              text: "Gagal Mengirim Data",
              icon: "error",
              confirmButtonText: "OK"
          });
    });
}

async function cek_out() {
    const status = document.getElementById("status");
    const kd_kantor = document.getElementById("kd_kantor").value;
    const latitude = document.getElementById("lat").value;
    const longitude = document.getElementById("long").value;
    
    // ✅ DEVICE ID (LocalStorage)
    const device_id = getDeviceId();

    // 🔐 Fingerprint
    const raw = getFingerprintRaw();
    const fingerprint = await sha256(raw);

    fetch("<?php echo base_url("index.php/absen/proses_checkout"); ?>", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            device_id: device_id,      // ✅ dikirim
            fingerprint: fingerprint,  // ✅ dikirim
            latitude: latitude,
            longitude: longitude,
            kd_kantor: kd_kantor
        })
    })
    .then(res => res.json())
    .then(res => {
          if (res.status === "success") {
            Swal.fire({
                title: "Berhasil!",
                text: res.message,
                icon: "success",
                confirmButtonText: "OK"
            });
          
        }
        else{
            Swal.fire({
                title: "Error!",
                text: res.message,
                icon: "error",
                confirmButtonText: "OK"
            });
        }
    })
    .catch(() => {
          Swal.fire({
              title: "Error!",
              text: "Gagal Mengirim Data",
              icon: "error",
              confirmButtonText: "OK"
          });
    });
}


</script>

<!-- <script>
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
</script> -->
