<?php

//asignado el idioma local de la maquina
setlocale(LC_ALL,"");

require_once('../lib/conexionMNU.php');
/*
$sql = "SELECT lineas,paginas FROM parametros ";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$lineas 	= $row['lineas'];
	$paginas 	= $row['paginas'];

// en caso q no existan registros en la tabla parametros
if ($row['lineas']==''){
	$lineas=10;
}
if ($row['paginas']==''){
	$paginas=10;
}
*/
$lineas=16;
$paginas=10; //aca solo poner numero PARES! sino se arruina la paginacion
//valores constantes para paginacion
define('NRO_REGISTROS_PAG', $lineas);
define('NRO_MAX_PAGINAS', $paginas);  

// load Smarty library
define('SMARTY_DIR', '../lib/smarty/libs/');
require(SMARTY_DIR . 'Smarty.class.php');
//echo SMARTY_DIR;
// The setup.php file is a good place to load
// required application library files, and you
// can do that right here. An example:
// require('guestbook/guestbook.lib.php');

class bd extends Smarty {
   function bd()
   {
        // Class Constructor. These automatically get set with each new instance.
        $this->Smarty();
        $this->template_dir = '../templates/';
        $this->compile_dir = '../compilado/';
        $this->config_dir = '../configs/';
        $this->cache_dir = '../cache/';
        $this->caching = false;
		$this->force_compile= true;
   }
}
?>