<?php
	$id= $_REQUEST['id'];
	
	//recuperando los existentes
	$sql = "SELECT localizacion FROM localizacion WHERE localizacion!= '$id' ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["nombre"];
		$i++;
	}
	
	$smarty->assign('existentes',$existentes);
	
	$sql = "SELECT * FROM localizacion WHERE localizacion = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('id',$id);
	$smarty->assign('loca',$resultado["localizacion"]);
	$smarty->assign('depto',$resultado["departamento"]);
	
	$smarty->display('sec/localiza/modificar.html');
	die();
?>
