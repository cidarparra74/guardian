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
		include("notarios/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("notarios/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("notarios/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("notarios/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("notarios/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("notarios/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM notarios ORDER BY notario ";
	$query= consulta($sql);
	$i=0;
	$notarios= array();
	
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$notarios[$i]= array('id_notario' => $row["id_notario"],
				'notario' => $row["notario"],
				'telefonos' => $row["telefonos"],
				'direccion' => $row["direccion"]);
		
		$i++;
	}
	
	$smarty->assign('notarios',$notarios);
	$smarty->display('adm/notarios/notarios.html');
	die();
	
?>
