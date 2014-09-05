<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MTaxi extends CI_Model  {

	function getTaxi($latI,$lngI,$distI,$latF='',$lngF='',$distF='')
	{
		$sql = 'SELECT 		
				ST_AsText("Geom") "Geom",
				"Id",
				"CityId",
				"Status",
				"Fone",
				"Adress",
  				ST_Distance(ST_Transform(ST_GeomFromText (\'POINT ('.$latI.' '.$lngI.')\', 4326),26986), ST_Transform(ST_GeomFromText (ST_AsText("Geom"), 4326),26986)) "DistanciaA",
				null "DistanciaB"	
 			FROM
 				taxi  
  			WHERE
  				(ST_Distance(ST_Transform(ST_GeomFromText (\'POINT ('.$latI.' '.$lngI.')\', 4326),26986), ST_Transform(ST_GeomFromText (ST_AsText("Geom"), 4326),26986)) <= '.$distI.')';
	  		
	  	if($latF != '' && $lngF != '') $sql .= '
			UNION
				SELECT 		
				ST_AsText("Geom") "Geom",
				"Id",
				"CityId",
				"Status",
				"Fone",
				"Adress",
				null "DistanciaA",
				ST_Distance(ST_Transform(ST_GeomFromText (\'POINT ('.$latF.' '.$lngF.')\', 4326),26986), ST_Transform(ST_GeomFromText (ST_AsText("Geom"), 4326),26986)) "DistanciaB"
 			FROM
 				taxi  
  			WHERE
				(ST_Distance(ST_Transform(ST_GeomFromText (\'POINT ('.$latF.' '.$lngF.')\', 4326),26986), ST_Transform(ST_GeomFromText (ST_AsText("Geom"), 4326),26986)) <= '.$distF.')';
		$query = $this->db->query($sql);
		return $query->result();
	}
}

/* End of file mtaxi.php */
/* Location: ./application/models/mtaxi.php */
?>