<?php

	$id_movimiento= $_REQUEST["id"];
	//recuperando los datos del movimiento
	$sql= "SELECT m.id_carpeta, m.corr_auto, m.auto_arch, m.auto_arch_plazo, m.id_us_corriente, m.id_us_archivo, m.obs_1, m.obs_3, u.nombres  FROM movimientos_carpetas m, usuarios u WHERE id_movimiento_carpeta='$id_movimiento' AND m.id_us_corriente=u.id_usuario ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$id_carpeta= $resultado["id_carpeta"];
	
	/***************para enviar la solicitud a otro usuarios****************************/
	$id_us_inicio= $resultado["id_us_corriente"];
	/**************************************************************/
	
	$aux= $resultado["corr_auto"];
	$aux_a= explode(" ",$aux);
	$fecha_fecha= $aux_a[0];
	$fecha_hora= $aux_a[1];
	
	$aux= $resultado["auto_arch_plazo"];
	$aux_a= explode(" ",$aux);
	$fecha_p_fecha= $aux_a[0];
	$fecha_p_hora= $aux_a[1];
	
	$observacion= $resultado["obs_1"];
	$observacion_p= $resultado["obs_3"];

	$corriente=  $resultado["nombres"];
	$id_us_archivo= $resultado["id_us_archivo"];
	
	//id de la carpeta
	$id= $id_carpeta;
	//recuperando los datos de la carpeta
	$sql= "SELECT c.carpeta, t.tipo, o.nombre, p.mis, p.nombres FROM carpetas c, tipos_bien t, oficinas o, propietarios p WHERE c.id_tipo_carpeta=t.id_tipo_bien AND c.id_oficina=o.id_oficina AND c.id_carpeta='$id' AND c.id_propietario=p.id_propietario ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$mostrar_propietario= "Propietario : ".$resultado["nombres"]."<br>MIS : ".$resultado["mis"];
	$mostrar_carpeta= "Tipo : ".$resultado["tipo_bien"]."<br>Of&nbsp;&nbsp;&nbsp;&nbsp;: ".$resultado["nombre"]."<br>Obs&nbsp;&nbsp;: ".$resultado["carpeta"];
			
	//recuperando los usuarios del sistema
	//id del usuario actual
	$login_acc= $_SESSION['nombreUsu'];
	$password_acc= md5($_SESSION['passwordUsu']);
	$sql= "SELECT id_usuario FROM usuarios WHERE login='$login_acc' AND password='$password_acc' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$id_us_actual= $resultado["id_usuario"];

	//recuperando los usuarios de archivo AND id_perfil='4'
	$sql= "SELECT id_usuario, nombres  FROM usuarios WHERE id_usuario!='$id_us_actual' ORDER BY nombres ";
	//echo "$sql";
	$result= consulta($sql);
	$ids_usuario= array();
	$nombres= array();
	$i=0;
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_usuario[$i]= $row["id_usuario"];
		$nombres[$i]=  $row["nombres"];
		
		$i++;
	}
	
	
	
	//recuperando la lista de todos los usuarios diferentes al actual AND id_perfil!='1'
	$sql= "SELECT id_usuario, nombres  FROM usuarios WHERE id_usuario!='$id_us_actual' ORDER BY nombres ";
	$result= consulta($sql);
	$ids_usuario_inicio= array();
	$nombres_inicio= array();
	$i=0;
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_usuario_inicio[$i]= $row["id_usuario"];
		$nombres_inicio[$i]=  $row["nombres"];
		
		$i++;
	}
	
	
	//para elegir el usuario destino, distinto del que lo mando
	//echo "inicio: $id_us_inicio";
	//print_r($ids_usuario_inicio);
	$smarty->assign('id_us_inicio',$id_us_inicio);
	$smarty->assign('ids_usuario_inicio',$ids_usuario_inicio);
	$smarty->assign('nombres_inicio',$nombres_inicio);
	
	$smarty->assign('ids_usuario',$ids_usuario);
	$smarty->assign('nombres',$nombres);
	
	$smarty->assign('id',$id_movimiento);
	$smarty->assign('id_carpeta',$id_carpeta);
	
	$smarty->assign('fecha_fecha',$fecha_fecha);
	$smarty->assign('fecha_hora',$fecha_hora);
	
	$smarty->assign('fecha_p_fecha',$fecha_p_fecha);
	$smarty->assign('fecha_p_hora',$fecha_p_hora);
	
	
	$smarty->assign('observacion',$observacion);
	$smarty->assign('observacion_p',$observacion_p);
	$smarty->assign('id_us_archivo',$id_us_archivo);
	
	$smarty->assign('corriente',$corriente);
		
	$smarty->assign('mostrar_carpeta',$mostrar_carpeta);
	$smarty->assign('mostrar_propietario',$mostrar_propietario);
			
	$smarty->display('mensajes/modificar_aceptada.html');
	die();
?>
