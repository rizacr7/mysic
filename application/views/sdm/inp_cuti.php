
<div class="page-content-wrapper py-3">
  <!-- Element Heading -->
  <div class="container">
    <div class="element-heading">
      <h6>Inp.Cuti Pegawai</h6>
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
                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Saldo Cuti</label>
                     <input class="form-control" id="saldo_cuti" name="saldo_cuti" type="text" readonly>
                  </div>
                  
                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Tgl.Awal Cuti</label>
                     <input class="form-control" id="tgl_cuti_awal" name="tgl_cuti_awal" type="date">
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Tgl.Akhir Cuti</label>
                     <input class="form-control" id="tgl_cuti_akhir" name="tgl_cuti_akhir" type="date">
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="exampleInputText">Jenis</label>
                    <select class="pe-4 form-select form-select" name="kdcuti" id="kdcuti">
                      <option value="CT">CUTI</option>
                      <option value="DC">DC</option>
                      <option value="DP">DISPENSASI</option>
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
	hitungsaldo();

  function hitungsaldo(){
		 $.ajax({
			url: "<?php echo base_url(); ?>index.php/sdm/ajaxdtsaldocuti",
			data : "no_peg="+$('#no_peg').val(),
			type: "GET",
			dataType: "JSON",
			success: function(res)
			{
				$('[name="saldo_cuti"]').val(res);
			}   
		});
	}

  function simpan() {
		data = $('#formModal').serialize();
    $("#btnsimpan").hide();
		dataurl = "<?php echo base_url(); ?>index.php/sdm/ins_cutipegawai";
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
				if ($('[name="tgl_cuti_awal"]').val() == '' || $('[name="tgl_cuti_akhir"]').val() == '') {
					Swal.fire({
              title: "Error!",
              text: "Tgl.Cuti Harus Diisi",
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
              text: "Pengajuan Cuti Berhasil!",
              icon: "success",
              confirmButtonText: "OK"
          });
					window.location.href='<?php echo base_url(); ?>index.php/sdm/inpcuti';
          $("#loading").hide();
				} else if (res == 2) {
          Swal.fire({
              title: "Error!",
              text: "Saldo Cuti Tidak Cukup",
              icon: "error",
              confirmButtonText: "OK"
          });
          return false;
          $("#loading").hide();
				} 
			}
		});
	}

</script>
	