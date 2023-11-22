<?php

	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM tipos_bien WHERE id_tipo_bien= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$tipo_bien= $resultado["tipo_bien"];
	$descripcion= $resultado["descripcion"];
	$con_inf_legal= $resultado['con_inf_legal'];
	$con_recepcion= $resultado['con_recepcion'];
	$bien= $resultado['bien'];
	$cuenta= $resultado['cuenta'];
	$con_perito= $resultado['con_perito'];
	$id_banca= $resultado['id_banca'];

	//recuperando los existentes
	$sql = "SELECT tipo_bien, id_banca FROM tipos_bien WHERE id_tipo_bien!= '$id' ORDER BY tipo_bien ";
	$query= consulta($sql);
	$existentes="";
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes.= $row["tipo_bien"].$row["id_banca"].";";
		$i++;
	}
	
	$sql = "SELECT * FROM bancas ORDER BY codigo ";
	$query= consulta($sql);
	$bancas= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$bancas[] = array('id_banca'=>$row["id_banca"], 
		'codigo'=>$row["codigo"],
		'banca'=>$row["banca"]);
	}
	$smarty->assign('id',$id);
	$smarty->assign('bien',$bien);
	$smarty->assign('bancas',$bancas);
	$smarty->assign('cuenta',$cuenta);
	$smarty->assign('id_banca',$id_banca);
	$smarty->assign('tipo_bien',$tipo_bien);
	$smarty->assign('con_perito',$con_perito);
	$smarty->assign('descripcion',$descripcion);
	$smarty->assign('con_inf_legal',$con_inf_legal);
	$smarty->assign('con_recepcion',$con_recepcion);
	
	$smarty->assign('existentes',$existentes);
	
	$smarty->display('adm/tipos_bien/modificar.html');
	die();
?>
