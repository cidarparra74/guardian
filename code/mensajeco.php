<?php

require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");

//vemos si es bsol
//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	if($enable_ws == 'S'){
		// caso banco SOL, se puede devolver desde CARPETAS las adicionales
		$smarty->assign('show','n'); 
	}else{
		$smarty->assign('show','s');
	}

$id_us_actual= $_SESSION["idusuario"];
$nombre_us_actual= $_SESSION["nombreusr"];


/**************datos del usuario actual*************/
$smarty->assign('id_us_actual',$id_us_actual);
$smarty->assign('nombre_us_actual',$nombre_us_actual);

/**************fin de datos del usuario actual*************/

	//href
	$carpeta_entrar="_main.php?action=mensajeco.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "mensajes";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	//aceptar la solicitud de archivo a comun
	if(isset($_REQUEST['aceptar_por_conf_arch'])){
		include("./mensajes/aceptar_por_conf_arch.php");
	}
	
	//aceptando la solicitud de archivo a comun
	if(isset($_REQUEST['boton_aceptar_por_conf_arch_x'])){
		include("./mensajes/aceptando_por_conf_arch.php");
	}
	
	//retornar carpeta a archivos
	if(isset($_REQUEST['retornar_conf_arch'])){
		include("./mensajes/retornar_conf_arch.php");
	}
	
	//retornando carpeta a archivo
	if(isset($_REQUEST['boton_retornar_conf_arch_x'])){
		include("./mensajes/retornando_conf_arch.php");
	}
	
	//modificar retorno de carpeta
	if(isset($_REQUEST['modificar_retorno'])){
		include("./mensajes/modificar_retorno.php");
	}
	
		//ventana para modificar la solicitud
	if(isset($_REQUEST['modificar_aceptada'])){
		$smarty->assign('readonly','readonly');
		include("./mensajes/modificar_aceptada.php");
	}
	
	//modificando el retorno de la carpeta
	if(isset($_REQUEST['boton_modificar_retorno_x'])){
		include("./mensajes/modificando_retorno.php");
	}
	
	//devolver la carpeta al propietario
	if(isset($_REQUEST["devolver_propietario"])){
		include("./mensajes/devolver_propietario.php");
	}
	
	//devolviendo la carpeta al propietario
	if(isset($_REQUEST['boton_devolver_propietario_x'])){
		include("./mensajes/devolviendo_propietario.php");
	}

	//adjudicarse carpetas del propietario
	if(isset($_REQUEST["adjudicarse_carpeta"])){
		include("./mensajes/adjudicarse_carpeta.php");
	}
	
	//devolviendo la carpeta al propietario
	if(isset($_REQUEST['boton_adjudicarse_carpeta_x'])){
		include("./mensajes/adjudicandose_carpeta.php");
	}

		
	//modificar solicitud de prestamo
	if(isset($_REQUEST["modificar_solicitud"])){
		include("./mensajes/modificar_solicitud.php");
	}
	
	//modificando solicitud de prestamo
	if(isset($_REQUEST['modificar_solicitud_boton_x'])){
		include("./mensajes/modificando_solicitud.php");
	}
	
	//eliminar solicitud de prestamo
	if(isset($_REQUEST["eliminar_solicitud"])){
		include("./mensajes/eliminar_solicitud.php");
	}
	
	//eliminando solicitud de prestamo
	if(isset($_REQUEST['eliminar_solicitud_boton_x'])){
		include("./mensajes/eliminando_solicitud.php");
	}
	
	//eliminar solicitudes rechazadas
	if(isset($_REQUEST["eliminar_rechazada"])){
		include("./mensajes/eliminar_rechazada.php");
	}
	
	//eliminando solicitudes rechazadas
	if(isset($_REQUEST["boton_eliminar_rechazada_x"])){
		include("./mensajes/eliminando_rechazada.php");
	}
	
	//imprimir el rotorno de la carpeta a archivo
	if(isset($_REQUEST['imprimir_envio_archivo'])){
		include("./mensajes/imprimir_envio_archivo.php");
	}
	
	//imprimir el rotorno de la carpeta a archivo
	if(isset($_REQUEST['ampliar_plazo'])){
		include("./mensajes/ampliar_plazo.php");
	}
	//imprimir el rotorno de la carpeta a archivo
	if(isset($_REQUEST['ampliar_plazo_x'])){
		include("./mensajes/ampliar_plazo.php");
	}
	/******************impresion************************************/
	/******************impresion************************************/
	if(isset($_REQUEST['imprimir_aceptados_firma'])){
		include("./mensajes/imprimir_aceptados_firma.php");
	}

	if(isset($_REQUEST['imprimir_prestadas_sc'])){
		include("./mensajes/imprimir_prestadas_sc.php");
	}
	
	if(isset($_REQUEST['imprimir_prestadas_confirmadas'])){
		include("./mensajes/imprimir_prestadas_confirmadas.php");
	}
	
	if(isset($_REQUEST['imprimir_retornadas_sc'])){
		include("./mensajes/imprimir_retornadas_sc.php");
	}
	
	if(isset($_REQUEST['imprimir_retornadas_confirmadas'])){
		include("./mensajes/imprimir_retornadas_confirmadas.php");
	}
	
	if(isset($_REQUEST['imprimir_devueltas_cliente'])){
		include("./mensajes/imprimir_devueltas_cliente.php");
	}
	
	if(isset($_REQUEST['imprimir_adjudicadas_banco'])){
		include("./mensajes/imprimir_adjudicadas_banco.php");
	}
	/******************fin de impresion************************************/
	/******************fin de impresion************************************/
	
	
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/
/// filtramos bandejas

if(isset($_REQUEST['filtro_lista'])){
	$filtro_lista = $_REQUEST['filtro_lista'];
	$_SESSION['filtro_lista'] = $filtro_lista;
}elseif(isset($_SESSION['filtro_lista'])){
	$filtro_lista = $_SESSION['filtro_lista'];
}else{
	$filtro_lista = "0";
}
$smarty->assign('filtro_lista',$filtro_lista);
$aviso = '0';

if($filtro_lista == '1'){
//recuperando los datos para la ventana
//lista de carpetaas solicitadas
$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_auto, m.id_us_autoriza, m.obs_1, u.nombres,
c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien 
FROM movimientos_carpetas m, usuarios u , carpetas c, oficinas o, propietarios p, tipos_bien t
WHERE m.flujo='0' AND m.id_us_corriente= '$id_us_actual' AND m.id_estado='1' AND m.id_us_autoriza=u.id_usuario
AND c.id_carpeta=m.id_carpeta AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
//echo "$sql";
$result= consulta($sql);
$i=0;
$sol_id_movimiento= array();
$sol_mis=array();
$sol_propietario=array();
$sol_carpeta=array();
$sol_obs=array();
$sol_fecha= array();
$sol_autoriza= array();

while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$sol_id_movimiento[$i]= $row["id_movimiento_carpeta"];
	/*
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
	$result_a= consulta($sql_a);
	$row= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	*/
	$sol_mis[$i]= $row["mis"];
	$sol_propietario[$i]= $row["nombres"];
	$sol_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"];
	
	$sol_obs[$i]= $row["obs_1"];
	$sol_fecha[$i]= $row["corr_auto"];
	$sol_autoriza[$i]= $row["nombres"];
	
	$i++;

}
if($i==0) $aviso='1';
//solicitadas
	$smarty->assign('sol_id_movimiento',$sol_id_movimiento);
	$smarty->assign('sol_mis',$sol_mis);
	$smarty->assign('sol_propietario',$sol_propietario);
	$smarty->assign('sol_carpeta',$sol_carpeta);
	$smarty->assign('sol_obs',$sol_obs);
	$smarty->assign('sol_fecha',$sol_fecha);
	$smarty->assign('sol_autoriza',$sol_autoriza);
}



if($filtro_lista == '2'){
//lista de carpetas con solicitud aceptada
$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.auto_arch, m.id_us_autoriza, m.obs_3, u.nombres ,
c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien 
FROM movimientos_carpetas m, usuarios u , carpetas c, oficinas o, propietarios p, tipos_bien t
WHERE m.flujo='0' AND m.id_us_corriente= '$id_us_actual' AND m.id_estado='3' AND m.id_us_autoriza=u.id_usuario
AND c.id_carpeta=m.id_carpeta AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
$result= consulta($sql);
$i=0;
$ace_id_movimiento= array();
$ace_mis=array();
$ace_propietario=array();
$ace_carpeta=array();
$ace_obs=array();
$ace_fecha= array();
$ace_autoriza= array();

while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$ace_id_movimiento[$i]= $row["id_movimiento_carpeta"];
	/*
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	*/
	$ace_mis[$i]= $row["mis"];
	$ace_propietario[$i]= $row["nombres"];
	$ace_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"];
	
	$ace_obs[$i]= $row["obs_3"];
	$ace_fecha[$i]= $row["auto_arch"];
	$ace_autoriza[$i]= $row["nombres"];
	
	$i++;

}
if($i==0) $aviso='1';
	//aceptadas
	$smarty->assign('ace_id_movimiento',$ace_id_movimiento);
	$smarty->assign('ace_mis',$ace_mis);
	$smarty->assign('ace_propietario',$ace_propietario);
	$smarty->assign('ace_carpeta',$ace_carpeta);
	$smarty->assign('ace_obs',$ace_obs);
	$smarty->assign('ace_fecha',$ace_fecha);
	$smarty->assign('ace_autoriza',$ace_autoriza);
}




if($filtro_lista == '3'){
//lista de carpetas con solicitud negada
$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.neg_auto_corr, m.id_us_autoriza, m.obs_2, u.nombres ,
c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien 
FROM movimientos_carpetas m, usuarios u , carpetas c, oficinas o, propietarios p, tipos_bien t
WHERE m.flujo='0' AND m.id_us_corriente= '$id_us_actual' AND m.id_estado='2' AND m.id_us_autoriza=u.id_usuario
AND c.id_carpeta=m.id_carpeta AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
$result= consulta($sql);
$i=0;
$neg_id_movimiento= array();
$neg_mis=array();
$neg_propietario=array();
$neg_carpeta=array();
$neg_obs=array();
$neg_fecha= array();
$neg_autoriza= array();

while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$neg_id_movimiento[$i]= $row["id_movimiento_carpeta"];
	/*
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	*/
	$neg_mis[$i]= $row["mis"];
	$neg_propietario[$i]= $row["nombres"];
	$neg_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"];
	
	$neg_obs[$i]= $row["obs_2"];
	$neg_fecha[$i]= $row["neg_auto_corr"];
	$neg_autoriza[$i]= $row["nombres"];
	
	$i++;
}
if($i==0) $aviso='1';
//negadas
	$smarty->assign('neg_id_movimiento',$neg_id_movimiento);
	$smarty->assign('neg_mis',$neg_mis);
	$smarty->assign('neg_propietario',$neg_propietario);
	$smarty->assign('neg_carpeta',$neg_carpeta);
	$smarty->assign('neg_obs',$neg_obs);
	$smarty->assign('neg_fecha',$neg_fecha);
	$smarty->assign('neg_autoriza',$neg_autoriza);
}


if($filtro_lista == '4'){
//lista de carpetas por confirmar
$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.arch_corr_prest, m.id_us_archivo, m.obs_4, u.nombres ,
c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien 
FROM movimientos_carpetas m, usuarios u , carpetas c, oficinas o, propietarios p, tipos_bien t
WHERE m.flujo='0' AND m.id_us_corriente= '$id_us_actual' AND m.id_estado='4' AND m.id_us_archivo=u.id_usuario
AND c.id_carpeta=m.id_carpeta AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
$result= consulta($sql);
$i=0;
$por_id_movimiento= array();
$por_mis=array();
$por_propietario=array();
$por_carpeta=array();
$por_obs=array();
$por_fecha= array();
$por_archivo= array();

while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$por_id_movimiento[$i]= $row["id_movimiento_carpeta"];
	/*
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	*/
	$por_mis[$i]= $row["mis"];
	$por_propietario[$i]= $row["nombres"];
	$por_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"];
	
	$por_obs[$i]= $row["obs_4"];
	$por_fecha[$i]= $row["arch_corr_prest"];
	$por_archivo[$i]= $row["nombres"];
	
	$i++;

}
if($i==0) $aviso='1';
//por confirmar
	$smarty->assign('por_id_movimiento',$por_id_movimiento);
	$smarty->assign('por_mis',$por_mis);
	$smarty->assign('por_propietario',$por_propietario);
	$smarty->assign('por_carpeta',$por_carpeta);
	$smarty->assign('por_obs',$por_obs);
	$smarty->assign('por_fecha',$por_fecha);
	$smarty->assign('por_archivo',$por_archivo);
}



if($filtro_lista == '5'){
//lista de carpetas confirmadas, en mi poder
$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.arch_corr_conf, m.id_us_archivo, m.obs_5, u.nombres ,
c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien 
FROM movimientos_carpetas m, usuarios u , carpetas c, oficinas o, propietarios p, tipos_bien t
WHERE m.flujo='0' AND m.id_us_corriente= '$id_us_actual' AND m.id_estado='5' AND m.id_us_archivo=u.id_usuario 
AND c.id_carpeta=m.id_carpeta AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien";
//echo "$sql";
$result= consulta($sql);
$i=0;
$mip_id_movimiento= array();
$mip_mis=array();
$mip_propietario=array();
$mip_tipo_carpeta=array();
$mip_carpeta=array();
$mip_obs=array();
$mip_fecha= array();
$mip_archivo= array();
$mip_devolver_cliente=array();
$mip_adjudicarse= array();

while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$mip_id_movimiento[$i]= $row["id_movimiento_carpeta"];
	/*
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.id_tipo_bien, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	*/
	$mip_mis[$i]= $row["mis"];
	$mip_propietario[$i]= $row["nombres"];
	$mip_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"];
	//$mip_tipo_carpeta[$i]= $row["id_tipo_bien"];
	
/*	$id_aux=$row_a["id_tipo_carpeta"];
	//para ver si el usuario puede devolver este tipo de carpeta o adjudicarsela
	$sql_a= "SELECT devolver FROM permisos WHERE id_tipo_carpeta='$id_aux' AND id_usuario='$id_us_actual' ";
	$result_a= consulta($sql_a);
	$resultado_d= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	$devolver_cliente_aux= $resultado_d["devolver"];

	$mip_devolver_cliente[$i]=$devolver_cliente_aux;
	
	//adjudicarse
	$sql_a= "SELECT devolver FROM permisos WHERE id_tipo_carpeta='7' AND id_usuario='$id_us_actual' ";
	$result_a= consulta($sql_a);
	$resultado_d= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	$devolver_cliente_aux= $resultado_d["devolver"];
	$mip_adjudicarse[$i]= $devolver_cliente_aux;
*/
	$mip_obs[$i]= $row["obs_5"];
	$mip_fecha[$i]= $row["arch_corr_conf"];
	$mip_archivo[$i]= $row["nombres"];
	
	$i++;

}
if($i==0) $aviso='1';
//confirmadas
	$smarty->assign('mip_id_movimiento',$mip_id_movimiento);
	$smarty->assign('mip_mis',$mip_mis);
	$smarty->assign('mip_propietario',$mip_propietario);
	$smarty->assign('mip_carpeta',$mip_carpeta);
	//$smarty->assign('mip_tipo_carpeta',$mip_tipo_carpeta);
	$smarty->assign('mip_obs',$mip_obs);
	$smarty->assign('mip_fecha',$mip_fecha);
	$smarty->assign('mip_archivo',$mip_archivo);
//	$smarty->assign('mip_devolver_cliente',$mip_devolver_cliente);
//	$smarty->assign('mip_adjudicarse',$mip_adjudicarse);
}



if($filtro_lista == '6'){
//lista de carpetas retornado sin confirmacion
$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_arch_ret, m.id_us_archivo, m.obs_6, u.nombres ,
c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien 
FROM movimientos_carpetas m, usuarios u , carpetas c, oficinas o, propietarios p, tipos_bien t
WHERE m.flujo='0' AND m.id_us_corriente= '$id_us_actual' AND m.id_estado='6' AND m.id_us_archivo=u.id_usuario 
AND c.id_carpeta=m.id_carpeta AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien";
$result= consulta($sql);
$i=0;
$ret_id_movimiento= array();
$ret_mis=array();
$ret_propietario=array();
$ret_carpeta=array();
$ret_obs=array();
$ret_fecha= array();
$ret_archivo= array();

while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$ret_id_movimiento[$i]= $row["id_movimiento_carpeta"];
	/*
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	*/
	$ret_mis[$i]= $row["mis"];
	$ret_propietario[$i]= $row["nombres"];
	$ret_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"];
	
	$ret_obs[$i]= $row["obs_6"];
	$ret_fecha[$i]= $row["corr_arch_ret"];
	$ret_archivo[$i]= $row["nombres"];
	
	$i++;

}
if($i==0) $aviso='1';
	//retorno sin confirmar
	$smarty->assign('ret_id_movimiento',$ret_id_movimiento);
	$smarty->assign('ret_mis',$ret_mis);
	$smarty->assign('ret_propietario',$ret_propietario);
	$smarty->assign('ret_carpeta',$ret_carpeta);
	$smarty->assign('ret_obs',$ret_obs);
	$smarty->assign('ret_fecha',$ret_fecha);
	$smarty->assign('ret_archivo',$ret_archivo);
}



if($filtro_lista == '7'){
//lista de carpetas devueltas al cliente
$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_dev, m.id_us_archivo, m.obs_8, u.nombres ,
c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien 
FROM movimientos_carpetas m, usuarios u , carpetas c, oficinas o, propietarios p, tipos_bien t
WHERE m.flujo='0' AND m.id_us_corriente= '$id_us_actual' AND m.id_estado='8' AND m.id_us_archivo=u.id_usuario 
AND c.id_carpeta=m.id_carpeta AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien";
$result= consulta($sql);
$i=0;
$dev_id_movimiento= array();
$dev_mis=array();
$dev_propietario=array();
$dev_carpeta=array();
$dev_obs=array();
$dev_fecha= array();
$dev_archivo= array();

while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$dev_id_movimiento[$i]= $row["id_movimiento_carpeta"];
	/*
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	*/
	$dev_mis[$i]= $row["mis"];
	$dev_propietario[$i]= $row["nombres"];
	$dev_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"];
	
	$dev_obs[$i]= $row["obs_8"];
	$dev_fecha[$i]= $row["corr_dev"];
	$dev_archivo[$i]= $row["nombres"];
	
	$i++;

}
if($i==0) $aviso='1';
	//lista de carpetas devueltas
	$smarty->assign('dev_id_movimiento',$dev_id_movimiento);
	$smarty->assign('dev_mis',$dev_mis);
	$smarty->assign('dev_propietario',$dev_propietario);
	$smarty->assign('dev_carpeta',$dev_carpeta);
	$smarty->assign('dev_obs',$dev_obs);
	$smarty->assign('dev_fecha',$dev_fecha);
	$smarty->assign('dev_archivo',$dev_archivo);
}


if($filtro_lista == '8'){
//lista de carpetas adjudicadas para el banco
$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_adj, m.id_us_archivo, m.obs_adj, u.nombres ,
c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien 
FROM movimientos_carpetas m, usuarios u, carpetas c, oficinas o, propietarios p, tipos_bien t
 WHERE m.flujo='0' AND m.id_us_corriente= '$id_us_actual' AND m.id_estado='9' AND m.id_us_archivo=u.id_usuario 
AND c.id_carpeta=m.id_carpeta AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien";
//echo "$sql";
$result= consulta($sql);
$i=0;
$adj_id_movimiento= array();
$adj_mis=array();
$adj_propietario=array();
$adj_carpeta=array();
$adj_obs=array();
$adj_fecha= array();
$adj_archivo= array();

while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$adj_id_movimiento[$i]= $row["id_movimiento_carpeta"];
	
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
	$result_a= consulta($sql_a);
	$row= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	
	$adj_mis[$i]= $row["mis"];
	$adj_propietario[$i]= $row["nombres"];
	$adj_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"];
	
	$adj_obs[$i]= $row["obs_adj"];
	$adj_fecha[$i]= $row["corr_adj"];
	$adj_archivo[$i]= $row["nombres"];
	
	$i++;

}
if($i==0) $aviso='1';
	//lista de carpetas adjudicadas para el banco
	$smarty->assign('adj_id_movimiento',$adj_id_movimiento);
	$smarty->assign('adj_mis',$adj_mis);
	$smarty->assign('adj_propietario',$adj_propietario);
	$smarty->assign('adj_carpeta',$adj_carpeta);
	$smarty->assign('adj_obs',$adj_obs);
	$smarty->assign('adj_fecha',$adj_fecha);
	$smarty->assign('adj_archivo',$adj_archivo);
}



	$smarty->assign('aviso',$aviso);
	$smarty->display('mensajeco.html');
	die();

?>
