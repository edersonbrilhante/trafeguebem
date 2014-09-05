<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Empresa extends CI_Controller {  

	function __construct() {
        parent::__construct();        
        verificaAcesso();
        if(!isAdm()){
            forbidden();
        }
    }

    function index(){
        $id = $this->input->get('id');
        if($id != ''){
            $this->load->model('MCompany');
            $this->MCompany->setId($id);
            $company = $this->MCompany->getInfoCompany($id);
            if(isset($company[0])){                
                $company = $company[0];
                $data['id'] = $company->Id;
                $data['nome'] = $company->Nome;
                $data['usuario'] = $this->MCompany->userCompanies();
            }            
            else{                
                die('Empresa n&atilde;o existe');
            }
        }else{            
            $data['id'] = '';
            $data['nome'] = '';
            $data['usuario'] = array();
        }
        $data['title'] = "Trafegue Bem - Empresa";
        $this->load->view('empresa/cadastro',$data);       
    }

    function lista(){
        $this->load->model('MCompany');
        $search = $this->input->get('search');
        $page = $this->input->get('page');
        $result = $this->MCompany->getEmpresaSearch($search,$page);
        $c = array();
        if($result)
        {
            foreach ($result as $key => $user)    
            {           
                $c[$key]['id'] = $user->Id;
                $c[$key]['nome'] = $user->Nome;
            }
        }
        echo json_encode($c);
    }

    function deletar(){
        $this->load->model('MCompany');

        $id = $this->input->post('id');
        
        $this->MCompany->setId($id);
        $res = $this->MCompany->delete();
        if($res){            
            $ret['title'] = 'Excluido com sucesso';
            $ret['res'] = 'ok';
        }
        else{
            $ret['title'] = 'Erro ao excluir';
            $ret['res'] = 'nok';            
        }
        echo json_encode($ret);
    }

    function cadastrar(){
        $this->load->model('MCompany');
        $this->load->model('MCompany_user');

        $id = $this->input->post('id');
        $nome = $this->input->post('nome');
        $usuario = $this->input->post('usuario');

        $this->MCompany->setId($id);
        $this->MCompany->setNome($nome);
        $res = false;
        if($id==''){
            $id = $this->MCompany->insert();
            if($id)
                $res = true;
        }
        else{
           $res = $this->MCompany->update();
        } 

        $ret = array();
        if($res){
            $this->MCompany_user->setCompanyId($id);
            $this->MCompany_user->delete();
            $usuarios = explode(',', $usuario);
            foreach ($usuarios as $usuario) {
                $this->MCompany_user->setUserId($usuario);
                $this->MCompany_user->setCompanyId($id);
                $this->MCompany_user->insert();
            }
            $ret['title'] = 'Cadastrado com sucesso';
            $ret['res'] = 'ok';
        }
        else{
            $ret['title'] = 'Erro no cadastro';
            $ret['res'] = 'nok';            
        }
        echo json_encode($ret);
    }
}