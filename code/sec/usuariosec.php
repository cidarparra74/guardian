<?php
//session_start();

require_once("../lib/setupSEC.php");
$smarty = new bd;	
require_once('../lib/verificar.php');


	//cargando para el overlib
	include("../lib/cargar_overlib.php");
	/*********************************operaciones de la ventana*************/
	/*********************************operaciones de la ventana*************/
	//href
	$carpeta_entrar="_main.php?action=sec/usuariosec.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	
	//relacionar contratos
	if(isset($_REQUEST['contrato'])){
		include("usuariosec/contratousr.php");
	}
	
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("usuariosec/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("usuariosec/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("usuariosec/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("usuariosec/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("usuariosec/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("usuariosec/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/ 
	
	$sql = "SELECT localizacion, appaterno, apmaterno, nombres, login 
	FROM usuario WHERE estado = '1'
	ORDER BY localizacion, nombres, appaterno, apmaterno  ";
	$query= consulta($sql);
	
	$usuariosec = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$usuariosec[] = array('localizacion' => $row["localizacion"],
							'nombre' => $row["nombres"].' '.$row["appaterno"].' '.$row["apmaterno"],
							'login' => $row["login"]);
	}
	
	$smarty->assign('usuariosec',$usuariosec);
	
	$smarty->display('sec/usuariosec/usuariosec.html');
	die();
	
?>
