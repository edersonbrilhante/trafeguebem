<html>
	<?php 
		if($page == 'mobile'){
			$this->load->view('head_mobile');
			$this->load->view('body_mobile');
		}
		elseif($page == 'insert'){
			$this->load->view('head_insert');
			$this->load->view('body_insert');
		}
		else{
			$this->load->view('head');
			$this->load->view('body');			
		}
	?>
</html>