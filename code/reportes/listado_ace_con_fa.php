<?php

	//recuperando los usuarios del sistema
	//id del usuario actual
	

	$id_us_actual = $_SESSION["idusuario"];
	$nombre_us_actual= $_SESSION["nombreusr"];
	
	$ids_usuario = array();
	//recuperando la lista de usuarios corrientes

		$ids_usuario[] = array('id' => $id_us_actual, 'nombre' => $nombre_us_actual);

	
	$smarty->assign('ids_usuario',$ids_usuario);
	
	$smarty->display('reportes/listado_ace_con_fa.html');
	die();
?>