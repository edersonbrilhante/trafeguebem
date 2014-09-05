<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cidade extends CI_Controller {  

	function __construct() {
        parent::__construct();        
        verificaAcesso();
        if(!isRoot()){
            forbidden();
        }
    }

    function index(){
        $id = $this->input->get('id');
        if($id != ''){
            $this->load->model('MCity');
            $city = $this->MCity->getInfoCity($id);
            if(isset($city[0])){                
                $city = $city[0];
                $data['id'] = $city->Id;
                $data['nome'] = $city->Nome;
                $data['stateid'] = $city->StateId;
            }            
            else{                
                die('Cidade n&atilde;o existe');
            }
        }else{            
            $data['id'] = '';
            $data['nome'] = '';
            $data['stateid'] = '';
        }
        $data['title'] = "Trafegue Bem - Cidade";
        $this->load->view('cidade/cadastro',$data);       
    }

    function lista(){
        $this->load->model('MCity');
        $search = $this->input->get('search');
        $page = $this->input->get('page');
        $result = $this->MCity->getCidadeSearch($search,$page);
        $c = array();
        if($result)
        {
            foreach ($result as $key => $user)    
            {           
                $c[$key]['id'] = $user->Id;
                $c[$key]['nome'] = $user->Nome;
                $c[$key]['stateid'] = $user->StateId;
            }
        }
        echo json_encode($c);
    }

    function deletar(){
        $this->load->model('MCity');

        $id = $this->input->post('id');
        
        $this->MCity->setId($id);
        $res = $this->MCity->delete();
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
        $this->load->model('MCity');

        $id = $this->input->post('id');
        $nome = $this->input->post('nome');
        $stateid = $this->input->post('estado');

        $this->MCity->setId($id);
        $this->MCity->setNome($nome);
        $this->MCity->setStateId($stateid);

        $res = false;
        if($id==''){
            $id = $this->MCity->insert();
            if($id)
                $res = true;
        }
        else{
           $res = $this->MCity->update();
        } 

        $ret = array();
        if($res){
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