<?php
	require_once('../lib/conexionMNU.php');
	require_once('../lib/fechas.php');
	$login= $_GET['login'];
	$antes= $_GET['antes'];
	$login= substr(str_replace("'","",$login),0,20); //SQLi
	$antes= substr(str_replace("'","",$antes),0,20); //SQLi
	$login = strtoupper($login);
	//buscamos los datos de esta placa si es que existiera
	if($antes!='')
		$sql= "SELECT login FROM usuarios WHERE login='$login' and login <> '$antes'";
	else
		$sql= "SELECT login FROM usuarios WHERE login='$login' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$login= $resultado["login"];
	if($respuesta != null || $respuesta != '')
		echo "T"; //existe el login
	else
		echo "F"; //no existe
	
?>
