<?php

	$id= $_REQUEST['id'];
	
	$sql = "SELECT u.* FROM usuarios u WHERE u.id_usuario='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);


	$smarty->assign('id',$id);
	$smarty->assign('id_perfil',$resultado["id_perfil"]);
	$smarty->assign('nombres',$resultado["nombres"]);
	$smarty->assign('ci',$resultado["ci"]);
	$smarty->assign('telefono',$resultado["telefono"]);
	$smarty->assign('direccion',$resultado["direccion"]);
	$smarty->assign('correoe',$resultado["correoe"]);
	//$smarty->assign('excepciones',$resultado["excepciones"]);
	$smarty->assign('p_login',$resultado["login"]);
	$smarty->assign('password','');
	$smarty->assign('id_oficina',$resultado["id_oficina"]);
	$smarty->assign('activo',$resultado["activo"]);

	
	//perfiles existentes
	$sql= "SELECT * FROM perfiles WHERE activo = 'S' ORDER BY perfil ";	
	$query= consulta($sql);
	$i=0;
	$perfiles= array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$perfiles[$i]= array('id_perfil' =>$row["id_perfil"],
								'perfil' =>$row["perfil"]);
		$i++;
	}
	$smarty->assign('perfiles',$perfiles);
	
	//oficinas
	$sql = "SELECT al.nombre as almacen, ofi.* FROM oficinas ofi LEFT JOIN almacen al ON ofi.id_almacen = al.id_almacen ORDER BY al.nombre, ofi.nombre ";
	$query= consulta($sql);
	$oficinas = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$oficinas[] = array('id_oficina' => $row["id_oficina"],
							'nombre' => $row["almacen"].'-'.$row["nombre"]);
	}
	
	$smarty->assign('oficinas',$oficinas);
	//si se loguea mediante WS no controlar el tamaño del login ni pass
	$sql="SELECT TOP 1 long_login, long_pass FROM opciones ";
			$query = consulta($sql);
			$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$smarty->assign('long_login',$data['long_login']);
			$smarty->assign('long_pass',$data['long_pass']);
	
	
	$smarty->display('adm/usuarios/modificar.html');
	die();
?>
