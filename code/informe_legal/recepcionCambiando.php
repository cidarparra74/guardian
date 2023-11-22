<?php

	$id=$_REQUEST["id"];
	$perito=$_REQUEST["perito"];
	$obs=str_replace("'","''",$_REQUEST["obs"]);
	$sql= "UPDATE informes_legales SET id_oficina='$perito' WHERE id_informe_legal='$id' ";
	ejecutar($sql);

?>
