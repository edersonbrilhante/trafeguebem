<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {  

	function index()
	{
		$this->load->library('user_agent');
		if($this->agent->is_mobile()){
			$this->mobile();
		}else{
		$data['title'] = "Trafegue Bem";
		$data['page'] = "home";
		$this->load->view('template',$data);			
		}

	}	

	function linha($code)
	{
		$data['title'] = "Trafegue Bem";
		$data['code'] = $code;
		$data['page'] = "linha";
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