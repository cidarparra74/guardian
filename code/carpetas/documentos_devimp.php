<?php

$id_carpeta = $_REQUEST["id"];

	$sql= "SELECT TOP 1 logo01 FROM opciones ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//datos principales
	$logo01   = $resultado['logo01'];
	$smarty->assign('logo01', $logo01);
	
		//nombre del propietario y codigo mis
	$sql= "SELECT ca.carpeta, td.tipo_bien, ca.operacion, ofi.nombre 
	FROM carpetas ca LEFT JOIN oficinas ofi ON ca.id_oficina = ofi.id_oficina 
	LEFT JOIN tipos_bien td ON ca.id_tipo_carpeta=td.id_tipo_bien 
	WHERE id_carpeta='$id_carpeta' ";

	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$carpeta= $resultado["carpeta"];
	$operacion= $resultado["operacion"];
	$oficina= $resultado["nombre"];
	$tipo= $resultado["tipo_bien"];
		$smarty->assign('carpeta',$carpeta);
		$smarty->assign('operacion',$operacion);
		$smarty->assign('oficina',$oficina);
		$smarty->assign('tipo',$tipo);
		$smarty->assign('fechadev',date("d/m/Y"));
//recuperando los datos para la ventana, los documentos que tiene el propietario
$sql= "SELECT do.documento, td.tipo, dp.* FROM documentos_propietarios dp  
LEFT JOIN documentos do ON do.id_documento = dp.id_documento 
LEFT JOIN tipos_documentos td ON td.id_tipo_documento = dp.id_tipo_documento 
WHERE dp.id_carpeta='$id_carpeta'  and dp.id_estado = 9 ORDER BY dp.id_documento_propietario ";

$query = consulta($sql);
$mis_documentos= array();
$i=0;
//primera vez que se cargan los documentos
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$mis_documentos[$i]= array('documento' => $row["documento"],
								'tipo' => $row["tipo"],
								'numero' => $row["nro_documento"],
								'numero_hojas' => $row["numero_hojas"],
								'observacion' => $row["observacion"],
								'id_dp' => $row["id_documento_propietario"]);
	$i++;
}
$cant_documentos=$i;
$smarty->assign('cant_documentos',$cant_documentos);
$smarty->assign('mis_documentos',$mis_documentos);

// ---- hasta aqui para el html
	$smarty->display('carpetas/documentos_devimp.html');
	die();
?>