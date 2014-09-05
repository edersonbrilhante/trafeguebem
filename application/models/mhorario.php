<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MHorario extends CI_Model  {
	
	function insert($rotaid,$segunda,$terca,$quarta,$quinta,$sexta,$sabado,$domingo,$especial,$horario,$seletivo){

		$data = array(
			'"RotaId"' => $rotaid,
			'"Segunda"' => $segunda,
			'"Terca"' => $terca,
			'"Quarta"' => $quarta,
			'"Quinta"' => $quinta,
			'"Sexta"' => $sexta,
			'"Sabado"' => $sabado,
			'"Domingo"' => $domingo,
			'"Especial"' => $especial,
			'"Horario"' => $horario,
			'"Seletivo"' => $seletivo
		);
		$this->db->insert('horario', $data); 
		$this->db->last_query();

	}
	
	function update($id,$rotaid,$segunda,$terca,$quarta,$quinta,$sexta,$sabado,$domingo,$especial,$horario,$seletivo){

		$data = array(
			'"RotaId"' => $rotaid,
			'"Segunda"' => $segunda,
			'"Terca"' => $terca,
			'"Quarta"' => $quarta,
			'"Quinta"' => $quinta,
			'"Sexta"' => $sexta,
			'"Sabado"' => $sabado,
			'"Domingo"' => $domingo,
			'"Especial"' => $especial,
			'"Horario"' => $horario,
			'"Seletivo"' => $seletivo
		);
		$this->db->where('Id', $id);
		$this->db->update('horario', $data); 
		$this->db->last_query();

	}
	function delete($ids,$rotaid){

		$this->db->where_not_in('Id', $ids);
		$this->db->where('RotaId', $rotaid);
		$this->db->delete('horario', $data); 
		echo $this->db->last_query();

	}
	
	function getHorario($code){
		$sql = ' SELECT * FROM (
			SELECT
				"Especial",
				 TO_CHAR(CAST("Horario" AS TIME),\'HH24:MI\') "Horario",
				\'util\' "Dia" 
			FROM 
				horario 
			WHERE 
				"RotaId" = '.$code.' AND 
				"Segunda" IS TRUE 
			UNION 
			SELECT
				"Especial",
				 TO_CHAR(CAST("Horario" AS TIME),\'HH24:MI\') "Horario",
				\'sabado\' "Dia" 
			FROM 
				horario 
			WHERE 
				"RotaId" = '.$code.' AND 
				"Sabado" IS TRUE 
			UNION  
			SELECT
				"Especial",
				 TO_CHAR(CAST("Horario" AS TIME),\'HH24:MI\') "Horario",
				\'domingo\' "Dia" 
			FROM 
				horario 
			WHERE 
				"RotaId" = '.$code.' AND 
				"Domingo" IS TRUE) h
			ORDER BY h."Horario"
			';
		$query = $this->db->query($sql);
		return $query->result();
	}
		
	function getHorarios($code){
		$sql = '
			SELECT
				"Id",
				"Especial",
				"Seletivo",
				 TO_CHAR(CAST("Horario" AS TIME),\'HH24:MI\') "Horario",
				 "Segunda", 
				 "Terca", 
				 "Quarta", 
				 "Quinta", 
				 "Sexta", 
				 "Sabado",
				 "Domingo" 
			FROM 
				horario 
			WHERE 
				"RotaId" = '.$code.'
			ORDER BY "Horario"
			';
		$query = $this->db->query($sql);
		return $query->result();
	}
	
}

/* End of file mhorario.php */
/* Location: ./application/models/mhorario.php */
?>