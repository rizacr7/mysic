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
	var x = document.getElementById("demo");
	
	$(document).ready(function(){
		<?php	if($_GET['loop'] == '1'){ ?>
				getLocation();	
		<?php	} ?>
	})


	var x = document.getElementById("demo");
	function getLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition);
	} else { 
		x.innerHTML = "Geolocation is not supported by this browser.";
	}
	}

	function showPosition(position) {
		var x = position.coords.longitude + ", " + position.coords.latitude;
		
		if(x != ""){
				window.location.href='<?php echo base_url(); ?>index.php/finger/view_gps?loop=0&latlong='+x;

		}else{
				window.location.href='<?php echo base_url(); ?>index.php/finger/view_gps?loop=1';
		}
	}
</script>
<script>
    var map, marker;
    var loading = document.getElementById("loading");
    var info = document.getElementById("info");

    // tampilkan spinner saat mulai load lokasi
    loading.style.display = "block";

    // Inisialisasi peta awal (pusat Indonesia)
    map = L.map('map').setView([-2.5489, 118.0149], 5);

    // Tambahkan layer peta OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap Contributors'
    }).addTo(map);

    // Ambil lokasi user
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        loading.style.display = "none";
        info.innerHTML = "Geolocation tidak didukung browser.";
    }

    function showPosition(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;

        // sembunyikan spinner
        loading.style.display = "none";

        info.innerHTML = "Latitude: " + lat + " | Longitude: " + lng;

        // Gerakkan peta ke lokasi user
        map.setView([lat, lng], 17);

        // Tambahkan marker
        marker = L.marker([lat, lng]).addTo(map)
            .bindPopup("<b>Lokasi Anda</b><br>Lat: " + lat + "<br>Lng: " + lng)
            .openPopup();
    }

    function showError(error) {
        loading.style.display = "none"; // sembunyikan spinner

        switch(error.code) {
            case error.PERMISSION_DENIED:
                info.innerHTML = "Izin lokasi ditolak.";
                break;
            case error.POSITION_UNAVAILABLE:
                info.innerHTML = "Lokasi tidak tersedia.";
                break;
            case error.TIMEOUT:
                info.innerHTML = "Permintaan lokasi timeout.";
                break;
            default:
                info.innerHTML = "Terjadi kesalahan mendeteksi lokasi.";
        }
    }
</script>

	