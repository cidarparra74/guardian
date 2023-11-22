<?php

	$id= $_REQUEST['id'];
	//tipos de bien
	$sql = "SELECT id_tipo_bien, tipo_bien FROM tipos_bien ORDER BY tipo_bien ";
	$query= consulta($sql);
	$i=0;
	$tiposbien= array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		$tiposbien[$i]= array('id_tipo_bien' => $row["id_tipo_bien"],
		'tipo_bien' => $row["tipo_bien"]);
		$i++;
	}
	
	$smarty->assign('tiposbien',$tiposbien);
	
	$sql = "SELECT * FROM tipos_carpetas WHERE id_tipo_carpeta= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$tipo= $resultado["tipo"];
	$descripcion= $resultado["descripcion"];
	$cuenta_ingreso= $resultado["cuenta_ingreso"];
	$cuenta_devolucion= $resultado["cuenta_devolucion"];
	$id_tipo_bien= $resultado["id_tipo_bien"];
	$cuenta= $resultado['cuenta'];
	
	//recuperando los existentes
	$sql = "SELECT tipo FROM tipos_carpetas WHERE tipo!= '$tipo' ORDER BY tipo ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["tipo"];

		$i++;
	}
	
	$smarty->assign('id',$id);
	$smarty->assign('tipo',$tipo);
	$smarty->assign('descripcion',$descripcion);
	$smarty->assign('cuenta_ingreso',$cuenta_ingreso);
	$smarty->assign('cuenta',$cuenta);
	$smarty->assign('cuenta_devolucion',$cuenta_devolucion);
	$smarty->assign('id_tipo_bien',$id_tipo_bien);
	
	$smarty->assign('existentes',$existentes);
	$smarty->display('adm/tipos_carpetas/modificar.html');
	die();
?>
