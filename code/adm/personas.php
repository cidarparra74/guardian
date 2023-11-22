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
		include("adm/personas/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("adm/personas/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("adm/personas/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("adm/personas/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("adm/personas/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("adm/personas/eliminando.php");
	}
	
	//asignar oficinas
	if(isset($_REQUEST['ofis'])){
		include("adm/personas/oficinas.php");
	}
	//asignando oficinas
	if(isset($_REQUEST['ofis_boton'])){
		include("adm/personas/oficinando.php");
	}
	
	//asignar especialidad
	if(isset($_REQUEST['tipos'])){
		include("adm/personas/experiencia.php");
	}
	//asignando especialidad
	if(isset($_REQUEST['tipos_boton'])){
		include("adm/personas/experienciando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	//$id_almacen = $_SESSION['id_almacen'];
	
	$sql = "SELECT pe.id_persona, pe.nombres, pe.apellidos, pe.tipo_rol, pe.id_oficina, al.nombre as recinto
	FROM personas pe 
	LEFT JOIN almacen al ON al.id_almacen = pe.id_oficina 
	ORDER BY tipo_rol, recinto, apellidos";
	$query= consulta($sql);

	$personas= array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$personas[]= array('id_persona' => $row["id_persona"],
							'nombres' => $row["nombres"],
							'apellidos' => $row["apellidos"],
							'tipo_rol' => $row["tipo_rol"],
							'id_oficina' => $row["id_oficina"],
							'recinto' => $row["recinto"]);
	}
	
	$smarty->assign('personas',$personas);
	$smarty->display('adm/personas/personas.html');
	die();
	
?>