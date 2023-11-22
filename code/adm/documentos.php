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
		$accion = 'A';
		include("documentos/modificar.php");
	}
	
	//adicionando se hace en modificando.php

	//modificar
	if(isset($_REQUEST['modificar'])){
		$accion = 'M';

		include("documentos/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("documentos/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		$accion = 'E';
		include("documentos/modificar.php");
	}
	
	//eliminando se hace en modificando.php

	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT id_documento, documento, convert(varchar,descripcion) as descri, vencimiento, 
			meses_vencimiento, tiene_fecha, requerido, imagen
			FROM documentos ORDER BY requerido DESC, documento ASC";
			//echo $sql;
	$query= consulta($sql);
	$i=0;
	$documentos = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		if($row["vencimiento"] == 1){
			$vencimiento = "si";
		}else{
			$vencimiento = "no";
		}
		
		if($row["tiene_fecha"] == 1){
			$tiene_fecha = "si";
		}else{
			$tiene_fecha = "no";
		}
		
		if($row["requerido"] == 1){
			$grupo = "Mandatorios";
		}else{
			$grupo = "Opcionales";
		}
		
		$documentos[$i]= array('id_documento' => $row["id_documento"],
								'grupo' => $grupo,
								'documento' => $row["documento"],
								'descripcion' =>$row["descri"],
								'meses_vencimiento' =>$row["meses_vencimiento"],
								'vencimiento' => $vencimiento,
								'tiene_fecha' => $tiene_fecha,
								'imagen' => $row["imagen"] );
		
		$i++;
	}
	
	$smarty->assign('documentos',$documentos);
	$smarty->display('adm/documentos/documentos.html');
	die();
	
?>
