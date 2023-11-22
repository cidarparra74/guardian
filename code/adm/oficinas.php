<?php
//session_start();

require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');


	//cargando para el overlib
	include("../lib/cargar_overlib.php");
	//verificar que banco es
	$sql = "SELECT TOP 1 enable_ws FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	$smarty->assign('enable_ws',$enable_ws);
	/*********************************operaciones de la ventana*************/
	/*********************************operaciones de la ventana*************/
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("oficinas/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("oficinas/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("oficinas/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("oficinas/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("oficinas/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("oficinas/eliminando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['representa'])){
		include("oficinas/representa.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/ 
	
	$sql = "SELECT al.nombre as almacen, ofi.* 
	FROM oficinas ofi LEFT JOIN almacen al ON ofi.id_almacen = al.id_almacen 
	ORDER BY al.nombre, ofi.codigo, ofi.nombre ";
	$query= consulta($sql);
	
	$oficinas = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$oficinas[] = array('id_oficina' => $row["id_oficina"],
							'codigo' => $row["codigo"], 
							'nombre' => $row["nombre"],
							'ciudad' =>	$row["almacen"],
							'id_almacen' =>	$row["id_almacen"]);
	}
	
	$smarty->assign('oficinas',$oficinas);
	
	$smarty->display('adm/oficinas/oficinas.html');
	die();
	
?>
