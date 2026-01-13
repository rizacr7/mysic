
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Form Izin Pegawai</h6>
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
                    <label class="form-label" for="exampleInputText">Jabatan</label>
                     <input class="form-control" id="kd_jab" name="kd_jab" type="text" value="<?php echo $Datapeg[0]->kd_jab?>" readonly>
                  </div>
                  
                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Tgl.Izin</label>
                     <input class="form-control" id="tgl_izin" name="tgl_izin" type="date">
                  </div>
                 
                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Kd.Izin</label>
                    <select class="pe-4 form-select form-select" name="kdizin" id="kdizin">
                      <option value="1">TERLAMBAT</option>
                      <option value="2">PULANG CEPAT</option>
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
	
  function simpan() {
		data = $('#formModal').serialize();
    $("#btnsimpan").hide();
		dataurl = "<?php echo base_url(); ?>index.php/sdm/ins_izinpegawai";
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
				if ($('[name="tgl_izin"]').val() == '') {
					Swal.fire({
              title: "Error!",
              text: "Tanggal Harus Diisi",
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
        $("#loading").show();
			},
			success: function (res) {
				if (res == 1) {
          Swal.fire({
              title: "Sukses!",
              text: "Data Berhasil Disimpan",
              icon: "success",
              confirmButtonText: "OK"
          });
					window.location.href='<?php echo base_url(); ?>index.php/sdm/inpizin';
          $("#loading").hide();
				} else if (res == 2) {
          Swal.fire({
              title: "Error!",
              text: "Data Izin untuk tanggal ini sudah ada",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#loading").hide();
          $("#btnsimpan").show();   
				} 
			}
		});
	}

</script>
	