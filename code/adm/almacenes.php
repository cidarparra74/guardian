<?php
//session_start();

//***print_r($_REQUEST);

//*****require('setup.php');
require_once("../lib/setup.php");
$smarty = new bd;	
 //echo getcwd();
 
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');



	//cargando para el overlib
	include("../lib/cargar_overlib.php");
	/*********************************operaciones de la ventana*************/
	/*********************************operaciones de la ventana*************/
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("almacenes/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("almacenes/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("almacenes/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("almacenes/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("almacenes/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("almacenes/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/ 
	
	$sql = "SELECT * FROM almacen ORDER BY nombre ";
	$query= consulta($sql);
	
	$oficinas = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$oficinas[] = array('id_oficina' => $row["id_almacen"],
							'nombre' => $row["nombre"]);
	}
	
	$smarty->assign('oficinas',$oficinas);
	
	$smarty->display('adm/almacenes/almacenes.html');
	die();
	
?>
