<?php


	$id_carpeta = $_REQUEST["id_cpd"];
	
	//recuperando el nombre de la carpeta y el nombre del propietario
	$sql= "select tt.tipo_bien, pp.nombres, cc.carpeta, pp.id_propietario
from carpetas cc
inner join propietarios pp on pp.id_propietario = cc.id_propietario
inner join tipos_bien tt on tt.id_tipo_bien = cc.id_tipo_carpeta
where cc.id_carpeta = $id_carpeta ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo_carpeta= $resultado["tipo_bien"];
	
	$smarty->assign('titulo_bien',$resultado["tipo_bien"]);
	$smarty->assign('titulo_nombre',$resultado["nombres"]);
	$smarty->assign('titulo_carpeta',$resultado["carpeta"]);
	$smarty->assign('id_propietario',$resultado["id_propietario"]);
	

	$sql = "SELECT TOP 1 rutadoc,extension FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$formatos_permitidos =  explode(',',$row["extension"]);
	$extension = "";
	$coma = '';
	foreach($formatos_permitidos as $ext){
		$extension .= $coma.".".$ext;
		$coma = ',';
	}
	$smarty->assign('rutadoc',$row["rutadoc"]);
	$smarty->assign('extension',$extension);
 
//recuperando los datos para la ventana, los documentos que tiene el propietario
//$sql= "SELECT dp.* FROM documentos_propietarios dp ";
//$sql.= "INNER JOIN documentos dd ON dd.id_documento = dp.id_documento  ";
//$sql.= "WHERE dp.id_carpeta='$id_carpeta' and dp.id_estado is NULL ORDER BY dp.id_documento_propietario ";
$sql= "SELECT do.documento, do.requerido, td.tipo, dp.* 
FROM documentos_propietarios dp  
LEFT JOIN documentos do ON do.id_documento = dp.id_documento 
LEFT JOIN tipos_documentos td ON td.id_tipo_documento = dp.id_tipo_documento 
WHERE dp.id_carpeta='$id_carpeta' 
ORDER BY do.requerido DESC ";
$query = consulta($sql);

$los_documentos= array();

$i=0;
//primera vez que se cargan los documentos

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$los_documentos[$i]= array('id_documento' => $row["id_documento"],
								'grupo' =>  $row["requerido"],
								'tipo' => $row["tipo"],
								'numero' => $row["nro_documento"],
								'fojas' => $row["numero_hojas"],
								'observacion' => $row["observacion"],
								'documento' => $row["documento"],
								'id_dp' => $row["id_documento_propietario"],
								'archivo' => $row["archivo"]);
	$i++;
}
$smarty->assign('los_documentos',$los_documentos);
$smarty->assign('id_carpeta',$id_carpeta);

$smarty->display('solicitud_carpeta/documentos.html');

	die();

?>
