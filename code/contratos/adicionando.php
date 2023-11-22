<?php

$nombres= $_REQUEST['nombres'];
//$mis= $_REQUEST['mis'];
$telefonos= $_REQUEST['telefonos'];
$direccion= $_REQUEST['direccion'];
$estado_civil= $_REQUEST['estado_civil'];
$nit= $_REQUEST['nit'];
$ci= $_REQUEST['ci'];
$emision= $_REQUEST['emision'];

$tipo_identificacion= $_REQUEST['tipo_identificacion'];

// verificar si ya existe CI
$sql = "SELECT nombres FROM propietarios WHERE ci = '$ci'";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
if($row["nombres"]==''){
	//fecha actual
	$fecha_actual= date("Y-m-d H:i:s");
	$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
	$sql= "INSERT INTO propietarios (nombres, mis, ci, direccion, 
		telefonos, id_tipo_identificacion, creacion_propietario, estado_civil, nit, emision) 
		VALUES('$nombres', '$ci', '$ci', '$direccion', 
		'$telefonos', '$tipo_identificacion', $fecha_actual, '$estado_civil', '$nit', '$emision') ";
	ejecutar($sql);

}else{
	$smarty->assign('alerta',"El nro documento ingresado ya esta registrado a nombre de ".$row["nombres"]);
	$smarty->assign('nombres',$nombres);
	//$smarty->assign('mis',$mis);
	$smarty->assign('emision',$emision);
	$smarty->assign('telefonos',$telefonos);
	$smarty->assign('direccion',$direccion);
	$smarty->assign('ci',$ci);
	include("./personas/adicionar.php");
}
?>
