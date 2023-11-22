<?php

require_once('../lib/conexionMNU.php');

$nombres= strtoupper($_GET['nom']);
//$mis= $_GET['mis'];
$telefonos= htmlentities($_GET['fon']);
$direccion= htmlentities($_GET['dir']);
$estado_civil= htmlentities($_GET['eci']);
$nit= htmlentities($_GET['nit']);
$ci= htmlentities($_GET['ci']);
$xcu= htmlentities($_GET['xcu']);
$emision= htmlentities($_GET['emi']);
$id_propietario = '1';
// verificar si ya existe CI

	$ci= substr(str_replace(";","",$ci),0,15);
$sql = "SELECT nombres FROM propietarios WHERE ci = '$ci'";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
if($row["nombres"]==''){
	//fecha actual
	$fecha_actual= date("Y-m-d H:i:s");
	$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
	$sql= "INSERT INTO propietarios (nombres, mis, ci, direccion, 
		telefonos, emision, creacion_propietario, estado_civil, nit) 
		VALUES('$nombres', '$ci', '$ci', '$direccion', 
		'$telefonos', '$emision', $fecha_actual, '$estado_civil', '$nit') ";
	ejecutar_con_filter($sql);
	echo $nombres."|".$ci."|".$id_propietario."|".$xcu;
}else{
	echo "||0|9";
}
?>