<?php
ini_set('odbc.defaultlrl','1048576');

	$id_movimiento= $_REQUEST["id"];
	//recuperando los datos del movimiento  SUBSTRING(m.obs_3,1,100) as
	$sql= "SELECT m.id_carpeta, m.auto_arch, m.auto_arch_plazo, m.id_us_corriente, m.id_us_autoriza, m.obs_1,  obs_3, u.nombres  FROM movimientos_carpetas m, usuarios u WHERE id_movimiento_carpeta='$id_movimiento' AND m.flujo='0' AND m.id_us_corriente=u.id_usuario ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$id_carpeta= $resultado["id_carpeta"];
	

	$fecha_auto_arch= $resultado["auto_arch"];
	$fecha_auto_arch_plazo= $resultado["auto_arch_plazo"];
	
	$observacion= $resultado["obs_1"];
	$observacion_auto= $resultado["obs_3"];
	$corriente=  $resultado["nombres"];
	
	//firma autorizada
	$aux= $resultado["id_us_autoriza"];
	$sql_a= "SELECT * FROM usuarios WHERE id_usuario='$aux' ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	$autoriza= $row_a["nombres"];
	
	//id de la carpeta
	$id= $id_carpeta;
	//recuperando los datos de la carpeta
	$sql= "SELECT c.carpeta, t.tipo_bien, o.nombre, p.mis, p.nombres FROM carpetas c, tipos_bien t, oficinas o, propietarios p WHERE c.id_tipo_carpeta=t.id_tipo_bien AND c.id_oficina=o.id_oficina AND c.id_carpeta='$id' AND c.id_propietario=p.id_propietario ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$mostrar_propietario= "Propietario : ".$resultado["nombres"]."<br>MIS : ".$resultado["mis"];
	$mostrar_carpeta= "Tipo : ".$resultado["tipo_bien"]."<br>Of&nbsp;&nbsp;&nbsp;&nbsp;: ".$resultado["nombre"]."<br>Ref.&nbsp;&nbsp;: ".$resultado["carpeta"];
			
	
	$smarty->assign('id',$id_movimiento);
	$smarty->assign('id_carpeta',$id_carpeta);
	$smarty->assign('fecha_auto_arch',$fecha_auto_arch);
	$smarty->assign('fecha_auto_arch_plazo',$fecha_auto_arch_plazo);
	$smarty->assign('observacion',$observacion);
	$smarty->assign('observacion_auto',$observacion_auto);
	$smarty->assign('corriente',$corriente);
	$smarty->assign('autoriza',$autoriza);
	$smarty->assign('mostrar_propietario',$mostrar_propietario);
	$smarty->assign('mostrar_carpeta',$mostrar_carpeta);
	
			
	$smarty->display('mensajes/aceptar_sol_auto_arch.html');
	die();
?>