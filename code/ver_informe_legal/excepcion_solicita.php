<?php


// este scrip es para cuando solictan la aprobcion

require_once("../lib/conexionMNU.php");

$id = $_REQUEST['id'];

$sql =  "SELECT ile.cliente, ile.ci_cliente, tii.identificacion, tbi.tipo_bien ".
			"FROM informes_legales ile ".
			"LEFT JOIN tipos_identificacion tii ON id_tipo = id_tipo_identificacion ".
			"LEFT JOIN tipos_bien tbi ON tbi.id_tipo_bien = ile.id_tipo_bien ".
			"WHERE id_informe_legal = $id ";
//
	$query = consulta($sql);
	
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$smarty->assign('cliente',$resultado["cliente"]);
	$smarty->assign('ci_cliente',$resultado["ci_cliente"]);
	$smarty->assign('identificacion',$resultado["identificacion"]);
	$smarty->assign('tipo_bien',$resultado["tipo_bien"]);

	$smarty->assign('id',$id);
	//$smarty->assign('excepciones',$excepciones);
	
	$smarty->display('ver_informe_legal/excepcion_solicita.html');
	die();

?>