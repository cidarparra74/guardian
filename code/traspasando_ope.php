<?php

	//18/07/2015
	require_once('../lib/verificar.php');
$filtro_id_prop= $_REQUEST["idprop"];
$id_usuario= $_REQUEST["id_usuario"];

$sql= "SELECT id_informe_legal FROM informes_legales
	WHERE estado<>'pub' AND estado<> 'npu' AND id_us_comun='$filtro_id_prop' ORDER BY id_informe_legal DESC ";
//echo $sql;
$query = consulta($sql);
$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$opc_id = "mover_".$row["id_informe_legal"];
	if(isset($_REQUEST[$opc_id])){
		$id = $row["id_informe_legal"];
		$sql= "UPDATE informes_legales SET id_us_comun='$id_usuario' WHERE id_informe_legal='$id' ";
		//echo $sql;
		ejecutar($sql);
		$i++;
	}
}
echo $i." traspaso(s) realizado(s).";
?>