<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$nombres= $_REQUEST['nombres'];
$id_perfil= $_REQUEST['perfil'];
$login= $_REQUEST['login'];
$login = strtoupper($login);
$login=addslashes($login);
//$cambiar= $_REQUEST['cambiar'];
//if(isset($_REQUEST['pasword']))
	$password_nuevo= $_REQUEST['pasword'];

$poner_password = crypt($password_nuevo,"vic");
$activo= $_REQUEST['activo'];
$id_oficina= $_REQUEST['oficina'];
$correoe= $_REQUEST['correoe'];
$ci= $_REQUEST['ci'];
$telefono= $_REQUEST['telefono'];
$direccion= $_REQUEST['direccion'];

if(isset($_REQUEST['chkpass']))
	$cambia_pass = 'S';
else
	$cambia_pass = 'N';
	
$sql= "UPDATE usuarios SET id_perfil='$id_perfil', nombres='$nombres', ingresos = 1,
login='$login', activo='$activo', id_oficina='$id_oficina' , correoe='$correoe' ,
ci='$ci', telefono='$telefono', direccion='$direccion', cambia_pass = '$cambia_pass'
WHERE id_usuario='$id' ";
	ejecutar($sql);
	
if($password_nuevo != ''){

	$sql= "UPDATE usuarios SET  password='$poner_password' WHERE id_usuario='$id' ";
	ejecutar($sql);
	/*
	//verificando si es usuario nuevo
	$sql= "SELECT ingresos FROM usuarios WHERE id_usuario='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$ingresos= $resultado["ingresos"];
	if($ingresos == 3){ 
		$sql= "UPDATE usuarios SET ingresos='0' WHERE id_usuario='$id' ";
		ejecutar($sql);
	}
	*/
}

?>
