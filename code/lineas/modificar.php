<?php

	$idl= $_REQUEST['idl'];
	
	$sql = "SELECT *, CONVERT(VARCHAR(10), fechaesc, 103) as fecha FROM lineas WHERE id_linea = '$idl' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('idl',$idl);
	$smarty->assign('numero',$resultado["numero"]);
	$smarty->assign('importe',$resultado["importe"]);
	$smarty->assign('moneda',$resultado["moneda"]);
	$smarty->assign('tipo',$resultado["tipo"]);
	$smarty->assign('escritura',$resultado["escritura"]);
	$smarty->assign('notario',$resultado["notario"]);
	$smarty->assign('fecha',$resultado["fecha"]);
	$smarty->assign('id',$resultado["id_propietario"]);
	
	$smarty->display('lineas/modificar.html');
	die();
?>
