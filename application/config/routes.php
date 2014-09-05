<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = '';

$route['adm/linha/restrito/(:num)'] = "home/restrito/$1";//
$route['linha/(:num)'] = "home/linha/$1";//
$route['horario/(:num)'] = "busca/getHour/$1";
$route['getLinha/(:num)'] = "busca/getLinha/$1";

$route['busca'] = "busca/index";
$route['busca/lista'] = "busca/lista";

$route['linha'] = "home";
$route['mobile'] = "home/mobile";
$route['horario/(:num)'] = "busca/getHour/$1";
$route['linha2/(:num)'] = "busca/getLinha/$1";

$route['deletar/linha'] = "linha/deletar";
$route['novo'] = "linha/cadastro";
$route['novo/horario'] = "linha/horarios";
$route['insert/horario'] = "linha/salvarHorario";
$route['insert'] = "linha/salvar";
$route['linhas'] = "linha/linhas";

$route['login'] = 'home/login';
$route['logon'] = 'home/logon';
$route['logoff'] = 'home/logoff';

$route['(:any)/linha/(:any)/(:num)'] = "home/linha/$2/$1/$3";
$route['(:any)'] = "home/index/$1";


$route['adm'] = "home/adm";
//linha
$route['adm/linha/lista'] = "linha/lista";

//usuario
$route['adm/usuario'] = "usuario/index";
$route['adm/usuario/lista'] = "usuario/lista";
$route['adm/usuario/cadastrar'] = "usuario/cadastrar";
$route['adm/usuario/deletar'] = "usuario/deletar";
//empresa
$route['adm/empresa'] = "empresa/index";
$route['adm/empresa/lista'] = "empresa/lista";
$route['adm/empresa/cadastrar'] = "empresa/cadastrar";
$route['adm/empresa/deletar'] = "empresa/deletar";
//cidade
$route['adm/cidade'] = "cidade/index";
$route['adm/cidade/lista'] = "cidade/lista";
$route['adm/cidade/cadastrar'] = "cidade/cadastrar";
$route['adm/cidade/deletar'] = "cidade/deletar";
//linha
$route['adm/linha'] = "linha/linhas";
$route['adm/linha/novo'] = "linha/cadastro";
$route['adm/linha/cadastrar'] = "linha/cadastrar";
$route['adm/linha/deletar'] = "linha/deletar";
$route['adm/linha/horario'] = "linha/	";
$route['adm/linha/horario/cadastrar'] = "linha/salvarHorario";


/* End of file routes.php */
/* Location: ./application/config/routes.php */