<?php 
	//principales
	$_SESSION["idusuario"] = $data['id_usuario'];
	$_SESSION["idperfil"] = $data['id_perfil'];
	$_SESSION["nombreusr"] = $data['nombres'];
	$_SESSION["id_oficina"] = $data['id_oficina'];
	$_SESSION["id_almacen"] = $data['id_almacen'];
	
	//adicionales
	$sqlOption = "SELECT TOP 1 * FROM opciones ";
	$queryOPC = consulta($sqlOption);
	$rowOPC = $queryOPC->fetchRow(DB_FETCHMODE_ASSOC);
	$_SESSION["enable_mail"] = $rowOPC['enable_mail'];
	$_SESSION["enable_ws"] = $rowOPC['enable_ws'];
	
//	$_SESSION["ws_url1"] = $rowOPC['ws_url1'];
//	$_SESSION["ws_url2"] = $rowOPC['ws_url2'];
//	$_SESSION["mail_smtp"] = $rowOPC['mail_smtp'];
//	$_SESSION["logo01"] = $rowOPC['logo01'];
//	$_SESSION["enable_ncaso"] = $rowOPC['id_almacen'];

	//unset($rowOPC);
	
	
	//para preservar carpeta de trabajo
	$MainDir = getcwd();
	$_SESSION['MainDir'] = $MainDir;
?>