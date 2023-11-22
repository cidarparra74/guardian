<?php
if($_REQUEST['eliminar_boton_x']=='Go!'){
	$id= $_REQUEST['id'];
	if($_REQUEST['xconfirm']=='2'){
		$sql= "SELECT id_informe_legal 
		FROM carpetas WHERE id_carpeta='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$idlegal= $resultado["id_informe_legal"];
		if($idlegal!=''){
		//borramos
			$sql= "DELETE FROM informes_legales_documentos WHERE id_informe_legal=$idlegal ";
			ejecutar($sql);
			
			$sql= "DELETE FROM informes_legales_inmuebles WHERE id_informe_legal=$idlegal ";
			ejecutar($sql);
			
			$sql= "DELETE FROM informes_legales_vehiculos WHERE id_informe_legal=$idlegal ";
			ejecutar($sql);
			
			$sql= "DELETE FROM informes_legales_pj WHERE id_informe_legal=$idlegal ";
			ejecutar($sql);
			
			$sql= "DELETE FROM informes_legales_propietarios WHERE id_informe_legal=$idlegal ";
			ejecutar($sql);

			$sql= "DELETE FROM informes_legales WHERE id_informe_legal='$idlegal' ";
			ejecutar($sql);
			
			$sql= "DELETE FROM documentos_informe WHERE din_inf_id=$idlegal ";
			ejecutar($sql);
			
			$sql= "DELETE FROM informes_legales_fechas WHERE id_informe_legal=$idlegal ";
			ejecutar($sql);
			
		$sql= "DELETE FROM ncaso_cfinal WHERE nrocaso IN (SELECT nrocaso FROM informes_legales WHERE id_informe_legal=$idlegal )";
		ejecutar($sql);
		}
	}
	
	$sql= "DELETE FROM carpetas WHERE id_carpeta='$id' ";
	ejecutar($sql);
	$sql= "DELETE FROM documentos_propietarios WHERE id_carpeta='$id'";
	ejecutar($sql);
	$sql= "DELETE FROM movimientos_carpetas WHERE id_carpeta='$id' ";
	ejecutar($sql);
	$sql= "DELETE FROM COMPROBANTES WHERE id_carpeta='$id' ";
	ejecutar($sql);
	
}else{
	echo 'Ingresando desde una opción no válida. (reportar a victor)'; die();
}
?>