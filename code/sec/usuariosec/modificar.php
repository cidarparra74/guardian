<?php
	$id= $_REQUEST['id'];
	
	//recuperando los existentes
	$sql = "SELECT * FROM localizacion  ";
	$query= consulta($sql);
	$localiza= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$localiza[$i]= array('id' => $row["localizacion"], 'depto' => $row["departamento"]);
		$i++;
	}
	
	$smarty->assign('localiza',$localiza);
	
	//recuperando los existentes
	$sql = "SELECT idperfil, descripcion FROM perfil  ";
	$query= consulta($sql);
	$perfil= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$perfil[$i]= array('id'=> $row["idperfil"], 'perfil'=> $row["descripcion"]);
		$i++;
	}
	
	$smarty->assign('perfil',$perfil);
	
	
	$sql = "SELECT * FROM usuario WHERE login = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('id',$id);
	$smarty->assign('login',$resultado["login"]);
	$smarty->assign('nombre',$resultado["nombres"]);
	$smarty->assign('paterno',$resultado["appaterno"]);
	$smarty->assign('materno',$resultado["apmaterno"]);
	$smarty->assign('estado',$resultado["estado"]);
	$smarty->assign('idperfil',$resultado["idperfil"]);
	$smarty->assign('loca',$resultado["localizacion"]);
	
	
	unset($link);
	require('../lib/conexionMNU.php');
	$sql = "select usu.nombres, ofi.nombre, alm.nombre as almacen from usuarios usu 
	inner join oficinas ofi on ofi.id_oficina = usu.id_oficina
	inner join almacen alm on alm.id_almacen = ofi.id_almacen 
	where login = '$id'";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('usuario',$resultado["nombres"]);
	$smarty->assign('oficina',$resultado["nombre"]);
	$smarty->assign('almacen',$resultado["almacen"]);
	
	$smarty->display('sec/usuariosec/modificar.html');
	die();
?>
