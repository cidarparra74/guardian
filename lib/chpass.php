<?php
require_once('../lib/conexionMNU.php');

$id= $_GET['id'];

$id= substr(str_replace("'","",$id),0,10);
$password_viejo= $_GET['act'];
$viejo_password = crypt($password_viejo,"vic");

$password_nuevo= $_GET['nue'];
$nuevo_password = crypt($password_nuevo,"vic");

//verificamos si la clave actual es valida
$sql="SELECT password FROM usuarios WHERE id_usuario='$id'";
$query = consulta($sql);
$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
if($data['password'] != $viejo_password) {
	echo '1'; //1=clave actual invalida
}else{
	if($password_nuevo != ''){
		$sql= "UPDATE usuarios SET password='$nuevo_password' WHERE id_usuario='$id' ";
		ejecutar($sql);
		echo '0'; //todo bien
	}else{
		echo '2.'; //2=clave nueva vacia
	}
	
}
?>