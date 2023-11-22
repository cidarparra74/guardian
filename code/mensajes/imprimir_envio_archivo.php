<?php

	$id_movimiento= $_REQUEST["id"];

	//recuperando las carpetas devueltas a archivo sin confirmar
	//lista de carpetas retornado sin confirmacion apellido
	$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_arch_ret, m.id_us_archivo, m.obs_6, u.nombres FROM movimientos_carpetas m, usuarios u WHERE m.flujo='0' AND m.id_us_corriente= '$id_us_actual' AND m.id_estado='6' AND m.id_us_archivo=u.id_usuario ";
	$result= ejecutar($sql);
	$i=0;
	//eliminando la tabla temporal de las devoluciones a archivo
	$sql_del= "DELETE FROM tmp_comun_retorno_carpeta_arch ";
	ejecutar($sql_del);
	
	
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ret_id_movimiento= $row["id_movimiento_carpeta"];
		
		$aux= $row["id_carpeta"];
		$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$ret_mis= $row_a["mis"];
		$ret_propietario= $row_a["nombres"];
		$ret_carpeta= "&nbsp;Tipo&nbsp;:&nbsp;".$row_a["tipo_bien"]."<br>&nbsp;of.&nbsp;&nbsp;&nbsp;:&nbsp;".$row_a["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row_a["carpeta"];
		$ret_tipo= $row_a["tipo_bien"];
		$ret_agencia= $row_a["nombre"];
		
		$ret_obs= $row["obs_6"];
		
		
		
		$ret_fecha= $row["corr_arch_ret"];
		$aux_a= explode(" ",$ret_fecha);
		$aux_b= $aux_a[0];
		$aux_c= $aux_a[1];
		$aux_d= $bd_fechas->devolver_fecha($bd_fecha,$aux_b);
		//echo "$aux_d<br>";
		$ret_fecha=$aux_d." ".$aux_c;
		$ret_fecha= "CONVERT(DATETIME,'$ret_fecha',102)";
		
		$ret_archivo=  $row["nombres"];
		
		$i++;
		//insertando en la tabla temporal para la impresion
		$sql_in= "INSERT INTO tmp_comun_retorno_carpeta_arch(mis, nombre, fecha_devolucion, tipo, agencia) ";
		$sql_in.= "VALUES('$ret_mis', '$ret_propietario', $ret_fecha, '$ret_tipo', '$ret_agencia')";
		//echo "$sql_in<br>";
		consulta($sql_in);
	}
	
	
	
	//recuperando los datos del movimiento
	/*$sql= "SELECT m.id_carpeta, m.corr_auto, m.auto_arch, m.arch_corr_prest, m.arch_corr_plazo, m.arch_corr_conf, m.corr_arch_ret, m.id_us_archivo, m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, m.obs_5, m.obs_6, u.nombres FROM movimientos_carpetas m, usuarios u WHERE id_movimiento_carpeta='$id_movimiento' AND m.flujo='0' AND m.id_estado='6' AND m.id_us_archivo=u.id_usuario ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$id_carpeta= $resultado["id_carpeta"];
	

	$fecha_corr_auto= $resultado["corr_auto"];
	$fecha_auto_arch= $resultado["auto_arch"];
	$fecha_arch_corr_prest= $resultado["arch_corr_prest"];
	$fecha_arch_corr_plazo= $resultado["arch_corr_plazo"];
	$fecha_arch_corr_conf= $resultado["arch_corr_conf"];
	$fecha_corr_arch_ret= $resultado["corr_arch_ret"];
	
	$observacion= $resultado["obs_1"];
	$observacion_auto= $resultado["obs_3"];
	$observacion_arch_corr= $resultado["obs_4"];
	$observacion_arch_conf= $resultado["obs_5"];
	$observacion_ret= $resultado["obs_6"];
	
	$archivo= $resultado["apellidos"]." ".$resultado["nombres"];
	
	//firma autorizada
	$aux= $resultado["id_us_autoriza"];
	$sql_a= "SELECT * FROM usuarios WHERE id_usuario='$aux' ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	$autoriza= $row_a["apellidos"]." ".$row_a["nombres"];
	
	//id de la carpeta
	$id= $id_carpeta;
	//recuperando los datos de la carpeta
	$sql= "SELECT c.carpeta, t.tipo_bien, o.nombre, p.mis, p.nombres FROM carpetas c, tipos_bien t, oficinas o, propietarios p WHERE c.id_tipo_carpeta=t.id_tipo_bien AND c.id_oficina=o.id_oficina AND c.id_carpeta='$id' AND c.id_propietario=p.id_propietario ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$mostrar_propietario= "Propietario : ".$resultado["nombres"]."<br>MIS : ".$resultado["mis"];
	$mostrar_carpeta= "Tipo : ".$resultado["tipo_bien"]."<br>Of&nbsp;&nbsp;&nbsp;&nbsp;: ".$resultado["nombre"]."<br>Obs&nbsp;&nbsp;: ".$resultado["carpeta"];
	
	$para_mis= $resultado["mis"];		
	$para_nombre= $resultado["nombres"];
	$para_tipo= $resultado["tipo_bien"];
	$para_agencia= $resultado["nombre"];
	$para_carpeta= $resultado["carpeta"];
	$para_archivo= $archivo;
	$para_fecha= $fecha_corr_arch_ret;
	//echo "acc: $para_fecha";
	
	$smarty->assign('para_fecha',$para_fecha);
	$smarty->assign('para_archivo',$para_archivo);
	$smarty->assign('para_mis',$para_mis);
	$smarty->assign('para_nombre',$para_nombre);
	$smarty->assign('para_tipo',$para_tipo);
	$smarty->assign('para_agencia',$para_agencia);
	$smarty->assign('para_carpeta',$para_carpeta);
	
	$smarty->assign('id',$id_movimiento);
	$smarty->assign('id_carpeta',$id_carpeta);
	$smarty->assign('archivo',$archivo);
	$smarty->assign('autoriza',$autoriza);
	$smarty->assign('mostrar_propietario',$mostrar_propietario);
	$smarty->assign('mostrar_carpeta',$mostrar_carpeta);
	
	$smarty->assign('fecha_corr_auto',$fecha_corr_auto);
	$smarty->assign('fecha_auto_arch',$fecha_auto_arch);
	$smarty->assign('fecha_arch_corr_prest',$fecha_arch_corr_prest);
	$smarty->assign('fecha_arch_corr_plazo',$fecha_arch_corr_plazo);
	$smarty->assign('fecha_arch_corr_conf',$fecha_arch_corr_conf);
	$smarty->assign('fecha_corr_arch_ret',$fecha_corr_arch_ret);
	
	$smarty->assign('observacion',$observacion);
	$smarty->assign('observacion_auto',$observacion_auto);
	$smarty->assign('observacion_arch_corr',$observacion_arch_corr);
	$smarty->assign('observacion_arch_conf',$observacion_arch_conf);
	$smarty->assign('observacion_ret',$observacion_ret);
	*/
	$bd= $bd_fechas->devolver_bd();
	$usuario= $bd_fechas->devolver_login();
	$password= $bd_fechas->devolver_password();
	
	
	$smarty->assign('ret_archivo',$ret_archivo);
	$smarty->assign('bd',$bd);
	$smarty->assign('usuario',$usuario);
	$smarty->assign('password',$password);
				
	$smarty->display('mensajes/imprimir_envio_archivo.html');
	die();
?>
