<?php

	$id=$_REQUEST["id"];
	$perito=$_REQUEST["perito"];
	$obs=str_replace("'","''",$_REQUEST["obs"]);
	$sql= "UPDATE informes_legales SET id_perito='$perito', perito_obs='$obs' WHERE id_informe_legal='$id' ";
	ejecutar($sql);

?>
