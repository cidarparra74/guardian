<?php

$id_corriente= $_REQUEST['usuario'];

if($id_corriente == "todos"){ //todos
	$nombre="todos";
}
else{
	$sql= "SELECT * FROM usuarios WHERE id_usuario='$id_corriente' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$nombre= $resultado["nombres"];
}

$smarty->assign('id_corriente',$id_corriente);
$smarty->assign('nombre',$nombre);

$smarty->display('reportes/listado_ace_con_fa_imp.html');
die();
?>