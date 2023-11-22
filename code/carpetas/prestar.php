<?php

	$id= $_REQUEST['id'];
	
	//recuperando los datos de la carpeta
	$sql= "SELECT c.carpeta, t.tipo_bien, o.nombre FROM carpetas c, tipos_bien t, oficinas o WHERE c.id_tipo_carpeta=t.id_tipo_bien AND c.id_oficina=o.id_oficina AND c.id_carpeta='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$mostrar_carpeta= "Tipo : ".$resultado["tipo_bien"]."<br>Of&nbsp;&nbsp;&nbsp;&nbsp;: ".$resultado["nombre"]."<br>Obs&nbsp;&nbsp;: ".$resultado["carpeta"];
			
	//usuarios del sistema
	//id del usuario actual
	/*
	$login_acc= $_SESSION['nombreUsu'];
	$password_acc= md5($_SESSION['passwordUsu']);
	$sql= "SELECT id_usuario FROM usuarios WHERE login='$login_acc' AND password='$password_acc' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_us_actual= $resultado["id_usuario"];
*/
//$id_us_actual= $_SESSION["idusuario"];
//$id_oficina = $_SESSION["id_oficina"];
$id_almacen = $_SESSION["id_almacen"];
	////AND id_perfil!='1'
	$sql= "SELECT us.id_usuario, us.nombres FROM usuarios us, oficinas ofi 
		WHERE  us.id_oficina = ofi.id_oficina AND ofi.id_almacen = $id_almacen AND us.activo='S'
		ORDER BY nombres ";
	//echo "$sql";
	$query = consulta($sql);
	$ids_usuario= array();
	$nombres= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_usuario[$i]= $row["id_usuario"];
		$nombres[$i]= $row["nombres"];
		
		$i++;
	}
	
	$smarty->assign('ids_usuario',$ids_usuario);
	$smarty->assign('nombres',$nombres);
	
	$smarty->assign('mostrar_carpeta',$mostrar_carpeta);
	$smarty->assign('id',$id);
			
	$smarty->display('carpetas/prestar.html');
	die();
?>
