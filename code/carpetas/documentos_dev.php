<?php


$id_carpeta = $_REQUEST["id"];

//devolviendo docs al propietario
if(isset($_REQUEST['boton_devolver_docs_x'])){
	// con el id carpeta buscar los docs seleccionados para dev en tabla DOCUMENTOS_PROPIETARIOS
	$docs = $_REQUEST["docs"];
	$ok='0';
	foreach($docs as $doc){
		$sql = "update documentos_propietarios set id_estado = 9 where id_documento_propietario = $doc ";
		//echo $sql;
		ejecutar($sql);
		$ok='1';
	}
	//se devolvio almenos uno?
	if($ok='1'){
		$smarty->assign('id_carpeta',$id_carpeta);
		$smarty->display('carpetas/documentos_devfin.html');
		die();
	}
	//
}else{
	
	$id_carpeta = $_REQUEST["id"];
	//recuperando el nombre de la carpeta y el nombre del propietario
	$sql= "SELECT tipo_bien FROM tipos_bien WHERE id_tipo_bien= (SELECT id_tipo_carpeta 
			FROM carpetas WHERE id_carpeta='$id_carpeta') ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo_carpeta= $resultado["tipo_bien"];	
	$smarty->assign('titulo_carpeta',$titulo_carpeta);
	$smarty->assign('titulo_nombre',$titulo_nombre);	
	$smarty->assign('editar','ok');
	
	//recuperando los datos para la ventana, los documentos que tiene el propietario
	$sql= "SELECT dp.id_documento_propietario, d.documento, dp.numero_hojas, td.tipo 
	FROM documentos_propietarios dp 
	INNER JOIN documentos d  on d.id_documento = dp.id_documento
	INNER JOIN tipos_documentos td on td.id_tipo_documento = dp.id_tipo_documento 
	WHERE dp.id_carpeta='$id_carpeta' and dp.id_estado is NULL ORDER BY d.documento ";

	//echo "$sql<br>";
	$query = consulta($sql);
	$mis_documentos= array();
	$i=0;
	//primera vez que se cargan los documentos

	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$mis_documentos[$i]= array('documento' => $row["documento"],
									'fojas' => $row["numero_hojas"],
									'tipo' => $row["tipo"],
									'id_dp' => $row["id_documento_propietario"]);
		$i++;
	}
	$smarty->assign('mis_documentos',$mis_documentos);
	$smarty->assign('id_carpeta',$id_carpeta);

	
	$smarty->display('carpetas/documentos_dev.html');

	die();
}
?>
