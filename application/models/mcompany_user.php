<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MCompany_user extends CI_Model  {
	
	private $UserId;		
	private $CompanyId;		

	function setUserId($value){
		$this->UserId = $value;
	}	

	function getUserId(){
		return $this->UserId;
	}

	function setCompanyId($value){
		$this->CompanyId = $value;
	}	

	function getCompanyId(){
		return $this->CompanyId;
	}

	function insert(){
		$data = array(
			'"UserId"' => $this->getUserId(),
			'"CompanyId"' => $this->getCompanyId()
		);
		
		$this->db->insert('company_user', $data);
		if($this->db->affected_rows()>0){
			return true;	
		}else{
			return false;
		}
	}

	function delete(){
		if($this->getUserId())$this->db->where('UserId', $this->getUserId());
		if($this->getCompanyId())$this->db->where('CompanyId', $this->getCompanyId());
		$this->db->delete('company_user'); 
		if($this->db->affected_rows()>0){
			return true;
		}else{
			return false;
		}
	}
}