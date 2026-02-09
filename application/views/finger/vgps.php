<style>
#map { 
	height: 380px; 
	width: 325px;  
	border-radius: 8px;
}
</style>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Titik Koordinat Absensi</h6>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-body">
          <div class="form-group">
            <p id="demo"></p>
            <div id="loading" style="display:none; text-align:center; margin-bottom:5px;">
              <span class="spinner-border text-primary spinner-border-sm"></span> Mengambil lokasi...
            </div>
            <div id="map"></div>
            <p id="info"></p>
          </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var map, marker, watchId;
var loading = document.getElementById("loading");
var info = document.getElementById("info");
var gpsLocked = false; 

$(document).ready(function(){
    <?php if($_GET['loop'] == '1'){ ?>
        startGPS();
    <?php } ?>
});

function startGPS() {

    if (!navigator.geolocation) {
        info.innerHTML = "Geolocation tidak didukung browser.";
        return;
    }

    loading.style.display = "block";

    watchId = navigator.geolocation.watchPosition(
        showPosition,
        showError,
        {
            enableHighAccuracy: true,
            timeout: 20000,
            maximumAge: 0
        }
    );
}


function showPosition(position) {

    // Kalau sudah pernah dapat titik, jangan proses lagi
    if (gpsLocked) return;

    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    var acc = position.coords.accuracy;
    loading.style.display = "none";

    info.innerHTML = "Lat: " + lat + 
                     " | Lng: " + lng + 
                     " | Akurasi: ±" + Math.round(acc) + " m";

    // Update map
    if (!map) {
        map = L.map('map').setView([lat, lng], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap Contributors'
        }).addTo(map);

        marker = L.marker([lat, lng]).addTo(map);
    } else {
        map.setView([lat, lng], 17);
        marker.setLatLng([lat, lng]);
    }

    // Kalau akurasi sudah bagus
    if (acc <= 75) {

        gpsLocked = true; // tandai sudah dapat
        navigator.geolocation.clearWatch(watchId); // 🛑 stop total
        loading.style.display = "none";

        console.log("GPS dihentikan. Titik terkunci.");
    }
}


function showError(error) {

    if (gpsLocked) return;

    loading.style.display = "none";

    switch(error.code) {
        case error.PERMISSION_DENIED:
            info.innerHTML = "Izin lokasi ditolak.";
            break;
        case error.TIMEOUT:
            info.innerHTML = "GPS timeout.";
            break;
        default:
            info.innerHTML = "Terjadi kesalahan mendeteksi lokasi.";
    }
}


function retryGPS() {
    if (watchId) {
        navigator.geolocation.clearWatch(watchId);
    }
    setTimeout(function(){
        startGPS();
    }, 3000);
}
</script>

	