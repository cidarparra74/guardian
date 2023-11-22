<?php

require_once('../lib/conexionMNU.php');

//nume&nota&fech&otor&regi&foja&tipo&idi&idp

$nume= $_POST['nume'];
$nota= $_POST['nota'];
$fech= $_POST['fech'];
$otor= $_POST['otor'];
$regi= $_POST['regi'];
$foja= $_POST['foja'];
$tipo= $_POST['tipo'];
$idi= $_POST['idi'];
$idp= $_POST['idp'];
if($fech!='')
$fecha= "CONVERT(DATETIME,'$fech',103)";
else
$fecha= 'null';

if($idp=='0'){
	//es nuevo
	//$fecha_actual= date("Y-m-d H:i:s");
	$fecha= "CONVERT(DATETIME,'$fech',102)";
	$sql= "INSERT INTO poderes (id_informe_legal, numero, notario, fecha,
			fojas, id_tipo_documento, otorgante, registro) 
		VALUES('$idi', '$nume', '$nota', $fecha, 
		'$foja', '$tipo', '$otor', '$regi') ";
	ejecutar_con_filter($sql);
	//vemos el id generado para este poder
	$sql="SELECT MAX(id_poder) as idp FROM poderes WHERE id_informe_legal = '$idi'";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$idp= $resultado["idp"];
}else{
	// existe
	$sql= "UPDATE poderes SET numero='$nume', notario='$nota', fecha=$fecha,
		fojas='$foja', id_tipo_documento='$tipo', otorgante='$otor', registro='$regi'
		WHERE id_poder='$idp' ";
	ejecutar_con_filter($sql);
	
}
echo "ok|$idp";
?>