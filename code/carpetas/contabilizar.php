<?php
	//si $asiento es 'a' es alta se contabioliza
	//insertamos en comprobantes

$id = $_REQUEST['id'];

//sacamos nro de caso
$sql = "SELECT il.nrocaso, tb.cuenta FROM carpetas ca 
LEFT JOIN informes_legales il ON ca.id_informe_legal = il.id_informe_legal
LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = ca.id_tipo_carpeta
WHERE id_carpeta = '$id'";

$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
$nrocaso= $row["nrocaso"];
$cuentaBien= $row["cuenta"];
//ws
require_once("ws_desembolso_bsol.php");

if($cuenta!='' && $operacion!='0'){
	$sql= "INSERT INTO comprobantes (id_carpeta, cuenta, debe, haber)
			VALUES ('$id', '$cuentaBien', '1', '0')";
	ejecutar($sql);
	
	//actualizamos cta en carpeta
	$sql="UPDATE carpetas SET cuenta = '$cuenta' WHERE id_carpeta = '$id'";
	ejecutar($sql);
	//llamar al WS execute
	require_once("ws_execute_bsol.php");
	//if($mensaje=='OK'){
	//}
}

?>