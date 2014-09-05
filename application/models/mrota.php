<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MRota extends CI_Model  {

	function insert($codigo,$codigointerno,$nome,$companyId,$cityId,$tipo,$geom,$way,$restrito,$modalidade,$status,$stop,$userId){
		$geom = ($geom!='')?"ST_GeomFromText('".$geom."', 4326),":'null,';
		$stop = ($stop!='')?"ST_GeomFromText('".$stop."', 4326),":'null,';
		$sql = 
			'INSERT INTO rota			
				("Codigo",
				"CodigoInterno",
				"Nome",
				"CompanyId", 
				"CityId", 
				"Tipo", 
	            "Geom", 
	            "Way",
	            "Restrito", 
	            "Modalidade",
	            "Status",
	            "Stop", 
            	"UserId")
				VALUES('.
			'\''.$codigo.'\','.
			'\''.$codigointerno.'\','.
			'\''.$nome.'\','.
			'\''.$companyId.'\','. 
			'\''.$cityId.'\','. 
			'\''.$tipo.'\','. 
           	$geom.
            '\''.$way.'\','.
            '\''.$restrito.'\','.
            '\''.$modalidade.'\','.
            '\''.$status.'\','.
            $stop.
            '\''.$userId.'\')';
		$this->db->query($sql); 
		$this->db->last_query();

	}

	function delete($id){
		$this->db->where('Id', $id);
		$this->db->delete('rota'); 
		$this->db->last_query();

	}

	function update($id,$codigo,$codigointerno,$nome,$companyId,$cityId,$tipo,$geom,$way,$restrito,$modalidade,$status,$stop,$userId){
		$geom = ($geom!='')?"ST_GeomFromText('".$geom."', 4326),":'null,';
		$stop = ($stop!='')?"ST_GeomFromText('".$stop."', 4326),":'null,';
		$sql = 'UPDATE
			rota
			SET
			"Codigo" = \''.$codigo.'\','.
			'"CodigoInterno" = \''.$codigointerno.'\','.
			'"Nome" = \''.$nome.'\','.
			'"CompanyId" = \''.$companyId.'\','. 
			'"CityId" = \''.$cityId.'\','. 
			'"Tipo" = \''.$tipo.'\','. 
            '"Geom" = '.$geom.
            '"Way" = \''.$way.'\','. 
            '"Restrito" = \''.$restrito.'\','. 
            '"Modalidade" = \''.$modalidade.'\','. 
            '"Status" = \''.$status.'\','. 
            '"Stop" = '.$stop.
            '"UserId" = \''.$userId.'\'
            WHERE
            	"Id" = '.$id;
		$this->db->query($sql); 
		$this->db->last_query();

	}

	function getTrajetos($latI,$lngI,$distI,$latF='',$lngF='',$distF='',$tipo='',$empresa='')
	{
		$sql = 'SELECT
				ST_AsText("Geom") "Geom",
				"Id",
				"Codigo",
				"Nome",
				"CompanyId",
				"CityId",
				"Status",
				"Tipo",
				"Way",
   				ST_AsText("Stop") "Stop",
  				ST_Distance(ST_Transform(ST_GeomFromText (\'POINT ('.$latI.' '.$lngI.')\', 4326),26986), ST_Transform("Geom",26986)) "DistanciaA"';
  		if($latF != '' && $lngF != '') $sql .= ',
				ST_Distance(ST_Transform(ST_GeomFromText (\'POINT ('.$latF.' '.$lngF.')\', 4326),26986), ST_Transform("Geom",26986)) "DistanciaB"';
		else $sql .= ', \'\' "DistanciaB"';	
 		
 		$sql .= '	
 			FROM
 				rota  
  			WHERE
				"Status" = \'1\' AND 
				"Restrito" is false AND
  				(ST_Distance(ST_Transform(ST_GeomFromText (\'POINT ('.$latI.' '.$lngI.')\', 4326),26986), ST_Transform("Geom",26986)) <= '.$distI.')';
	
		if($latF != '' && $lngF != '') $sql .= ' AND
  				(ST_Distance(ST_Transform(ST_GeomFromText (\'POINT ('.$latF.' '.$lngF.')\', 4326),26986), ST_Transform("Geom",26986)) <= '.$distF.')';
 		
		if($tipo != '') $sql .= ' AND
 	 			"Tipo" = \''.$tipo.'\'';

		if($empresa != '') $sql .= ' AND
 	 			"CompanyId" = '.$empresa;

 	 	$sql .= ' 	
			ORDER BY 
				"DistanciaA"';

		if($latF != '' && $lngF != '') $sql .= ', "DistanciaB"';

		$query = $this->db->query($sql);
		$this->db->last_query();
		return $query->result();

	}

	function getTrajetosWithStop($latI,$lngI,$distI,$latF='',$lngF='',$distF='')
	{
		$sql = 'SELECT 		
				ST_AsText("Geom") "Geom",
				"Id",
				"Codigo",
				"Nome",
				"CompanyId",
				"CityId",
				"Status",
				"Tipo",
				"Way",
   				ST_AsText("Stop") "Stop",
   				s."DistanciaA",
   				s."DistanciaB"
   			FROM 
   				rota,
				(SELECT
   				UNNEST("RotaId") "RotaId",
  				ST_Distance(ST_Transform(ST_GeomFromText (\'POINT ('.$latI.' '.$lngI.')\', 4326),26986), ST_Transform(ST_GeomFromText (ST_AsText("Geom"), 4326),26986)) "DistanciaA"';
  		if($latF != '' && $lngF != '') $sql .= ',
				ST_Distance(ST_Transform(ST_GeomFromText (\'POINT ('.$latF.' '.$lngF.')\', 4326),26986), ST_Transform(ST_GeomFromText (ST_AsText("Geom"), 4326),26986)) "DistanciaB"';
		else $sql .= ', \'\' "DistanciaB"';	
 		
 		$sql .= '	
 			FROM
 				stop  
  			WHERE
  				(ST_Distance(ST_Transform(ST_GeomFromText (\'POINT ('.$latI.' '.$lngI.')\', 4326),26986), ST_Transform(ST_GeomFromText (ST_AsText("Geom"), 4326),26986)) <= '.$distI.')';
	
		if($latF != '' && $lngF != '') $sql .= 'AND
  				(ST_Distance(ST_Transform(ST_GeomFromText (\'POINT ('.$latF.' '.$lngF.')\', 4326),26986), ST_Transform(ST_GeomFromText (ST_AsText("Geom"), 4326),26986)) <= '.$distF.')';
 		
 		$sql .= ') s 
			WHERE 
				"Id" = s."RotaId"';

 	 	$sql .= '  				
			ORDER BY 
				s."DistanciaA"';
				
		if($latF != '' && $lngF != '') $sql .= ', s."DistanciaB"';

		$query = $this->db->query($sql);
		return $query->result();

	}

	/*retorna trajetos 
		parametros:
			term: nome ou codigo
			tipo: tipo (onibus ou lotação)
	*/
	function getLinhasByTerm($term,$tipo='',$empresa=''){

		$sql = 'SELECT 					
					ST_AsText("Geom") "Geom",
					"Id",
					"Codigo",
					"Nome",
					"CompanyId",
					"CityId",
					"Status",
					"Tipo",
					"Way",
					"Restrito",
					"Modalidade",
	   				ST_AsText("Stop") "Stop",
	   				\'\' "DistanciaA",
	   				\'\' "DistanciaB"
				FROM 
					rota 
				WHERE 
					"Status" = \'1\' AND 
					"Restrito" is false AND (
					("Codigo" ILIKE \'%'.$this->db->escape_like_str($term).'%\' OR 
					"Nome" ILIKE \'%'.$this->db->escape_like_str($term).'%\'))';

		if($tipo != '') $sql .= ' AND
 	 			"Tipo" = \''.$tipo.'\'';

		if($empresa != '') $sql .= ' AND
 	 			"CompanyId" = '.$empresa;


 	 	$sql .= ' ORDER BY 
 	 		"Codigo", 
 	 		"Nome"';
		$query = $this->db->query($sql);
		return $query->result();
	}

	/*retorna trajeto 
		parametros:
			id: codigo da linha
			tipo: tipo (onibus ou lotação)
	*/
	function getLinhasById($id,$tipo=''){

		$sql = 'SELECT 					
					ST_AsText("Geom") "Geom",
					"Id",
					"Codigo",
					"CodigoInterno",
					"Nome",
					"CompanyId",
					"CityId",
					"Status",
					"Tipo",
					"Way",
					"Restrito",
					"Modalidade",
	   				ST_AsText("Stop") "Stop",
	   				\'\' "DistanciaA",
	   				\'\' "DistanciaB"
				FROM 
					rota 
				WHERE 
					"Id" = '.$id;

		if($tipo != '') $sql .= ' AND
 	 			"Tipo" = \''.$tipo.'\'';
 	 		
		$query = $this->db->query($sql);
		return $query->result();
	}

	/*retorna trajeto 
		parametros:
			id: codigo interno da empresa
			tipo: tipo (onibus ou lotação)
	*/
	function getLinhasByIdInterno($id,$empresa='',$sentido){

		$sql = 'SELECT 					
					ST_AsText("Geom") "Geom",
					"Id",
					"Codigo",
					"CodigoInterno",
					"Nome",
					"CompanyId",
					"CityId",
					"Status",
					"Tipo",
					"Way",
					"Restrito",
					"Modalidade",
	   				ST_AsText("Stop") "Stop",
	   				\'\' "DistanciaA",
	   				\'\' "DistanciaB"
				FROM 
					rota 
				WHERE 
					"CodigoInterno" = \''.$id.'\'';

		if($empresa != '') $sql .= ' AND
 	 			"CompanyId" = '.$empresa;

		if($sentido != '') $sql .= ' AND
 	 			"Way" = '.$sentido;
 	 		
		$query = $this->db->query($sql);
		return $query->result();
	}

	/*retorna linhas 
		@param:
			id: codigo da linha
			tipo: tipo (onibus ou lotação)
		@return 
	*/
	function getLinhasByCompany($company='', $codigo='', $codigoInterno='', $linha='', $sentido='', $modalidade='', $restrito='', $status='', $page=1){
		$page--;
		$sql = 'SELECT 					
					ST_AsText(r."Geom") "Geom",
					r."Id",
					r."Codigo",
					r."CodigoInterno",
					r."Nome",
					r."CompanyId",
					r."CityId",
					r."Status",
					r."Tipo",
					r."Way",
					r."Restrito",
					r."Modalidade",
	   				ST_AsText(r."Stop") "Stop",
	   				\'\' "DistanciaA",
	   				\'\' "DistanciaB"
				FROM 
					rota r';

		$where = array();				
		if(sizeof($company)>0) {
			if(is_array($company)){
				$where[] = 'r."CompanyId" IN ('.implode(',',$company).')';
			}
			else				
				$where[] = 'r."CompanyId" = '.$company;
 	 	}
 	 	if(!isAdm()){
 	 		$sql .= '	JOIN
					'.$this->db->dbprefix('rota_user').' u
				ON
					r."Id" = u."RotaId"';
			$where[] = 'u."UserId" = '.myId();
 	 	}
				
		if($codigo!='') $where[] = 'r."Codigo" ILIKE \'%'.$codigo."%'";
		if($codigoInterno!='') $where[] = 'r."CodigoInterno" ILIKE \'%'.$codigoInterno."%'";
		if($linha!='') $where[] = 'r."Nome" ILIKE \'%'.$linha."%'";
		if($sentido!='') $where[] = 'r."Way" = '.$sentido;
		if($restrito!='') $where[] = 'r."Restrito" is '.(($restrito==1)?'TRUE':'FALSE');
		if($modalidade!='') $where[] = 'r."Modalidade" = \''.$modalidade.'\'';
		if($status!='') $where[] = 'r."Status" = \''.$status.'\'';

		if(sizeof($where)>0)$sql .= ' WHERE ' . implode(' AND ' , $where);

 	 	$sql .= ' ORDER BY r."CodigoInterno", r."Codigo", r."Nome", r."Way"  LIMIT 50 OFFSET '.$page*50;
 	 			
		$query = $this->db->query($sql);
		return $query->result();
	}

	/*retorna rotas 
		@return rotas
	*/
	function getRotaSearch($search='',$page=1){
		$page--;
		$sql = 'SELECT 					
					r.*
				FROM '.$this->db->dbprefix('rota').' r
				JOIN
					'.$this->db->dbprefix('company_user').' u
				ON
					r."CompanyId" = u."CompanyId" 
				WHERE 
					u."CompanyId" IN ('.implode(',',companiesIds()).')';
		if($search!=''){
			$sql .= ' AND 
					("Nome" ILIKE \'%'.$search.'%\' OR
					"Codigo" ILIKE \'%'.$search.'%\')';
		}
		$sql .= ' LIMIT 99 OFFSET '.($page*99); 	 		

		$query = $this->db->query($sql);
		return $query->result();
	}

}