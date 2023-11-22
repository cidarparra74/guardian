<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$fecha_eli= $_REQUEST['fecha_eli'];
$fecha_eli = "CONVERT(datetime,'$fecha_eli',103)";

$idus = $_SESSION["idusuario"];
$sql = "SELECT  u.login
	FROM usuarios u  ".
	"WHERE u.id_usuario='$idus' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$user_eli= $resultado["login"];

$sql = "UPDATE usuarios SET activo = 'E', fecha_eli = $fecha_eli, user_eli = '$user_eli', login = '.'+login WHERE id_usuario='$id'";
ejecutar($sql);
//echo $sql;
?>