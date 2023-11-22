<?php

require_once("../lib/setup.php"); 
$smarty = new bd;	
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');


	//cargando para el overlib
	include("../lib/cargar_overlib.php");
	/*********************************operaciones de la ventana*************/
	/*********************************operaciones de la ventana*************/
	//contratos
	if(isset($_REQUEST['contratos'])){
		include("bancas/contratos.php");
	}
	
	//guardar contratos de la banca
	if(isset($_REQUEST['contrato_boton'])){
		include("bancas/contrato_guardar.php");
	}
	
	//contratosver
	if(isset($_REQUEST['contratosver'])){
		include("bancas/contratosver.php");
	}
	
	//clausulas
	if(isset($_REQUEST['clausula'])){
		include("bancas/clausula.php");
	}
	
	//guardar clausulas 
	if(isset($_REQUEST['clausula_boton'])){
		include("bancas/clausula_guardar.php");
	}
	
	//incisos
	if(isset($_REQUEST['inciso'])){
		include("bancas/inciso.php");
	}
	
	//guardar clausulas 
	if(isset($_REQUEST['inciso_boton'])){
		include("bancas/inciso_guardar.php");
	}
	
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("bancas/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("bancas/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("bancas/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("bancas/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("bancas/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("bancas/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT * FROM bancas ORDER BY banca ";
	$query= consulta($sql);
	$i=0;
	$bancas= array();
	
	while($row=  $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$bancas[$i]= array('id' =>$row["id_banca"],
							'codigo' => $row["codigo"],
							'banca' => $row["banca"]);
		
		$i++;
	}
	
	$smarty->assign('bancas',$bancas);
	
	$smarty->display('adm/bancas/bancas.html');
	die();
	
?>
