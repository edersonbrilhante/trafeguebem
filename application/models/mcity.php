<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MCity extends CI_Model  {
		
	private $Id;		
	private $Nome;		
	private $StateId;

	function setId($value){
		$this->Id = $value;
	}	

	function getId(){
		return $this->Id;
	}

	function setNome($value){
		$this->Name = $value;
	}	

	function getNome(){
		return $this->Name;
	}

	function setStateId($value){
		$this->StateId = $value;
	}	

	function getStateId($value){
		return $this->StateId;
	}	

	function getCidadeSearch($search='',$page=1){
		$page--;
		$sql = 'SELECT 					
					*
				FROM city';
		if($search!='') $sql .= ' WHERE 
					"Nome" ILIKE \'%'.$search.'%\'';
			$sql .= ' ORDER BY "Nome" LIMIT 50 OFFSET '.($page*50); 	 		

		$query = $this->db->query($sql);
		return $query->result();
	}

	function getInfoCity($id){
		$sql = 'SELECT 					
					*
				FROM 
					city
				WHERE "Id" = '.$id;
 	 		
		$query = $this->db->query($sql);
		return $query->result();
	}

	/*retorna companies 
		@return companies
	*/
	function getAll($city='',$page=1){
		$page--;
		$sql = 'SELECT 					
					*
				FROM 
					city';
 	 		
		$query = $this->db->query($sql);
		return $query->result();
	}

	function insert(){
		$data = array(
			'"Nome"' => $this->getNome(),
			'"StateId"' => $this->getStateId()
		);
		$this->db->insert('city', $data); 
		if($this->db->affected_rows()>0){
			return $this->db->insert_id();	
		}else{
			return false;
		}
	}

	function update(){
		$data = array(
			'"Nome"' => $this->getNome(),
			'"StateId"' => $this->getStateId()
		);
		$this->db->where('Id', $this->getId());
		$this->db->update('city', $data); 
		if($this->db->affected_rows()>0){
			return $this->getId();
		}else{
			return false;
		}
	}

	function delete(){
		$this->db->where('Id', $this->getId());
		$this->db->delete('city', $data); 
		if($this->db->affected_rows()>0){
			return true;
		}else{
			return false;
		}
	}

}