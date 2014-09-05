<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Usuario extends CI_Controller {  

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
            $this->load->model('MUser');
            $this->MUser->setId($id);
            $user = $this->MUser->getInfoUser();
            if(isset($user[0])){                
                $user = $user[0];
                $data['id'] = $user->Id;
                $data['name'] = $user->Name;
                $data['user'] = $user->User;
                $data['permissao'] = $user->Permissao;
                $data['empresa'] = $this->MUser->companiesUser($id);
                $data['rota'] = $this->MUser->rotasUser($id);
            }
            else{                
                die('Usu&aacute;rio n&atilde;o existe');
            }
        }else{            
            $data['id'] = '';
            $data['name'] = '';
            $data['user'] = '';
            $data['permissao'] = '';
            $data['empresa'] = array();
            $data['rota'] = array();
        }
        $data['title'] = "Trafegue Bem - Usuario";
        $this->load->view('usuario/cadastro',$data);       
    }

    function lista(){
        $this->load->model('MUser');
        $search = $this->input->get('search');
        $page = $this->input->get('page');
        $result = $this->MUser->getUsuarioSearch($search,$page);
        $u = array();
        $permissao = array('1'=>'Root','2'=>'Admin','3'=>'Usuário Comum');
        if($result)
        {
            foreach ($result as $key => $user)    
            {           
                $u[$key]['id'] = $user->Id;
                $u[$key]['name'] = $user->Name;
                $u[$key]['user'] = $user->User;
                $u[$key]['permissao'] = $permissao[$user->Permissao];
            }
        }
        echo json_encode($u);
    }

    function deletar(){
        $this->load->model('MUser');

        $id = $this->input->post('id');
        
        $this->MUser->setId($id);
        $res = $this->MUser->delete();
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
        $this->load->model('MUser');
        $this->load->model('MCompany_user');
        $this->load->model('MRota_user');

        $id = $this->input->post('id');
        $nome = $this->input->post('nome');
        $password = $this->input->post('password');
        $user = $this->input->post('user');
        $permissao = $this->input->post('permissao');
        $empresa = $this->input->post('empresa');
        $rota = $this->input->post('rota');

        $this->MUser->setId($id);
        $this->MUser->setName($nome);
        $this->MUser->setPassword($password);
        $this->MUser->setUser($user);
        if($permissao=='')
            if($id!=myId())
                $permissao='3';
            else
                $permissao=myPerm();

        $this->MUser->setPermissao($permissao);
        $res = false;
        if($id==''){
            $id = $this->MUser->insert();
            if($id)
                $res = true;
        }
        else{
           $res = $this->MUser->update();
        } 

        $ret = array();
        if($res){
            $this->MCompany_user->setUserId($id);
            $this->MCompany_user->delete();
            $empresas = explode(',', $empresa);
            foreach ($empresas as $empresa) {
                $this->MCompany_user->setUserId($id);
                $this->MCompany_user->setCompanyId($empresa);
                $this->MCompany_user->insert();
            }
            $rotas = explode(',', $rota);
            foreach ($rotas as $rota) {
                $this->MRota_user->setUserId($id);
                $this->MRota_user->setRotaId($rota);
                $this->MRota_user->insert();
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