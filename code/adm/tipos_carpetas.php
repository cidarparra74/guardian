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
		include("tipos_carpetas/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("tipos_carpetas/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("tipos_carpetas/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("tipos_carpetas/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("tipos_carpetas/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("tipos_carpetas/eliminando.php");
	}
	
	//documentos del tipo de carpeta
	if(isset($_REQUEST['documentos'])){
		include("tipos_carpetas/documentos.php");
	}
	
	//guardando los documentos del tipo de carpeta
	if(isset($_REQUEST['guardar_documentos'])){
		include("tipos_carpetas/guardar_documentos.php");
	}
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM tipos_carpetas ORDER BY tipo ";
	$query= consulta($sql);
	$i=0;
	$tipocarpetas= array();
	
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipocarpetas[$i]= array('id_tipo_carpeta' => $row["id_tipo_carpeta"],
		 'tipo' => $row["tipo"],
		 'descripcion' => $row["descripcion"],
		 'cuenta_ingreso' => $row["cuenta_ingreso"],
		 'cuenta_devolucion' => $row["cuenta_devolucion"]);
		
		$i++;
	}
	
	$smarty->assign('tipocarpetas',$tipocarpetas);
	$smarty->display('adm/tipos_carpetas/tipos_carpetas.html');
	die();
	
?>
