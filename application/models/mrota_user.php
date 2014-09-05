<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MRota_user extends CI_Model  {
	
	private $UserId;		
	private $RotaId;		

	function setUserId($value){
		$this->UserId = $value;
	}	

	function getUserId(){
		return $this->UserId;
	}

	function setRotaId($value){
		$this->RotaId = $value;
	}	

	function getRotaId(){
		return $this->RotaId;
	}

	function insert(){
		$data = array(
			'"UserId"' => $this->getUserId(),
			'"RotaId"' => $this->getRotaId()
		);
		
		$this->db->insert('rota_user', $data);
		if($this->db->affected_rows()>0){
			return true;	
		}else{
			return false;
		}
	}

	function delete(){
		if($this->getUserId())$this->db->where('UserId', $this->getUserId());
		if($this->getRotaId())$this->db->where('RotaId', $this->getRotaId());
		$this->db->delete('rota_user'); 
		if($this->db->affected_rows()>0){
			return true;
		}else{
			return false;
		}
	}
}