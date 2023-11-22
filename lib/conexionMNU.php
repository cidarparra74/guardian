<?php
//
//     Archivo de configuracion interfase DB menu. Victor Rivas
//
//=============== conexion a la base de datos SQL Server ==============
  	
	//require_once("../lib/para_fechas.php");
	require_once('../lib/DB.php');
	require_once("../lib/conexion.php");
	require_once("../lib/codificar.php");
	
	if(file_exists("../config.php")){
		//esto para compatibilidad versiones anteriores
		require_once("../config.php");
	}else{
		if(file_exists("../config.ini")){
			$laConfig=parse_ini_file("../config.ini");
			$usuario = $laConfig['usuario']; 
			$password = $laConfig['password']; 
			$host= $laConfig['host']; 
			$bdName = $laConfig['bdName']; 
			$usuario=decode($usuario);
			$password=decode($password);
		

		}else{
			echo "No se defini&oacute; par&aacute;metros de conexi&oacute;n!";
			header("Location: ../code/_gencla.php");
			die();
		}
	}
	$dsn = "odbc://$usuario:$password@$host/$bdName";
	//$bd_fecha= "mssql";

	$options = array(
		'debug'       => 2,
		'portability' => DB_PORTABILITY_ALL,
	);
	$db = new DB();
	
	$link =& $db->connect($dsn, $options); 
//print_r($link);
	//$link =& DB::connect($dsn, $options); //conectamos con el servidor 
	//$bd_fechas= new p_fecha;
	//$db = new DB();
//echo $dsn."<br>";
	$err = $db->isError($link); 
//echo $err;
//die();
	//if (DB::isError($link))
	if ($err)
	{	//echo DB::errorMessage($link);
		//die ("Error al conectarse con la Base de Datos");
		header("Location: ../code/_gencla.php");
			die();
	}
	
?>