<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Busca extends CI_Controller {  

	function __construct() {
        parent::__construct();
    }

    function index(){
		$dados = json_decode($this->input->get('json'),true);
		$this->load->model('MRota');
		$this->load->model('MTaxi');

		$latI = $dados['coord']['start'][0];
		$lngI = $dados['coord']['start'][1];
		$distI = $dados['coord']['start'][2];
		$latF = $dados['coord']['end'][0];
		$lngF = $dados['coord']['end'][1];
		$distF = $dados['coord']['end'][2];
		$type = $dados['type'];
		$term = $dados['term'];
		$empresa = $dados['empresa'];

		$l = array();

		if($type['l']){
			if($term != '')
				$linhas = $this->MRota->getLinhasByTerm($term,'L',$empresa);
			else
				$linhas = $this->MRota->getTrajetos($latI,$lngI,$distI,$latF,$lngF,$distF,'L',$empresa);
			if($linhas)
			{
				foreach ($linhas as $linha)
				{			
					$coord = array();		
					$pontos = explode(',',substr($linha->Geom, 11, -1));
					foreach ($pontos as $ponto) {
						list($lat,$lng) = explode(' ', $ponto);
						$coord[] = array('lat'=>$lat,'lng'=>$lng);
					}			
					$coordStrop = array();
					$stops = explode(',',substr($linha->Stop, 11, -1));
					foreach ($stops as $stop) {
						list($lat,$lng) = explode(' ', $ponto);
						$coordStrop[] = array('lat'=>$lat,'lng'=>$lng);
					}
					$l['lotacao'][] = array('key'=>$linha->Id,'geom'=>$coord,'stops'=>$coordStrop,'code'=>$linha->Codigo,'way'=>$linha->Way,'name'=>$linha->Nome,'distanciaA'=>$linha->DistanciaA,'distanciaB'=>$linha->DistanciaB);	
				}
			}
		}

		if($type['b']){
			if($term != '')
				$linhas = $this->MRota->getLinhasByTerm($term,'B',$empresa);
			else
				$linhas = $this->MRota->getTrajetos($latI,$lngI,$distI,$latF,$lngF,$distF,'B',$empresa);
			if($linhas)
			{
				foreach ($linhas as $linha)
				{			
					$coord = array();		
					$pontos = explode(',',substr($linha->Geom, 11, -1));
					foreach ($pontos as $ponto) {
						list($lat,$lng) = explode(' ', $ponto);
						$coord[] = array('lat'=>$lat,'lng'=>$lng);
					}			
					$coordStrop = array();
					$stops = explode(',',substr($linha->Stop, 11, -1));
					foreach ($stops as $stop) {
						list($lat,$lng) = explode(' ', $ponto);
						$coordStrop[] = array('lat'=>$lat,'lng'=>$lng);
					}
					$l['bus'][] = array('key'=>$linha->Id,'geom'=>$coord,'stops'=>$coordStrop,'code'=>$linha->Codigo,'way'=>$linha->Way,'name'=>$linha->Nome,'distanciaA'=>$linha->DistanciaA,'distanciaB'=>$linha->DistanciaB);	
				}
			}
		}

		if($type['t'] && $term == ''){
			$taxis = $this->MTaxi->getTaxi($latI,$lngI,$distI,$latF,$lngF,$distF);
			if($taxis)
			{
				foreach ($taxis as $taxi)
				{			
					$coord = array();		
					list($lat,$lng)  = explode(' ',substr($taxi->Geom, 6, -1));
					$coord = array('lat'=>$lat,'lng'=>$lng);

					$l['taxi'][] = array('key'=>$taxi->Id,'geom'=>$coord,'fone'=>$taxi->Fone,'adress'=>$taxi->Adress,'distanciaA'=>$taxi->DistanciaA,'distanciaB'=>$taxi->DistanciaB);	
				}
			}
		}

		echo json_encode($l);
		
    }

    function getLinha($code){
    	$l = array();
    	if($code){
    		$this->load->model('MRota');
			$linhas = $this->MRota->getLinhasById($code);
			if($linhas)
			{
				foreach ($linhas as $linha)
				{			
					$coord = array();		
					$pontos = explode(',',substr($linha->Geom, 11, -1));
					if(!empty($pontos[0]))
					foreach ($pontos as $ponto) {
						list($lat,$lng) = explode(' ', $ponto);
						$coord[] = array('lat'=>$lat,'lng'=>$lng);
					}			
					$coordStrop = array();
					$stops = explode(',',substr($linha->Stop, 11, -1));
					if(!empty($stops[0]))
					foreach ($stops as $stop) {
						list($lat,$lng) = explode(' ', $stop);
						$coordStrop[] = array('lat'=>$lat,'lng'=>$lng);
					}
					$l = array('key'=>$linha->Id,'geom'=>$coord,'stops'=>$coordStrop,'code'=>$linha->Codigo,'way'=>$linha->Way,'name'=>$linha->Nome,'restrito'=>$linha->Restrito,'tipo'=>$linha->Tipo,'modalidade'=>$linha->Modalidade,'status'=>$linha->Status,'city'=>$linha->CityId,'company'=>$linha->CompanyId,'codigoInterno'=>$linha->CodigoInterno);	
				}
			}
		}
		echo json_encode($l);
    }


    function getHour($id){
		$this->load->model('MHorario');	
    	$horas =$this->MHorario->getHorario($id);
		$l = array();
		if($horas)
		{
			foreach ($horas as $hora)
			{			
				$l[$hora->Dia][] = array(
					'especial'=>$hora->Especial,
					'horario'=>$hora->Horario);
			}
		}
		echo json_encode($l);
    }

    function lista(){
        verificaAcesso();
		$this->load->model('MRota'); 
		$this->load->model('MCompany'); 

		$company = $this->input->get('company');
		$codigo = $this->input->get('codigo');
		$codigoInterno = $this->input->get('codigoInterno');
		$linha = $this->input->get('linha');
		$sentido = $this->input->get('sentido');
		$modalidade = $this->input->get('modalidade');
		$restrito = $this->input->get('restrito');
		$status = $this->input->get('status');
		$companies = companiesIds();

		if($company=='')$company=$companies;
		elseif(!in_array($company, $companies))return false;
	
		$page = $this->input->get('page');
    	$linhas = $this->MRota->getLinhasByCompany($company, $codigo, $codigoInterno, $linha, $sentido, $modalidade, $restrito, $status, $page);
    	$l = array();

		$c = array();
		$companies = $this->MCompany->getAll();
		foreach ($companies as $company) {
			$c[$company->Id] = $company->Nome;
		}

		$modalidade = array('S'=>'Semi-direto','C'=>'Comum', 'T'=>'Turismo');
    	if($linhas)
		{
			foreach ($linhas as $linha)
			{			
				$coord = array();		
				if($linha->Geom !=''){
					$pontos = explode(',',substr($linha->Geom, 11, -1));
					foreach ($pontos as $ponto) {
						list($lat,$lng) = explode(' ', $ponto);
						$coord[] = array('lat'=>$lat,'lng'=>$lng);
					}	
				}	
				$coordStrop = array();
				if($linha->Stop !=''){
					$stops = explode(',',substr($linha->Stop, 11, -1));
					foreach ($stops as $stop) {
						list($lat,$lng) = explode(' ', $ponto);
						$coordStrop[] = array('lat'=>$lat,'lng'=>$lng);
					}
				}
				$restrito = ($linha->Restrito=='t')?'Sim':'N&atilde;o';
				$status = ($linha->Status=='1')?'Sim':'N&atilde;o';
				$company = $c[$linha->CompanyId];
				$l['linhas'][] = array(
					'key'=>$linha->Id,
					'geom'=>$coord,
					'stops'=>$coordStrop,
					'company'=>$company,
					'codigoInterno'=>$linha->CodigoInterno,
					'codigo'=>$linha->Codigo,
					'nome'=>$linha->Nome,
					'city'=>$linha->CityId,
					'tipo'=>$linha->Tipo,
					'way'=>$linha->Way,
					'modalidade'=>$modalidade[$linha->Modalidade],
					'restrito'=>$restrito,
					'status'=>$status
					);	
			}
		}
		echo json_encode($l);
    }

}

/* End of file busca.php */
/* Location: ./application/controllers/busca.php */

?>