<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {  

	function index($empresa='')
	{
		$this->load->library('user_agent');
		//if($this->agent->is_mobile()){
		//	$this->mobile();
		//}else{
		$data['empresa'] = '';
        if($empresa!=''){  
	    	$this->load->model('MCompany'); 
	        $company = $this->MCompany->getCompanyByName($empresa);
	        if(isset($company[0])){
	        	$data['empresa'] =  $company[0]->Id;
	        }
	    }
		$data['title'] = "Trafegue Bem";
		$data['page'] = "home";
		$this->load->view('template',$data);			
		///}

	}	

	function login(){
		if($this->session->userdata('userId')!=''){
			header('Location: /adm');
		}
		else{
			$data['title'] = "Login - Trafegue Bem";
			$this->load->view('login',$data);		
		}
	}

	function logoff(){
		$this->session->unset_userdata('userId');
		header('Location: /login');		
	}

	function logon(){
		$user = $this->input->post('user');		
		$password = $this->input->post('password');
		$this->load->model('MUser');
		$login =  $this->MUser->Login($user,$password);
		if(isset($login[0]->Id)){
			$this->session->set_userdata('userId', $login[0]->Id);
			$this->session->set_userdata('userPermissao', $login[0]->Permissao);
			$res['msg'] = 'Sucesso';
			$res['erro'] = 0;	
			echo json_encode($res);		
		}
		else{
			$res['msg'] = 'Login Inválido';
			$res['erro'] = 1;
			echo json_encode($res);		
		}

	}

	function adm(){
		verificaAcesso();

		$data['title'] = "Trafegue Bem";

		if(isAdm()){
			$data['modulos'][] = array(
				'link' => '/adm/usuario',
				'icone' => 'usuario.png',
				'nome' => 'Usuário'
			);
		}

		if(isRoot()){
			$data['modulos'][] = array(
				'link' => '/adm/empresa',
				'icone' => 'empresa.png',
				'nome' => 'Empresas'
			);
			$data['modulos'][] = array(
				'link' => '/adm/cidade',
				'icone' => 'cidade.png',
				'nome' => 'Cidades'
			);
		}
		$data['modulos'][] = array(
			'link' => '/adm/linha',
			'icone' => 'linha.png',
			'nome' => 'Linhas'
		);

		$this->load->view('adm',$data);
	}

	function linha($code,$empresa='',$sentido='')
	{
		$data['title'] = "Trafegue Bem";
		$data['code'] = $code;
		$data['page'] = "linha";
		$data['empresa'] = "";
        if($empresa!=''){  
	    	$this->load->model('MCompany'); 
	        $company = $this->MCompany->getCompanyByName($empresa);
	        if(isset($company[0])){
	        	$data['empresa'] =  $company[0]->Id;

	        	$this->load->model('MRota'); 
		        $rota = $this->MRota->getLinhasByIdInterno($code,$company[0]->Id,$sentido);
	         	if(isset($rota[0])){
		        	$data['code'] =  $rota[0]->Id;
		        }
	        }
	    }
       

		$this->load->view('template',$data);
	}	

	function restrito($code,$empresa='',$sentido='')
	{
		verificaAcesso();
		$data['title'] = "Trafegue Bem";
		$data['code'] = $code;
		$data['page'] = "linha";
		$data['empresa'] = "";    

		$this->load->view('template',$data);
	}	

	function mobile()
	{
		$data['title'] = "Trafegue Bem";
		$data['page'] = "mobile";
		$this->load->view('template', $data);
	}	
	

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
?>
