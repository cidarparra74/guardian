<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	


$nombres= $_REQUEST['nombres'];
$id_perfil= $_REQUEST['perfil'];
$login= $_REQUEST['login'];
$antes= $_REQUEST['antes'];
$password= $_REQUEST['pasword'];
//revisamos login
$login=addslashes($login);
$login = str_replace("'","",strtoupper($login));
$login = substr(str_replace(";","",$login),0,20);

if(isset($_REQUEST['chkpass']))
	$cambia_pass = 'S';
else
	$cambia_pass = 'N';

//validar que no exista el login
if($antes!='')
		$sql= "SELECT COUNT(login) AS nro FROM usuarios WHERE login='$login' and login <> '$antes'";
	else
		$sql= "SELECT COUNT(login) as nro FROM usuarios WHERE login='$login' ";
//echo $sql;
$query = consulta($sql);
$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
if($data['nro'] == '0'){

	$password_poner = crypt($password,"vic");
	$activo= $_REQUEST['activo'];
	$id_oficina= $_REQUEST['oficina'];
	$correoe= $_REQUEST['correoe'];

	$ci= $_REQUEST['ci'];
	$telefono= $_REQUEST['telefono'];
	$direccion= $_REQUEST['direccion'];

	$sql= "INSERT INTO usuarios(id_perfil, nombres, login, password, activo, id_oficina, correoe, ci, telefono, direccion, ingresos, cambia_pass) ";
	$sql.= "VALUES('$id_perfil', '$nombres', '$login', '$password_poner', '$activo', '$id_oficina', '$correoe', '$ci', '$telefono', '$direccion', 3, '$cambia_pass') ";
	//echo $sql;
	ejecutar($sql);
}else{
	$alerta = 'El login ingresado ya existe, por favor indique otro.';
	$smarty->assign('alerta',$alerta);
	$smarty->assign('nombres',$nombres);
	include("../code/adm/usuarios/adicionar.php");
	die();
}

?>
