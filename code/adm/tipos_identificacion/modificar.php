<?php

	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM tipos_identificacion WHERE id_tipo= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$identificacion= $resultado["identificacion"];
	$descripcion= $resultado["descripcion"];
	
	//recuperando los existentes
	$sql = "SELECT identificacion FROM tipos_identificacion WHERE id_tipo!= '$id' ORDER BY identificacion ";
	$query= consulta($sql);
	$existentes="";
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes.= $row["identificacion"].";";
		$i++;
	}
	
	$smarty->assign('id',$id);
	$smarty->assign('identificacion',$identificacion);
	$smarty->assign('descripcion',$descripcion);
	
	$smarty->assign('existentes',$existentes);
	$smarty->display('adm/tipos_identificacion/modificar.html');
	die();
?>
