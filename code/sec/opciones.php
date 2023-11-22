<?php

require_once('../lib/verificar.php');

require_once("../lib/setupSEC.php");
$smarty = new bd;	

	//cargando para el overlib
//	include("../lib/cargar_overlib.php");
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		$cta_ahorro = $_REQUEST["cta_ahorro"];
		$cta_corriente = $_REQUEST["cta_corriente"];
		$conjunta = $_REQUEST["conjunta"];
		$indistinta = $_REQUEST["indistinta"];
		$servicios = $_REQUEST["servicios"]; 
		$servadic = $_REQUEST["servadic"];
		$fallindi = $_REQUEST["fallindi"];
		$fallconj = $_REQUEST["fallconj"]; 
		$servtarjeta = $_REQUEST["servtarjeta"];
		$servtarindi = $_REQUEST["servtarindi"];
		
		$sql = "UPDATE [parametros_c]
				SET [cta_ahorro] = '$cta_ahorro',
					[cta_corriente] = '$cta_corriente' ,
					[conjunta] = '$conjunta',
					[indistinta] = '$indistinta',
					[servicios] = '$servicios',
					[servadic] = '$servadic',
					[fallindi] = '$fallindi',
					[fallconj] = '$fallconj',
					[servtarjeta] = '$servtarjeta',
					[servtarindi] = '$servtarindi'";
				
		ejecutar($sql);
		$disable = 'readonly';
		$ok='S';
	}else{
	
		/***************************************************************/
		//valores por defecto
		/***************************************************************/
			
		$sql= "select * from tipo"; 
		$query= consulta($sql);
		$tipos = array();
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$tipos[]= array('id' => $row["codigo"],
							'tipo' => $row["descripcion"]);
		}
		$smarty->assign('tipos',$tipos);
		
		
		$sql= "select idclausula, descri from clausula order by descri"; 
		$query= consulta($sql);
		$clausulas = array();
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$clausulas[]= array('id' => $row["idclausula"],
							'tipo' => $row["descri"]);
		}
		$smarty->assign('clausulas',$clausulas);
		
		
		$sql= "select idcontrato, titulo from contrato order by titulo"; 
		$query= consulta($sql);
		$contratos = array();
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$contratos[]= array('id' => $row["idcontrato"],
							'tipo' => $row["titulo"]);
		}
		$smarty->assign('contratos',$contratos);
		
		/***************************************************************/
		$ok='N';
		$sql = "SELECT * FROM parametros_c ";
		$query= consulta($sql);
		if($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$cta_ahorro = $row["cta_ahorro"];
			$cta_corriente = $row["cta_corriente"];
			$conjunta = $row["conjunta"];
			$indistinta = $row["indistinta"];
			$servicios = $row["servicios"];
			$servadic = $row["servadic"];
			$fallindi = $row["fallindi"];
			$fallconj = $row["fallconj"];
			$servtarindi = $row["servtarindi"];
			$servtarjeta = $row["servtarjeta"];
		}else{
			
			$sql = "INSERT INTO [parametros_c]
           ([cta_ahorro]
           ,[cta_corriente]
           ,[conjunta]
           ,[indistinta]
           ,[servicios])
			VALUES ('0' ,'0' ,'0' ,'0' ,'0')";
			
			ejecutar($sql);
			$cta_ahorro = '0';
			$cta_corriente = '0';
			$conjunta = '0';
			$indistinta = '0';
			$servicios = '0';
			//echo $sql;
		}
			$disable = '';
	}
	//
	$smarty->assign('ok',$ok);
	$smarty->assign('cta_ahorro',$cta_ahorro);
	$smarty->assign('cta_corriente',$cta_corriente);
	$smarty->assign('conjunta',$conjunta);
	$smarty->assign('indistinta',$indistinta);
	$smarty->assign('servicios',$servicios);
	$smarty->assign('servadic',$servadic);
	$smarty->assign('fallindi',$fallindi);
	$smarty->assign('fallconj',$fallconj);
	$smarty->assign('servtarindi',$servtarindi);
	$smarty->assign('servtarjeta',$servtarjeta);
	$smarty->assign('disable',$disable);
	
	$smarty->display('sec/opciones/opciones.html');
	die();
?>