<?php
/*

	???? este usar para aceptar, ver lista_mensajes.php para imprimir ???????
http://localhost/GuardianPro/code/_main.php?action=buscamen.php&aceptar_sol_auto_arch=acc&id=
*/
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');

//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="_main.php?action=buscamen.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "mensajes";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
$id_almacen = $_SESSION["id_almacen"];
	$sql= "SELECT us.id_usuario, us.nombres FROM usuarios us, oficinas ofi 
	WHERE us.id_oficina = ofi.id_oficina AND ofi.id_almacen = $id_almacen AND us.activo='S'
	ORDER BY nombres ";
	//recuperando la lista de usuarios corrientes  WHERE id_perfil='2'
	//$sql= "SELECT id_usuario, nombres FROM usuarios  ORDER BY nombres ";
	$query = consulta($sql);
	$i=0;
	$f_ids_usuario= array();
	//$f_usuario= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_ids_usuario[$i]= array('id_usuario' =>$row["id_usuario"],
								'nombres' =>$row["nombres"]);
		
		$i++;
	}
	
	//recuperando la lista de oficinas
	$id_almacen = $_SESSION['id_almacen'];
	//oficinas
	$sql= "SELECT id_oficina, nombre FROM oficinas WHERE id_almacen = $id_almacen	ORDER BY nombre ";
	//$sql= "SELECT id_oficina, nombre FROM oficinas ORDER BY nombre ";
	$query = consulta($sql);
	$i=0;
	$f_ids_oficina= array();
	//$f_oficina= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_ids_oficina[$i]= array('id_oficina'=>$row["id_oficina"],
								'nombre'=>$row["nombre"]);
		
		$i++;
	}
	
	//filtro de la ventana
	if(!isset($_SESSION["arch_id_usuario"])){
		$f_id_usuario= "ninguno";
		$f_id_oficina= "ninguno";
		$f_id_estado= "ninguno";
	}else{
		$f_id_usuario= $_SESSION["arch_id_usuario"];
		$f_id_oficina= $_SESSION["arch_id_oficina"];
		$f_id_estado= $_SESSION["arch_id_estado"];
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
	
	//para los estados
	$f_estado= array();
	$f_estado[0]=array('estado'=>"Aceptados con Firma Autorizada", 					'id'=>'3');
	$f_estado[1]=array('estado'=>"Prestados a Solicitantes sin Confirmar", 			'id'=>'4');
	$f_estado[2]=array('estado'=>"Prestados a Solicitantes Confirmados", 			'id'=>'5');
	$f_estado[3]=array('estado'=>"Devueltos a Boveda por Solicitante sin Confirmar", 'id'=>'6');
	$f_estado[4]=array('estado'=>"Devueltos a Boveda Confirmados", 					'id'=>'7');
	$f_estado[5]=array('estado'=>"Devueltos al Cliente", 							'id'=>'8');
	$f_estado[6]=array('estado'=>"Adjudicadas para el Banco", 						'id'=>'9');
	//$f_estado[7]=array('estado'=>"Recepcionadas para archivar", 					'id'=>'R');
	
	$smarty->assign('f_estado',$f_estado);

	$smarty->assign('f_ids_usuario',$f_ids_usuario);
	//$smarty->assign('f_usuario',$f_usuario);
	
	$smarty->assign('f_ids_oficina',$f_ids_oficina);
	//$smarty->assign('f_oficina',$f_oficina);

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
	//echo "consulta: $armar_consulta <br>";
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

$printLst=0;
	/**************************************impresion***********************/
	/**************************************impresion***********************/
	
	if(isset($_REQUEST['imprimir_seccion'])){
		//segun el nro pasado al final se llamara al html correspondiente
		$printLst=$_REQUEST['imprimir_seccion'];
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
	
	/***************************fin de las impresion***********************/
		
	//ventana para aceptar la solicitud con firma autorizada
	if(isset($_REQUEST['aceptar_sol_auto_arch'])){
		include("./mensajes/aceptar_sol_auto_arch.php");
	}
	
	//aceptando la solicitud con firma autorizada
	if(isset($_REQUEST['boton_aceptar_sol_auto_arch_x'])){
		include("./mensajes/aceptando_sol_auto_arch.php");
	}
	
	//modificar solicitudo aceptada de autoriza a archivo
	if(isset($_REQUEST['modificar_sol_auto_arch'])){
		include("./mensajes/modificar_sol_auto_arch.php");
	}
	//echo $_REQUEST['modificar_sol_auto_arch_x'];
	//modificando solicitud aceptada de autoriza a archivo
	if(isset($_REQUEST['modificar_sol_auto_arch_x'])){
	//echo 'ok';
		include("./mensajes/modificando_sol_auto_arch.php");
	}
	
	//confirmar el retorno de la carpeta
	if(isset($_REQUEST['confirmar_retorno'])){
		include("./mensajes/confirmar_retorno.php");
	}
	
	//confirmando el retorno de la carpeta
	if(isset($_REQUEST['boton_confirmar_retorno_x'])){
		include("./mensajes/confirmando_retorno.php");
	}
	
	//quitar de la lista las carpetas devueltas a archivo
	if(isset($_REQUEST['eliminar_en_archivo'])){
		include("./mensajes/eliminar_en_archivo.php");
	}
	
	//quitando de la lista las carpetas devueltas a archivo
	if(isset($_REQUEST['boton_eliminar_en_archivo_x'])){
		include("./mensajes/eliminando_en_archivo.php");
	}
	
	//quitar de la lista las carpetas devueltas al cliente
	if(isset($_REQUEST['eliminar_devuelta_cliente'])){
		include("./mensajes/eliminar_devuelta_cliente.php");
	}
	//quitando de la lista las carpetas devueltas al cliente
	if(isset($_REQUEST['boton_eliminar_devuelta_cliente_x'])){
		include("./mensajes/eliminando_devuelta_cliente.php");
	}
	
	//quitar de la lista las carpetas adjudicadas para el banco
	if(isset($_REQUEST['eliminar_adjudicada'])){
		include("./mensajes/eliminar_adjudicada.php");
	}
	//quitando de la lista las carpetas devueltas al cliente
	if(isset($_REQUEST['boton_eliminar_adjudicada_x'])){
		include("./mensajes/eliminando_adjudicada.php");
	}
	
	/********** modificacion de valores en caso de envio directo desde archivo ********/

	//modificar el envio de la carpeta
	if(isset($_REQUEST['modificar_envio'])){
		include("./mensajes/modificar_envio.php");
	}
	
	//modificando el envio de la carpeta
	if(isset($_REQUEST['modificar_envio_boton_x'])){
		include("./mensajes/modificando_envio.php");
	}
	
	//eliminar el envio de la carpeta
	if(isset($_REQUEST['eliminar_envio'])){
		include("./mensajes/eliminar_envio.php");
	}
	
	//eliminando el envio de la carpeta
	if(isset($_REQUEST['eliminar_envio_boton_x'])){
		include("./mensajes/eliminando_envio.php");
	}
		
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana



//lista de carpetas aceptadas con firma autorizada
if($f_id_estado == 3){


$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, 
	m.obs_1, u.nombres, c.carpeta, m.id_us_autoriza, 
	o.nombre, p.nombres as cliente, p.mis, t.tipo_bien,
	left(CONVERT(VARCHAR(10), m.auto_arch, 103)  +' '+ 
	CONVERT(VARCHAR(10), m.auto_arch, 108),16) AS fecha 
FROM movimientos_carpetas m
inner join carpetas c on c.id_carpeta=m.id_carpeta
left join usuarios u on u.id_usuario=m.id_us_corriente 
left join oficinas o on o.id_oficina=c.id_oficina 
left join propietarios p on c.id_propietario=p.id_propietario
left join tipos_bien t on c.id_tipo_carpeta=t.id_tipo_bien
WHERE m.flujo='0' 
AND o.id_almacen = $id_almacen 
AND m.id_estado='3' $armar_consulta
ORDER BY u.nombres, m.auto_arch ";
	
	$query = consulta($sql);
	$i=0;
	$sol_id_movimiento= array();
	//lista de carpetas en solicitud con firma autorizada.....
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$aux= $row["id_us_autoriza"];
		$sql_a= "SELECT nombres FROM usuarios WHERE id_usuario='$aux' ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);

		$sol_id_movimiento[$i]= array('id_mov' => $row["id_movimiento_carpeta"],
										'sol_mis' => $row["mis"],
										'sol_propietario' => $row["cliente"],
										'sol_carpeta' => "&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"],
										'sol_obs_1'=> $row["obs_1"],
										'sol_fecha'=> $row["fecha"],
										'sol_corriente'=> $row["nombres"],
										'sol_autoriza'=> $row_a["nombres"]);
		$i++;

	}
	
	//solicitud de prestamo mandado por autoriza de archivo a corriente
	$smarty->assign('sol_id_movimiento',$sol_id_movimiento);
}




if($f_id_estado == 4){
//prestados a solicitante sin confirmar

$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta, m.obs_4, 
	m.obs_1, u.nombres as solicita, c.carpeta, m.id_us_autoriza, u2.nombres as autoriza, 
	o.nombre, p.nombres as cliente, p.mis, t.tipo_bien,
	substring(CONVERT(VARCHAR(10), m.arch_corr_prest, 103)  +' '+ CONVERT(VARCHAR(10), m.arch_corr_prest, 108),1,16) 
	AS fecha, 
	substring(CONVERT(VARCHAR(10), m.auto_arch, 103)  +' '+ CONVERT(VARCHAR(10), m.arch_corr_prest, 108),1,16) 
	AS fecha1
FROM movimientos_carpetas m
inner join carpetas c on c.id_carpeta=m.id_carpeta
left join usuarios u on u.id_usuario=m.id_us_corriente 
left join usuarios u2 on u2.id_usuario=m.id_us_autoriza
left join oficinas o on o.id_oficina=c.id_oficina 
left join propietarios p on c.id_propietario=p.id_propietario
left join tipos_bien t on c.id_tipo_carpeta=t.id_tipo_bien
WHERE m.flujo='0' 
AND o.id_almacen = $id_almacen 
AND m.id_estado='4' $armar_consulta
ORDER BY u.nombres, m.arch_corr_prest ";
		
$query = consulta($sql);

$sin_id_movimiento= array();
//
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

	$sin_id_movimiento[]= array('sin_id_movimiento' => $row["id_movimiento_carpeta"],
									'sin_mis' => $row["mis"],
									'sin_propietario' => $row["cliente"],
									'sin_carpeta' => "&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"],
									'sin_obs_1'=> $row["obs_1"],
									'sin_obs_4'=> $row["obs_4"],
									'sin_fecha'=> $row["fecha"],
									'sin_corriente'=> $row["solicita"],
									'sin_autoriza'=> $row["autoriza"],
									'sin_fecha_arch_corr_sc'=> $row["fecha1"]);


}
//prestamos sin confirmar de archivo a comun

	$smarty->assign('sin_id_movimiento',$sin_id_movimiento);

}






if($f_id_estado == 5){
//lista de carpetas confirmadas por corriente para archivo
$sql= "SELECT m.id_movimiento_carpeta, m.id_carpeta,  
substring(CONVERT(VARCHAR(10), m.arch_corr_conf, 103)  +' '+ CONVERT(VARCHAR(10), m.arch_corr_conf, 108),1,16) 
	AS fecha1,
substring(CONVERT(VARCHAR(10), m.arch_corr_plazo, 103)  +' '+ CONVERT(VARCHAR(10), m.arch_corr_plazo, 108),1,16) 
	AS plazo, t.tipo_bien, o.nombre, c.carpeta ,
	m.id_us_corriente, m.id_us_autoriza,  
	m.obs_5, u.nombres as solicita, u2.nombres as autoriza, p.nombres as cliente, p.mis
	FROM movimientos_carpetas m
	inner join carpetas c on c.id_carpeta=m.id_carpeta
	left join usuarios u on u.id_usuario=m.id_us_corriente 
	left join usuarios u2 on u2.id_usuario=m.id_us_autoriza
	left join oficinas o on o.id_oficina=c.id_oficina 
	left join propietarios p on c.id_propietario=p.id_propietario
	left join tipos_bien t on c.id_tipo_carpeta=t.id_tipo_bien
	WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' 
	AND o.id_almacen = $id_almacen AND m.id_estado='5' AND m.id_us_corriente=u.id_usuario $armar_consulta 
	ORDER BY u.nombres, m.arch_corr_conf ";	

$query = consulta($sql);

$con_id_movimiento= array();

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	
	$con_id_movimiento[]= array('con_id_movimiento' => $row["id_movimiento_carpeta"],
									'con_mis' => $row["mis"],
									'con_propietario' => $row["cliente"],
									'con_carpeta' => "&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"],
									'con_obs_5'=> $row["obs_5"],
									'plazo'=> $row["plazo"],
									'con_corriente'=> $row["solicita"],
									'con_autoriza'=> $row["autoriza"],
									'fecha1'=> $row["fecha1"]);

}
	//prestamos confirmados
	$smarty->assign('con_id_movimiento',$con_id_movimiento);

}





////lista de carpetas en retorno desde corriente
if($f_id_estado == 6){
	
	$sql= "SELECT m.id_movimiento_carpeta,   
substring(CONVERT(VARCHAR(10), m.corr_arch_ret, 103)  +' '+ CONVERT(VARCHAR(10), m.corr_arch_ret, 108),1,16) 
	AS fecha1, t.tipo_bien, o.nombre, c.carpeta ,
	m.obs_1, m.obs_6,   
	u.nombres as solicita, u2.nombres as autoriza, p.nombres as cliente, p.mis
	FROM movimientos_carpetas m
	inner join carpetas c on c.id_carpeta=m.id_carpeta
	left join usuarios u on u.id_usuario=m.id_us_corriente 
	left join usuarios u2 on u2.id_usuario=m.id_us_autoriza
	left join oficinas o on o.id_oficina=c.id_oficina 
	left join propietarios p on c.id_propietario=p.id_propietario
	left join tipos_bien t on c.id_tipo_carpeta=t.id_tipo_bien
	WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' 
	AND o.id_almacen = $id_almacen AND m.id_estado='6' AND m.id_us_corriente=u.id_usuario $armar_consulta 
	ORDER BY u.nombres, m.corr_arch_ret ";
$query = consulta($sql);
$ret_id_movimiento= array();

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	
	$ret_id_movimiento[]= array('id'=>$row["id_movimiento_carpeta"],
								'ret_corriente'=>$row["solicita"],
								'fecha1'=>$row["fecha1"],
								'ret_mis'=>$row["mis"],
								'ret_propietario'=>$row["cliente"],
								'ret_carpeta'=>"&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"],
								'ret_obs_1'=>$row["obs_1"],
								'ret_obs_6'=>$row["obs_6"],
								'ret_autoriza'=>$row["autoriza"]);

}
	//retorno de carpetas sin confirmar (confirmacion de retorno)
	$smarty->assign('ret_id_movimiento',$ret_id_movimiento);

}






//lista de carpetas guardadas en archivo

if($f_id_estado == 7){

	$sql= "SELECT m.id_movimiento_carpeta,   
substring(CONVERT(VARCHAR(10), m.corr_arch_ret_conf, 103)  +' '+ CONVERT(VARCHAR(10), m.corr_arch_ret_conf, 108),1,16) 
	AS fecha1, t.tipo_bien, o.nombre, c.carpeta ,
	m.obs_1, m.obs_7,   
	u.nombres as solicita, u2.nombres as autoriza, p.nombres as cliente, p.mis
	FROM movimientos_carpetas m
	inner join carpetas c on c.id_carpeta=m.id_carpeta
	left join usuarios u on u.id_usuario=m.id_us_corriente 
	left join usuarios u2 on u2.id_usuario=m.id_us_autoriza
	left join oficinas o on o.id_oficina=c.id_oficina 
	left join propietarios p on c.id_propietario=p.id_propietario
	left join tipos_bien t on c.id_tipo_carpeta=t.id_tipo_bien
	WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' 
	AND o.id_almacen = $id_almacen AND m.id_estado='7' AND m.id_us_corriente=u.id_usuario $armar_consulta 
	ORDER BY u.nombres, m.corr_arch_ret_conf ";

$query = consulta($sql);

$arch_id_movimiento= array();

////lista de carpetas en retorno desde corriente
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	
	$arch_id_movimiento[]= array('id'=>$row["id_movimiento_carpeta"],
								'arch_corriente'=>$row["solicita"],
								'fecha1'=>$row["fecha1"],
								'arch_mis'=>$row["mis"],
								'arch_propietario'=>$row["cliente"],
								'arch_carpeta'=>"&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"],
								'arch_obs_1'=>$row["obs_1"],
								'arch_obs_7'=>$row["obs_7"],
								'arch_autoriza'=>$row["autoriza"]);

}

	//lista de carpetas retornadas a archivo
	$smarty->assign('arch_id_movimiento',$arch_id_movimiento);

}




//lista de carpetas devueltas al cliente

if($f_id_estado == 8){

$sql= "SELECT m.id_movimiento_carpeta,   
substring(CONVERT(VARCHAR(10), m.corr_dev, 103)  +' '+ CONVERT(VARCHAR(10), m.corr_dev, 108),1,16) 
	AS fecha1, t.tipo_bien, o.nombre, c.carpeta ,
	m.obs_1, m.obs_8,   
	u.nombres as solicita, u2.nombres as autoriza, p.nombres as cliente, p.mis
	FROM movimientos_carpetas m
	inner join carpetas c on c.id_carpeta=m.id_carpeta
	left join usuarios u on u.id_usuario=m.id_us_corriente 
	left join usuarios u2 on u2.id_usuario=m.id_us_autoriza
	left join oficinas o on o.id_oficina=c.id_oficina 
	left join propietarios p on c.id_propietario=p.id_propietario
	left join tipos_bien t on c.id_tipo_carpeta=t.id_tipo_bien
	WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' 
	AND o.id_almacen = $id_almacen AND m.id_estado='8' AND m.id_us_corriente=u.id_usuario $armar_consulta 
	ORDER BY u.nombres, m.corr_dev ";
	

$query = consulta($sql);

$cli_id_movimiento= array();

////lista de carpetas en retorno desde corriente
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	
	$cli_id_movimiento[]= array('id'=>$row["id_movimiento_carpeta"],
								'cli_corriente'=>$row["solicita"],
								'fecha1'=>$row["fecha1"],
								'cli_mis'=>$row["mis"],
								'cli_propietario'=>$row["cliente"],
								'cli_carpeta'=>"&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"],
								'cli_obs_1'=>$row["obs_1"],
								'cli_obs_8'=>$row["obs_8"],
								'cli_autoriza'=>$row["autoriza"]);

}
	//lista de carpetas devueltas al cliente
	$smarty->assign('cli_id_movimiento',$cli_id_movimiento);

}





//lista de carpetas adjudicadas para el banco

if($f_id_estado == 9){

$sql= "SELECT m.id_movimiento_carpeta,   
substring(CONVERT(VARCHAR(10), m.corr_adj, 103)  +' '+ CONVERT(VARCHAR(10), m.corr_adj, 108),1,16) 
	AS fecha1, t.tipo_bien, o.nombre, c.carpeta ,
	m.obs_1, m.obs_adj,   
	u.nombres as solicita, u2.nombres as autoriza, p.nombres as cliente, p.mis
	FROM movimientos_carpetas m
	inner join carpetas c on c.id_carpeta=m.id_carpeta
	left join usuarios u on u.id_usuario=m.id_us_corriente 
	left join usuarios u2 on u2.id_usuario=m.id_us_autoriza
	left join oficinas o on o.id_oficina=c.id_oficina 
	left join propietarios p on c.id_propietario=p.id_propietario
	left join tipos_bien t on c.id_tipo_carpeta=t.id_tipo_bien
	WHERE c.id_oficina=o.id_oficina AND c.id_carpeta=m.id_carpeta AND m.flujo='0' 
	AND o.id_almacen = $id_almacen AND m.id_estado='9' AND m.id_us_corriente=u.id_usuario $armar_consulta 
	ORDER BY u.nombres, m.corr_adj ";		

$query = consulta($sql);

$adj_id_movimiento= array();

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	
$adj_id_movimiento[]= array('id'=>$row["id_movimiento_carpeta"],
								'adj_corriente'=>$row["solicita"],
								'fecha1'=>$row["fecha1"],
								'adj_mis'=>$row["mis"],
								'adj_propietario'=>$row["cliente"],
								'adj_carpeta'=>"&nbsp;Tipo&nbsp;:&nbsp;".$row["tipo_bien"]."<br>&nbsp;ofi.&nbsp;&nbsp;&nbsp;:&nbsp;".$row["nombre"]."<br>&nbsp;Obs.&nbsp;:&nbsp;".$row["carpeta"],
								'adj_obs_1'=>$row["obs_1"],
								'adj_obs_9'=>$row["obs_adj"],
								'adj_autoriza'=>$row["autoriza"]);
}

	//lista de carpetas adjudicadas para el banco
	$smarty->assign('adj_id_movimiento',$adj_id_movimiento);
	
}

if($printLst==0)
	$smarty->display('buscamen.html');
else
	$smarty->display('mensajes/impresion_est_'.$printLst.'.html');
	die();

?>
