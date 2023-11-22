<?php


	//recpueramos los datos del informe legal
	$sql= "SELECT cliente, ci_cliente FROM informes_legales WHERE id_informe_legal='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	

	$cliente= $resultado['cliente'];
	$ci_cliente= $resultado['ci_cliente'];
	$tipo_bien_c = substr($tipo_bien,0,1);
	
	// Vemos si hay excepciones solicitadas/respondidas a este I.L.
	$sql= "SELECT estado FROM informes_legales_excepciones WHERE id_informe_legal='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$estado = $resultado['estado'];
	$obs = "";
	if($estado == "REC"){
		// la escepcion fue rechazada
		$obs = "(Con excepción Rechazada)";
	}elseif($estado == "ACE"){
		// la escepcion fue rechazada
		$obs = "(Con excepción Aprobada)";
	}
	
	$smarty->assign('id',$id);
	$smarty->assign('tipo_bien',$tipo_bien);
	$smarty->assign('tipo_bien_c',$tipo_bien_c);
	$smarty->assign('cliente',$cliente);
	$smarty->assign('ci_cliente',$ci_cliente);
	$smarty->assign('obs',$obs);
	
	$smarty->display('informe_legal/deshabilitar_bien.html');
	die();
?>
