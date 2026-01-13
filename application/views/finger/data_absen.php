
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Data Absensi</h6>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-body">
          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-12">
                  <select name="bulan" id="bulan" class="pe-4 form-select form-select">
                    <option <?php 
                    if(isset($_GET['bulan'])){
                      if($_GET['bulan']=="01"){
                        echo "selected=\"selected\"";
                      }
                    }  
                    else{
                      if(date('m') == '01') echo "selected=\"selected\"";
                    }
                    ?>value="01">JANUARI
                    <option <?php 
                    if(isset($_GET['bulan'])){
                      if($_GET['bulan']=="02"){
                        echo "selected=\"selected\"";
                      }
                    }  
                    else{
                      if(date('m') == '02') echo "selected=\"selected\"";
                    }
                    ?>value="02">FEBRUARI
                    <option <?php 
                    if(isset($_GET['bulan'])){
                      if($_GET['bulan']=="03"){
                        echo "selected=\"selected\"";
                      }
                    }  
                    else{
                      if(date('m') == '03') echo "selected=\"selected\"";
                    }
                    ?>value="03">MARET
                    <option <?php 
                    if(isset($_GET['bulan'])){
                      if($_GET['bulan']=="04"){
                        echo "selected=\"selected\"";
                      }
                    }  
                    else{
                      if(date('m') == '04') echo "selected=\"selected\"";
                    }
                    ?>value="04">APRIL
                    <option <?php 
                    if(isset($_GET['bulan'])){
                      if($_GET['bulan']=="05"){
                        echo "selected=\"selected\"";
                      }
                    }  
                    else{
                      if(date('m') == '05') echo "selected=\"selected\"";
                    }
                    ?>value="05">MEI
                    <option <?php 
                    if(isset($_GET['bulan'])){
                      if($_GET['bulan']=="06"){
                        echo "selected=\"selected\"";
                      }
                    }  
                    else{
                      if(date('m') == '06') echo "selected=\"selected\"";
                    }
                    ?>value="06">JUNI
                    <option <?php 
                    if(isset($_GET['bulan'])){
                      if($_GET['bulan']=="07"){
                        echo "selected=\"selected\"";
                      }
                    }  
                    else{
                      if(date('m') == '07') echo "selected=\"selected\"";
                    }
                    ?>value="07">JULI
                    <option <?php 
                    if(isset($_GET['bulan'])){
                      if($_GET['bulan']=="08"){
                        echo "selected=\"selected\"";
                      }
                    }  
                    else{
                      if(date('m') == '08') echo "selected=\"selected\"";
                    }
                    ?>value="08">AGUSTUS
                    <option <?php 
                    if(isset($_GET['bulan'])){
                      if($_GET['bulan']=="09"){
                        echo "selected=\"selected\"";
                      }
                    }  
                    else{
                      if(date('m') == '09') echo "selected=\"selected\"";
                    }
                    ?>value="09">SEPTEMBER
                    <option <?php 
                    if(isset($_GET['bulan'])){
                      if($_GET['bulan']=="10"){
                        echo "selected=\"selected\"";
                      }
                    }  
                    else{
                      if(date('m') == '10') echo "selected=\"selected\"";
                    }
                    ?>value="10">OKTOBER
                    <option <?php 
                    if(isset($_GET['bulan'])){
                      if($_GET['bulan']=="11"){
                        echo "selected=\"selected\"";
                      }
                    }  
                    else{
                      if(date('m') == '11') echo "selected=\"selected\"";
                    }
                    ?>value="11">NOVEMBER
                    <option <?php 
                    if(isset($_GET['bulan'])){
                      if($_GET['bulan']=="12"){
                        echo "selected=\"selected\"";
                      }
                    }  
                    else{
                      if(date('m') == '12') echo "selected=\"selected\"";
                    }
                    ?>value="12">DESEMBER
                    
                  </select>
                </div>
              </div>
              <p></p>
              <div class="row">
                <div class="col-12 col-md-12">
                <?php
                if(isset($_GET['tahun'])){
                  $tahun = $_GET['tahun'];
                }  
                else{
                  $_GET['tahun']=date('Y');
                }
                ?>
                <input type="text" placeholder="TAHUN" name='tahun' id='tahun' class="form-control" required="true" onkeyup="uppercase(this)" value="<?php echo $_GET['tahun']?>">	
                </div>
              </div>
              <p></p>
              <div class="row">
                <div class="col-12 col-md-12">
                  <button type="button" class="btn btn-primary w-100" onclick="viewdt()">View</button>
                </div>
              </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-12">
            
                  <div class="spinner-grow text-primary" role="status" id='loading'style="display: none">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-md-12">
                <div class="table-responsive">
                  <div class="box-body" id="div_tabel_data"></div>
                </div>
              </div>
            </div>
            
          </div>


      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	get_data_tabel();
	
	function get_data_tabel() {
		var tahun = $('#tahun').val();
		var bulan = $('#bulan').val();
		$.ajax({
			data : "tahun="+tahun+"&bulan="+bulan,  
			type:"POST",
			url: '<?php echo base_url(); ?>index.php/finger/tab_finger',
			beforeSend:function(){
				$('#loading').show();
			}, 
			success: function (res) {
				$("#div_tabel_data").html(res);
				$('#loading').hide();
			}
		});
	}

	function viewdt() {
      var tahun = $('#tahun').val();
      var bulan = $('#bulan').val();
      $.ajax({
			data : "tahun="+tahun+"&bulan="+bulan,  
			type:"POST",
      url: '<?php echo base_url(); ?>index.php/finger/tab_finger',
			beforeSend:function(){
				$('#loading').show();
			}, 
      success: function (res) {
      $("#div_tabel_data").html(res);
			$('#loading').hide();
            }
        });
		
    }
    
</script>
	