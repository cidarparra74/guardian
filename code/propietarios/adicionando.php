<?php

$nombres= $_REQUEST['nombres'];
//$mis= $_REQUEST['mis'];
$telefonos= $_REQUEST['telefonos'];
$direccion= $_REQUEST['direccion'];
$estado_civil= $_REQUEST['estado_civil'];
$nit= $_REQUEST['nit'];
$ci= $_REQUEST['ci_cliente'];
$emision= $_REQUEST['emision'];
$xnombres= $_REQUEST['xnombres'];
$xtelefonos= $_REQUEST['xtelefonos'];
$xdireccion= $_REQUEST['xdireccion'];

	$direccion = str_replace("'","''",$direccion);
// verificar si ya existe CI
$sql = "SELECT nombres FROM propietarios WHERE ci = '$ci' AND emision = '$emision'";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
if($row["nombres"]==''){
	$fecha_actual= date("Y-m-d H:i:s");
	$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
	$sql= "INSERT INTO propietarios (nombres, mis, ci, direccion, 
		telefonos, emision, creacion_propietario, estado_civil, nit) 
		VALUES('$nombres', '$ci', '$ci', '$direccion', 
		'$telefonos', '$emision', $fecha_actual, '$estado_civil', '$nit') ";
	ejecutar($sql);
}else{
	//aqui nunca debiera ingresar
	$smarty->assign('alerta',"El nro documento ingresado ya esta registrado a nombre de ".$row["nombres"]);
	
	include("./propietarios/adicionar.php");
}
?>
