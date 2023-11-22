<?php

require_once('../lib/conexionMNU.php');
$ida= $_GET['ida'];
$oid= $_GET['oid'];

	$ida= substr(str_replace("'","",$ida),0,10);

	$sql= "SELECT * FROM apoderados WHERE id_apoderado='$ida'";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$registro = 'ok|'.$oid.'|'.
				$resultado["apoderado"].'|'.
				$resultado["ci"].'|'.
				$resultado["tipo"].'|'.
				$resultado["vigente"].'|'.
				$resultado["porcentaje"].'|'.
				$resultado["facultades"].'|'.
				$resultado["restricciones"];
echo $registro;

?>