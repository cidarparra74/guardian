<?php

	$informes=$_REQUEST["informes"];
	$peritos=$_REQUEST["peritos"];
	$obs="Asignado automaticamente";
	foreach($informes as $key=>$id){
		$perito = $peritos[$key];
		$sql= "UPDATE informes_legales SET id_perito='$perito', perito_obs='$obs' WHERE id_informe_legal='$id' ";
		ejecutar($sql);
		//echo $sql."<br />";
	}

?>
