<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Linha extends CI_Controller {  

	function __construct() {
        parent::__construct();        
        verificaAcesso();
       if($this->input->get_post('id')!='') linhaPermitida($this->input->get_post('id'));
    }

    function deletar(){
        if(!isAdm()){
            forbidden();
        }
        $id = $this->input->get('id');
        $this->load->model('MRota');
        $this->MRota->delete($id);
    }

    function lista(){
        $this->load->model('MRota');
        $search = $this->input->get('search');
        $page = $this->input->get('page');
        $result = $this->MRota->getRotaSearch($search,$page);
        $r = array();
        if($result)
        {
            foreach ($result as $key => $rota)    
            {           
                $r[$key]['id'] = $rota->Id;
                $r[$key]['nome'] = $rota->Nome;
            }
        }
        echo json_encode($r);
    }

    function cadastro()
    {
        if(!isAdm()){
            forbidden();
        }
        $data['title'] = "Trafegue Bem";
        $id = $this->input->get('id');
        $clonar = $this->input->get('clonar');
        if($id != ''){
            $this->load->model('MRota');
            $linha = $this->MRota->getLinhasById($id);
            if(isset($linha[0])){                
                $linha = $linha[0];
                $data['id'] = $linha->Id;
                $data['company'] = $linha->CompanyId;
                $data['codigoInterno'] = $linha->CodigoInterno;
                $data['codigo'] = $linha->Codigo;
                $data['nome'] = $linha->Nome;
                $data['city'] = $linha->CityId;
                $data['tipo'] = $linha->Tipo;
                $data['way'] = $linha->Way;
                $data['restrito'] = $linha->Restrito;
                $data['modalidade'] = $linha->Modalidade;
                $data['status'] = $linha->Status;
            }else{                
                die('Linha não existe');
            }
        }
        else{
            $data['id'] = '';
            $data['company'] = '';
            $data['codigoInterno'] = '';
            $data['codigo'] = '';
            $data['nome'] = '';
            $data['city'] = '';
            $data['tipo'] = '';
            $data['way'] = '';
            $data['restrito'] = '';
            $data['modalidade'] = '';
            $data['status'] = '';
        }
        $data['clonar'] = $clonar;

        $this->load->model('MCompany'); 
        $c = array();
        $companies = $this->MCompany->getAll();
        foreach ($companies as $company) {
            if(in_array($company->Id,companiesIds()))
                $c[$company->Id] = $company->Nome;
        }
        $data['companies'] = $c;

        $this->load->model('MCity'); 
        $c = array();
        $cities = $this->MCity->getAll();
        foreach ($cities as $city) {
            $c[$city->Id] = $city->Nome;
        }
        $data['cities'] = $c;

        $this->load->view('novo/insert',$data);
    }   

    function linhas(){  
        $data['title'] = "Trafegue Bem";

        $this->load->model('MCompany'); 
        $c = array();
        $companies = $this->MCompany->getAll();
        foreach ($companies as $company) {
            if(in_array($company->Id,companiesIds()))
                $c[$company->Id] = $company->Nome;
        }
        $data['companies'] = $c;

        if(!isAdm())
            $this->load->view('novo/linhas_padrao',$data);
        else
            $this->load->view('novo/linhas',$data);
    }


    function horarios(){
        $data['title'] = "Trafegue Bem";
        $id = $this->input->get('id');

        $this->load->model('MHorario'); 
        $this->load->model('MRota'); 

        $linha = $this->MRota->getLinhasById($id);
        if(!isset($linha[0])){    
            die('Linha não existe');    
        }
        $horas =$this->MHorario->getHorarios($id);
        $l = array();
        if($horas)
        {
            foreach ($horas as $hora)
            {           
                $l[] = array(
                    'id'=>$hora->Id,
                    'segunda'=>$hora->Segunda,
                    'terca'=>$hora->Terca,
                    'quarta'=>$hora->Quarta,
                    'quinta'=>$hora->Quinta,
                    'sexta'=>$hora->Sexta,
                    'sabado'=>$hora->Sabado,
                    'domingo'=>$hora->Domingo,
                    'seletivo'=>$hora->Seletivo,
                    'especial'=>$hora->Especial,
                    'horario'=>$hora->Horario);
            }
        }
        $data['horarios'] = $l;
        $this->load->view('novo/horario',$data);
    }

    function salvar(){
        if(!isAdm()){
            forbidden();
        }
        $dados = json_decode($this->input->post('json'),true);

        $codigo = $dados['codigo'];
        $codigointerno = $dados['codigointerno'];
        $nome = $dados['nome'];
        $companyId = $dados['company'];
        $cityId = $dados['city'];
        $tipo = $dados['tipo'];
        $restrito = $dados['restrito'];
        $modalidade = $dados['modalidade'];
        $nodes = $dados['geom'];
        $way = $dados['way'];
        $id = $dados['id'];
        $clonar = $dados['clonar'];
        $status = $dados['status'];
        $stop = null;
        $userId = $this->session->userdata('userId');
        $geom = '';
        if(sizeof($nodes)){
            foreach ($nodes as $node) {
                $geom[] = implode(' ', $node);
            }
            $geom = implode(',', $geom);
            $geom = "LINESTRING (".$geom.")";
        }

        $this->load->model('MRota');
        if($id==''||$clonar==1) $this->MRota->insert($codigo,$codigointerno,$nome,$companyId,$cityId,$tipo,$geom,$way,$restrito,$modalidade,$status,$stop,$userId);
        else $this->MRota->update($id,$codigo,$codigointerno,$nome,$companyId,$cityId,$tipo,$geom,$way,$restrito,$modalidade,$status,$stop,$userId);
    }

    function salvarHorario(){
        if(!isAdm()){
            forbidden();
        }
		$dados = json_decode($this->input->post('json'),true);
        
        $id = explode(',',$this->input->post('id'));
        $rotaid = $this->input->post('rotaid');
        $segunda = explode(',',$this->input->post('segunda'));
        $terca = explode(',',$this->input->post('terca'));
        $quarta = explode(',',$this->input->post('quarta'));
        $quinta = explode(',',$this->input->post('quinta'));
        $sexta = explode(',',$this->input->post('sexta'));
        $sabado = explode(',',$this->input->post('sabado'));
        $domingo = explode(',',$this->input->post('domingo'));
        $especial = explode(',',$this->input->post('especial'));
        $horario = explode(',',$this->input->post('horario'));
        $seletivo = explode(',',$this->input->post('seletivo'));
        
        $total = sizeof($horario);
        $ids = array();
        for ($i=0;$i<$total;$i++) {
            if($horario[$i] !=''){
                $this->load->model('MHorario');
                if($id[$i]=='') $this->MHorario->insert(
                    $rotaid,
                    $segunda[$i],
                    $terca[$i],
                    $quarta[$i],
                    $quinta[$i],
                    $sexta[$i],
                    $sabado[$i],
                    $domingo[$i],
                    $especial[$i],
                    $horario[$i],
                    $seletivo[$i]);
                else{
                    $ids[] = $id[$i];
                    $this->MHorario->update(
                        $id[$i],
                        $rotaid,
                        $segunda[$i],
                        $terca[$i],
                        $quarta[$i],
                        $quinta[$i],
                        $sexta[$i],
                        $sabado[$i],
                        $domingo[$i],
                        $especial[$i],
                        $horario[$i],
                        $seletivo[$i]);
                }
            }
        }
        if(sizeof($ids)>0) $this->MHorario->delete($ids,$rotaid);
    }
    
}

?>