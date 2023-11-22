<?php
/*
	$sql= "SELECT MAX(id_informe_legal_fecha) AS maximo FROM informes_legales_fechas ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$max_id= bcadd($resultado["maximo"],1,0);
	
	
	
	$sql= "INSERT INTO informes_legales_fechas(id_informe_legal_fecha, id_informe_legal, fecha_quitar) ";
	$sql.="VALUES('$max_id', '$id', $fecha_actual) ";
	ejecutar($sql); */
	
	$idus = $_SESSION["idusuario"];
	
	$fecha_actual= date("Y-m-d H:i:s");
	$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
	
	$sql= "INSERT INTO informes_legales_fechas(id_informe_legal, fecha_quitar, usr_acep) ";
	$sql.="VALUES( '$id', $fecha_actual, '$idus') ";
	ejecutar($sql);
	
	$sql= "UPDATE informes_legales SET habilitar_informe='0', usr_acep='$idus', estado='npu' WHERE id_informe_legal='$id' ";
	//echo $sql;
	ejecutar($sql);
?>
