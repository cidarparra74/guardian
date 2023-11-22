<?php
//
//     Victor Rivas
//
  	
// cargar librerias
	session_start();
//	header ("Expires: Thu, 29 Mar 2010 23:59:00 GMT"); //la pagina expira en una fecha pasada
//	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
//	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
//	header ("Pragma: no-cache"); 
// verificamos sesiones
	if(isset($_SESSION["idusuario"]) and isset($_SESSION["nombreusr"])){
	//print_r($_REQUEST);
	//restauramos carpeta de trabajo
	//chdir( $_SESSION['MainDir'] );
	//llamamos al modulo correspondiente
	
		if(isset($_REQUEST['action'])){
			$action = $_REQUEST['action'];
			if($action==''){
				$action = '_intro.php';
			}
		}else{
			$action = '_intro.php';
		}
		require_once('../code/'.$action);

	}else{
		$_SESSION = array();
		// no se ha iniciado sesion correctamente
		header("Location: ../code/_login.php");
	}
	die();
	
?>