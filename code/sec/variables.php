<?php
//session_start();

require_once("../lib/setupSEC.php");
$smarty = new bd;	
require_once('../lib/verificar.php');


	//cargando para el overlib
	include("../lib/cargar_overlib.php");
	/*********************************operaciones de la ventana*************/
	/*********************************operaciones de la ventana*************/
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("variables/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("variables/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("variables/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("variables/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("variables/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("variables/eliminando.php");
	}
	
	//clausulas en la que esta contenida la variable
	if(isset($_REQUEST['clausulas'])){
		include("variables/clausulas.php");
	}
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/ 
	
	$sql = "SELECT * 
	FROM var_texto
	ORDER BY idtexto";
	$query= consulta($sql);
	
	$variables = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$variables[] = array('idtexto' => $row["idtexto"],
							'descripcion' => $row["descripcion"]);
	}
	
	$smarty->assign('variables',$variables);
	
	$smarty->display('sec/variables/variables.html');
	die();
	
?>
