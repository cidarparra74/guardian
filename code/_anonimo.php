<?php
//
//     Victor Rivas
//
  	
// cargar librerias
//	session_start();
	
// verificamos sesiones
	if(isset($_REQUEST["flag"])){
		$flag = $_REQUEST["flag"];
		if(isset($_REQUEST['action'])){
			$action = $_REQUEST['action'];
			if($action!=''){
				require_once('../code/'.$action);
				
			}
		}else{
			header("Location: ../code/_login.php");
		}
		

	}else{
		$_SESSION = array();
		// no se ha iniciado sesion correctamente
		header("Location: ../code/_login.php");
	}
	die();
	
?>