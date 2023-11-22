<?php

require_once("../lib/setup.php"); 
$smarty = new bd;	
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');



	//cargando para el overlib
	include("../lib/cargar_overlib.php");
	/*********************************operaciones de la ventana*************/
	/*********************************operaciones de la ventana*************/
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("objetos/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("objetos/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("objetos/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("objetos/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("objetos/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("objetos/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM objetos ORDER BY objeto ";
	$query= consulta($sql);
	$objetos= array();
	while($row=  $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$objetos[]= array('id' =>$row["id_objeto"],
							'objeto' => $row["objeto"]);
	}
	
	$smarty->assign('objetos',$objetos);
	
	$smarty->display('adm/objetos/objetos.html');
	die();
	
?>
