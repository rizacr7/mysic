
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Inp. PJK SPPD Pegawai</h6>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-body">
          <div class="form-group">
              <div class="row">
                <div class="col-12 col-md-12">
                  <form id="formModal" onsubmit="return false">
                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">No.Pegawai</label>
                    <input class="form-control" id="no_peg" name="no_peg" type="text" value="<?php echo $Datapeg[0]->no_peg?>" readonly>
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Nm.Pegawai</label>
                     <input class="form-control" id="na_peg" name="na_peg" type="text" value="<?php echo $Datapeg[0]->na_peg?>" readonly>
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Unit</label>
                     <input class="form-control" id="nm_unit" name="nm_unit" type="text" value="<?php echo $Datapeg[0]->nm_unit?>" readonly>
                  </div>
                  <div class="form-group" style="display:none">
                    <label class="form-label" for="exampleInputText">Unit</label>
                     <input class="form-control" id="kd_unit" name="kd_unit" type="text" value="<?php echo $Datapeg[0]->kd_unit?>" readonly>
                  </div>
                  <div class="form-group" style="display:none">
                    <label class="form-label" for="exampleInputText">Beban</label>
                     <input class="form-control" id="beban" name="beban" type="text" value="<?php echo $Datapeg[0]->kd_akun_unit?>" readonly>
                  </div>
                  <div class="form-group" style="display:none">
                    <label class="form-label" for="exampleInputText">Jab</label>
                     <input class="form-control" id="kd_jab" name="kd_jab" type="text" value="<?php echo $Datapeg[0]->kd_jab?>" readonly>
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">No.Ref SPPD</label>
                    <select class="pe-4 form-select form-select" name="bukti_sppd" id="bukti_sppd" onchange="getdatasppd()">
                      <option value="">--Pilih--</option>
                      <?php
                        $query = "SELECT BUKTI,DALAM_RANGKA as keterangan FROM t_sppd WHERE no_peg = '".$Datapeg[0]->no_peg."' AND APPROVE = '1' AND HAPUS = 0 AND STS_PJK = 0";
                        $refsppd = $this->db_hrdonline->query($query)->result();
                        foreach ($refsppd as $row) {
                          echo "<option value='".$row->BUKTI."'>".$row->BUKTI." - ".$row->keterangan."</option>";
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Tgl.Awal Sppd</label>
                     <input class="form-control" id="tgl_awal" name="tgl_awal" type="date">
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Tgl.Akhir Sppd</label>
                     <input class="form-control" id="tgl_akhir" name="tgl_akhir" type="date">
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Jam Berangkat</label>
                     <input class="form-control" id="jamberangkat" name="jamberangkat" type="time">
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Jam Kedatangan</label>
                     <input class="form-control" id="jampulang" name="jampulang" type="time">
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Kota Tujuan</label>
                    <input class="form-control" id="tujuan" name="tujuan" type="text">
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Tujuan Khusus</label>
                    <select class="pe-4 form-select form-select" name="khusus" id="khusus">
                      <option value="0">--Pilih--</option>
                      <option value="0">Tidak</option>
                      <option value="1">Ya</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Keperluan</label>
                    <select class="pe-4 form-select form-select" name="keperluan" id="keperluan">
                      <option value="">--Pilih--</option>
                      <option value="DINAS">Dinas</option>
                      <option value="PENDIDIKAN">Pendidikan</option>
                      <option value="PROYEK">Proyek</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Kendaraan</label>
                    <select class="pe-4 form-select form-select" name="kendaraan" id="kendaraan">
                        <option value="">--Pilih--</option>
                        <option value="1">Dinas</option>
                        <option value="2">Kereta Api</option>
                        <option value="3">Bus</option>
                        <option value="4">Kapal</option>
                        <option value="5">Pesawat</option>
                        <option value="6">lain-lain</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Akomodasi</label>
                    <select class="pe-4 form-select form-select" name="akomodasi" id="akomodasi">
                        <option value="">--Pilih--</option>
                        <option value="2">Luar</option>
                        <option value="3">Mess</option>
                        <option value="1">Hotel</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Kas Keluar</label>
                    <select class="pe-4 form-select form-select" name="kas_keluar" id="kas_keluar" onchange="getkabiaya()">
                      <option value="">--Pilih--</option>
                      <option value="1">Kas Kantor Pusat</option>
                      <option value="2">Kas Unit</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Beban</label>
                    <select class="pe-4 form-select form-select" name="beban" id="beban">
                      <option value="">--Pilih--</option>
                      <?php
                        $query = "SELECT kd_akun_unit,nm_unit FROM m_unit WHERE is_del = 0 AND  kd_akun_unit IS NOT NULL ORDER BY kd_akun_unit";
                        $bebanunit = $this->db_hrdonline->query($query)->result();
                        foreach ($bebanunit as $row) {
                          echo "<option value='".$row->kd_akun_unit."'>".$row->kd_akun_unit." | ".$row->nm_unit."</option>";
                        }
                      ?>
                    </select>
                  </div>

                   <div class="form-group" style="display:none" id="divkabiaya">
                    <label class="form-label" for="exampleInputText">Koordinator Biaya</label>
                    <select class="pe-4 form-select form-select" name="ka_biaya" id="ka_biaya">
                      <option value="">--Pilih--</option>
                      <?php
                        $query = "SELECT kd_akun_unit,nm_unit FROM m_unit WHERE is_del = 0 AND  kd_akun_unit IS NOT NULL ORDER BY kd_akun_unit";
                        $bebanunit = $this->db_hrdonline->query($query)->result();
                        foreach ($bebanunit as $row) {
                          echo "<option value='".$row->kd_akun_unit."'>".$row->kd_akun_unit." | ".$row->nm_unit."</option>";
                        }
                      ?>
                    </select>
                  </div>


                  <div class="form-group">
                  <label class="form-label" for="exampleInputText">Keterangan</label>
                  <textarea class="form-control" id="keterangan" name="keterangan" cols="3" rows="5"></textarea>
                  </div>
                </div>
              </div>
              <p></p>
              <div class="row">
                <div class="col-12 col-md-12">
                  <div class="spinner-border text-primary" role="status" style="display:none" align="center" id="loading">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                  <button type="button" class="btn btn-primary w-100" onclick="simpan()" id="btnsimpan">Simpan</button>
                  </div>
                </div>
                </form>
            </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

    function getdatasppd(){
		 $.ajax({
			url: "<?php echo base_url(); ?>index.php/sdm/getdatasppd",
			data : "bukti_sppd="+$('#bukti_sppd option:selected').val(),
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				$('[name="tujuan"]').val(data.TUJUAN);
				$('[name="keperluan"]').val(data.KEPERLUAN);
				$('[name="kendaraan"]').val(data.KENDARAAN);
        $('[name="keterangan"]').val(data.DALAM_RANGKA);
				$('[name="tgl_awal"]').val(data.TGL_AWAL);
				$('[name="tgl_akhir"]').val(data.TGL_AKHIR);
				$('[name="akomodasi"]').val(data.AKOMODASI);
        $('[name="beban"]').val(data.BEBAN);
				
				// hitungbiayaawal();
			}   
		});
	}

  function getkabiaya(){
    var kaskeluar = $('#kas_keluar option:selected').val();
    if(kaskeluar == "2"){
      $('#divkabiaya').show();
    }
    else{
      $('#divkabiaya').hide();
      $('#ka_biaya').val('');
    }
  }

  function simpan() {
		data = $('#formModal').serialize();
    $("#btnsimpan").hide();
		dataurl = "<?php echo base_url(); ?>index.php/sdm/ins_ppdpegawai";
		$.ajax({
			url: dataurl,
			data: data,
			type: "POST",
			beforeSend: function () {
				if ($('[name="no_peg"]').val() == '') {
					Swal.fire({
              title: "Error!",
              text: "Nomer Pegawai Kosong",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
					return false;
				}
				if ($('[name="tgl_awal"]').val() == '' || $('[name="tgl_akhir"]').val() == '') {
					Swal.fire({
              title: "Error!",
              text: "Tgl.SPPD Harus Diisi",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
					return false;
				}
        if ($('[name="jamberangkat"]').val() == '' || $('[name="jampulang"]').val() == '') {
					Swal.fire({
              title: "Error!",
              text: "Jam Berangkat dan Jam Pulang Harus Diisi",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
					return false;
				}
				if ($('[name="keterangan"]').val() == '') {
						Swal.fire({
              title: "Error!",
              text: "Keterangan Harus Diisi",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
					return false;
				}
        if ($('[name="kas_keluar"]').val() == '') {
						Swal.fire({
              title: "Error!",
              text: "Kas Keluar Harus Diisi",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
					return false;
				}
         if ($('[name="kas_keluar"]').val() == '2' && $('[name="ka_biaya"]').val() == '') {
						Swal.fire({
              title: "Error!",
              text: "Koordinator Biaya Harus Diisi",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
					return false;
				}
        if ($('[name="akomodasi"]').val() == '') {
						Swal.fire({
              title: "Error!",
              text: "Akomodasi Harus Diisi",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
					return false;
				}
        if ($('[name="kendaraan"]').val() == '') {
						Swal.fire({
              title: "Error!",
              text: "Kendaraan Harus Diisi",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
					return false;
				}
        $("#loading").show();
			},
			success: function (res) {
				if (res == 1) {
          Swal.fire({
              title: "Sukses!",
              text: "Input PJK SPPD Berhasil!",
              icon: "success",
              confirmButtonText: "OK"
          });
					window.location.href='<?php echo base_url(); ?>index.php/sdm/inp_pjksppd';
          $("#loading").hide();
				} else if (res == 2) {
          Swal.fire({
              title: "Error!",
              text: "Tanggal Akhir SPPD Tidak Boleh Kurang Dari Tanggal Awal SPPD",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
          $("#loading").hide();
          return false;
				}  else if (res == 4) {
          Swal.fire({
              title: "Error!",
              text: "Data Akomodasi PJK tidak sama dengan Data Akomodasi SPPD",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
          $("#loading").hide();
          return false;
				} else if (res == 3) {
          Swal.fire({
              title: "Error!",
              text: "Data Booking Belum Dientri Oleh Sekper",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
          $("#loading").hide();
          return false;
				} 
			}
		});
	}
</script>
	