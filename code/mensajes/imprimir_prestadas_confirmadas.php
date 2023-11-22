<?php

//lista de carpetas aceptadas con firma autorizada

$solicitante= $id_us_actual;
if($solicitante != "ninguno"){
	$query_a= $solicitante;
}
else{
	$query_a= "ninguno";
}

$oficina= "ninguno";
if($oficina != "ninguno"){
	$query_b= $oficina;
	//recuperando el nombre de la oficina
	$sql= "SELECT nombre FROM oficinas WHERE id_oficina='$oficina' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo_b= "Oficina: ".$resultado["nombre"];
}
else{
	$query_b= "ninguno";
	$titulo_b= "";
}
	
	//variables de conexion
	
	$smarty->assign('query_a',$query_a);
	$smarty->assign('query_b',$query_b);
	$smarty->assign('titulo_b',$titulo_b);
	
	$smarty->display('mensajes/imprimir_prestadas_confirmadas.html');
	die();

?>
