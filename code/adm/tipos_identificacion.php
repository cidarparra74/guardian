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
		include("tipos_identificacion/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("tipos_identificacion/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("tipos_identificacion/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("tipos_identificacion/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("tipos_identificacion/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("tipos_identificacion/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM tipos_identificacion ORDER BY identificacion ";
	$query= consulta($sql);
	$i=0;
	$tipoident= array();
	
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipoident[$i]= array('id_tipo' => $row["id_tipo"],
		 'identificacion' => $row["identificacion"],
		 'descripcion' => $row["descripcion"]);
		
		$i++;
	}
	
	$smarty->assign('tipoident',$tipoident);
	$smarty->display('adm/tipos_identificacion/tipos_identificacion.html');
	die();
	
?>
