<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from designing-world.com/affan-v1.7/home.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 01 Mar 2025 15:09:07 GMT -->
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
  <!-- <link rel="icon" href="<?php echo base_url()?>assets/img/core-img/favicon.ico"> -->
  <link rel="icon" href="<?php echo base_url()?>assets/img/siclogo.png">
  <link rel="apple-touch-icon" href="<?php echo base_url()?>assets/img/icons/logo_mysic.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url()?>assets/img/icons/logo_mysic.png">
  <link rel="apple-touch-icon" sizes="167x167" href="<?php echo base_url()?>assets/img/icons/logo_mysic.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url()?>assets/img/icons/logo_mysic.png">

  <!-- Style CSS -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/style.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/vanilla-dataTables.min.css">

  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  <!-- Web App Manifest -->
  <link rel="manifest" href="<?php echo base_url()?>/manifest.json">

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
  <!-- <script src="<?php echo base_url()?>assets/js/pwa.js"></script> -->
  <script src="<?php echo base_url()?>/sw.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

  <!-- Header Area -->
  <div class="header-area" id="headerArea">
    <div class="container">
      <!-- Header Content -->
      <div class="header-content header-style-five position-relative d-flex align-items-center justify-content-between">
        <!-- Logo Wrapper -->
        <div class="logo-wrapper">
          <a href="#">
            <img src="<?php echo base_url()?>assets/img/siclogo.png" alt="">
          </a>
        </div>

        <!-- Page Title -->
       <div class="page-heading">
          <!-- <h6 class="mb-0">
              <span style="color:#EE3643;">My</span>
              <span style="color:#0C519D;"> SIC</span>
          </h6> -->
          <img src="<?php echo base_url()?>assets/img/logo_mysic.png" alt="" style="height:35px;">
          
       </div>
        
        <!-- Navbar Toggler -->
        <div class="navbar--toggler" id="affanNavbarToggler" data-bs-toggle="offcanvas" data-bs-target="#affanOffcanvas"
          aria-controls="affanOffcanvas">
          <span class="d-block"></span>
          <span class="d-block"></span>
          <span class="d-block"></span>
        </div>
      </div>
    </div>
  </div>
