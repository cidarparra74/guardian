<?php
//session_start();
/************************************************************************************/
/************************************************************************************/


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
		include("tramitadores/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("tramitadores/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("tramitadores/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("tramitadores/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("tramitadores/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("tramitadores/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM tramitadores ORDER BY tramitador ";
	$query= consulta($sql);
	$i=0;
	$tramitadores= array();
	
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tramitadores[$i]= array('id_tramitador' => $row["id_tramitador"],
		'tramitador' => $row["tramitador"],
		'telefonos' => $row["telefonos"],
		'direccion' => $row["direccion"]);
		
		$i++;
	}
	
	$smarty->assign('tramitadores',$tramitadores);
	$smarty->display('adm/tramitadores/tramitadores.html');
	die();
	
?>
