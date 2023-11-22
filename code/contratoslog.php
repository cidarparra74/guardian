<?php
//session_start();
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
//vemos que login tiene el user para validarlo en SEC
	$quien = $_REQUEST["who"];
	$guser = $_SESSION["idusuario"];
	//leemos del guardian
	$sql = "SELECT login, nombres FROM usuarios WHERE id_usuario=$guser";
	//echo $sql;
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$glogin = $row["login"];
	$gnombre = $row["nombres"];
	$_SESSION['glogin'] = $glogin;
	
	//leemos la URL para los WS del SEC
	$sql = "SELECT TOP 1 ws_url4, tipodoc FROM opciones";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	if($row['ws_url4']==''){
		echo 'No se pudo completar la operación, URL no definida ';
			echo '<br>';
			echo 'Revise al configuraci&oacute;n del Servicio WEB para el SEC.';
			die();
	}
	$_SESSION['ws_url4']=$row['ws_url4'];
	$_SESSION['tipodoc']=$row['tipodoc'];

	unset($link); 
	
//verificar si este usuario esta registrado en SEC
require_once('../lib/conexionSEC.php');
	//leemos del SEC
	$sql = "SELECT login FROM usuario WHERE login='$glogin'";
	//echo $sql;
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$slogin = $row["login"];
	if(strtoupper(trim($slogin))!=strtoupper(trim($glogin))){
		//NO existe user en SEC
		//insertamos
		$sql="INSERT INTO usuario (login, nombres, estado, idperfil) VALUES ('$glogin', '$gnombre', '1', '0' )";
		//echo $sql;
		ejecutar($sql);
	}
	if(isset($_SESSION["quien"]))  unset($_SESSION["quien"]);
	if(isset($_SESSION["word"]))   unset($_SESSION["word"]);
	if(isset($_SESSION["fuente"])) unset($_SESSION["fuente"]);
	//vemos si puede abrir en word
	if(isset($_REQUEST["view"])){
		$word = $_REQUEST["view"];
	}else{
		$word = 'n';
	}
	$_SESSION['word']=$word;
	$_SESSION["tipopersona"]='';
	// $glogin sera el que nos indique con que usuario trabajar en SEC
	if($quien=='1' || $quien=='4' || $quien=='6' || $quien=='9'  || $quien=='10'){
		//listando para el usuario sin VoBo || usuarios contr automatico || con VoBo !! puede firmar
		require_once('../code/contratos.php');
	}elseif($quien=='2' || $quien=='5' || $quien=='7'){
		//listando a nivel Regional sin Notific || Regional con Notific || a nivel de usuarios relacionados en SEC 
		require_once('../code/contratosr.php');
	}elseif($quien=='3'){
		// listando a nivel nacional
		require_once('../code/contratosn.php');
	}elseif($quien=='8'){
		// con firma condicional segun campo 'con_firma_abogado' en SEC
		require_once('../code/contratos.php');
	}elseif($quien=='A' or $quien=='B'){
		// CONTRATO CAJA DE AHORRO / CUENTA CORRIENTE
		require_once('../code/contratos_pla.php');
	}elseif($quien=='C'){
		// CONTRATO SERVICIOS ADICIONALES
		require_once('../code/contratos/adicionar_ser.php');
	}
?>