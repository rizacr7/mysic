
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Form Permintaan Kendaraan Pool</h6>
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
                    <label class="form-label" for="exampleInputText">Tujuan</label>
                     <input class="form-control" id="tujuan" name="tujuan" type="text">
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Lokasi Jemput</label>
                     <input class="form-control" id="tmpt_berangkat" name="tmpt_berangkat" type="text">
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Jumlah Penumpang</label>
                     <input class="form-control" id="jmlh_penumpang" name="jmlh_penumpang" type="number">
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Keperluan</label>
                    <select class="pe-4 form-select form-select" name="keperluan" id="keperluan">
                      <option value="DINAS">DINAS</option>
											<option value="NON.DINAS">NON DINAS</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Tgl.Berangkat</label>
                     <input class="form-control" id="tgl_berangkat" name="tgl_berangkat" type="date">
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Jam Berangkat</label>
                     <input class="form-control" id="jam_berangkat" name="jam_berangkat" type="time">
                  </div>

                   <div class="form-group">
                    <label class="form-label" for="exampleInputText">Tgl.Kedatangan</label>
                     <input class="form-control" id="tgl_tiba" name="tgl_tiba" type="date">
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Jam Kedatangan</label>
                     <input class="form-control" id="jam_tiba" name="jam_tiba" type="time">
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
		dataurl = "<?php echo base_url(); ?>index.php/umum/ins_permintaankendaraan";
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
				if ($('[name="tgl_berangkat"]').val() == '') {
					Swal.fire({
              title: "Error!",
              text: "Tanggal Berangkat Harus Diisi",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
					return false;
				}
        if ($('[name="tujuan"]').val() == '') {
					Swal.fire({
              title: "Error!",
              text: "Tujuan Harus Diisi",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
					return false;
				}
        if ($('[name="tmpt_berangkat"]').val() == '') {
					Swal.fire({
              title: "Error!",
              text: "Lokasi Jemput Harus Diisi",
              icon: "error",
              confirmButtonText: "OK"
          });
          $("#btnsimpan").show();
					return false;
				}
        if ($('[name="jmlh_penumpang"]').val() == '') {
					Swal.fire({
              title: "Error!",
              text: "Jumlah Penumpang Harus Diisi",
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
					window.location.href='<?php echo base_url(); ?>index.php/umum/request_kendaraan';
          $("#loading").hide();
				} else if (res == 2) {
          Swal.fire({
              title: "Error!",
              text: "Tanggal Tiba Kurang Dari Tanggal Berangkat",
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
	