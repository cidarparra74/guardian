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
		include("tipos_documentos/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("tipos_documentos/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("tipos_documentos/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("tipos_documentos/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("tipos_documentos/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("tipos_documentos/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM tipos_documentos ORDER BY tipo ";
	$query= consulta($sql);
	$i=0;
	$tipodocs= array();
	
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipodocs[$i]= array('id_tipo_documento' => $row["id_tipo_documento"],
		'tipo' => $row["tipo"],
		'descripcion' => $row["descripcion"]);
		
		$i++;
	}
	
	$smarty->assign('tipodocs',$tipodocs);
	$smarty->display('adm/tipos_documentos/tipos_documentos.html');
	die();
	
?>
