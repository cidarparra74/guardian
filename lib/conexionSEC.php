<?php
//
//     Archivo de configuracion interfase DB menu. Victor Rivas
//
//=============== conexion a la base de datos SQL Server ==============
  	
	//require_once("../lib/para_fechas.php");
	require_once('../lib/DB.php');
	require_once("../lib/conexion.php");
	require_once("../configsec.php");

	$dsn = "odbc://$Susuario:$Spassword@$Shost/$SbdName";

	//$bd_fecha= "mssql";

	$options = array(
		'debug'       => 2,
		'portability' => DB_PORTABILITY_ALL,
	);
/*
	$link =& DB::connect($dsn, $options); //conectamos con el servidor 
	
	if (DB::isError($link))
	{
		//echo DB::errorMessage();
		die ("Error al conectarse con la Base de Datos SEC");
	}
*/
	$db = new DB();
	$link =& $db->connect($dsn, $options); 
	$err = $db->isError($link); 
	if ($err)
	{	//echo DB::errorMessage($link);
		die ("Error al conectarse con la Base de Datos");
	}	
?>