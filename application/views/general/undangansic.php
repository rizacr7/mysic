<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>

<!-- Page Content Wrapper -->
  <div class="page-content-wrapper py-3">
    <div class="custom-container">
      <div class="card">
        <div class="card-body px-5 text-center">
          <?php 
            $queryhadiah = "SELECT * FROM peserta WHERE no_peg = '".$this->session->userdata('username')."' and is_checkin = 0";
            $rdt = $this->db_undian->query($queryhadiah)->num_rows();
            if($rdt != 0){
                $data = $this->db_undian->query($queryhadiah)->result();
                $nama = $data[0]->nama;
                $no_peg = $data[0]->no_peg;
               
          ?>
          <h4>Selamat Datang <br> <?php echo $nama?></h4>

          <div id="qrcode" class="my-3"></div>
          <p class="text-muted">Tunjukkan QR Code ini saat masuk ke Gedung Wisma</p>
          <?php } else {?>
          
          <h4>Anda sudah melakukan check-in</h4>
          <?php } ?>
          <div class="mt-4">
          <a class="btn btn-creative btn-danger" href="<?php echo base_url('index.php/welcome/sukses'); ?>">Go to Home</a>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">
	$(document).ready(function(){
        var noPeg = "<?php echo $no_peg; ?>";
    
        $('#qrcode').qrcode({
            width: 200,
            height: 200,
            text: noPeg
        });
    });
</script>
	