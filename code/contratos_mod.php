<?php

require_once("../lib/setup.php");
$smarty = new bd;
require_once('../lib/conexionSEC.php');
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//echo $glogin;
	//preparamos datos del usuario
	if(isset($_SESSION["USER"]) && $_SESSION["USER"]!=0){
		$USER = $_SESSION["USER"];
	}else{
		if(isset($_SESSION['glogin'])){
			$_SESSION["USER"]=$_SESSION['glogin'];
			$USER = $_SESSION['glogin'];
		}else{
			die("No se encontr&oacute; usuario con sesi&oacute;n activa!");
		}
	}
	
	//href
	$carpeta_entrar="_main.php?action=contratos_mod.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "contratos_mod";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	/**********************ver si pasamos a otra pantalla*************************/
	//adicionar Paso 0, elegir contrato nuevo por primera vez
	if(isset($_REQUEST['vermodelo'])){
		include("./contratos/vermodelo.php");
	}
	
	/**************************************************/
	//SELECCION DEL MODELO DE CONTRATO

	
	//recuperando los contratos del usuario
	$sql= "SELECT cu.idusuario, c.idcontrato, c.titulo
		 FROM contrato c, contratousuario cu
		 WHERE cu.idusuario = '$USER' AND cu.idcontrato=c.idcontrato AND
		 c.habilitado=1 
		 ORDER BY c.titulo";
	
	$query = consulta($sql);
	$contratos=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		$contratos[]= array('id' => $row["idcontrato"],
								'titulo' => $row["titulo"]);
		
	}
	
	$smarty->assign('contratos',$contratos);
	$smarty->display('contratos_mod.html');
	die();

?>