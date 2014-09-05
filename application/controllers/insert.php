<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Insert extends CI_Controller {  

	function __construct() {
        parent::__construct();
        if(empty($this->session->userdata('userId')){         
            $data['title'] = "Login - Trafegue Bem";
            $this->load->view('login',$data);             
            $home = ci_class_loader(APPPATH.'/controllers/home');
            $home->login();
        }  
    }

    function insert()
    {
        $data['title'] = "Trafegue Bem";
        $data['page'] = "insert";
        $id = $this->input->get('id');
        if($id != ''){
            $this->load->model('MRota');
            $linha = $this->MRota->getLinhasById($id);
            $linha = $linha[0];
            $data['id'] = $linha->Id;
            $data['company'] = $linha->CompanyId;
            $data['codigoInterno'] = $linha->CodigoInterno;
            $data['codigo'] = $linha->Codigo;
            $data['nome'] = $linha->Nome;
            $data['city'] = $linha->CityId;
            $data['tipo'] = $linha->Tipo;
            $data['way'] = $linha->Way;
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
        }

        $this->load->model('MCompany'); 
        $c = array();
        $companies = $this->MCompany->getAll();
        foreach ($companies as $company) {
            $c[$company->Id] = $company->Nome;
        }
        $data['companies'] = $c;

        $this->load->model('MCity'); 
        $c = array();
        $companies = $this->MCompany->getAll();
        foreach ($companies as $company) {
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

        $this->load->view('template',$data);
    }   

    function linhas(){
        $data['title'] = "Trafegue Bem";
        $data['page'] = "home";

        $this->load->model('MCompany'); 
        $c = array();
        $companies = $this->MCompany->getAll();
        foreach ($companies as $company) {
            $c[$company->Id] = $company->Nome;
        }
        $data['companies'] = $c;

        $this->load->view('novo/linhas',$data);
    }

    function rota(){
		$dados = json_decode($this->input->post('json'),true);

        $codigo = $dados['codigo'];
    	$codigointerno = $dados['codigointerno'];
    	$nome = $dados['nome'];
    	$companyId = $dados['company'];
    	$cityId = $dados['city'];
    	$tipo = $dados['tipo'];
    	$nodes = $dados['geom'];
        $way = $dados['way'];
    	$id = $dados['id'];
    	$stop = null;
    	$userId = 1;
    	if($nodes != ''){
            foreach ($nodes as $node) {
                $geom[] = implode(' ', $node);
            }
            $geom = implode(',', $geom);
    		$geom = "LINESTRING (".$geom.")";
    	}

    	$this->load->model('MRota');
        if($id=='') $this->MRota->insert($codigo,$codigointerno,$nome,$companyId,$cityId,$tipo,$geom,$way,$stop,$userId);
    	else $this->MRota->update($id,$codigo,$codigointerno,$nome,$companyId,$cityId,$tipo,$geom,$way,$stop,$userId);
    }
}

?>