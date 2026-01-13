<?php
class Func_global extends CI_Model{
	function __construct(){
		$this->load->database();
	}
	
	function tgl_dsql($data) {
		if ($data != "") {
			$str = explode("-", $data);
			$tgl = $str[0];
			$bln = $str[1];
			$thn = $str[2];
			$hasil = $thn . "-" . $bln . "-" . $tgl;
			return $hasil;
		}
	}
	
	function tgl_dsql_excel($data) {
		if ($data != "") {
			$str = explode("/", $data);
			$tgl = $str[0];
			$bln = $str[1];
			$thn = $str[2];
			$hasil = $thn . "-" . $bln . "-" . $tgl;
			return $hasil;
		}
	}
	
	function day($data){
		if($data !=""){
			$str = explode("-",$data);
			$hari= $str[2];
			return $hari;
		}
	}
	
	function koma($data) {
		return round($data, 2);
	}
	
	function bulan($data){
	        if($data != ""){
	            $str = explode("-",$data);
	            $thn = $str[0];
	            $bln = $str[1];
	            $hasil = $bln.$thn;
	            return $hasil;
	        }
	}
	
	function month($data){
	        if($data !=""){
	            $str = explode("-",$data);
	            $bln = $str[1];
	            return $bln;
	        }
	}
	
	function year($data){
	        if($data !=""){
	            $str = explode("-",$data);
	            $thn = $str[0];
	            return $thn;
	        }
	}
	
	function year_ori($data){
	        if($data !=""){
	            $str = explode("-",$data);
	            $thn = $str[2];
	            return $thn;
	        }
	}
	
	
	function tgl_ind($data){
	        if($data !=""){
	            $str = explode("-",$data);
	            $tgl = $str[2];
	            return $tgl;
	        }
	}
	
	function bulanThn($data){
        if($data != ""){
            $str = explode("-",$data);
            $thn = $str[0];
            $bln = $str[1];
            $hasil = $thn . "-" . $bln . "-";
            return $hasil;
        }
	}
	
	
	function ind_tgl($data) {
		if ($data != "") {
			$str = explode("-", $data);
			$bln1 = $str[1];
			if ($bln1 == '01') {
				$bln = "JANUARI";
			} else if ($bln1 == '02') {
				$bln = "FEBRUARI";
			} else if ($bln1 == '03') {
				$bln = "MARET";
			} else if ($bln1 == '04') {
				$bln = "APRIL";
			} else if ($bln1 == '05') {
				$bln = "MEI";
			} else if ($bln1 == '06') {
				$bln = "JUNI";
			} else if ($bln1 == '07') {
				$bln = "JULI";
			} else if ($bln1 == '08') {
				$bln = "AGUSTUS";
			} else if ($bln1 == '09') {
				$bln = "SEPTEMBER";
			} else if ($bln1 == '10') {
				$bln = "OKTOBER";
			} else if ($bln1 == '11') {
				$bln = "NOVEMBER";
			} else if ($bln1 == '12') {
				$bln = "DESEMBER";
			}
			$hasil = $bln;
			return $hasil;
		}
	}
	
	
	function dsql_tgl($data) {
		if ($data != "") {
			$str = explode("-", $data);
			$tgl = $str[2];
			$bln = $str[1];
			$thn = $str[0];
			$hasil = $tgl . "-" . $bln . "-" . $thn;
			return $hasil;
		}
	}
	
	function dtsql_tgl($data) {
		if ($data != "") {
			$str1 = explode(" ", $data);
			$tgl1 = $str1[0];
			$hasil = $this -> dsql_tgl($tgl1);
			return $hasil;
		}
	}
	
	function dsql_tglfull($data) {
		if ($data != "") {
			$str = explode("-", $data);
			$tgl = $str[2];
			$bln1 = $str[1];
			if ($bln1 == '01') {
				$bln = "Januari";
			} else if ($bln1 == '02') {
				$bln = "Februari";
			} else if ($bln1 == '03') {
				$bln = "Maret";
			} else if ($bln1 == '04') {
				$bln = "April";
			} else if ($bln1 == '05') {
				$bln = "Mei";
			} else if ($bln1 == '06') {
				$bln = "Juni";
			} else if ($bln1 == '07') {
				$bln = "Juli";
			} else if ($bln1 == '08') {
				$bln = "Agustus";
			} else if ($bln1 == '09') {
				$bln = "September";
			} else if ($bln1 == '10') {
				$bln = "Oktober";
			} else if ($bln1 == '11') {
				$bln = "November";
			} else if ($bln1 == '12') {
				$bln = "Desember";
			}
			$thn = $str[0];
			$hasil = $tgl . " " . $bln . " " . $thn;
			return $hasil;
		}
	}
	
	function dtsql_tglfull($data) {
		if ($data != "") {
			$str1 = explode(" ", $data);
			$tgl1 = $str1[0];
			$hasil = $this -> dsql_tglfull($tgl1);
			return $hasil;
		}
	}
	
	//function nmr_format($data) {
	//	return number_format($data, 0, ",", ".");
	//}
	
	function nmr_format($data) {
		return number_format($data, 0, ",", ".");
	}
	
	function nmr_format_2($data) {
		return number_format($data, 0, ".", ",");
	}
	
	function nmr_biasa($data) {
		$kata=str_replace(".", "", $data);
		$kata=str_replace(",", ".", $kata);
		return $kata;
	}
	
	function duit($data){
		return number_format($data,2,",",".");
	}
	
	function ambil_maxid($table, $field) {
		$qmaxid = "select max($field) as maxid from $table";
		$maxid = mysql_fetch_array(mysql_query($qmaxid));
		return $maxid['maxid'] + 1;
	}
	
	function tanggal($tanggal){
	    $pecah=explode("-",$tanggal);
	    return date("Y-m-d", mktime(0,0,0, $pecah[1], $pecah[2]+30, $pecah[0]));
	}
	
	function rupiah($data){
		$b = str_replace('.', '', $data);
		$b = str_replace(',', '.', $b);
		return $b;
	}
	
	function decimal($data) {
		return number_format($data, 2, ",", ".");
	}
	
	
	function Terbilang($a) {
		    $ambil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		    if ($a < 12)
		        return " " . $ambil[$a];
		    elseif ($a < 20)
		        return Terbilang($a - 10) . "belas";
		    elseif ($a < 100)
		        return Terbilang($a / 10) . " puluh" . Terbilang($a % 10);
		    elseif ($a < 200)
		        return " seratus" . Terbilang($a - 100);
		    elseif ($a < 1000)
		        return Terbilang($a / 100) . " ratus" . Terbilang($a % 100);
		    elseif ($a < 2000)
		        return " seribu" . Terbilang($a - 1000);
		    elseif ($a < 1000000)
		        return Terbilang($a / 1000) . " ribu" . Terbilang($a % 1000);
		    elseif ($a < 1000000000)
		        return Terbilang($a / 1000000) . " juta" . Terbilang($a % 1000000);
	}
	
	function terbilangtgl($a){
			$ambil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		    if ($a < 12)
		        return " " . $ambil[$a];
		    elseif ($a < 20)
		        return Terbilang($a - 10) . "belas";
		    elseif ($a < 100)
		        return Terbilang($a / 10) . " puluh" . Terbilang($a % 10);
	}

}
?>