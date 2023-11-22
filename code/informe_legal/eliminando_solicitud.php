<?php


$id= $_REQUEST["id"];

$idus = $_SESSION["idusuario"];
//fecha de acptacion

$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";


		$sql= "UPDATE informes_legales SET estado='sol', fecha_aprob='' WHERE id_informe_legal='$id' ";
//echo $sql;
ejecutar($sql);

?>
