<?php
$estado= $_REQUEST['estado'];
if($estado=="boveda"){
		$consulta= " AND  ((m.id_estado is null) OR (m.id_estado<=3))  ";
}else{
	if($estado=="prestados"){
			$consulta= " AND  ((m.id_estado > 3) AND (m.id_estado<=7))  ";
	}else{
		if($estado=="cliente"){
				$consulta= " AND  (m.id_estado = 8)  ";
		}
	}
}
if(isset($_REQUEST["gorep"])){
	//quitamos el temporal
	$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('logo',$resultado['logo01']);
	
	$id_oficina = $_REQUEST["id_oficina"];
	if($id_oficina!='0') $xand = " AND ofi.id_oficina = $id_oficina ";
	else {
	$id_almacen = $_SESSION["id_almacen"];
	$xand = " AND ofi.id_almacen='$id_almacen'";
	};
	$orden = $_REQUEST["orden"];
	if($orden =='1') $xord = " ca.nombres, dp.id_carpeta ";
	elseif($orden =='2') $xord = " ca.tipo_bien, ca.nombres ";
	elseif($orden =='3') $xord = " ca.tipo_bien, ca.nombres ";
	
	//$id_oficina = $REQUEST["salto"];
	//recuperando las gavetas del sistema
//Modificado por Percy 04/10/2017 WHERE (cast(dp.observacion as varchar) <> '' or
$sql= "SELECT ca.id_carpeta, ca.carpeta, ca.id_oficina, ofi.nombre, 
ca.tipo_bien, ca.id_propietario, ca.nombres, ca.ci, do.documento, 
CASE dp.numero_hojas WHEN '0' THEN 'Falta Documento! '+cast(dp.observacion as varchar(250)) ELSE cast(dp.observacion as varchar(250)) END observacion, 
dp.numero_hojas 
FROM documentos_propietarios dp 
INNER JOIN documentos do ON do.id_documento =dp.id_documento 
INNER JOIN (SELECT ca.id_carpeta, ca.carpeta, ca.id_oficina, tc.tipo_bien, pr.nombres, pr.id_propietario, pr.ci  
	FROM carpetas ca LEFT JOIN tipos_bien tc ON ca.id_tipo_carpeta = tc.id_tipo_bien 
	LEFT JOIN propietarios pr ON ca.id_propietario = pr.id_propietario)  ca ON ca.id_carpeta = dp.id_carpeta 
INNER JOIN oficinas ofi ON ofi.id_oficina = ca.id_oficina 
LEFT JOIN movimientos_carpetas m ON m.id_carpeta=ca.id_carpeta AND (m.flujo!='1' OR m.id_estado='8') 
WHERE (dp.observacion LIKE '%excepc%' or dp.numero_hojas = '0' or dp.observacion LIKE '%compra%cartera%' or dp.observacion LIKE '%adjuntar%') AND dp.noobs = '0' 
$xand $consulta ORDER BY $xord ";
//echo $sql; exit;
$listado = array();
$query = consulta($sql);
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$listado[] = array('id'=>$row["id_carpeta"],
						'carpeta'=>$row["carpeta"],
						'oficina'=>$row["nombre"],
						'tipo_bien'=>$row["tipo_bien"],
						'ci'=>$row["ci"],
						'nombres'=>$row["nombres"],
						'documento'=>$row["documento"],
						'observacion'=>$row["observacion"],
						'fojas'=>$row["numero_hojas"]);
}
	$smarty->assign('listado',$listado);
if ($_REQUEST[marcado]){
	$smarty->display('reportes/docus_obs_imp_excel.html');
	die();
}else{
	$smarty->display('reportes/docus_obs_imp.html');
	die();
}
}else{
	//recuperar lista de regionales
	$id_almacen = $_SESSION["id_almacen"];
	$sql = "SELECT id_oficina, nombre FROM oficinas WHERE id_almacen = $id_almacen  ORDER BY nombre";
	$result = consulta($sql);
	$oficinas = array();
	$oficinas[] = array('id_oficina' => '0', 
						'nombre' => 'Todas');
	while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$oficinas[] = array('id_oficina' => $row['id_oficina'], 
							'nombre' => $row['nombre']);
	}
	$smarty->assign('oficinas',$oficinas);
	$smarty->display('reportes/docus_obs.html');
	die();
}
/*
//observados en recepcion
SELECT ca.id_oficina, ofi.nombre, 
ca.tipo_bien, ca.id_propietario, ca.nombres, ca.ci, do.documento, 
CASE dp.fojas WHEN '0' THEN 'Falta Documento!' 
ELSE cast(dp.obs as varchar(250)) END observacion, dp.fojas 
FROM documentos_informe dp 
INNER JOIN documentos do ON do.id_documento =dp.din_doc_id 
INNER JOIN (
SELECT il.id_informe_legal, us.id_oficina, tc.tipo_bien, pr.nombres, 
pr.id_propietario, pr.ci 
FROM informes_legales il 
INNER JOIN  usuarios us ON us.id_usuario = il.id_us_comun
LEFT JOIN tipos_bien tc ON il.id_tipo_bien = tc.id_tipo_bien 
LEFT JOIN propietarios pr ON il.id_propietario = pr.id_propietario
) 
ca on ca.id_informe_legal = dp.din_inf_id 
INNER JOIN oficinas ofi ON ofi.id_oficina = ca.id_oficina 
WHERE (cast(dp.obs as varchar) <> '' or dp.fojas = '0') 
 and ofi.id_almacen = 30
ORDER BY ca.tipo_bien, ca.nombres 
*/
?>

