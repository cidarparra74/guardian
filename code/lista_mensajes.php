<?php

//*****require('setup.php');
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');

//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
$id_almacen = $_SESSION["id_almacen"];
	//href  &carpeta_entrar=lista_mensajes
	$carpeta_entrar="../code/_main.php?action=lista_mensajes.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "lista_mensajes";
	$smarty->assign('carpeta_acc',$carpeta_acc);

	// usuarios del mismo recinto
	$sql= "SELECT us.id_usuario, us.nombres 
			FROM usuarios us, oficinas ofi 
			WHERE us.id_oficina = ofi.id_oficina AND ofi.id_almacen = $id_almacen AND us.activo='S'
			ORDER BY nombres ";
	
	//todos los usuarios
	//$sql= "SELECT id_usuario, nombres FROM usuarios  ORDER BY nombres ";
	$query = consulta($sql);
	$i=0;
	//$f_ids_usuario= array();
	$f_usuario= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		//$f_ids_usuario[$i]= $row["id_usuario"];
		$f_usuario[$i]= array('id'=>$row["id_usuario"], 'nombres'=>$row["nombres"]);
		$i++;
	}
	//$smarty->assign('f_ids_usuario',$f_ids_usuario);
	$smarty->assign('f_usuario',$f_usuario);
	
	//recuperando la lista de oficinas, para este usuario
	$sql= "SELECT id_oficina, nombre FROM oficinas WHERE id_almacen = ".$_SESSION["id_almacen"]." ORDER BY nombre ";
	$query = consulta($sql);
	$i=0;
	$f_oficina= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_oficina[$i]= array('id'=>$row["id_oficina"], 'nombre'=>$row["nombre"]);
		$i++;
	}
	$smarty->assign('f_oficina',$f_oficina);
	
	//filtro de la ventana
	//$f_filtro= $_REQUEST["filtro"];
	if(!isset($f_id_usuario)){
		$f_id_usuario= "ninguno";
		$f_id_oficina= "ninguno";
		$f_id_estado= "ninguno";
		$_SESSION["arch_id_usuario"]="ninguno";
		$_SESSION["arch_id_oficina"]="ninguno";
		$_SESSION["arch_id_estado"]="ninguno";
	}
	
	//si se presiona el boton de buscar
	if(isset($_REQUEST['buscar_boton'])){
		$f_id_usuario= $_REQUEST['filtro_usuario'];
		$f_id_oficina= $_REQUEST['filtro_oficina'];
		$f_id_estado= $_REQUEST['filtro_estado'];
		
		$_SESSION["arch_id_usuario"]= $f_id_usuario;
		$_SESSION["arch_id_oficina"]= $f_id_oficina;
		$_SESSION["arch_id_estado"]= $f_id_estado;
	}
	else{
		$f_id_usuario= $_SESSION["arch_id_usuario"];
		$f_id_oficina= $_SESSION["arch_id_oficina"];
		$f_id_estado= $_SESSION["arch_id_estado"];
	}
	
	//para los estados
	$f_estado= array();
	$f_estado[0]=array('estado'=>"Aceptados con Firma Autorizada", 					'id'=>'3');
	$f_estado[1]=array('estado'=>"Prestados a Solicitantes sin Confirmar", 			'id'=>'4');
	$f_estado[2]=array('estado'=>"Prestados a Solicitantes Confirmados", 			'id'=>'5');
	$f_estado[3]=array('estado'=>"Devueltos a Boveda por Solicitante sin Confirmar", 'id'=>'6');
	$f_estado[4]=array('estado'=>"Devueltos a Boveda Confirmados", 					'id'=>'7');
	$f_estado[5]=array('estado'=>"Devueltos al Cliente", 							'id'=>'8');
	$f_estado[6]=array('estado'=>"Adjudicadas para el Banco", 						'id'=>'9');
	$f_estado[7]=array('estado'=>"Recepcionadas para archivar", 					'id'=>'R');
	
	$smarty->assign('f_estado',$f_estado);
	
	$smarty->assign('f_id_usuario',$f_id_usuario);
	$smarty->assign('f_id_oficina',$f_id_oficina);
	$smarty->assign('f_id_estado',$f_id_estado);
	
	//armando la consulta
	if($f_id_usuario == "ninguno"){
		if($f_id_oficina == "ninguno"){
			$armar_consulta="";
		}
		else{
			$armar_consulta= "AND o.id_oficina='$f_id_oficina' ";
		}
	}
	else{
		$armar_consulta= "AND m.id_us_corriente='$f_id_usuario' ";
		if($f_id_oficina != "ninguno"){
			$armar_consulta=$armar_consulta."AND o.id_oficina='$f_id_oficina' ";
		}
	}
		
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	

	/**************************************impresion***********************/
	/**************************************impresion***********************/
	if(isset($_REQUEST['imprimir_aceptados_firma'])){
		include("autoriza/lista_mensajes/imprimir_aceptados_firma.php");
	}
	/*if(isset($_REQUEST['imprimir_aceptados_firma_boton'])){
		include("autoriza/lista_mensajes/imprimir_aceptados_firma_imp.php");
	}*/
	
	if(isset($_REQUEST['imprimir_prestadas_sc'])){
		include("autoriza/lista_mensajes/imprimir_prestadas_sc.php");
	}
	
	if(isset($_REQUEST['imprimir_prestadas_confirmadas'])){
		include("autoriza/lista_mensajes/imprimir_prestadas_confirmadas.php");
	}
	
	if(isset($_REQUEST['imprimir_retornadas_sc'])){
		include("autoriza/lista_mensajes/imprimir_retornadas_sc.php");
	}
	
	if(isset($_REQUEST['imprimir_retornadas_confirmadas'])){
		include("autoriza/lista_mensajes/imprimir_retornadas_confirmadas.php");
	}
	
	if(isset($_REQUEST['imprimir_devueltas_cliente'])){
		include("autoriza/lista_mensajes/imprimir_devueltas_cliente.php");
	}
	
	if(isset($_REQUEST['imprimir_adjudicadas_banco'])){
		include("autoriza/lista_mensajes/imprimir_adjudicadas_banco.php");
	}
	
	/***************************fin de las impresion***********************/
	/***************************fin de las impresion***********************/
		
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana

$id_us_actual = $_SESSION['idusuario'];
/* ---------------------------------------------------------------------------------------------- */
//lista de carpetas aceptadas con firma autorizada

$sol_list= array();
if($f_id_estado == 3){

$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, CONVERT(DATETIME,m.auto_arch,103) as fecha, m.auto_arch_plazo, 
m.id_us_corriente, m.id_us_autoriza, m.obs_1, m.obs_3, u.nombres,
o.nombre, p.nombres as propietario, p.mis, t.tipo_bien, c.carpeta
FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c, propietarios p, tipos_bien t 
WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta 
AND m.flujo='0' AND o.id_almacen = $id_almacen AND m.id_estado='3' 
AND m.id_us_corriente=u.id_usuario AND c.id_propietario=p.id_propietario 
AND c.id_tipo_carpeta=t.id_tipo_bien
ORDER BY u.nombres, m.auto_arch ";
	$query = consulta($sql);

	//lista de carpetas en solicitud con firma autorizada.....
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$aux= $row["id_us_autoriza"];
		$sql_a= "SELECT * FROM usuarios WHERE id_usuario='$aux' ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		$sol_autoriza = $row_a["nombres"];
		
		$sol_list[]= array('id' => $row["id_movimiento_carpeta"],
							'id_carpeta' => $row["id_carpeta"],
							'id_carpeta' => $row["id_carpeta"],
							'mis' => $row["mis"],
							'propietario' => $row["propietario"],
							'tipo' => $row["tipo_bien"],
							'nombre' => $row["nombre"],
							'carpeta' => $row["carpeta"],
							'obs_1' => $row["obs_1"],
							'obs_3' => $row["obs_3"],
							'fecha' => $row["fecha"],
							'fecha_plazo' => $row["auto_arch_plazo"],
							'nombres' => $row["nombres"],
							'sol_autoriza' => $row["sol_autoriza"]);
	}

}








//lista de carpetas prestadas sin confirmar
/*
if($f_id_estado == "ninguno"){
	$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.auto_arch, 
	m.auto_arch_plazo, m.arch_corr_prest, m.arch_corr_plazo, m.id_us_corriente, 
	m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, u.nombres
	FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c 
	WHERE c.id_oficina=o.id_oficina 
	AND c.id_carpeta=m.id_carpeta 
	AND m.flujo='0' 
	AND o.id_almacen = $id_almacen 
	AND m.id_estado='4' 
	AND m.id_us_corriente=u.id_usuario $armar_consulta 
	ORDER BY u.nombres, m.arch_corr_prest ";
}
else{
*/
$sin_list= array();
if($f_id_estado == 4){
		$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.auto_arch, 
		m.auto_arch_plazo, m.arch_corr_prest, m.arch_corr_plazo, m.id_us_corriente, 
		m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, u.nombres
		FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c 
		WHERE c.id_oficina=o.id_oficina 
		AND c.id_carpeta=m.id_carpeta 
		AND m.flujo='0' 
		AND o.id_almacen = $id_almacen 
		AND m.id_estado='4' 
		AND m.id_us_corriente=u.id_usuario $armar_consulta 
		ORDER BY u.nombres, m.arch_corr_prest ";	
		
	$query = consulta($sql);
	$i=0;
	
	$sin_mis=array();
	$sin_propietario=array();
	$sin_carpeta=array();
	$sin_obs_1=array();
	$sin_obs_3=array();
	$sin_obs_4=array();
	$sin_fecha= array();
	$sin_fecha_plazo= array();

	$sin_fecha_arch_corr_sc= array();
	$sin_fecha_arch_corr_sc_plazo= array();

	$sin_corriente= array();
	$sin_autoriza= array();
	//
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$sin_list[$i]= $row["id_movimiento_carpeta"];
		
		$aux= $row["id_carpeta"];
		$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$sin_mis[$i]= $row_a["mis"];
		$sin_propietario[$i]= $row_a["nombres"];
		$sin_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row_a["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row_a["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row_a["carpeta"];
		
		$sin_obs_1[$i]= $row["obs_1"];
		$sin_obs_3[$i]= $row["obs_3"];
		$sin_obs_4[$i]= $row["obs_4"];
		
		$sin_fecha[$i]= $row["auto_arch"];
		$sin_fecha_plazo[$i]= $row["auto_arch_plazo"];
		$sin_corriente[$i]= $row["nombres"];
		
		$sin_fecha_arch_corr_sc[$i]= $row["arch_corr_prest"];
		$sin_fecha_arch_corr_sc_plazo[$i]= $row["arch_corr_plazo"];
		
		$aux= $row["id_us_autoriza"];
		$sql_a= "SELECT * FROM usuarios WHERE id_usuario='$aux' ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$sin_autoriza[$i]= $row_a["nombres"];
		
		$i++;

	}
	$smarty->assign('sin_mis',$sin_mis);
	$smarty->assign('sin_propietario',$sin_propietario);
	$smarty->assign('sin_carpeta',$sin_carpeta);
	$smarty->assign('sin_obs_1',$sin_obs_1);
	$smarty->assign('sin_obs_3',$sin_obs_3);
	$smarty->assign('sin_obs_4',$sin_obs_4);
	$smarty->assign('sin_fecha',$sin_fecha);
	$smarty->assign('sin_fecha_plazo',$sin_fecha_plazo);
	$smarty->assign('sin_fecha_arch_corr_sc',$sin_fecha_arch_corr_sc);
	$smarty->assign('sin_fecha_arch_corr_sc_plazo',$sin_fecha_arch_corr_sc_plazo);
	$smarty->assign('sin_corriente',$sin_corriente);
	$smarty->assign('sin_autoriza',$sin_autoriza);
}










//lista de carpetas prestadas confirmadas
/*
if($f_id_estado == "ninguno"){
	$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.auto_arch, m.auto_arch_plazo, m.arch_corr_prest, m.arch_corr_plazo, m.arch_corr_conf, m.id_us_corriente, m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, m.obs_5, u.nombres FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' AND o.id_almacen = $id_almacen AND m.id_estado='5' AND m.id_us_corriente=u.id_usuario $armar_consulta ORDER BY u.nombres, m.arch_corr_conf ";
}
else{
*/
//echo $f_id_estado;
$con_list= array();
if($f_id_estado == 5){
		$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.auto_arch, m.auto_arch_plazo, m.arch_corr_prest, 
		m.arch_corr_plazo, m.arch_corr_conf, m.id_us_corriente, m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, m.obs_5, u.nombres 
		FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c 
		WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' AND o.id_almacen = $id_almacen 
		AND m.id_estado='5' AND m.id_us_corriente=u.id_usuario $armar_consulta 
		ORDER BY u.nombres, m.arch_corr_conf ";	
	$query = consulta($sql);
	$i=0;
	
	$con_mis=array();
	$con_propietario=array();
	$con_carpeta=array();
	$con_obs_1=array();
	$con_obs_3=array();
	$con_obs_4=array();
	$con_obs_5= array();
	$con_fecha= array();
	$con_fecha_plazo= array();

	$con_fecha_arch_corr_sc= array();
	$con_fecha_arch_corr_sc_plazo= array();

	$con_fecha_arch_corr_conf= array();

	$con_corriente= array();
	$con_autoriza= array();
	//lista de carpetas confirmadas por corriente para archivo
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$con_list[$i]= $row["id_movimiento_carpeta"];
		
		$aux= $row["id_carpeta"];
		$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$con_mis[$i]= $row_a["mis"];
		$con_propietario[$i]= $row_a["nombres"];
		$con_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row_a["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row_a["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row_a["carpeta"];
		
		$con_obs_1[$i]= $row["obs_1"];
		$con_obs_3[$i]= $row["obs_3"];
		$con_obs_4[$i]= $row["obs_4"];
		$con_obs_5[$i]= $row["obs_5"];
		
		$con_fecha[$i]= $row["auto_arch"];
		$con_fecha_plazo[$i]= $row["auto_arch_plazo"];
		$con_corriente[$i]= $row["nombres"];
		
		$con_fecha_arch_corr_sc[$i]= $row["arch_corr_prest"];
		$con_fecha_arch_corr_sc_plazo[$i]= $row["arch_corr_plazo"];
		
		$con_fecha_arch_corr_conf[$i]= $row["arch_corr_conf"];
		
		$aux= $row["id_us_autoriza"];
		$sql_a= "SELECT * FROM usuarios WHERE id_usuario='$aux' ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$con_autoriza[$i]= $row_a["nombres"];
		
		$i++;

	}
	$smarty->assign('con_mis',$con_mis);
	$smarty->assign('con_propietario',$con_propietario);
	$smarty->assign('con_carpeta',$con_carpeta);
	$smarty->assign('con_obs_1',$con_obs_1);
	$smarty->assign('con_obs_3',$con_obs_3);
	$smarty->assign('con_obs_4',$con_obs_4);
	$smarty->assign('con_obs_5',$con_obs_5);
	$smarty->assign('con_fecha',$con_fecha);
	$smarty->assign('con_fecha_plazo',$con_fecha_plazo);
	$smarty->assign('con_fecha_arch_corr_sc',$con_fecha_arch_corr_sc);
	$smarty->assign('con_fecha_arch_corr_sc_plazo',$con_fecha_arch_corr_sc_plazo);
	$smarty->assign('con_fecha_arch_corr_conf',$con_fecha_arch_corr_conf);
	$smarty->assign('con_corriente',$con_corriente);
	$smarty->assign('con_autoriza',$con_autoriza);
}








//lista de carpetas en retorno desde corriente
/*
if($f_id_estado == "ninguno"){
	$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_auto, m.auto_arch, m.auto_arch_plazo, m.arch_corr_prest, m.arch_corr_plazo, m.arch_corr_conf, m.corr_arch_ret, m.id_us_corriente, m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, m.obs_5, m.obs_6, u.nombres FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' AND o.id_almacen = $id_almacen AND m.id_estado='6' AND m.id_us_corriente=u.id_usuario $armar_consulta ORDER BY u.nombres, m.corr_arch_ret ";
}
else{
*/
$ret_list= array();
if($f_id_estado == 6){
	$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_auto, m.auto_arch, m.auto_arch_plazo, m.arch_corr_prest, 
		m.arch_corr_plazo, m.arch_corr_conf, m.corr_arch_ret, m.id_us_corriente, m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, 
		m.obs_5, m.obs_6, u.nombres 
		FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c 
		WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' AND o.id_almacen = $id_almacen 
		AND m.id_estado='6' AND m.id_us_corriente=u.id_usuario $armar_consulta 
		ORDER BY u.nombres, m.corr_arch_ret ";
	$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_auto, m.auto_arch, m.auto_arch_plazo, m.arch_corr_prest, 
		m.arch_corr_plazo, m.arch_corr_conf, m.corr_arch_ret, m.id_us_corriente, m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, 
		m.obs_5, m.obs_6, u.nombres 
		FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c 
		WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' AND o.id_almacen = $id_almacen 
		AND m.id_estado='6' AND m.id_us_corriente=u.id_usuario $armar_consulta 
		ORDER BY u.nombres, m.corr_arch_ret ";

	$query = consulta($sql);
	$i=0;
	
	$ret_mis=array();
	$ret_propietario=array();
	$ret_carpeta=array();
	$ret_obs_1=array();
	$ret_obs_3=array();
	$ret_obs_4=array();
	$ret_obs_5= array();
	$ret_obs_6= array();

	$ret_fecha_corr_auto= array();
	$ret_fecha_auto_arch= array();
	$ret_fecha_auto_arch_plazo= array();
	$ret_fecha_arch_corr_prest= array();
	$ret_fecha_arch_corr_plazo= array();
	$ret_fecha_corr_arch_ret= array();

	$ret_corriente= array();
	$ret_autoriza= array();
	////lista de carpetas en retorno desde corriente
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ret_list[$i]= $row["id_movimiento_carpeta"];
		
		$aux= $row["id_carpeta"];
		$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$ret_mis[$i]= $row_a["mis"];
		$ret_propietario[$i]= $row_a["nombres"];
		$ret_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row_a["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row_a["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row_a["carpeta"];
		
		$ret_obs_1[$i]= $row["obs_1"];
		$ret_obs_3[$i]= $row["obs_3"];
		$ret_obs_4[$i]= $row["obs_4"];
		$ret_obs_5[$i]= $row["obs_5"];
		$ret_obs_6[$i]= $row["obs_6"];
		
		$ret_corriente[$i]= $row["nombres"];
		
		$ret_fecha_corr_auto[$i]= $row["corr_auto"];
		$ret_fecha_auto_arch[$i]= $row["auto_arch"];
		$ret_fecha_auto_arch_plazo[$i]= $row["auto_arch_plazo"];
		$ret_fecha_arch_corr_prest[$i]= $row["arch_corr_prest"];
		$ret_fecha_arch_corr_plazo[$i]= $row["arch_corr_plazo"];
		$ret_fecha_corr_arch_ret[$i]= $row["corr_arch_ret"];
		
		$aux= $row["id_us_autoriza"];
		$sql_a= "SELECT * FROM usuarios WHERE id_usuario='$aux' ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$ret_autoriza[$i]= $row_a["nombres"];
		
		$i++;

	}
	$smarty->assign('ret_mis',$ret_mis);
	$smarty->assign('ret_propietario',$ret_propietario);
	$smarty->assign('ret_carpeta',$ret_carpeta);
	$smarty->assign('ret_obs_1',$ret_obs_1);
	$smarty->assign('ret_obs_3',$ret_obs_3);
	$smarty->assign('ret_obs_4',$ret_obs_4);
	$smarty->assign('ret_obs_5',$ret_obs_5);
	$smarty->assign('ret_obs_6',$ret_obs_6);
	$smarty->assign('ret_fecha_corr_auto',$ret_fecha_corr_auto);
	$smarty->assign('ret_fecha_auto_arch',$ret_fecha_auto_arch);
	$smarty->assign('ret_fecha_auto_arch_plazo',$ret_fecha_auto_arch_plazo);
	$smarty->assign('ret_fecha_arch_corr_prest',$ret_fecha_arch_corr_prest);
	$smarty->assign('ret_fecha_arch_corr_plazo',$ret_fecha_arch_corr_plazo);
	$smarty->assign('ret_fecha_corr_arch_ret',$ret_fecha_corr_arch_ret);
	$smarty->assign('ret_corriente',$ret_corriente);
	$smarty->assign('ret_autoriza',$ret_autoriza);
}







//lista de carpetas guardadas en archivo
/*
if($f_id_estado == "ninguno"){
	$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_auto, m.auto_arch, m.auto_arch_plazo, m.arch_corr_prest, m.arch_corr_plazo, m.arch_corr_conf, m.corr_arch_ret, m.corr_arch_ret_conf, m.id_us_corriente, m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, m.obs_5, m.obs_6, m.obs_7, u.nombres FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' AND o.id_almacen = $id_almacen AND m.id_estado='7' AND m.id_us_corriente=u.id_usuario $armar_consulta ORDER BY u.nombres, m.corr_arch_ret_conf ";
}
else{
*/
$arch_list= array();
if($f_id_estado == 7){
		$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_auto, m.auto_arch, m.auto_arch_plazo, 
		m.arch_corr_prest, m.arch_corr_plazo, m.arch_corr_conf, m.corr_arch_ret, m.corr_arch_ret_conf, 
		m.id_us_corriente, m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, m.obs_5, m.obs_6, m.obs_7, u.nombres 
		FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c 
		WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' AND o.id_almacen = $id_almacen 
		AND m.id_estado='7' AND m.id_us_corriente=u.id_usuario $armar_consulta 
		ORDER BY u.nombres, m.corr_arch_ret_conf ";

	$query = consulta($sql);
	$i=0;
	
	$arch_mis=array();
	$arch_propietario=array();
	$arch_carpeta=array();
	$arch_obs_1=array();
	$arch_obs_3=array();
	$arch_obs_4=array();
	$arch_obs_5= array();
	$arch_obs_6= array();
	$arch_obs_7= array();

	$arch_fecha_corr_auto= array();
	$arch_fecha_auto_arch= array();
	$arch_fecha_auto_arch_plazo= array();
	$arch_fecha_arch_corr_prest= array();
	$arch_fecha_arch_corr_plazo= array();
	$arch_fecha_corr_arch_ret= array();
	$arch_fecha_corr_arch_ret_conf= array();

	$arch_corriente= array();
	$arch_autoriza= array();
	////lista de carpetas en retorno desde corriente
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$arch_list[$i]= $row["id_movimiento_carpeta"];
		
		$aux= $row["id_carpeta"];
		$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$arch_mis[$i]= $row_a["mis"];
		$arch_propietario[$i]= $row_a["nombres"];
		$arch_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row_a["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row_a["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row_a["carpeta"];
		
		$arch_obs_1[$i]= $row["obs_1"];
		$arch_obs_3[$i]= $row["obs_3"];
		$arch_obs_4[$i]= $row["obs_4"];
		$arch_obs_5[$i]= $row["obs_5"];
		$arch_obs_6[$i]= $row["obs_6"];
		$arch_obs_7[$i]= $row["obs_7"];
		
		$arch_corriente[$i]=$row["nombres"];
		
		$arch_fecha_corr_auto[$i]= $row["corr_auto"];
		$arch_fecha_auto_arch[$i]= $row["auto_arch"];
		$arch_fecha_auto_arch_plazo[$i]= $row["auto_arch_plazo"];
		$arch_fecha_arch_corr_prest[$i]= $row["arch_corr_prest"];
		$arch_fecha_arch_corr_plazo[$i]= $row["arch_corr_plazo"];
		$arch_fecha_corr_arch_ret[$i]= $row["corr_arch_ret"];
		$arch_fecha_corr_arch_ret_conf[$i]= $row["corr_arch_ret_conf"];
		
		$aux= $row["id_us_autoriza"];
		$sql_a= "SELECT * FROM usuarios WHERE id_usuario='$aux' ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$arch_autoriza[$i]= $row_a["nombres"];
		
		$i++;

	}
	$smarty->assign('arch_mis',$arch_mis);
	$smarty->assign('arch_propietario',$arch_propietario);
	$smarty->assign('arch_carpeta',$arch_carpeta);
	$smarty->assign('arch_obs_1',$arch_obs_1);
	$smarty->assign('arch_obs_3',$arch_obs_3);
	$smarty->assign('arch_obs_4',$arch_obs_4);
	$smarty->assign('arch_obs_5',$arch_obs_5);
	$smarty->assign('arch_obs_6',$arch_obs_6);
	$smarty->assign('arch_obs_7',$arch_obs_7);
	$smarty->assign('arch_fecha_corr_auto',$arch_fecha_corr_auto);
	$smarty->assign('arch_fecha_auto_arch',$arch_fecha_auto_arch);
	$smarty->assign('arch_fecha_auto_arch_plazo',$arch_fecha_auto_arch_plazo);
	$smarty->assign('arch_fecha_arch_corr_prest',$arch_fecha_arch_corr_prest);
	$smarty->assign('arch_fecha_arch_corr_plazo',$arch_fecha_arch_corr_plazo);
	$smarty->assign('arch_fecha_corr_arch_ret',$arch_fecha_corr_arch_ret);
	$smarty->assign('arch_fecha_corr_arch_ret_conf',$arch_fecha_corr_arch_ret_conf);
	$smarty->assign('arch_corriente',$arch_corriente);
	$smarty->assign('arch_autoriza',$arch_autoriza);
}






//lista de carpetas devueltas al cliente
/*
if($f_id_estado == "ninguno"){
	$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_auto, m.auto_arch, m.auto_arch_plazo, m.arch_corr_prest, m.arch_corr_plazo, m.arch_corr_conf, m.corr_arch_ret, m.corr_dev, m.id_us_corriente, m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, m.obs_5, m.obs_6, m.obs_8, u.nombres FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' AND o.id_almacen = $id_almacen AND m.id_estado='8' AND m.id_us_corriente=u.id_usuario $armar_consulta ORDER BY u.nombres, m.corr_dev ";
}
else{
*/
$cli_list= array();
if($f_id_estado == 8){
		$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_auto, m.auto_arch, m.auto_arch_plazo, 
		m.arch_corr_prest, m.arch_corr_plazo, m.arch_corr_conf, m.corr_arch_ret, m.corr_dev, m.id_us_corriente, 
		m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, m.obs_5, m.obs_6, m.obs_8, u.nombres 
		FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c 
		WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' AND o.id_almacen = $id_almacen 
		AND m.id_estado='8' AND m.id_us_corriente=u.id_usuario $armar_consulta 
		ORDER BY u.nombres, m.corr_dev ";	

	$query = consulta($sql);
	$i=0;
	
	$cli_mis=array();
	$cli_propietario=array();
	$cli_carpeta=array();
	$cli_obs_1=array();
	$cli_obs_3=array();
	$cli_obs_4=array();
	$cli_obs_5= array();
	$cli_obs_6= array();
	$cli_obs_8= array();

	$cli_fecha_corr_auto= array();
	$cli_fecha_auto_arch= array();
	$cli_fecha_auto_arch_plazo= array();
	$cli_fecha_arch_corr_prest= array();
	$cli_fecha_arch_corr_plazo= array();
	$cli_fecha_corr_arch_ret= array();
	$cli_fecha_corr_dev= array();

	$cli_corriente= array();
	$cli_autoriza= array();
	////lista de carpetas en retorno desde corriente
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$cli_list[$i]= $row["id_movimiento_carpeta"];
		//echo "acc: "; echo $cli_list[$i];
		
		$aux= $row["id_carpeta"];
		$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$cli_mis[$i]= $row_a["mis"];
		$cli_propietario[$i]= $row_a["nombres"];
		$cli_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row_a["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row_a["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row_a["carpeta"];
		
		$cli_obs_1[$i]= $row["obs_1"];
		$cli_obs_3[$i]= $row["obs_3"];
		$cli_obs_4[$i]= $row["obs_4"];
		$cli_obs_5[$i]= $row["obs_5"];
		$cli_obs_6[$i]= $row["obs_6"];
		$cli_obs_8[$i]= $row["obs_8"];
		
		$cli_corriente[$i]= $row["nombres"];
		
		$cli_fecha_corr_auto[$i]= $row["corr_auto"];
		$cli_fecha_auto_arch[$i]= $row["auto_arch"];
		$cli_fecha_auto_arch_plazo[$i]= $row["auto_arch_plazo"];
		$cli_fecha_arch_corr_prest[$i]= $row["arch_corr_prest"];
		$cli_fecha_arch_corr_plazo[$i]= $row["arch_corr_plazo"];
		$cli_fecha_corr_arch_ret[$i]= $row["corr_arch_ret"];
		$cli_fecha_corr_dev[$i]= $row["corr_dev"];
		
		$aux= $row["id_us_autoriza"];
		$sql_a= "SELECT * FROM usuarios WHERE id_usuario='$aux' ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$cli_autoriza[$i]= $row_a["nombres"];
		
		$i++;

	}
	$smarty->assign('cli_mis',$cli_mis);
	$smarty->assign('cli_propietario',$cli_propietario);
	$smarty->assign('cli_carpeta',$cli_carpeta);
	$smarty->assign('cli_obs_1',$cli_obs_1);
	$smarty->assign('cli_obs_3',$cli_obs_3);
	$smarty->assign('cli_obs_4',$cli_obs_4);
	$smarty->assign('cli_obs_5',$cli_obs_5);
	$smarty->assign('cli_obs_6',$cli_obs_6);
	$smarty->assign('cli_obs_8',$cli_obs_8);
	$smarty->assign('cli_fecha_corr_auto',$cli_fecha_corr_auto);
	$smarty->assign('cli_fecha_auto_arch',$cli_fecha_auto_arch);
	$smarty->assign('cli_fecha_auto_arch_plazo',$cli_fecha_auto_arch_plazo);
	$smarty->assign('cli_fecha_arch_corr_prest',$cli_fecha_arch_corr_prest);
	$smarty->assign('cli_fecha_arch_corr_plazo',$cli_fecha_arch_corr_plazo);
	$smarty->assign('cli_fecha_corr_arch_ret',$cli_fecha_corr_arch_ret);
	$smarty->assign('cli_fecha_corr_dev',$cli_fecha_corr_dev);
	$smarty->assign('cli_corriente',$cli_corriente);
	$smarty->assign('cli_autoriza',$cli_autoriza);
}




//lista de carpetas adjudicadas para el banco
/*
if($f_id_estado == "ninguno"){
	$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_auto, m.auto_arch, m.auto_arch_plazo, m.arch_corr_prest, m.arch_corr_plazo, m.arch_corr_conf, m.corr_arch_ret, m.corr_dev, m.id_us_corriente, m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, m.obs_5, m.obs_6, m.obs_8, u.nombres FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' AND o.id_almacen = $id_almacen AND m.id_estado='9' AND m.id_us_corriente=u.id_usuario $armar_consulta ORDER BY u.nombres, m.corr_dev ";
}else{
*/
$adj_list= array();
if($f_id_estado == 9){
		$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.corr_auto, m.auto_arch, m.auto_arch_plazo, 
		m.arch_corr_prest, m.arch_corr_plazo, m.arch_corr_conf, m.corr_arch_ret, m.corr_dev, m.corr_adj, 
		m.id_us_corriente, m.id_us_autoriza, m.obs_1, m.obs_3, m.obs_4, m.obs_5, m.obs_6, m.obs_adj, u.nombres 
		FROM movimientos_carpetas m, usuarios u, oficinas o, carpetas c 
		WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' AND o.id_almacen = $id_almacen 
		AND m.id_estado='9' AND m.id_us_corriente=u.id_usuario $armar_consulta 
		ORDER BY u.nombres, m.corr_adj ";	

	$query = consulta($sql);
	$i=0;
	
	$adj_mis=array();
	$adj_propietario=array();
	$adj_carpeta=array();
	$adj_obs_1=array();
	$adj_obs_3=array();
	$adj_obs_4=array();
	$adj_obs_5= array();
	$adj_obs_6= array();
	$adj_obs_adj= array();

	$adj_fecha_corr_auto= array();
	$adj_fecha_auto_arch= array();
	$adj_fecha_auto_arch_plazo= array();
	$adj_fecha_arch_corr_prest= array();
	$adj_fecha_arch_corr_plazo= array();
	$adj_fecha_corr_arch_ret= array();
	$adj_fecha_corr_adj= array();

	$adj_corriente= array();
	$adj_autoriza= array();

	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$adj_list[$i]= $row["id_movimiento_carpeta"];
		//echo "acc: "; echo $cli_list[$i];
		
		$aux= $row["id_carpeta"];
		$sql_a= "SELECT c.carpeta, o.nombre, p.nombres, p.mis, t.tipo_bien FROM carpetas c, oficinas o, propietarios p, tipos_bien t WHERE c.id_carpeta='$aux' AND c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=t.id_tipo_bien ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$adj_mis[$i]= $row_a["mis"];
		$adj_propietario[$i]= $row_a["nombres"];
		$adj_carpeta[$i]= "&nbsp;Tipo&nbsp;:&nbsp;".$row_a["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row_a["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row_a["carpeta"];
		
		$adj_obs_1[$i]= $row["obs_1"];
		$adj_obs_3[$i]= $row["obs_3"];
		$adj_obs_4[$i]= $row["obs_4"];
		$adj_obs_5[$i]= $row["obs_5"];
		$adj_obs_6[$i]= $row["obs_6"];
		$adj_obs_8[$i]= $row["obs_8"];
		
		$adj_corriente[$i]= $row["nombres"];
		
		$adj_fecha_corr_auto[$i]= $row["corr_auto"];
		$adj_fecha_auto_arch[$i]= $row["auto_arch"];
		$adj_fecha_auto_arch_plazo[$i]= $row["auto_arch_plazo"];
		$adj_fecha_arch_corr_prest[$i]= $row["arch_corr_prest"];
		$adj_fecha_arch_corr_plazo[$i]= $row["arch_corr_plazo"];
		$adj_fecha_corr_arch_ret[$i]= $row["corr_arch_ret"];
		$adj_fecha_corr_dev[$i]= $row["corr_adj"];
		
		$aux= $row["id_us_autoriza"];
		$sql_a= "SELECT * FROM usuarios WHERE id_usuario='$aux' ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		
		$adj_autoriza[$i]= $row_a["nombres"];
		
		$i++;

	}
	$smarty->assign('adj_mis',$adj_mis);
	$smarty->assign('adj_propietario',$adj_propietario);
	$smarty->assign('adj_carpeta',$adj_carpeta);
	$smarty->assign('adj_obs_1',$adj_obs_1);
	$smarty->assign('adj_obs_3',$adj_obs_3);
	$smarty->assign('adj_obs_4',$adj_obs_4);
	$smarty->assign('adj_obs_5',$adj_obs_5);
	$smarty->assign('adj_obs_6',$adj_obs_6);
	$smarty->assign('adj_obs_adj',$adj_obs_adj);
	$smarty->assign('adj_fecha_corr_auto',$adj_fecha_corr_auto);
	$smarty->assign('adj_fecha_auto_arch',$adj_fecha_auto_arch);
	$smarty->assign('adj_fecha_auto_arch_plazo',$adj_fecha_auto_arch_plazo);
	$smarty->assign('adj_fecha_arch_corr_prest',$adj_fecha_arch_corr_prest);
	$smarty->assign('adj_fecha_arch_corr_plazo',$adj_fecha_arch_corr_plazo);
	$smarty->assign('adj_fecha_corr_arch_ret',$adj_fecha_corr_arch_ret);
	$smarty->assign('adj_fecha_corr_adj',$adj_fecha_corr_adj);
	$smarty->assign('adj_corriente',$adj_corriente);
	$smarty->assign('adj_autoriza',$adj_autoriza);
}


	//solicitud de prestamo mandado por autoriza de archivo a corriente
	$smarty->assign('sol_list',$sol_list);
	
	
	//prestamos sin confirmar de archivo a comun
	$smarty->assign('sin_list',$sin_list);

	
	//prestamos confirmados
	$smarty->assign('con_list',$con_list);
	
	
	//retorno de carpetas sin confirmar (confirmacion de retorno)
	$smarty->assign('ret_list',$ret_list);
	
	
	//lista de carpetas retornadas a archivo
	$smarty->assign('arch_list',$arch_list);
	
	
	//lista de carpetas devueltas al cliente
	$smarty->assign('cli_list',$cli_list);
	
	
	//lista de carpetas adjudicadas para el banco
	$smarty->assign('adj_list',$adj_list);



//lista de carpetas para archivo
if($f_id_estado == 'R'){
	$sql = "SELECT il.id_informe_legal, il.cliente, tb.tipo_bien, tb.con_inf_legal, us.nombres , il.fecha_recepcion ".
	"FROM informes_legales il INNER JOIN tipos_bien tb ".
	"ON  tb.id_tipo_bien = il.id_tipo_bien ".
	"INNER JOIN usuarios us ON us.id_usuario = il.id_us_comun ".
	"WHERE il.id_us_comun='$id_us_actual' AND il.estado='cat' ORDER BY il.fecha_recepcion DESC ";	

	$query = consulta($sql);
	$rec_lista=array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$aux= $row["fecha_recepcion"];
		$aux_1= explode(" ",$aux);
		
		$aux=dateDMESY(dateDMY($aux_1[0]));
		$rec_lista[] = array('id_inf' => $row["id_informe_legal"],
							'clien' => $row["cliente"],
							'tbien' => $row["tipo_bien"],
							'con_il' => $row["con_inf_legal"],
							'nombu' => $row["nombres"],
							'fecha' => $aux);
		$i++;
	}
	$smarty->assign('rec_lista',$rec_lista);
}	
	$smarty->display('lista_mensajes.html');
	die();

?>
