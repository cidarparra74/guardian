<?php
if(!isset($_REQUEST['marcado']))
	return;
	
$ids = $_REQUEST['marcado'];

foreach($ids as $id){
	$sql="SELECT motivoeli, nombres, ci FROM propietarios WHERE id_propietario = $id ";
	$query= consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$motivoeli =  $row["motivoeli"];
		$nombres =  $row["nombres"];
		$ci =  $row["ci"];
		$id_us_elim=$_SESSION["idusuario"];
		$sqlx= "INSERT INTO carpetas_bk (id_propietario, fechaeli, motivoeli, nombres, ci, usuario_sol, usuario_eli) 
		VALUES ($id, GETDATE(), '$motivoeli', '$nombres', '$ci', 0, '$id_us_elim') ";

		ejecutar($sqlx);
		$sql= "DELETE FROM documentos_propietarios WHERE id_carpeta IN (SELECT id_carpeta FROM carpetas WHERE id_propietario = $id) ";
		ejecutar($sql);
		$sql= "DELETE FROM movimientos_carpetas WHERE id_carpeta IN (SELECT id_carpeta FROM carpetas WHERE id_propietario = $id)  ";
		ejecutar($sql);
		$sqlx= "DELETE FROM carpetas WHERE id_propietario = $id ";
		ejecutar($sqlx);
		$sqlx= "DELETE FROM propietarios WHERE id_propietario = $id ";
		ejecutar($sqlx);
		
		$sql= "DELETE FROM informes_legales_documentos WHERE id_informe_legal IN (SELECT id_informe_legal FROM informes_legales WHERE id_propietario = $id ) ";
		ejecutar($sql);
		
		$sql= "DELETE FROM informes_legales_inmuebles WHERE id_informe_legal IN (SELECT id_informe_legal FROM informes_legales WHERE id_propietario = $id ) ";
		ejecutar($sql);
		
		$sql= "DELETE FROM informes_legales_vehiculos WHERE id_informe_legal IN (SELECT id_informe_legal FROM informes_legales WHERE id_propietario = $id ) ";
		ejecutar($sql);
		
		$sql= "DELETE FROM informes_legales_pj WHERE id_informe_legal IN (SELECT id_informe_legal FROM informes_legales WHERE id_propietario = $id )";
		ejecutar($sql);
		
		$sql= "DELETE FROM informes_legales_bk WHERE id_informe_legal IN (SELECT id_informe_legal FROM informes_legales WHERE id_propietario = $id )";
		ejecutar($sql);
		
		$sql= "DELETE FROM informes_legales_propietarios WHERE id_informe_legal IN (SELECT id_informe_legal FROM informes_legales WHERE id_propietario = $id ) ";
		ejecutar($sql);
		
		$sql= "DELETE FROM documentos_informe WHERE din_inf_id IN (SELECT id_informe_legal FROM informes_legales WHERE id_propietario = $id ) ";
		ejecutar($sql);
		
		$sql= "DELETE FROM informes_legales_fechas WHERE id_informe_legal IN (SELECT id_informe_legal FROM informes_legales WHERE id_propietario = $id )";
		ejecutar($sql);
		
		$sql= "DELETE FROM ncaso_cfinal WHERE nrocaso IN (SELECT nrocaso FROM informes_legales WHERE id_propietario = $id )";
		ejecutar($sql);
			
			
		$sql= "DELETE FROM informes_legales WHERE id_propietario = $id ";
		ejecutar($sql);
		

	}
}
?>