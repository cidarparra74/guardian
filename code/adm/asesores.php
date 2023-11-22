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
		include("asesores/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("asesores/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("asesores/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("asesores/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("asesores/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("asesores/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM asesores ORDER BY apellidos ";
	$query= consulta($sql);

	$i=0;
	$asesores= array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$asesores[$i]= array('id_asesor' => $row["id_asesor"],
							'ci' => $row["ci"],
							'nombres' => $row["nombres"],
							'apellidos' => $row["apellidos"],
							'telefonos' => $row["telefonos"],
							'direccion' => $row["direccion"]);
							
		$i++;
	}
	
	$smarty->assign('asesores',$asesores);
	$smarty->display('adm/asesores/asesores.html');
	die();
	
?>
