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
		include("sociedades/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("sociedades/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("sociedades/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("sociedades/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("sociedades/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("sociedades/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM sociedades ORDER BY sociedad ";
	$query= consulta($sql);
	$sociedades= array();
	while($row=  $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$sociedades[]= array('id' =>$row["id_sociedad"],
							'sociedad' => $row["sociedad"]);
	}
	
	$smarty->assign('sociedades',$sociedades);
	
	$smarty->display('adm/sociedades/sociedades.html');
	die();
	
?>
