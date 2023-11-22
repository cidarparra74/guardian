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
		include("responsables/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("responsables/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("responsables/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("responsables/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("responsables/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("responsables/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM responsables ORDER BY apellidos ";
	$query= consulta($sql);
	$i=0;
	$responsables= array();
	
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$responsables[$i]= array('id_responsable' => $row["id_responsable"],
		'ci' => $row["ci"],
		'nombres' => $row["nombres"],
		'apellidos' => $row["apellidos"],
		'telefonos' => $row["telefonos"],
		'direccion' => $row["direccion"]);
		$i++;
	}
	
	$smarty->assign('responsables',$responsables);
	$smarty->display('adm/responsables/responsables.html');
	die();
	
?>
