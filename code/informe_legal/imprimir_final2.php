<?php

if(!isset($_REQUEST["fileName"])){
	$smarty->assign('id',$id);
	$smarty->assign('reporte','imp_informe_final.html');
	$smarty->display('informe_legal/imprimir_final2.html');
}else{
	//armamos reporte I.L. final
	chdir('..');
	require_once("../lib/setup.php");
	require_once("../lib/fechas.php");
	$smarty = new bd;
	$reporte = $_REQUEST["fileName"];
	$id = $_REQUEST["id"];
	
	$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('logo',$resultado['logo01']);
	//recuperamos los datos del informe legal

	$sql= "SELECT inf_nro_esc, inf_nro_asi, inf_nro_mat, inf_fch_grav, 
	inf_fch_esc, inf_obs,  id_notario, id_tramitador, inf_gravmonto, montoprestamo,
	inf_plazo, pr.nombres nombrepro, pr.ci, emision, CONVERT(varchar(10),inf_fch_ini,103) as fini, 
	CONVERT(varchar(10),inf_fch_fin,103) as ffin, inf_nota, 
	en.entidad, nt.nombres nombrenot, il.motivo, u.nombres nombreabo, tr.nombres nombretram, ua.nombres nombreag,
	(SELECT MAX(fecha_quitar) FROM informes_legales_fechas WHERE id_informe_legal=il.id_informe_legal) last_update
	FROM informes_legales  il 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien 
	LEFT JOIN propietarios pr ON pr.id_propietario = il.id_propietario 
	LEFT JOIN entidades en ON en.id = il.id_entidad 
	LEFT JOIN personas nt ON nt.id_persona=il.id_notario
	LEFT JOIN usuarios u ON u.id_usuario=il.usr_acep
	LEFT JOIN usuarios ua ON ua.id_usuario=il.id_us_comun
	LEFT JOIN personas tr ON tr.id_persona=il.id_tramitador
	WHERE il.id_informe_legal='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
//print_r($resultado); die();
/*
	if($resultado["fecha_recepcion"]!= ""){
		$aux_c = explode(" ",$resultado["fecha_recepcion"]);
		$fecha = dateDMESY(dateDMY($aux_c[0])).'&nbsp;-&nbsp;'.substr($aux_c[1],0,5);
	}else{
		$fecha = "";
	}
*/
//	$fecha_actual= date("d/m/Y");

		$aux_b= $resultado["last_update"];
		if($aux_b!=''){
		$aux_c= explode(" ",$aux_b);
		$fecha_a= dateDMESY(dateDMY($aux_c[0]));
		$aux1= $fecha_a." ".substr($aux_c[1],0,5);
		}else $aux1= '';
	$smarty->assign('id',		   $id);


	$smarty->assign('inf_nro_esc', $resultado['inf_nro_esc']);
	$smarty->assign('inf_nro_asi', $resultado['inf_nro_asi']);
	$smarty->assign('inf_nro_mat', $resultado['inf_nro_mat']);
	$smarty->assign('inf_fch_grav', $resultado['inf_fch_grav']);
	$smarty->assign('inf_fch_esc', $resultado['inf_fch_esc']);
	$smarty->assign('inf_obs', $resultado['inf_obs']);
	$smarty->assign('inf_nota', $resultado['inf_nota']);
	$smarty->assign('inf_gravmonto', $resultado['inf_gravmonto']);
	$smarty->assign('inf_plazo', $resultado['inf_plazo']);
	$smarty->assign('montoprestamo', $resultado['montoprestamo']);
	$smarty->assign('inf_fch_ini', $resultado['fini']);
	$smarty->assign('inf_fch_fin', $resultado['ffin']);
	$smarty->assign('motivo', $resultado['motivo']);
	$smarty->assign('entidad', $resultado['entidad']);
	$smarty->assign('cliente', $resultado['nombrepro']);
	$smarty->assign('ci_cliente', $resultado['ci']);
	$smarty->assign('id_doc', $resultado['emision']);
	$smarty->assign('elaborado',$resultado['nombreabo']);
	$smarty->assign('notario',$resultado['nombrenot']);
	$smarty->assign('encarg_ag',$resultado['nombreag']);
	$smarty->assign('tramitador',$resultado['nombretram']);
	$smarty->assign('last_update', $aux1);
	
	$smarty->display('informe_legal/'.$reporte);
}
die();
?>