<?php

	$id= $_REQUEST["id"];
	//este solo se llama desde opciones de elaborar informe legal
	
	$acep = $_REQUEST['aceptar_informe'];
	
	
	$sql= "SELECT il.id_propietario, il.id_us_comun, il.id_tipo_bien, pr.nombres, pr.ci, pr.emision,
	tb.tipo_bien, us.nombres as usuario
	FROM informes_legales il LEFT JOIN propietarios pr ON pr.id_propietario = il.id_propietario
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien
	INNER JOIN usuarios us ON us.id_usuario = il.id_us_comun
	WHERE il.id_informe_legal='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$cliente= $resultado["nombres"];
	$ci_cliente= $resultado["ci"];
	$emision= $resultado["emision"];
	$id_tipo_bien= $resultado["id_tipo_bien"];
	$id_us_comun= $resultado["id_us_comun"];
	$id_propietario= $resultado["id_propietario"];

	$nombre_usuario= $resultado["usuario"];

	if($resultado["tipo_bien"]!='') $smarty->assign('tipo_bien',$resultado["tipo_bien"]);

	$smarty->assign('id',$id);
	$smarty->assign('acep',$acep);
	$smarty->assign('cliente',$cliente);
	$smarty->assign('ci_cliente',$ci_cliente);
	$smarty->assign('emision',$emision);
	$smarty->assign('id_tipo_bien',$id_tipo_bien);
	$smarty->assign('nombre_usuario',$nombre_usuario);
	
	// recuperamos posibles i.l. guardados. OJO si son i.l. tendran bandera=i
	$sql= "SELECT il.id_informe_legal, convert(varchar(10),il.fecha_aceptacion,103) as fecha, 
	us.nombres as asesor, il.nrocaso
	FROM informes_legales_bk il
	INNER JOIN usuarios us ON us.id_usuario = il.usr_acep
	WHERE il.id_propietario='$id_propietario' AND il.id_tipo_bien = '$id_tipo_bien' AND il.bandera='i' ";
	//echo $sql;
	$query = consulta($sql);
	$informes=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$informes[] = array('id'  => $row["id_informe_legal"],
							'nro' => $row["nrocaso"],
							'fecha' => $row["fecha"],
							'asesor' => $row["asesor"]);
	}
	$smarty->assign('informes',$informes);
	
	
	// recuperamos posibles i.l. existentes. 
	$sql= "SELECT il.id_informe_legal, convert(varchar(10),il.fecha_aceptacion,103) as fecha, 
	us.nombres as asesor, il.nrocaso
	FROM informes_legales il
	INNER JOIN usuarios us ON us.id_usuario = il.usr_acep
	WHERE il.id_propietario='$id_propietario' AND il.id_tipo_bien = '$id_tipo_bien'  ";
	//echo $sql;
	$query = consulta($sql);
	$existen=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existen[] = array('id'  => $row["id_informe_legal"],
							'nro' => $row["nrocaso"],
							'fecha' => $row["fecha"],
							'asesor' => $row["asesor"]);
	}
	$smarty->assign('existen',$existen);

	$smarty->display('informe_legal/aceptar_solicitud_il.html');
	die();

?>
