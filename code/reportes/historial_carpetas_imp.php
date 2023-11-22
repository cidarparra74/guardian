<?php

$gaveta= $_REQUEST['gaveta'];
$tipo= $_REQUEST['tipo'];
$oficina= $_REQUEST['oficina'];
$estado= $_REQUEST['estado'];
$posicion_gaveta= $_REQUEST['posicion_gaveta'];

$titulo_doble="ninguno";
if($gaveta != "todos"){
	if($posicion_gaveta != "todos"){
		$titulo_doble= $posicion_gaveta.$gaveta;
	}
}

$titulo_estado= "";
if($estado == "todos"){
	$titulo_estado= "Estado: todos";
}
if($estado == 1){
	$titulo_estado= "Estado: Carpetas Solicitadas";
}
if($estado == 2){
	$titulo_estado= "Estado: Carpetas Rechazadas";
}
if($estado == 3){
	$titulo_estado= "Estado: Aceptados con Firma Autorizada";
}
if($estado == 4){
	$titulo_estado= "Estado: Prestados a Solicitante sin Confirmar";
}
if($estado == 5){
	$titulo_estado= "Estado: Prestados a Solicitante Confirmados";
}
if($estado == 6){
	$titulo_estado= "Estado: Devueltos a Boveda por Solicitante sin Confirmar";
}
if($estado == 7){
	$titulo_estado= "Estado: Devueltos a Boveda Confirmados";
}
if($estado == 8){
	$titulo_estado= "Estado: Devueltos al Cliente";
}


//para los titulos
$titulo_gaveta= "Gaveta: ".$gaveta;
$titulo_posicion= "Posición Gaveta: ".$posicion_gaveta;

if($tipo != "todos"){
	$sql= "SELECT * FROM tipos_carpetas WHERE id_tipo_carpeta='$tipo' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo_tipo= "Tipo de Carpetas: ".$resultado["tipo"];
}
else{
	$titulo_tipo= "Tipo de Carpetas: todos";
}

//oficinas
if($oficina != "todos"){
	$sql= "SELECT * FROM oficinas WHERE id_oficina='$oficina' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo_oficina= "Oficina: ".$resultado["nombre"];
}
else{
	$titulo_oficina= "Oficina: todos";
}



//fechas de creacion
$anios= $_REQUEST['fecha1Year'];
$meses= $_REQUEST['fecha1Month'];
$dias= $_REQUEST['fecha1Day'];
$titulo= "Del: $dias / $meses / $anios  ";

$fecha_aux= $anios."-".$meses."-".$dias." 00:00:00";
$fecha_inicio= "CONVERT(DATETIME,'$fecha_aux',102)";

$anios= $_REQUEST['fecha2Year'];
$meses= $_REQUEST['fecha2Month'];
$dias= $_REQUEST['fecha2Day'];
$titulo=$titulo."AL: $dias / $meses / $anios ";

$fecha_aux= $anios."-".$meses."-".$dias." 00:00:00";
$fecha_fin= "CONVERT(DATETIME,'$fecha_aux',102)";

$smarty->assign('titulo_gaveta',$titulo_gaveta);
$smarty->assign('titulo_posicion',$titulo_posicion);
$smarty->assign('titulo_tipo',$titulo_tipo);
$smarty->assign('titulo_oficina',$titulo_oficina);
$smarty->assign('titulo_estado',$titulo_estado);
$smarty->assign('titulo',$titulo);
$smarty->assign('titulo_doble',$titulo_doble);

$smarty->assign('gaveta',$gaveta);
$smarty->assign('tipo',$tipo);
$smarty->assign('oficina',$oficina);
$smarty->assign('estado',$estado);
$smarty->assign('posicion_gaveta',$posicion_gaveta);
$smarty->assign('fecha_inicio',$fecha_inicio);
$smarty->assign('fecha_fin',$fecha_fin);

$smarty->display('reportes/historial_carpetas_imp.html');
die();
?>