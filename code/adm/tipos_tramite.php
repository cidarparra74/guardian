<?php
//session_start();


require('setup.php');
$smarty = new bd;	
include("conexion_pear.php");

//session_start();
//verificando al administrador del sistema
$login_ver= $_SESSION['nombreadm'];
$password_ver= $_SESSION['passwordadm'];

include("verificar_admin.php");

if(!verificar_entrada($login_ver, $password_ver)){
	$smarty->display("administrador/ingreso_no_valido.html");
	die();
}

	//cargando para el overlib
	include("../archivo/cargar_overlib.php");
	/*********************************operaciones de la ventana*************/
	/*********************************operaciones de la ventana*************/
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("tipos_tramite/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("tipos_tramite/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("tipos_tramite/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("tipos_tramite/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("tipos_tramite/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("tipos_tramite/eliminando.php");
	}
	
	//documentos del tipo de bien
	if(isset($_REQUEST['documentos'])){
		include("tipos_tramite/documentos.php");
	}
	
	//guardando los documentos del tipo de bien
	if(isset($_REQUEST['guardar_documentos'])){
		include("tipos_tramite/guardar_documentos.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM tipos_tramite ORDER BY descripcion ";
	$result= $link->query($sql);
	$i=0;
	$ids_tipo_tramite= array();
	$descripcion= array();
	
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_tipo_tramite[$i]= array('id' => $row["id_tipo_tramite"],
									  'des' => $row["descripcion"]);
		
		$i++;
	}
	
	$smarty->assign('ids_tipo_tramite',$ids_tipo_tramite);
	
	$smarty->display('administrador/tipos_tramite/tipos_tramite.html');
	die();
	
?>