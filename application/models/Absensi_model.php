<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_model extends CI_Model{
	function __construct(){
		$this->load->database();
		$this->db_hrdonline = $this->load->database("hrdonline", TRUE);
	}
	
	function hitungradius($data){
		$koordinatKantor = $data['koordinatKantor'];
		$latitude = $data['latitude'];
		$longitude = $data['longitude'];

		$str = explode(",", $koordinatKantor);
		$lat1 = $str[0];
		$lon1 = $str[1];

		// --- koordinat absen ---
		$lat2 = $latitude;
		$lon2 = $longitude;

		// --- rumus menghitung jarak ---
		$theta = $lon1 - $lon2;
		$miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
		$miles = acos($miles);
		$miles = rad2deg($miles);
		$miles = $miles * 60 * 1.1515;
		$feet = $miles * 5280;
		$yards = $feet / 3;
		$kilometers = round($miles * 1.609344,2);
		$meters = $kilometers * 1000;

		return $meters;
	}
	
}
?>