<?php
	$id= $_REQUEST['id'];
	
	//recuperando los existentes
	$sql = "SELECT descripcion FROM expedido WHERE codigo!= '$id' ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["descripcion"];
		$i++;
	}
	
	$smarty->assign('existentes',$existentes);
	
	$sql = "SELECT * FROM expedido WHERE codigo = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('id',$id);
	$smarty->assign('depto',$resultado["descripcion"]);
	
	$smarty->display('sec/extension/modificar.html');
	die();
?>
