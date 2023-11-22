<?php

require_once("../lib/setup.php"); 
$smarty = new bd;	
//require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');



	//cargando para el overlib
	include("../lib/cargar_overlib.php");
	/*********************************operaciones de la ventana*************/
	/*********************************operaciones de la ventana*************/
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("variablesec/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("variablesec/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("variablesec/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("variablesec/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("variablesec/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("variablesec/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM variable_campo ORDER BY tabla, campo, idtexto ";
	$query= consulta($sql);
	$i=0;
	$variablesec= array();
	
	while($row=  $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$variablesec[$i]= array('id' =>$row["idtexto"],
							'tabla' => $row["tabla"],
							'campo' => $row["campo"],
							'adicional' => $row["adicional"]);
		$i++;
	}
	
	$smarty->assign('variablesec',$variablesec);
	
	$smarty->display('adm/variablesec/variablesec.html');
	die();
	
?>
