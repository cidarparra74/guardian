<?php

	//print_r($_POST);
	//die();
	if(isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
	}else{
		$id = 0;
	}
		
	$sql = "SELECT d.*
	FROM documentos d WHERE d.id_documento='$id' ORDER BY d.documento ";
	//echo $sql;
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
	$imagen= $resultado["imagen"];
	$documento= $resultado["documento"];
	$descripcion= $resultado["descripcion"];
	$meses_vencimiento= $resultado["meses_vencimiento"];
	$vencimiento = $resultado["vencimiento"];
	$tiene_fecha = $resultado["tiene_fecha"];
	$post_desembolso = $resultado["post_desembolso"];
	$requerido = $resultado["requerido"];
	$seguro = $resultado["seguro"];
	$con_numero = $resultado["con_numero"];
	$tiene_coment = $resultado["tiene_coment"];
	if($post_desembolso == null) {$post_desembolso = 0;}
	if($requerido == null) {$requerido = 0;}
	if($seguro == null) {$seguro = 0;}

	//para el select 

	$imagen = ''.$imagen;
	
	$smarty->assign('id',$id);
	$smarty->assign('imagen',$imagen);
	$smarty->assign('documento',$documento);
	$smarty->assign('descripcion',$descripcion);
	$smarty->assign('vencimiento',$vencimiento);
	$smarty->assign('meses_vencimiento',$meses_vencimiento);
	$smarty->assign('tiene_fecha',$tiene_fecha);
	$smarty->assign('con_numero',$con_numero);
	$smarty->assign('post_desembolso',$post_desembolso);
	$smarty->assign('requerido',$requerido);
	$smarty->assign('seguro',$seguro);
	$smarty->assign('accion',$accion);
	$smarty->assign('tiene_coment',$tiene_coment);

	$smarty->display('adm/documentos/modificar.html');
	die();
?>
