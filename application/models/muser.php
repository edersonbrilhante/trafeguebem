<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MUser extends CI_Model  {
	
	private $Id;		
	private $Name;		
	private $Password;		
	private $User;	
	private $Permissao;	

	function setId($value){
		$this->Id = $value;
	}	

	function getId(){
		return $this->Id;
	}

	function setName($value){
		$this->Name = $value;
	}	

	function getName(){
		return $this->Name;
	}

	function setPassword($value){
		$this->Password = $value;
	}	

	function getPassword(){
		return $this->Password;
	}

	function setUser($value){
		$this->User = $value;
	}	

	function getUser(){
		return $this->User;
	}	

	function setPermissao($value){
		$this->Permissao = $value;
	}	

	function getPermissao(){
		return $this->Permissao;
	}	

	/*retorna companies 
		@return companies
	*/
	function login($user='',$password=''){
		$sql = 'SELECT 					
					*
				FROM 
					"user"
				WHERE 
					"User" = \''.$user.'\' AND
					"Password" = \''.$password.'\'';
 	 		
		$query = $this->db->query($sql);
		if($query)
			return $query->result();
		else
			return false;
	}
	/*retorna companies 
		@return companies
	*/
	function companiesUser($id){
		$sql = 'SELECT 					
					c.*
				FROM 
					company c
					JOIN
						company_user u
					ON
						c."Id" = u."CompanyId"				
				WHERE 
					"UserId" = '.$id;
 	 		
		$query = $this->db->query($sql);
		if($query)
			return $query->result();
		else
			return array();
	}

	/*retorna rotas 
		@return rotas
	*/
	function rotasUser($id){
		$sql = 'SELECT 					
					r.*
				FROM 
					rota r
					JOIN
						rota_user u
					ON
						r."Id" = u."RotaId"				
				WHERE 
					u."UserId" = '.$id;
 	 		
		$query = $this->db->query($sql);
		if($query)
			return $query->result();
		else
			return array();
	}

	/*retorna users 
		@return users
	*/
	function getAll($page=1){
		$page--;
		$sql = 'SELECT 					
					*
				FROM 
					user';
 	 		
		$query = $this->db->query($sql);
		return $query->result();
	}

	/*retorna user 
		@return user
	*/
	function getInfoUser(){
		$sql = 'SELECT 					
					*
				FROM 
					'.$this->db->dbprefix('user').'
				WHERE "Id" = '.$this->getId();
 	 		
		$query = $this->db->query($sql);
		return $query->result();
	}

	/*retorna user 
		@return user
	*/
	function getUsuarioSearch($search='',$page=1){
		$page--;
		$sql = 'SELECT 					
					*
				FROM '.$this->db->dbprefix('user').' u';
		$where =  array();
		if(!isRoot()){
			$sql .= '
				LEFT JOIN
					'.$this->db->dbprefix('company_user').' c
				ON
					u."Id" = c."CompanyId"';
			$where[] = 'u."Id" = '.myId().' OR ((c."CompanyId" IN ('.implode(',',companiesIds()).') OR c."CompanyId" IS NULL) AND u."Permissao" = \'3\')';
		}
		if($search!=''){ 
			$where[] = '(u."Name" ILIKE \'%'.$search.'%\' OR
					u."User" ILIKE \'%'.$search.'%\')';
		}
		if(sizeof($where)){
			$sql .= ' WHERE '.implode(' AND ', $where);
		}
		$sql .= ' LIMIT 99 OFFSET '.($page*99); 	 		

		$query = $this->db->query($sql);
		return $query->result();
	}

	function insert(){
		$data = array(
			'"Name"' => $this->getName(),
			'"Password"' => $this->getPassword(),
			'"User"' => $this->getUser(),
			'"Permissao"' => $this->getPermissao()
		);
		
		$this->db->insert('user', $data);
		if($this->db->affected_rows()>0){
			return $this->db->insert_id();	
		}else{
			return false;
		}
	}

	function update(){
		$data = array(
			'"Name"' => $this->getName(),
			'"User"' => $this->getUser(),
			'"Permissao"' => $this->getPermissao()
		);
		if($this->getPassword()!='')$data['"Password"'] = $this->getPassword();
		$this->db->where('Id', $this->getId());
		$this->db->update('user', $data); 
		if($this->db->affected_rows()>0){
			return $this->getId();
		}else{
			return false;
		}
	}

	function delete(){
		$this->db->where('Id', $this->getId());
		$this->db->delete('user'); 
		if($this->db->affected_rows()>0){
			return true;
		}else{
			return false;
		}
	}

}