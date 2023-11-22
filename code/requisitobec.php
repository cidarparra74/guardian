<?php
require_once("../lib/setup.php");
$smarty = new bd;	
//require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
//cargando para el overlib
require_once("../lib/cargar_overlib.php");

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
if(isset($_REQUEST['documentos'])){
	include("./requisito/documentosver.php");
}
//*****************************************************************
	//recuperando los lugares de emision
	$sql= "SELECT * FROM emisiones ";
	$query = consulta($sql);
	$i=0;
	$emisiones=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$emisiones[$i]= $row["emision"];
		$i++;
	}
	$smarty->assign('emisiones',$emisiones);
	
	//recuperando los tipos de bien
	$sql= "SELECT * FROM tipos_bien WHERE con_recepcion = 'S' AND categoria = '1' ORDER BY id_tipo_bien ";
	$query = consulta($sql);
	$i=0;
	$tiposbien=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tiposbien[$i]= array('id' => $row["id_tipo_bien"],
							'descri' => $row["tipo_bien"]);
		$i++;
	}
	
	$smarty->assign('tiposbien',$tiposbien);
	
	
	$smarty->display('requisitobec.html');
	die();

?>
