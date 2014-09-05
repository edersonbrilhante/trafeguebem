<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('toUTF8'))
{
    function toUtf8($string){
		return iconv('iso-8859-1','utf-8',$string);
	}
}

if ( ! function_exists('string_html'))
{
	function string_html($string){
		return htmlentities($string, ENT_QUOTES, 'ISO-8859-1');
	}
}

if ( ! function_exists('companiesIds'))
{
	function companiesIds(){
		$CI =& get_instance();
        $CI->load->model('MUser'); 
        $id = $CI->session->userdata('userId');
        $companies = $CI->MUser->companiesUser($id);
        $c = array();
        if($companies)
        foreach ($companies as $company) {
        	$c[]=$company->Id;
        }
        return $c;
	}
}

if ( ! function_exists('companyId'))
{
	function companyId(){
		$c = companiesIds();
		if(sizeof($c)>1){
			return '';
		}elseif(sizeof($c)==1){
			return $c[0];
		}
	}
}

if ( ! function_exists('verificaAcesso'))
{
	function verificaAcesso(){
		$CI =& get_instance();
	    if(!$CI->session->userdata('userId')){
			echo '<h1>Acesso negado</h1>';
			die;
	    }
	}
}

if ( ! function_exists('forbidden'))
{
	function forbidden(){
		echo '<h1>Acesso negado</h1>';
		die;
	}
}

if ( ! function_exists('myPerm'))
{
	function myPerm(){
		verificaAcesso();
		$CI =& get_instance();
		$userid = $CI->session->userdata('userId');
	    $CI->load->model('MUser'); 
	    $CI->MUser->setId($userid);
	    $info = $CI->MUser->getInfoUser();
		return $info[0]->Permissao;
	}
}


if ( ! function_exists('isRoot'))
{
	function isRoot(){
		verificaAcesso();
		$CI =& get_instance();
		$userid = $CI->session->userdata('userId');
	    $CI->load->model('MUser'); 
	    $CI->MUser->setId($userid);
	    $info = $CI->MUser->getInfoUser();
	    if($info[0]->Permissao == 1){
			return true;
		}
		return false;
	}
}


if ( ! function_exists('isAdm'))
{
	function isAdm(){
		verificaAcesso();
		$CI =& get_instance();
		$userid = $CI->session->userdata('userId');
	    $CI->load->model('MUser'); 
	    $CI->MUser->setId($userid);
	    $info = $CI->MUser->getInfoUser();
	    if($info[0]->Permissao == 2 || isRoot()){
			return true;
		}
		return false;
	}
}

if ( ! function_exists('isUser'))
{
	function isUser(){
		verificaAcesso();
		$CI =& get_instance();
		$userid = $CI->session->userdata('userId');
	    $CI->load->model('MUser'); 
	    $CI->MUser->setId($userid);
	    $info = $CI->MUser->getInfoUser();
	    if($info[0]->Permissao == 3 || isAdm() || isRoot()){
			return true;
		}
		return false;
	}
}

if ( ! function_exists('myId'))
{
	function myId(){
		$CI =& get_instance();
		return $CI->session->userdata('userId');
	}

}

if ( ! function_exists('linhaPermitida'))
{
	function linhaPermitida($id){
		$CI =& get_instance();
	    $CI->load->model('MRota'); 
	    $linha = $CI->MRota->getLinhasById($id);
	    $linha = $linha[0];
	    if(!in_array($linha->CompanyId, companiesIds())){
			echo '<h1>Acesso negado</h1>';
			die;
	    }
	}
}
