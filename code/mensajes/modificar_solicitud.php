<?php

	$id= $_REQUEST['id'];
	$id_para_movimiento= $id;
	
	//recuperando el id de la carpeta
	$sql= "SELECT id_carpeta FROM movimientos_carpetas WHERE id_movimiento_carpeta='$id' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$id= $resultado["id_carpeta"];
	
	//recuperando los datos de la carpeta
	$sql= "SELECT c.carpeta, c.id_propietario, t.tipo_bien, o.nombre FROM carpetas c, tipos_bien t, oficinas o WHERE c.id_tipo_carpeta=t.id_tipo_bien AND c.id_oficina=o.id_oficina AND c.id_carpeta='$id' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$mostrar_carpeta= "Tipo : ".$resultado["tipo_bien"]."<br>Of&nbsp;&nbsp;&nbsp;&nbsp;: ".$resultado["nombre"]."<br>Obs&nbsp;&nbsp;: ".$resultado["carpeta"];
	
	
	//datos del propietario
	$aux= $resultado["id_propietario"];
	$sql= "SELECT nombres, mis FROM propietarios WHERE id_propietario='$aux' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo_nombre= $resultado["nombres"];
	$titulo_mis= $resultado["mis"];

	
	//usuarios del sistema
	//id del usuario actual
	/* $login_acc= $_SESSION['nombreUsu'];
	$password_acc= md5($_SESSION['passwordUsu']);
	$sql= "SELECT id_usuario FROM usuarios WHERE login='$login_acc' AND password='$password_acc' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$id_us_actual= $resultado["id_usuario"]; */
	$id_us_actual= $_SESSION["idusuario"];

	//usuarios autoriza
// comentado por percy
//	$sql= "SELECT id_usuario, nombres  FROM usuarios WHERE id_usuario!='$id_us_actual' AND id_perfil='3' ORDER BY nombres ";
	//echo "$sql";
	$id_almacen = $_SESSION["id_almacen"];
	
	//recuperando los usuarios destino    AND id_perfil='3'
//	$sql= "SELECT id_usuario, nombres FROM usuarios INNER JOIN almacen ON id_usautoriza=usuarios.id_usuario WHERE almacen.id_almacen='$id_almacen'  ORDER BY nombres ";
//Modificado por Percy 20170816	
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
		$nombres[$i]=  $row["nombres"];
		
		$i++;
	}
	
	//recuperando los datos del prestamo
	$sql= "SELECT * FROM movimientos_carpetas WHERE id_carpeta='$id' AND flujo='0' AND id_estado='1' ";
	//echo "$sql <br>";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$id_movimiento= $resultado["id_movimiento_carpeta"];
	$aux_fecha= $resultado["corr_auto"];
	$aux_a= explode(" ",$aux_fecha);
	$plazo_fecha= $aux_a[0];
	$plazo_hora= $aux_a[1];
	$id_us_autoriza= $resultado["id_us_autoriza"];
	$observacion= $resultado["obs_1"];
	
	
	$smarty->assign('id_movimiento',$id_movimiento);
	$smarty->assign('plazo_fecha',$plazo_fecha);
	$smarty->assign('plazo_hora',$plazo_hora);
	$smarty->assign('id_us_autoriza',$id_us_autoriza);
	$smarty->assign('observacion',$observacion);
	
	
	$smarty->assign('ids_usuario',$ids_usuario);
	$smarty->assign('nombres',$nombres);
	
	$smarty->assign('mostrar_carpeta',$mostrar_carpeta);
	$smarty->assign('id',$id);
	
	$smarty->assign('titulo_nombre',$titulo_nombre);
	$smarty->assign('titulo_mis',$titulo_mis);
			
	$smarty->display('mensajes/modificar_solicitud.html');
	die();
?>
