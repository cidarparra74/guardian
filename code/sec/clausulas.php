<?php
session_start();

require_once("../lib/setupSEC.php");
$smarty = new bd;	
require_once('../lib/verificar.php');


	//cargando para el overlib
	include("../lib/cargar_overlib.php");
	/*********************************operaciones de la ventana*************/
	/*********************************operaciones de la ventana*************/
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("clausulas/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("clausulas/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("clausulas/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("clausulas/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("clausulas/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("clausulas/eliminando.php");
	}
	
	//contratos en la que esta contenida la clausula
	if(isset($_REQUEST['contratos'])){
		include("sec/clausulas/contratos.php");
	}
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/ 
	
	//mostramos todos los contratos
	$sql = "SELECT idcontrato, titulo FROM contrato ORDER BY titulo";
	$miscontratos= array();
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$miscontratos[]= array('id' => $row["idcontrato"],
							'titulo' => $row["titulo"]);
	}
	$smarty->assign('miscontratos',$miscontratos);
	$idcc = '*';
	if(isset($_REQUEST['idcc'])){
		$idcc = $_REQUEST['idcc'];
		$_SESSION['idcc']=$idcc;
	}elseif(isset($_SESSION['idcc']) and $_SESSION['idcc'] != ''){
		$idcc = $_SESSION['idcc'];
	} 
	//las clausulas del contrato:
	if($idcc != '*'){
	$sql = "SELECT cl.idclausula, rc.posicion, cl.titulo, cl.descri, count(*) as incisos 
		FROM clausula cl 
		INNER JOIN rel_cc rc ON rc.idclausula = cl.idclausula 
		LEFT JOIN numeral nu ON nu.idclausula = cl.idclausula 
		WHERE rc.idcontrato = $idcc 
		GROUP BY cl.idclausula, cl.titulo, cl.descri, rc.posicion 
		ORDER BY rc.posicion ";
	}else{
	$sql = "SELECT cl.idclausula, cl.titulo, cl.descri, count(*) as incisos 
		FROM clausula cl
		LEFT JOIN numeral nu ON nu.idclausula = cl.idclausula
		GROUP BY cl.idclausula, cl.titulo, cl.descri
		ORDER BY cl.titulo ";
	}
	$query= consulta($sql);
	
	$clausulas = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$clausulas[] = array('id' => $row["idclausula"],
							'posicion' => '0',
							'titulo' => $row["titulo"],
							'descri' => $row["descri"],
							'incisos' => $row["incisos"]);
							//aumentar descripcion
	}
	
	$smarty->assign('clausulas',$clausulas);
	$smarty->assign('idcc',$idcc);
	
	$smarty->display('sec/clausulas/clausulas.html');
	die();
	
?>
