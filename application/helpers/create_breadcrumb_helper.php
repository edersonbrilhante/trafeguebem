<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('create_breadcrumb')){
	function create_breadcrumb(){
		$ci = &get_instance();
		$i=1;
		$uri = $ci->uri->segment($i);
		if($uri != '')
		{
			$link =	'<ul>';

			while($uri != ''){
				$prep_link = '/';
				for($j=1; $j<=$i;$j++){
					$prep_link .= $ci->uri->segment($j).'/';
				}
				if($ci->uri->segment($i+1) == ''){
					
					$caminho = $ci->uri->segment($i);

					if($ci->uri->segment(1)=='mobile' || $ci->uri->segment(2)=='mobile')$link.=	'<li><b>'.ajusta_caminho($caminho).'</b></li> ';
					else $link.=	'<li><span>></span><b>'.ajusta_caminho($caminho).'</b></li> ';
					
				}elseif($ci->uri->segment($i+1) != 'mobile'){					

					$link.= '<li><span>></span><a href="'.$prep_link.'"'.'>'.ajusta_caminho($ci->uri->segment($i)).'</a></li> ';
				}
	
				$i++;
				$uri = $ci->uri->segment($i);
			}
			$link .= '</ul>';
		}
		return $link;
	}

	function ajusta_caminho($chave)
	{
		$caminhos = array(
			'adm' => 'Administra&ccedil;&atilde;o',
			'usuario' => 'Usu&aacute;rios',
			'cidade' => 'Cidades',
			'empresa' => 'Empresas',
			'linha' => 'Linhas'
		);
		
		return (isset($caminhos[$chave]))?$caminhos[$chave]:$chave;
	}
}
?>
