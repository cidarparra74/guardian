<?php

	$id= $_REQUEST['id'];
	
	$sql= "SELECT c.id_carpeta, c.carpeta, c.id_propietario, c.id_oficina, o.nombre AS o_nombre, tc.tipo_bien AS tipo_carpeta 
	FROM carpetas c, oficinas o, tipos_bien tc ";
	$sql.= "WHERE c.id_oficina=o.id_oficina AND c.id_tipo_carpeta=tc.id_tipo_bien AND id_carpeta='$id' ";

	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$carpeta= $resultado["carpeta"];
	
	$p_id= $resultado["id_propietario"];
	//$p_nombres= $resultado["p_apellidos"]." ".$resultado["p_nombres"];
	$o_id= $resultado["id_oficina"];
	$o_nombre= $resultado["o_nombre"];
	$tipo_carpeta= $resultado["tipo_carpeta"];
	
	//verificando que se pueda eliminar
	$sql= "SELECT MAX(id_carpeta) AS existe FROM documentos_propietarios WHERE id_carpeta='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$existe= $resultado["existe"];
	if($existe == 0){
		$puede_eliminar="si";
	}
	else{
		$puede_eliminar="no";
	}
	
	$smarty->assign('id',$id);
	$smarty->assign('carpeta',$carpeta);
	
	$smarty->assign('p_id',$p_id);
	//$smarty->assign('p_nombres',$p_nombres);
	$smarty->assign('o_id',$o_id);
	$smarty->assign('o_nombre',$o_nombre);
	$smarty->assign('tipo_carpeta',$tipo_carpeta);
	
	$smarty->assign('puede_eliminar',$puede_eliminar);
	
	$smarty->display('carpetas/eliminar.html');
	die();
?>
