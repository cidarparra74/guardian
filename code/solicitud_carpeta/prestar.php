<?php

	$id= $_REQUEST['id'];
	
	//recuperando los datos de la carpeta
	$sql= "SELECT c.carpeta, t.tipo_bien, o.nombre FROM carpetas c, tipos_bien t, oficinas o 
	WHERE c.id_tipo_carpeta=t.id_tipo_bien AND c.id_oficina=o.id_oficina AND c.id_carpeta='$id' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$mostrar_carpeta= "Tipo : ".$resultado["tipo_bien"]."<br>Of&nbsp;&nbsp;&nbsp;&nbsp;: ".$resultado["nombre"]."<br>Nro Bien&nbsp;&nbsp;: ".$resultado["carpeta"];
			
	//usuarios del sistema
	$id_us_actual = $_SESSION["idusuario"];
	$id_almacen = $_SESSION["id_almacen"];
	//recuperando los usuarios destino   
	//antes: al asesor legal del recinto
//	$sql= "SELECT id_usuario, nombres 
//	FROM usuarios INNER JOIN almacen ON id_usautoriza=usuarios.id_usuario 
//	WHERE almacen.id_almacen='$id_almacen' ORDER BY nombres ";
	//ahora: a los abogados del recinto
		//obtenemos perfil de abogados:
		$sql="SELECT TOP 1 id_perfil_abo, id_perfil_cat FROM opciones ";
		$result= consulta($sql);
		$row= $result->fetchRow(DB_FETCHMODE_ASSOC);
		$id_perfil_abo= $row["id_perfil_abo"]."','".$row["id_perfil_cat"];
	//obtenemos los abogados del recinto
	$sql= "SELECT us.id_usuario, us.nombres 
	FROM usuarios us INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina
	WHERE us.id_perfil IN ('$id_perfil_abo') AND ofi.id_almacen='$id_almacen' AND us.activo='S' ORDER BY us.nombres ";
	$result= consulta($sql);
	$ids_usuario= array();
	$nombres= array();
	$i=0;
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_usuario[$i]= $row["id_usuario"];
		$nombres[$i]= $row["nombres"];
		
		$i++;
	}
	
	$smarty->assign('ids_usuario',$ids_usuario);
	$smarty->assign('nombres',$nombres);
	
	$smarty->assign('mostrar_carpeta',$mostrar_carpeta);
	$smarty->assign('id',$id);
			
	$smarty->display('solicitud_carpeta/prestar.html');
	die();
?>
