<?php

require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/conexionMNU.php');
require_once('../lib/fechas.php');
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	//href
	$carpeta_entrar="_main.php?action=mensajeau.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "mensajes";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	//ventana para aceptar la solicitud nomobres
	if(isset($_REQUEST['aceptar_solicitud'])){
		include("./mensajes/aceptar_solicitud.php");
	}
	
	//ventana para rechazar la solicitud
	if(isset($_REQUEST['rechazar_solicitud'])){
		include("./mensajes/rechazar_solicitud.php");
	}
	
	//aceptando la solicitud
	if(isset($_REQUEST['boton_aceptar_solicitud_x'])){
		//echo "acc";
		//die();
		$valor= $_REQUEST['boton_aceptar_solicitud_x'];
		if($valor == "acc"){
			include("./mensajes/aceptando_solicitud.php");
		}
	}
	
	//rechazando la solicitud
	if(isset($_REQUEST['boton_rechazar_solicitud_x'])){
		$valor= $_REQUEST['boton_rechazar_solicitud_x'];
		if($valor == "acc"){
			include("./mensajes/rechazando_solicitud.php");
		}
	}
	
	//ventana para modificar la solicitud
	if(isset($_REQUEST['modificar_aceptada'])){
		$smarty->assign('readonly','');
		include("./mensajes/modificar_aceptada.php");
	}
	
	//ventana para modificar la solicitud
	if(isset($_REQUEST['modificar_rechazada'])){
		include("./mensajes/modificar_rechazada.php");
	}
	
	//eliminar el envio de la solicitud
	if(isset($_REQUEST['eliminar_envio'])){
		include("./mensajes/eliminar_envio.php");
	}
	
	//eliminar el envio de la solicitud
	if(isset($_REQUEST['eliminar_envio_rechazo'])){
		include("./mensajes/eliminar_envio_rechazo.php");
	}
	
	//eliminando el envio realizado, yendo un paso atras
	if(isset($_REQUEST['boton_eliminar_envio_x'])){
		$valor = $_REQUEST['boton_eliminar_envio_x'];
		if($valor == "acc"){
			include("./mensajes/eliminando_envio.php");
		}
	}
	
	
	//mandando o rechazando todos los seleccionandos
	if(isset($_REQUEST['mandar_todos_x'])){
		$valor = $_REQUEST['mandar_todos_x'];
		if($valor == "acc"){
			include("./mensajes/mandar_todos.php");
		}
	}
	
	//aceptando varias solicitudes al mismo tiempo
	if(isset($_REQUEST['boton_aceptar_todos_x'])){
		$valor = $_REQUEST['boton_aceptar_todos_x'];
		if($valor == "acc"){
			include("./mensajes/mandando_todos_aceptar.php");
		}
	}
	
	//rechazando varias solicitudes al mismo tiempo
	/*if(isset($_REQUEST['boton_rechazar_todos_x'])){
		include("./mensajes/mandando_todos_rechazar.php");
	}*/
	
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/


//los usuarios solicitantes de la misma regional
$id_almacen = $_SESSION["id_almacen"];
$sql="SELECT us.id_usuario, us.nombres 
FROM usuarios us 
INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina
WHERE ofi.id_almacen = '$id_almacen' AND us.activo='S'
ORDER BY us.nombres";
$result= consulta($sql);
$solicitantes=array();
while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$solicitantes[]= array('id'=>$row["id_usuario"],
							'nombre'=>$row["nombres"]);
}
$smarty->assign('solicitantes',$solicitantes);

/// filtramos bandejas
$f_usuario = "";
if(isset($_REQUEST['filtro_lista'])){
	$filtro_lista = $_REQUEST['filtro_lista'];
	$filtro_usuario = $_REQUEST['filtro_usuario'];
	$smarty->assign('filtro_lista',$filtro_lista);
	$smarty->assign('filtro_usuario',$filtro_usuario);
	if($filtro_usuario!='*'){
		$f_usuario = " AND m.id_us_corriente = '$filtro_usuario' ";
	}
}else{
	$filtro_lista = "0";
}

//recuperando los datos para la ventana
//lista de carpetas solicitadas

$id_us_actual = $_SESSION["idusuario"];

if($filtro_lista == '1'){
//modificado por Percy para poder todas las carpetas del almacen
$sql= "SELECT c.carpeta, m.id_movimiento_carpeta, m.id_carpeta, m.corr_auto, m.id_us_corriente, 
	m.obs_1, u.nombres, o.nombre, p.nombres AS cliente, p.mis, t.tipo_bien
FROM movimientos_carpetas m
	INNER JOIN carpetas c ON c.id_carpeta=m.id_carpeta
	LEFT JOIN usuarios u ON m.id_us_corriente=u.id_usuario
	LEFT JOIN oficinas o ON c.id_oficina=o.id_oficina
	LEFT JOIN propietarios p ON c.id_propietario=p.id_propietario
	LEFT JOIN tipos_bien t ON c.id_tipo_carpeta=t.id_tipo_bien
WHERE m.flujo='0' $f_usuario
	AND m.id_us_autoriza= '$id_us_actual' 
	AND m.id_estado='1' 
ORDER BY u.nombres, m.corr_auto";
$result= consulta($sql);
$i=0;
$sol_id_movimiento= array();
$sol_mis=array();
$sol_propietario=array();
$sol_carpeta=array();
$sol_obs=array();
$sol_fecha= array();
$sol_corriente= array();
$cantidad_poner= array();

$sol_ids_corriente= array();
$ids_corriente= array();
$primer_solicitante= array();
$bande=0;
$bande1=0;
$anterior=0;
$actual=0;
$contador=0;

//lista de carpetas en solicitud
while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$sol_id_movimiento[$i]= $row["id_movimiento_carpeta"];
	$sol_ids_corriente[$i]= $row["id_us_corriente"];
	
	if($bande1 == 0){
		$ids_corriente[$contador]= $row["id_us_corriente"];
		$anterior= $row["id_us_corriente"];
		$bande1++;
		$contador++;
	}
	else{
		$actual= $row["id_us_corriente"];
		if($anterior != $actual){
			$ids_corriente[$contador]= $row["id_us_corriente"];
			$contador++;
		}
		$anterior=$actual;
	}
	
	if($bande==0){
		$primer_solicitante=$row["id_us_corriente"];
		$bande++;
	}

	$sol_mis[$i]= $row["mis"];
	$sol_propietario[$i]= $row["cliente"];
	$sol_carpeta[$i]= "Tipo:&nbsp;".$row["tipo_bien"].
						"<br>Ofi.:&nbsp;".$row["nombre"].
						"<br>Obs.:&nbsp;".$row["carpeta"];
	
	$sol_obs[$i]= $row["obs_1"];
	$sol_fecha[$i]= fechaDMYh($row["corr_auto"]);
	$sol_corriente[$i]= $row["nombres"];
	
	$cantidad_poner[$i]= $i+1;
	
	$i++;

}
$cantidad_total= $i;
//solicitadas
	$smarty->assign('sol_id_movimiento',$sol_id_movimiento);
	$smarty->assign('sol_mis',$sol_mis);
	$smarty->assign('sol_propietario',$sol_propietario);
	$smarty->assign('sol_carpeta',$sol_carpeta);
	$smarty->assign('sol_obs',$sol_obs);
	$smarty->assign('sol_fecha',$sol_fecha);
	$smarty->assign('sol_corriente',$sol_corriente);
	$smarty->assign('cantidad_poner',$cantidad_poner);
	$smarty->assign('cantidad_total',$cantidad_total);
	$smarty->assign('ids_corriente',$ids_corriente);
	$smarty->assign('primer_solicitante',$primer_solicitante);
	$smarty->assign('sol_ids_corriente',$sol_ids_corriente);
	$lugar_grupo=0;
	$lugar_form=1;
	$para_id_us=0;
	$para_lista=1;
	$para_evento=0;
	$smarty->assign('lugar_grupo',$lugar_grupo);
	$smarty->assign('lugar_form',$lugar_form);
	$smarty->assign('para_id_us',$para_id_us);
	$smarty->assign('para_lista',$para_lista);
	$smarty->assign('para_evento',$para_evento);
}

if($filtro_lista == '2'){
//lista de carpetas con solicitud aceptada
$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.auto_arch, m.id_us_corriente, m.obs_3, u.nombres 
FROM movimientos_carpetas m, usuarios u 
WHERE m.flujo='0' $f_usuario
AND m.id_us_autoriza= '$id_us_actual' 
AND m.id_estado='3' 
AND m.id_us_corriente=u.id_usuario 
ORDER BY u.nombres, m.auto_arch ";
$result= consulta($sql);
$i=0;
$ace_id_movimiento= array();
$ace_mis=array();
$ace_propietario=array();
$ace_carpeta=array();
$ace_obs=array();
$ace_fecha= array();
$ace_corriente= array();

while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$ace_id_movimiento[$i]= $row["id_movimiento_carpeta"];
	
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	
	$ace_mis[$i]= $row_a["mis"];
	$ace_propietario[$i]= $row_a["nombres"];
	$ace_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row_a["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row_a["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row_a["carpeta"];
	
	$ace_obs[$i]= $row["obs_3"];
	$ace_fecha[$i]= fechaDMYh($row["auto_arch"]);
	$ace_corriente[$i]= $row["nombres"];
	
	$i++;

}
//aceptadas
	$smarty->assign('ace_id_movimiento',$ace_id_movimiento);
	$smarty->assign('ace_mis',$ace_mis);
	$smarty->assign('ace_propietario',$ace_propietario);
	$smarty->assign('ace_carpeta',$ace_carpeta);
	//print_r($ace_obs);
	$smarty->assign('ace_obs',$ace_obs);
	$smarty->assign('ace_fecha',$ace_fecha);
	$smarty->assign('ace_corriente',$ace_corriente);
}

if($filtro_lista == '3'){
//lista de carpetas con solicitud negada - rechazadas
$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.neg_auto_corr, m.id_us_corriente, m.obs_2, u.nombres 
FROM movimientos_carpetas m, usuarios u 
WHERE m.flujo='0' $f_usuario
AND m.id_us_autoriza= '$id_us_actual' 
AND m.id_estado='2' 
AND m.id_us_corriente=u.id_usuario 
ORDER BY u.nombres, m.neg_auto_corr ";
//echo $sql;
$result= consulta($sql);
$i=0;
$neg_id_movimiento= array();
$neg_mis=array();
$neg_propietario=array();
$neg_carpeta=array();
$neg_obs=array();
$neg_fecha= array();
$neg_corriente= array();

while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$neg_id_movimiento[$i]= $row["id_movimiento_carpeta"];
	
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	
	$neg_mis[$i]= $row_a["mis"];
	$neg_propietario[$i]= $row_a["nombres"];
	$neg_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row_a["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row_a["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row_a["carpeta"];
	
	$neg_obs[$i]= $row["obs_2"];
	$neg_fecha[$i]= fechaDMYh($row["neg_auto_corr"]);
	$neg_corriente[$i]= $row["nombres"];
	
	$i++;

}
//negadas
	$smarty->assign('neg_id_movimiento',$neg_id_movimiento);
	$smarty->assign('neg_mis',$neg_mis);
	$smarty->assign('neg_propietario',$neg_propietario);
	$smarty->assign('neg_carpeta',$neg_carpeta);
	$smarty->assign('neg_obs',$neg_obs);
	$smarty->assign('neg_fecha',$neg_fecha);
	$smarty->assign('neg_corriente',$neg_corriente);
}

	$smarty->assign('filtro_lista',$filtro_lista);
	//$smarty->assign('filtro_lista','1');
	$smarty->display('mensajeau.html');
	die();

?>
