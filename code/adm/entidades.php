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
		include("entidades/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("entidades/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("entidades/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("entidades/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("entidades/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("entidades/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM entidades ORDER BY entidad ";
	$query= consulta($sql);
	$i=0;
	$entidades= array();
	
	while($row=  $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$entidades[$i]= array('id' =>$row["id"],
							'entidad' => $row["entidad"]);
		
		$i++;
	}
	
	$smarty->assign('entidades',$entidades);
	
	$smarty->display('adm/entidades/entidades.html');
	die();
	
?>
