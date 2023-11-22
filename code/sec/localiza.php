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
		include("localiza/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("localiza/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("localiza/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("localiza/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("localiza/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("localiza/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/ 
	
	$sql = "SELECT * 
	FROM localizacion 
	ORDER BY departamento, localizacion ";
	$query= consulta($sql);
	
	$localiza = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$localiza[] = array('localizacion' => $row["localizacion"],
							'departamento' => $row["departamento"]);
	}
	
	$smarty->assign('localiza',$localiza);
	
	$smarty->display('sec/localiza/localiza.html');
	die();
	
?>
