<?php

	$id= $_REQUEST['id'];
	$sql= "UPDATE informes_legales SET sincarpeta = 'S' WHERE id_informe_legal = $id ";
	ejecutar($sql);
	
?>