<?php

	$id_movimiento= $_REQUEST["id"];
	//recuperando los datos del movimiento
	$sql= "SELECT m.id_carpeta, m.corr_auto, m.auto_arch, m.arch_corr_prest, m.arch_corr_plazo, m.arch_corr_conf, m.corr_arch_ret, m.corr_arch_ret_conf, m.id_us_corriente, m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, m.obs_5, m.obs_6, m.obs_7, u.nombres  FROM movimientos_carpetas m, usuarios u WHERE id_movimiento_carpeta='$id_movimiento' AND m.flujo='0' AND m.id_estado='7' AND m.id_us_corriente=u.id_usuario ";
	//echo "$sql";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$id_carpeta= $resultado["id_carpeta"];
	

	$fecha_corr_auto= $resultado["corr_auto"];
	$fecha_auto_arch= $resultado["auto_arch"];
	$fecha_arch_corr_prest= $resultado["arch_corr_prest"];
	$fecha_arch_corr_plazo= $resultado["arch_corr_plazo"];
	$fecha_arch_corr_conf= $resultado["arch_corr_conf"];
	$fecha_corr_arch_ret= $resultado["corr_arch_ret"];
	$fecha_corr_arch_ret_conf= $resultado["corr_arch_ret_conf"];
	
	$observacion= $resultado["obs_1"];
	$observacion_auto= $resultado["obs_3"];
	$observacion_arch_corr= $resultado["obs_4"];
	$observacion_arch_conf= $resultado["obs_5"];
	$observacion_corr_arch_ret= $resultado["obs_6"];
	$observacion_corr_arch_ret_conf= $resultado["obs_7"];
	
	$corriente=  $resultado["nombres"];
	
	//firma autorizada
	$aux= $resultado["id_us_autoriza"];
	$sql_a= "SELECT * FROM usuarios WHERE id_usuario='$aux' ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	$autoriza= $row_a["apellidos"]." ".$row_a["nombres"];
	
	//id de la carpeta
	$id= $id_carpeta;
	//recuperando los datos de la carpeta
	$sql= "SELECT c.carpeta, t.tipo, o.nombre, p.mis, p.nombres FROM carpetas c, tipos_bien t, oficinas o, propietarios p WHERE c.id_tipo_carpeta=t.id_tipo_bien AND c.id_oficina=o.id_oficina AND c.id_carpeta='$id' AND c.id_propietario=p.id_propietario ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$mostrar_propietario= "Propietario : ".$resultado["nombres"]."<br>MIS : ".$resultado["mis"];
	$mostrar_carpeta= "Tipo : ".$resultado["tipo_bien"]."<br>Of&nbsp;&nbsp;&nbsp;&nbsp;: ".$resultado["nombre"]."<br>Obs&nbsp;&nbsp;: ".$resultado["carpeta"];
			
	
	$smarty->assign('id',$id_movimiento);
	$smarty->assign('id_carpeta',$id_carpeta);
	$smarty->assign('corriente',$corriente);
	$smarty->assign('autoriza',$autoriza);
	$smarty->assign('mostrar_propietario',$mostrar_propietario);
	$smarty->assign('mostrar_carpeta',$mostrar_carpeta);
	
	$smarty->assign('fecha_corr_auto',$fecha_corr_auto);
	$smarty->assign('fecha_auto_arch',$fecha_auto_arch);
	$smarty->assign('fecha_arch_corr_prest',$fecha_arch_corr_prest);
	$smarty->assign('fecha_arch_corr_plazo',$fecha_arch_corr_plazo);
	$smarty->assign('fecha_arch_corr_conf',$fecha_arch_corr_conf);
	$smarty->assign('fecha_corr_arch_ret',$fecha_corr_arch_ret);
	$smarty->assign('fecha_corr_arch_ret_conf',$fecha_corr_arch_ret_conf);
	
	$smarty->assign('observacion',$observacion);
	$smarty->assign('observacion_auto',$observacion_auto);
	$smarty->assign('observacion_arch_corr',$observacion_arch_corr);
	$smarty->assign('observacion_arch_conf',$observacion_arch_conf);
	$smarty->assign('observacion_corr_arch_ret',$observacion_corr_arch_ret);
	$smarty->assign('observacion_corr_arch_ret_conf',$observacion_corr_arch_ret_conf);

	$smarty->display('lista_mensajes/eliminar_en_archivo.html');
	die();
?>
