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
		include("extension/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("extension/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("extension/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("extension/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("extension/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("extension/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/ 
	
	$sql = "SELECT codigo, descripcion 
			FROM expedido ORDER BY descripcion ";
	$query= consulta($sql);
	
	$extension = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$extension[] = array('codigo' => $row["codigo"],
							'descripcion' => $row["descripcion"]);
	}
	
	$smarty->assign('extension',$extension);
	
	$smarty->display('sec/extension/extension.html');
	die();
	
?>
