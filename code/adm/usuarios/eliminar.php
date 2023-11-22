<?php

	$id= $_REQUEST['id'];
	
	$sql = "SELECT u.nombres, u.login, u.activo, p.perfil, o.nombre as oficina, al.nombre as almacen
	FROM usuarios u  
	inner join perfiles p on p.id_perfil = u.id_perfil
	inner join oficinas o ON o.id_oficina = u.id_oficina 
	LEFT JOIN almacen al ON o.id_almacen = al.id_almacen ".
	"WHERE u.id_usuario='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$nombres= $resultado["nombres"];
	
	$login= $resultado["login"];
	$activo= $resultado["activo"];

	$smarty->assign('id',$id);
	$smarty->assign('nombres',$nombres);
	$smarty->assign('login',$login);
	$smarty->assign('activo',$activo);

	$smarty->assign('perfil',$resultado["perfil"]);
	$smarty->assign('oficina',$resultado["oficina"]);
	$smarty->assign('almacen',$resultado["almacen"]);
	
	
	$smarty->assign('fecha_eli',date("d-m-Y"));
	
	$smarty->display('adm/usuarios/eliminar.html');
	die();
?>
