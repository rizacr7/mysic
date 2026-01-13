
  <!-- Footer Nav -->
  <div class="footer-nav-area" id="footerNav">
    <div class="container px-0">
      <!-- Footer Content -->
      <div class="footer-nav position-relative">
        <ul class="h-100 d-flex align-items-center justify-content-between ps-0">
           <li class="active">
            <a href="<?php echo base_url('index.php/welcome/sukses'); ?>">
              <i class="bi bi-house"></i>
              <span>Home</span>
            </a>
          </li>

          <li>
            <a href="<?php echo base_url('index.php/finger/view_dtfinger'); ?>">
              <i class="bi bi-calendar2"></i>
              <span>View</span>
            </a>
          </li>
          <li>
            <a href="<?php echo base_url('index.php/profile/profilepegawai'); ?>">
              <i class="bi bi-person"></i>
              <span>Profile</span>
            </a>
          </li>
          <li>
            <a href="<?php echo base_url(); ?>index.php/sdm/pagemenu">
              <i class="bi bi-list-task"></i>
              <span>All Menu</span>
            </a>
          </li>

          <li>
            <a href="<?php echo base_url('index.php/welcome/logout'); ?>">
              <i class="bi bi-box-arrow-right"></i>
              <span>Log Out</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>

</body>


<!-- Mirrored from designing-world.com/affan-v1.7/home.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 01 Mar 2025 15:09:21 GMT -->
</html>

<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('<?= base_url('sw.js') ?>')
      .then(reg => console.log('SW registered', reg.scope))
      .catch(err => console.error('SW error', err));
  });
}
</script>