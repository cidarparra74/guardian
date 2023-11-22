<?php
	$idd = $_REQUEST['descargar'];
	$sql= "SELECT * FROM documentos_propietarios WHERE id_documento_propietario = $idd ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$iddocumento= $resultado["id_documento"];
	$filename = $resultado['archivo'];
	//nombre de archivo que se va a descargar
	$file = $rutadoc."/".$filename;
	//recuperando el nombre del documento
	$sql= "SELECT documento FROM documentos WHERE id_documento = $iddocumento ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$documento= trim($resultado["documento"]);
	$documento= strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $documento), '_'));
	// con que nombre se va a descargar
	$filename = $documento."_".$filename;
	header('Content-type: application/octet-stream');
	header("Content-Type: ".mime_content_type($file));
	header("Content-Disposition: attachment; filename=".$filename);
	while (ob_get_level()) {
		ob_end_clean();
	}
	readfile($file);
?>