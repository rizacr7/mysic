<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from designing-world.com/affan-v1.7/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 01 Mar 2025 15:10:06 GMT -->
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Affan - PWA Mobile HTML Template">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

  <meta name="theme-color" content="#0134d4">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <!-- Title -->
  <title>My SIC</title>

  <!-- Favicon -->
  <link rel="icon" href="<?php echo base_url()?>assets/img/siclogo.png">
  <link rel="apple-touch-icon" href="img/icons/logo_mysic.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url()?>assets/img/icons/logo_mysic.png">
  <link rel="apple-touch-icon" sizes="167x167" href="<?php echo base_url()?>assets/img/icons/logo_mysic.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url()?>assets/img/icons/logo_mysic.png">

  <!-- Style CSS -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/style.css">
  <!-- Web App Manifest -->
  <link rel="manifest" href="<?php echo base_url()?>/manifest.json">
  
</head>

<body>
  <!-- Preloader -->
  <div id="preloader">
    <div class="spinner-grow text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <!-- Internet Connection Status -->
  <div class="internet-connection-status" id="internetStatus"></div>

  <!-- Login Wrapper Area -->
  <div class="login-wrapper d-flex align-items-center justify-content-center">
    <div class="custom-container">
      <div class="text-center px-4">
        <img class="login-intro-img" src="<?php echo base_url()?>assets/img/logo_mysic.png" alt="">
      </div>

      <!-- Register Form -->
      <div class="register-form mt-4">
        <h5 class="mb-3 text-center">Log in to the  
          <span style="color:#EE3643;">My</span>
          <span style="color:#0C519D;">SIC</span>
        </h5>

        <form action="<?php echo site_url('Welcome/proses_login'); ?>" method="post" class="login100-form validate-form" >
        <?php
        if (validation_errors() || $this->session->flashdata('result_login')) {
          ?>
          <div class="alert custom-alert-one alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle"></i>
            <?php echo validation_errors(); ?>
            <?php echo $this->session->flashdata('result_login'); ?>
            <button class="btn btn-close position-relative p-1 ms-auto" type="button" data-bs-dismiss="alert"
            aria-label="Close"></button>
          </div>

        <?php } ?>
          <div class="form-group">
            <input class="form-control" type="text" id="username" name="username" placeholder="Username">
          </div>

          <div class="form-group position-relative">
            <input class="form-control" id="password" name="password" type="password" placeholder="Enter Password">
            <!-- <div class="position-absolute" id="password-visibility">
              <i class="bi bi-eye"></i>
              <i class="bi bi-eye-slash"></i>
            </div> -->
          </div>

          <button class="btn btn-primary w-100" type="submit">Sign In</button>
        </form>
        <p></p>
        <div class="login-meta-data text-center">
          <p class="mt-3 mb-0">Khusus Untuk Pengguna Android Klik Install App</p>
        </div>
        <p></p>
        <button class="btn btn-outline-danger w-100" id="installApp">Install App My SIC</button>
      </div>

    </div>
  </div>

  <!-- All JavaScript Files -->
  <script src="<?php echo base_url()?>assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo base_url()?>assets/js/slideToggle.min.js"></script>
  <script src="<?php echo base_url()?>assets/js/internet-status.js"></script>
  <script src="<?php echo base_url()?>assets/js/tiny-slider.js"></script>
  <script src="<?php echo base_url()?>assets/js/venobox.min.js"></script>
  <script src="<?php echo base_url()?>assets/js/countdown.js"></script>
  <script src="<?php echo base_url()?>assets/js/rangeslider.min.js"></script>
  <script src="<?php echo base_url()?>assets/js/vanilla-dataTables.min.js"></script>
  <script src="<?php echo base_url()?>assets/js/index.js"></script>
  <script src="<?php echo base_url()?>assets/js/imagesloaded.pkgd.min.js"></script>
  <script src="<?php echo base_url()?>assets/js/isotope.pkgd.min.js"></script>
  <script src="<?php echo base_url()?>assets/js/dark-rtl.js"></script>
  <script src="<?php echo base_url()?>assets/js/active.js"></script>
  <script src="<?php echo base_url()?>/sw.js"></script>
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

</body>


<!-- Mirrored from designing-world.com/affan-v1.7/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 01 Mar 2025 15:10:06 GMT -->
</html>