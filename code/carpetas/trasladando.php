<?php

$filtro_id_carpeta= $_REQUEST["idprop"];
$id_oficina= $_REQUEST["id_oficina"];

$sql= "SELECT c.id_carpeta FROM carpetas c
WHERE c.id_propietario='$filtro_id_carpeta' ORDER BY c.operacion ";
//echo $sql;
$query = consulta($sql);

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$opc_id = "mover_".$row["id_carpeta"];
	if(isset($_REQUEST[$opc_id])){
		$id = $row["id_carpeta"];
		$sql= "UPDATE carpetas SET id_oficina='$id_oficina' WHERE id_carpeta='$id' ";
		ejecutar($sql);
	}
}

?>