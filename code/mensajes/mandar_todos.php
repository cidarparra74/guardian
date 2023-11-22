<?php

	
	//recuperando todos los marcados
	$cantidad= $_REQUEST['cantidad_total'];
	$id_us_mandar= $_REQUEST['para_id_us'];
	//recuperando el nombre de usuario a mandar
	$sql="SELECT nombres FROM usuarios WHERE id_usuario='$id_us_mandar' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$nombre_us_mandar= $resultado["nombres"];
	
	
	$propietario= array();
	$carpeta= array();
	$motivo= array();
	$solicitante= array();
	$fecha_solicitud= array();
	$ids_mov= array();
	$cantidad_poner=array();
	$contador=0;
	
	for($i=1; $i<=$cantidad; $i++){
		$aux= "marcado_".$i."_".$id_us_mandar;
		$aux_a= "id_marca_".$i;
		if(isset($_REQUEST["$aux"])){ //entonces solicitud marcada
			$id_envio= $_REQUEST["$aux_a"];
			$ids_mov[$contador]= $id_envio;
			
			//recuperando los datos de la carpeta
			$sql= "SELECT m.id_carpeta, m.corr_auto, m.id_us_inicio, m.id_us_corriente, m.obs_1, u.nombres FROM movimientos_carpetas m, usuarios u WHERE id_movimiento_carpeta='$id_envio' AND m.id_us_corriente=u.id_usuario ";
			$result= consulta($sql);
			$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
			$id_carpeta= $resultado["id_carpeta"];
			
			$fecha_solicitud[$contador]= $resultado["corr_auto"];
			$motivo[$contador]= $resultado["obs_1"];
			$solicitante[$contador]= $resultado["nombres"];
			
			$id_us_inicio= $resultado["id_us_inicio"];
			$sql= "SELECT id_usuario, nombres FROM usuarios WHERE id_usuario='$id_us_inicio'  ";
			$result= consulta($sql);
			$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
			$ids_usuario_inicio[$contador]= $resultado["id_usuario"];
			$nombres_inicio[$contador]= $resultado["nombres"];
		
			//id de la carpeta
			$id= $id_carpeta;
			//recuperando los datos de la carpeta
			$sql= "SELECT c.carpeta, t.tipo_bien, o.nombre, p.mis, p.nombres FROM carpetas c, tipos_bien t, oficinas o, propietarios p WHERE c.id_tipo_carpeta=t.id_tipo_bien AND c.id_oficina=o.id_oficina AND c.id_carpeta='$id' AND c.id_propietario=p.id_propietario ";
			$result= consulta($sql);
			$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
			$propietario[$contador]= "Propietario : ".$resultado["nombres"]."<br>MIS : ".$resultado["mis"];
			$carpeta[$contador]= "Tipo : ".$resultado["tipo_bien"]."<br>Of&nbsp;&nbsp;&nbsp;&nbsp;: ".$resultado["nombre"]."<br>Obs&nbsp;&nbsp;: ".$resultado["carpeta"];
			
			$cantidad_poner[$contador]=$contador+1;
			$contador++;
		}
	}
	
	//recuperando los usuarios del sistema
	//id del usuario actual
	/*
	$login_acc= $_SESSION['nombreUsu'];
	$password_acc= md5($_SESSION['passwordUsu']);
	$sql= "SELECT id_usuario FROM usuarios WHERE login='$login_acc' AND password='$password_acc' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$id_us_actual= $resultado["id_usuario"];
	*/
	//recuperando la lista de usuarios de archivo  // AND id_perfil='4'
	//$sql= "SELECT id_usuario, nombres FROM usuarios WHERE id_usuario!='$id_us_actual'  ORDER BY nombres ";
	$id_almacen = $_SESSION["id_almacen"];
	//recuperando la lista de usuarios de archivo
	$sql= "SELECT id_usuario, nombres FROM usuarios us 
INNER JOIN oficinas ofi ON ofi.id_oficina=us.id_oficina
inner join opciones op on op.id_perfil_cat = us.id_perfil
		WHERE ofi.id_almacen='$id_almacen' ORDER BY nombres ";
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
	/*	
	//recuperando la lista de todos los usuarios diferentes al actual  // AND id_perfil!='1'
	$sql= "SELECT id_usuario, nombres FROM usuarios WHERE id_usuario!='$id_us_actual'  ORDER BY nombres ";
	$result= consulta($sql);
	$ids_usuario_inicio= array();
	$nombres_inicio= array();
	$i=0;
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_usuario_inicio[$i]= $row["id_usuario"];
		$nombres_inicio[$i]= $row["nombres"];
		
		$i++;
	}
	*/
	
	
	$smarty->assign('ids_usuario_inicio',$ids_usuario_inicio);
	$smarty->assign('nombres_inicio',$nombres_inicio);
	
	$smarty->assign('ids_mov',$ids_mov);
	$smarty->assign('fecha_solicitud',$fecha_solicitud);
	$smarty->assign('motivo',$motivo);
	$smarty->assign('solicitante',$solicitante);
	$smarty->assign('propietario',$propietario);
	$smarty->assign('carpeta',$carpeta);
	$smarty->assign('cantidad_poner',$cantidad_poner);
	$smarty->assign('cantidad',$cantidad);
	
	$smarty->assign('id_us_mandar',$id_us_mandar);
	$smarty->assign('nombre_us_mandar',$nombre_us_mandar);
	
			
	$smarty->display('mensajes/mandar_todos.html');
	die();
?>
