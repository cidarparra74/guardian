<?php

	$sql= "DELETE FROM informes_legales_documentos WHERE id_informe_legal='$id' ";
	ejecutar($sql);
	$sql= "DELETE FROM informes_legales_excepciones WHERE id_informe_legal='$id' ";
	ejecutar($sql);
	//echo $tipo;
	if ($tipo = '1'){
		$sql= "DELETE FROM informes_legales_inmuebles WHERE id_informe_legal='$id' ";
		ejecutar($sql);
		
		$sql= "DELETE FROM informes_legales_propietarios WHERE id_informe_legal='$id' ";
		ejecutar($sql);
	}else{
		$sql= "DELETE FROM informes_legales_vehiculos WHERE id_informe_legal='$id' ";
		ejecutar($sql);
		
		$sql= "DELETE FROM informes_legales_propietarios WHERE id_informe_legal='$id' ";
		ejecutar($sql);
	}
	
	$sql= "DELETE FROM documentos_informe WHERE din_inf_id='$id' ";
	ejecutar($sql);
	
	$sql= "DELETE FROM informes_legales WHERE id_informe_legal='$id' ";
	ejecutar($sql);
	
?>