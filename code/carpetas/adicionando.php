<?php

$id_propietario= $_REQUEST['id_propietarix'];
$id_oficina= $_REQUEST['oficina'];
$carpeta= $_REQUEST['carpeta'];
$operacion= $_REQUEST['operacion'];
$tipo_carpeta= $_REQUEST['tipo_carpeta'];

$usuario= $_SESSION["idusuario"];

	
	$id = $_REQUEST['id']; //el id_carpeta

	$sql= "SELECT c.id_informe_legal, il.nrocaso,  c.nrocaso
		FROM carpetas c
		LEFT JOIN informes_legales il ON il.id_informe_legal = c.id_informe_legal
		WHERE c.id_carpeta='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_il = $resultado['id_informe_legal'];
	$nrocaso = $resultado['nrocaso'];
	//$cuenta = $resultado['cuenta'];
	//echo $sql;
	
//fecha actual
$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

//insertamps en carpetas
$sql= "INSERT INTO carpetas (id_oficina, id_propietario, id_usuario, 
	carpeta, id_tipo_carpeta, creacion_carpeta, operacion, id_informe_legal, nrocaso) 
	VALUES('$id_oficina', '$id_propietario', '$usuario', 
	'$carpeta', '$tipo_carpeta', $fecha_actual, '$operacion', '$id_il', '$nrocaso') ";
	
ejecutar($sql);
//insertamos en comprobantes
/*
$sql= "SELECT cuenta FROM tipos_bien WHERE id_tipo_bien = '$tipo_carpeta' ";
		//echo $sql;
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$cuenta=$row["cuenta"];
	//	$cuentah=$row["cuentah"];
	
if($cuenta!=''){
	$sql = "SELECT max(id_carpeta) as auto_increment 
		FROM carpetas WHERE id_propietario='$id_propietario' AND id_usuario='$usuario'";
		$query= consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$Auto_increment= $resultado["auto_increment"];
	$sql= "INSERT INTO comprobantes (id_carpeta, cuenta, debe, haber)
			VALUES ('$Auto_increment', '$cuenta', '1', '0')";
	ejecutar($sql);
	
}
*/
?>