	<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class MCompany extends CI_Model  {
		
		private $Id;		
		private $Nome;		

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

	/*retorna companies 
		@return companies
	*/
	function getAll($company='',$page=1){
		$page--;
		$sql = 'SELECT 					
					*
				FROM 
					company';
 	 		
		$query = $this->db->query($sql);
		return $query->result();
	}

	function userCompanies($id=0){
		$id = ($id==0)?$this->getId():$id;
		$sql = 'SELECT 					
					u.*
				FROM 
					"user" u
					JOIN
						company_user c
					ON
						u."Id" = c."UserId"				
				WHERE 
					"CompanyId" = '.$id;
 	 		
		$query = $this->db->query($sql);
		if($query)
			return $query->result();
		else
			return false;
	}

	/*retorna companies 
		@return companies
	*/
	function getCompanyByName($company=''){
		$sql = 'SELECT 					
					*
				FROM 
					company
				WHERE "Nome" ILIKE \'%'.$company.'%\'';
 	 		
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getInfoCompany($id){
		$id = ($id==0)?$this->getId():$id;
		$sql = 'SELECT 					
					*
				FROM 
					company
				WHERE "Id" = '.$id;
 	 		
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getEmpresaSearch($search='',$page=1){
		$page--;
		$sql = 'SELECT 					
					c.*
				FROM '.$this->db->dbprefix('company').' c
				JOIN
					'.$this->db->dbprefix('company_user').' u
				ON
					c."Id" = u."CompanyId"
				WHERE 
					u."CompanyId" IN ('.implode(',',companiesIds()).')';
		if($search!='') $sql .= ' AND 
					("Nome" ILIKE \'%'.$search.'%\')';
		$sql .= ' ORDER BY "Nome" LIMIT 99 OFFSET '.($page*99); 	 		

		$query = $this->db->query($sql);
		return $query->result();
	}

	function insert(){
		$data = array(
			'"Nome"' => $this->getNome()
		);
		$this->db->insert('company', $data);
		if($this->db->affected_rows()>0){
			return $this->db->insert_id();	
		}else{
			return false;
		}
	}

	function update(){
		$data = array(
			'"Nome"' => $this->getNome()
		);
		$this->db->where('Id', $this->getId());
		$this->db->update('company', $data); 
		if($this->db->affected_rows()>0){
			return $this->getId();
		}else{
			return false;
		}
	}

	function delete(){
		$this->db->where('Id', $this->getId());
		$this->db->delete('company');
		if($this->db->affected_rows()>0){
			return true;
		}else{
			return false;
		}
	}

}