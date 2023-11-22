<?php 
	$idp = $_REQUEST['levantar'];
	
	
//recuperamos datos basicos del I.L.
$sql =  "SELECT ile.cliente, ile.ci_cliente, tii.identificacion, tbi.tipo_bien, ".
			"ile.otras_observaciones, ile.conclusiones, ile.motivo, ile.id_us_comun, 
			ile.exe_justifica, ile.exe_aprobar, ile.bandera ".
			"FROM informes_legales ile ".
			"LEFT JOIN tipos_identificacion tii ON id_tipo = id_tipo_identificacion ".
			"LEFT JOIN tipos_bien tbi ON tbi.id_tipo_bien = ile.id_tipo_bien ".
			"WHERE id_informe_legal = $idp ";
//echo $sql;
	$query = consulta($sql);
	
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$otras_observaciones = $resultado["otras_observaciones"];
	$id_us_comun = $resultado["id_us_comun"];
	
	$smarty->assign('cliente',$resultado["cliente"]);
	$smarty->assign('ci_cliente',$resultado["ci_cliente"]);
	$smarty->assign('identificacion',$resultado["identificacion"]);
	$smarty->assign('tipo_bien',$resultado["tipo_bien"]);
	$smarty->assign('otras_observaciones',$otras_observaciones);
	$smarty->assign('conclusiones',$resultado["conclusiones"]);
	$smarty->assign('motivo',$resultado["motivo"]);
	$smarty->assign('justifi',$resultado["exe_justifica"]);
	$smarty->assign('aprobar',$resultado["exe_aprobar"]);
	//para la badera
	$banderaimg = 'b'.$resultado["bandera"].'.png';
	$smarty->assign('banderaimg',$banderaimg);
	
	//leemos parametros especiales
	$sql= "SELECT TOP 1 il_estado_fin  FROM opciones ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	// para el estado final del I.L (Rojo;amarillo;Verde)
	$il_estado=explode(';',$row["il_estado_fin"]);
	if($resultado["bandera"]=='r')     $smarty->assign('banderatxt',$il_estado[0]);
	elseif($resultado["bandera"]=='a') $smarty->assign('banderatxt',$il_estado[1]);
	elseif($resultado["bandera"]=='v') $smarty->assign('banderatxt',$il_estado[2]);
	else $smarty->assign('banderatxt',$il_estado[3]);
	
	$smarty->assign('idp',$idp);
	$smarty->display('ver_informe_legal/levantar_exe.html');
	die();
?>