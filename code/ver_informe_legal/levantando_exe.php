<?php

$id = $_REQUEST["idp"];

	if($_REQUEST['aprobar']){
		$aprobar = $_REQUEST['aprobar'];
		$sql = "UPDATE informes_legales SET exe_aprobar='$aprobar' WHERE id_informe_legal = $id " ;
		ejecutar($sql);
		
	}
	

?>