
  <!-- # Sidenav Left -->
  <div class="offcanvas offcanvas-start" id="affanOffcanvas" data-bs-scroll="true" tabindex="-1"
    aria-labelledby="affanOffcanvsLabel">

    <button class="btn-close btn-close-white text-reset" type="button" data-bs-dismiss="offcanvas"
      aria-label="Close"></button>

    <div class="offcanvas-body p-0">
      <div class="sidenav-wrapper">
        <!-- Sidenav Profile -->
        <div class="sidenav-profile bg-gradient">
          <div class="sidenav-style1"></div>

          <?php
          $query = "select a.*,b.nm_unit,c.nm_statpeg,d.nm_jab,b.kd_akun_unit,e.nm_job,FLOOR(DATEDIFF(CURDATE(), a.tgl_lahir) / 365) AS umur from mas_peg a 
          left join m_unit b on a.kd_unit = b.kd_unit  
          left join m_statuspegawai c on a.status_peg = c.kd_statpeg
          left join m_jabatan d on a.kd_jab = d.kd_jab
          left join m_jobdesc e on a.kd_job = e.kd_job
          where a.no_peg = '".$this->session->userdata('username')."'";
          $Datapeg = $this->db->query($query)->result();

          $image_url ="https://hrkita.sic.co.id/foto_pegawai/" . $Datapeg[0]->foto_pegawai;

          if($Datapeg[0]->foto_pegawai == "") {
              if($Datapeg[0]->sex == "P"){
                $image_url = base_url() . "assets/img/bg-img/user2.png"; 
              }
              else{
                $image_url = base_url() . "assets/img/bg-img/user1.png"; 
              }
          }
          
          ?>
          <!-- User Thumbnail -->
          <div class="user-profile">
            <img src="<?php echo $image_url; ?>" alt="">
          </div>

          <!-- User Info -->
          <div class="user-info">
            <h6 class="user-name mb-0"><?php echo $this->session->userdata('nama')?></h6>
            <span><?php echo $this->session->userdata('username')?></span>
          </div>
        </div>

        <!-- Sidenav Nav -->
        <ul class="sidenav-nav ps-0">
          <li>
            <a href="<?php echo base_url('index.php/welcome/sukses'); ?>"><i class="bi bi-house-door"></i> Home</a>
          </li>
          <li>
            <a href="<?php echo base_url('index.php/profile/profilepegawai'); ?>"><i class="bi bi-person"></i> Profile
            </a>
          </li>
          <li>
            <a href="<?php echo base_url('index.php/finger/view_dtfinger'); ?>"><i class="bi bi-calendar2"></i> View Absensi
            </a>
          </li>
          <li>
            <a href="<?php echo base_url(); ?>index.php/finger/view_gps?loop=1"><i class="bi bi-pin-map"></i> Maps
            </a>
          </li>
          <li>
            <a href="<?php echo base_url(); ?>index.php/sdm/pageapprove"><i class="bi bi-check-square-fill"></i> Approve Data
            </a>
          </li>
          <li>
            <a href="#"><i class="bi bi-backpack"></i> Cuti Pegawai</a>
            <ul>
              <li>
                <a href="<?php echo base_url('index.php/sdm/inpcuti'); ?>"> Input Cuti</a>
              </li>
              <li>
                <a href="<?php echo base_url('index.php/sdm/datacuti'); ?>"> Data Cuti</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#"><i class="bi bi-airplane"></i> SPPD Pegawai</a>
            <ul>
              <li>
                <a href="<?php echo base_url('index.php/sdm/inpsppd'); ?>"> Input SPPD</a>
              </li>
              <li>
                <a href="<?php echo base_url('index.php/sdm/datasppd'); ?>"> Data SPPD</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#"><i class="bi bi-building"></i> PJK SPPD Pegawai</a>
            <ul>
              <li>
                <a href="<?php echo base_url('index.php/sdm/inp_pjksppd'); ?>"> Input PJK SPPD</a>
              </li>
              <li>
                <a href="<?php echo base_url('index.php/sdm/datapjksppd'); ?>"> Data PJK SPPD</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#"><i class="bi bi-ticket"></i> Izin Pegawai</a>
            <ul>
              <li>
                <a href="<?php echo base_url('index.php/sdm/inpizin'); ?>"> Form Izin</a>
              </li>
              <li>
                <a href="<?php echo base_url('index.php/sdm/dataizin'); ?>"> Data Izin</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="<?php echo base_url('index.php/welcome/logout'); ?>"><i class="bi bi-box-arrow-right"></i> Logout</a>
          </li>
        </ul>

        <!-- Social Info -->
        <!-- <div class="social-info-wrap">
          <a href="#">
            <i class="bi bi-facebook"></i>
          </a>
          <a href="#">
            <i class="bi bi-twitter"></i>
          </a>
          <a href="#">
            <i class="bi bi-linkedin"></i>
          </a>
        </div> -->

        <!-- Copyright Info -->
        <div class="copyright-info">
          <p>
            <span id="copyrightYear"></span>
            &copy; Made by <a href="#"> SIC Software Development</a>
          </p>
        </div>
      </div>
    </div>
  </div>
