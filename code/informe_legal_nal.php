<?php
//opciones 
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//para ver quien puede publicar
	if(isset($_REQUEST['pub'])){
		$con_publicar = $_REQUEST['pub'];
		$_SESSION['con_publicar'] = $con_publicar;
	}else{
		if(isset($_SESSION['con_publicar'])){
			$con_publicar = $_SESSION['con_publicar'];
		}else{
			$con_publicar = 'N';
		}
	}
	$carpeta_entrar="../code/_main.php?action=informe_legal_nal.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "informe_legal";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	

	
	//---- ver si esta habilitado la adicion de i.l. sin recepcion
	$sql="SELECT TOP 1 enable_ilsin, id_perfil_abo FROM opciones ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$us_asesor = $row['id_perfil_abo'];
	if($row['enable_ilsin']=='S')
		$smarty->assign('enable_ilsin',1);
	else
		$smarty->assign('enable_ilsin',0);
	
	//recuperando la lista de usuarios corrientes ///SOLO ASESORES LEGALES
	$sql= "SELECT id_usuario, us.nombres, ofi.nombre FROM usuarios us
	INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
	WHERE us.id_perfil = '$us_asesor' AND us.activo='S' 
	ORDER BY us.nombres";
	$query = consulta($sql);
	$f_usuario= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_usuario[]= array('id' => $row["id_usuario"],
						'usuario' =>$row["nombres"].' / '.$row["nombre"]);
	}
	$smarty->assign('f_usuario',$f_usuario);
	
	//recuperando los tipos de bien
	$sql= "SELECT id_tipo_bien, tipo_bien FROM tipos_bien WHERE con_inf_legal ='S'  ORDER BY tipo_bien ";
	$query = consulta($sql);
	$tiposbien=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tiposbien[]= array('id' => $row["id_tipo_bien"],
							'descri' => $row["tipo_bien"]);
	}
	$smarty->assign('tiposbien',$tiposbien);
	
	//recuperando los tipos de bien
	$sql= "SELECT id_almacen, nombre FROM almacen ORDER BY nombre ";
	$query = consulta($sql);
	$i=0;
	$almacenes=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$almacenes[$i]= array('id_almacen' => $row["id_almacen"],
							'nombre' => $row["nombre"]);
		$i++;
	}
	
	$smarty->assign('almacenes',$almacenes);
	/*
		unset($_SESSION["filtro_fecha"]);
		unset($_SESSION["filtro_fech2"]);
		unset($_SESSION["inf_id_usuario"]);
		unset($_SESSION["inf_id_tipo_bien"]);
		unset($_SESSION["inf_cliente"]);
		unset($_SESSION["inf_ci_cliente"]);
		unset($_SESSION["inf_estado"]);
		unset($_SESSION["inf_almacen"]);
	*/
		//filtro de la ventana 
		$f_filtro ="";
	if(!isset($_SESSION["filtro_fecha"]) || $_SESSION["inf_id_usuario"] == "ninguno"){
		$f_filtro ="*";
		$aux1 = date("d/m/Y");
		$_SESSION["filtro_fecha"]= $aux1;
		$_SESSION["filtro_fech2"]= $aux1;
		$_SESSION["inf_id_usuario"]="*";
		$_SESSION["inf_id_tipo_bien"]="*";
		$_SESSION["inf_cliente"]="";
		$_SESSION["inf_ci_cliente"]="";
		$_SESSION["inf_estado"]='0';
		$_SESSION["inf_almacen"] = $_SESSION["id_almacen"];
	}
	
	//si se presiona el boton de buscar
	if(isset($_REQUEST['buscar_boton'])){
	
		$filtro_usuario= $_REQUEST['filtro_usuario'];
		$filtro_tipo_bien= $_REQUEST['filtro_tipo_bien'];
		$filtro_cliente= $_REQUEST['filtro_cliente'];
		$filtro_ci_cliente= $_REQUEST['filtro_ci_cliente'];
		$filtro_estado= $_REQUEST['filtro_estado'];
		$filtro_fecha= $_REQUEST['filtro_fecha'];
		$filtro_fech2= $_REQUEST['filtro_fech2'];
		$filtro_almacen= $_REQUEST["filtro_almacen"];
	}else{
		$filtro_usuario=$_SESSION["inf_id_usuario"];
		$filtro_tipo_bien=$_SESSION["inf_id_tipo_bien"];
		$filtro_cliente=$_SESSION["inf_cliente"];
		$filtro_ci_cliente=$_SESSION["inf_ci_cliente"];
		$filtro_estado=$_SESSION["inf_estado"];
		$filtro_fecha= $_SESSION["filtro_fecha"];
		$filtro_fech2= $_SESSION["filtro_fech2"];
		$filtro_almacen= $_SESSION["inf_almacen"];
	}

	$_SESSION["inf_id_usuario"]=$filtro_usuario;
	$_SESSION["inf_id_tipo_bien"]=$filtro_tipo_bien;
	$_SESSION["inf_cliente"]=$filtro_cliente;
	$_SESSION["inf_ci_cliente"]=$filtro_ci_cliente;
	$_SESSION["inf_estado"]=$filtro_estado;
	$_SESSION["filtro_fecha"]= $filtro_fecha;
	$_SESSION["filtro_fech2"]= $filtro_fech2;
	$_SESSION["inf_almacen"]= $filtro_almacen;
		
	$smarty->assign('filtro_usuario',$filtro_usuario);
	$smarty->assign('filtro_tipo_bien',$filtro_tipo_bien); //1->vehiculos, 2->inmuebles
	$smarty->assign('filtro_cliente',$filtro_cliente);
	$smarty->assign('filtro_ci_cliente',$filtro_ci_cliente);
	$smarty->assign('filtro_estado',$filtro_estado);
	$smarty->assign('filtro_fecha',$filtro_fecha);
	$smarty->assign('filtro_fech2',$filtro_fech2);
	$smarty->assign('filtro_almacen',$filtro_almacen);
	
	//armando la consulta
	$armar_consulta="";
	if($filtro_usuario != "*"){
		$armar_consulta.= "AND ile.usr_acep='$filtro_usuario' ";
		//vemos de que recinto es
		$sql="SELECT ofi.id_almacen 
		FROM usuarios us INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina
		WHERE us.id_usuario = '$filtro_usuario'";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$filtro_almacen = $row["id_almacen"];
	}
	
	if($filtro_tipo_bien != "*"){
		$armar_consulta.= "AND ile.id_tipo_bien='$filtro_tipo_bien' ";
	}
	if($filtro_cliente != ""){
		$armar_consulta.= "AND ile.cliente LIKE '%$filtro_cliente%' ";
	}
	if($filtro_ci_cliente != ""){
		$armar_consulta.= "AND ile.ci_cliente LIKE '%$filtro_ci_cliente%' ";
	}
	if($filtro_almacen != "*"){
		$armar_consulta.= " AND ofi.id_almacen = '$filtro_almacen' ";
	}
	//fecha
	if($filtro_fecha != '' && $filtro_fech2 == ''){
		$armar_consulta .= "AND CONVERT(DATETIME, CONVERT(VARCHAR(10), ile.fecha, 103), 103) = '$filtro_fecha' ";
	}
	if($filtro_fecha != '' && $filtro_fech2 != ''){
		$armar_consulta .= "AND CONVERT(DATETIME, CONVERT(VARCHAR(10), ile.fecha, 103), 103) >= CONVERT(DATETIME, '$filtro_fecha', 103) AND CONVERT(DATETIME, CONVERT(VARCHAR(10), ile.fecha, 103), 103) <= CONVERT(DATETIME, '$filtro_fech2', 103) ";
	}
	
	
	//---------------
	if(isset($_REQUEST['modificar'])){
		$id=$_REQUEST['id'];
	//	$id_us_actual = $_SESSION["idusuario"];
		//recuperando la lista de usuarios corrientes ///SOLO DE LA MISMA CIUDAD de LA RECE
		$sql= "SELECT us.id_usuario, us.nombres FROM usuarios us
		WHERE us.id_oficina = ( SELECT ur.id_oficina FROM usuarios ur
		INNER JOIN informes_legales il ON il.id_us_comun = ur.id_usuario
		WHERE il.id_informe_legal = '$id' ) AND us.activo='S' 
		ORDER BY us.nombres";
		//echo $sql;
		$query = consulta($sql);
		$i=0;
		$f_ids_usuario= array();
		$f_usuario= array();
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$f_ids_usuario[$i]= $row["id_usuario"];
			$f_usuario[$i]= $row["nombres"];
			$i++;
		}
		$smarty->assign('f_ids_usuario',$f_ids_usuario);
		$smarty->assign('f_usuario',$f_usuario);
	}
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	//adicionar, acc_tipo_bien especifica el bien
	if(isset($_REQUEST['acc_tipo_bien'])){
		$id_tipo_bien = $_REQUEST['acc_tipo_bien'];
		//echo $id_tipo_bien;
		if($id_tipo_bien != "*"){
			include("./informe_legal/adicionar_informe.php");
		}
	}
	
	//ver documentos del informe legal (Vico)
	if(isset($_REQUEST['verdocs'])){
		include("./ver_informe_legal/documentos1.php");
	}
	
	//Guardar Informe con sus documentos (vico)
	if(isset($_REQUEST["guardar_doc_infor"])){
 	include("./ver_informe_legal/guardar_infordocu.php");
	}
	//aceptar la solicitud del informe legal // aqui no se define $aprobando asi entra aceptando_solicitud de manera correcta.
	if(isset($_REQUEST['aceptar_informe'])){
		include("./informe_legal/aceptar_solicitud.php");
	}
	//aceptando
	if(isset($_REQUEST['aceptar_boton_x'])){
		include("./informe_legal/aceptando_solicitud.php");
	}
//http://localhost/guardian2/code/_main.php?action=informe_legal.php&modificar=HIPOTECARIA INMUEBLE&id=2
	//modificar
	if(isset($_REQUEST['modificar'])){
		$id=$_REQUEST['id'];
		// TIPO BIEN: 1=Inmueble   2=MAquinaria   3=Vehiculo
		$tipo_bien = $_REQUEST['modificar'];
		include("./informe_legal/elaborar_informe.php");
	}
	//modificando
	if(isset($_REQUEST['modificar_boton_x'])){
		$id=$_REQUEST['id'];
		$tipo_bien = $_REQUEST['modificar_boton_x'];
		include("./informe_legal/elaborar_guardar.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		//verificamos si es vehiculo o inmueble
		$id=$_REQUEST['id'];
		//$tipo = substr($_REQUEST['eliminar'],0,1);
		//$tipo = $_REQUEST['eliminar_boton_x'];
		include("./informe_legal/eliminar_informe.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton_x'])){
		//verificamos si es vehiculo o inmueble
		$id=$_REQUEST['id'];
		$tipo = $_REQUEST['eliminar_boton_x'];
		include("./informe_legal/eliminando_informe.php");
	}
	
	//impresion
	if(isset($_REQUEST['imprimir'])){
		//verificamos si es vehiculo o inmueble
		$id=$_REQUEST['id'];
		$tipo_bien = $_REQUEST['imprimir'];
		include("./informe_legal/imprimir_bien2.php");
	}
	
	// impresion 2
	if(isset($_REQUEST['imprimir_segundo'])){
		$id=$_REQUEST['id'];
		include("./informe_legal/imprimir_final2.php");
	}
	
	//impresion del i.l. guardado historico
	if(isset($_REQUEST['imprimirh'])){
		include("./informe_legal/imprimir_histo.php");
	}
	
	//habilitacion
	if(isset($_REQUEST['habilitar_informe'])){
		$id=$_REQUEST['id'];
		$tipo_bien = $_REQUEST['habilitar_informe'];
		include("./informe_legal/habilitar_bien.php");
	}
	//habilitando informe
	if(isset($_REQUEST['habilitando_informe'])){
		$id=$_REQUEST['id'];
		$tipo_bien = $_REQUEST['habilitando_informe'];
		include("./informe_legal/habilitando_bien.php");
		$smarty->assign('carpeta_entrar',$carpeta_entrar);
	}
	
	//deshabilitacion
	if(isset($_REQUEST['deshabilitar_informe'])){
		$id=$_REQUEST['id'];
		$tipo_bien = $_REQUEST['deshabilitar_informe'];
		include("./informe_legal/deshabilitar_bien.php");
	}
	//deshabilitadno informe
	if(isset($_REQUEST['deshabilitando_informe'])){
		$id=$_REQUEST['id'];
		$tipo_bien = $_REQUEST['deshabilitando_informe'];
		include("./informe_legal/deshabilitando_bien.php");
	}
	
	//busqueda de datos del vehiculo
	if(isset($_REQUEST['busqueda_datos_vehiculo'])){
		include("./informe_legal/busqueda_datos_vehiculo.php");
	}
	
	//viendo el detalle de fechas de este informe legal
	if(isset($_REQUEST["ver_detalle"])){
		include("./informe_legal/ver_detalle.php");
	}
	
		
	// reasignacion del il a otro usuario
	if(isset($_REQUEST["cambiarus"])){
		include("./informe_legal/reasigna_usr.php");
	}
	//reasignacion
	if(isset($_REQUEST['reasignar_boton'])){
		include("./informe_legal/reasignar_informe.php");
	}
	
	//informe Final
	if(isset($_REQUEST['inf_final'])){
		$id=$_REQUEST['id'];
		$tibien=$_REQUEST['inf_final'];
		include("./informe_legal/inf_final.php");
	}
	//guardando informe  final
	if(isset($_REQUEST["btn_inf_final"])){
		$id=$_REQUEST['id'];
		include("./informe_legal/inf_final_guardar.php");
	}
	
	//buscar numero de escritura
	if(isset($_REQUEST["btn_busca_esc"])){
		$_nro_esc=$_REQUEST['inf_nro_esc'];
		$id=$_REQUEST['id'];
		$tibien=$_REQUEST['tibien'];
		include("./informe_legal/inf_final.php");
	}
	
	//excepcion a informe legal
	if(isset($_REQUEST['excepcion'])){
		include("./informe_legal/excepcion.php");
	}
	
	//imprimir observaciones de la excepcion 
	if(isset($_REQUEST['imprimir_exce'])){
		$volvera = "..php?carpeta_entrar=informe_legal";
		$id = $_REQUEST['id'];
		include("./ver_informe_legal/excepcion_imprime.php");
	}
	
	//mostrar los docs vencidos del I.L.
	if(isset($_REQUEST['hayvencidos'])){
		$id = $_REQUEST['id'];
		include("./informe_legal/vervencidos.php");
	}
	
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/

//recuperando los datos para la ventana

//para los aprobados- Bandeja de solicitudes de I.L.
/*
	$sql= "SELECT ile.*, us.nombres, tb.tipo_bien, tb.bien, ca.id_informe_legal as catastro,
	mo.id_us_corriente as prestado, mo.id_estado 
	FROM informes_legales ile 
	INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun  
	INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina  
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	LEFT JOIN carpetas ca ON ca.id_informe_legal=ile.id_informe_legal 
	LEFT JOIN movimientos_carpetas mo on mo.id_carpeta=ca.id_carpeta 
	WHERE (ca.id_carpeta NOT IN ( 
	SELECT c.id_carpeta FROM Carpetas c 
	INNER JOIN movimientos_carpetas m on m.id_carpeta=c.id_carpeta 
	WHERE id_estado>7 ) or ca.id_carpeta is null)
	AND ile.estado = 'apr' 
	$armar_consulta ORDER BY ile.id_informe_legal DESC ";

$query = consulta($sql);

$sol_il= array();

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

	$aux= $row["fecha_solicitud"];
	$aux_1= explode(" ",$aux);
	$sol_fecha= dateDMESY(dateDMY($aux_1[0]));
	
	$aux= $row["fecha_aprob"];
	$aux_1= explode(" ",$aux);
	$apr_fecha= dateDMESY(dateDMY($aux_1[0]));

	$sol_il[]= array( 'id' => $row["id_informe_legal"],
						'habilitar' =>	$row["habilitar_informe"],
						'cliente' =>	$row["cliente"] ,
						'sol_fecha' =>	$sol_fecha,
						'apr_fecha' =>	$apr_fecha,
						'usuario' =>	$row["nombres"],
						'catastro' =>	$row["catastro"],
						'prestado' =>	$row["prestado"],
						'tipo_bien' =>	$row["tipo_bien"]);

}

*/

$informes = array();
if($f_filtro != "*"){

// informes legales en elaboracion
if($filtro_estado == "0"){
	$f_estado = "(ile.estado='ace' OR ile.estado='npu') ";
}elseif($filtro_estado == "1"){
	$f_estado = "ile.estado='pub'";
}elseif($filtro_estado == "3"){
	//aprobados para aceptar el i.l.
	$f_estado = "ile.estado='apr'";
}else{
	$f_estado = "(ile.estado='ace' OR ile.estado='npu' OR ile.estado='pub') ";
}
/*	
	$sql= "SELECT ile.id_informe_legal,
	ile.habilitar_informe,
	ile.puede_operar,
	ile.cliente,
	ile.fecha, ile.fecha_aceptacion,
	ile.usr_acep, us.nombres, tb.tipo_bien, tb.bien, us2.nombres as asesor
	FROM informes_legales ile 
	INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
	LEFT JOIN usuarios us2  ON us2.id_usuario  =ile.usr_acep
	inner join oficinas ofi ON ofi.id_oficina = us.id_oficina 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	WHERE ile.estado in ('ace', 'npu')
	AND ofi.id_almacen = $id_almacen ORDER BY ile.id_informe_legal DESC ";

*/

	$sql= "SELECT ile.id_informe_legal,
	ile.habilitar_informe,
	ile.puede_operar,
	ile.cliente, 
	convert(varchar(16),ile.fecha,103) as fsolicita, 
	convert(varchar(16),ile.fecha_aceptacion,103) as facepta,
	ile.usr_acep, us.nombres, tb.tipo_bien, tb.bien, us2.nombres as asesor
		FROM informes_legales ile 
		INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
		LEFT JOIN usuarios us2  ON us2.id_usuario  =ile.usr_acep 
		inner join oficinas ofi ON ofi.id_oficina = us.id_oficina 
		INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
		WHERE $f_estado 
	$armar_consulta ORDER BY ile.id_informe_legal DESC ";
	

//echo $sql;
$query = consulta($sql);

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$id = $row["id_informe_legal"];
	
	$sql_a="SELECT estado FROM informes_legales_excepciones WHERE id_informe_legal='$id' ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	if($row_a["estado"] == "ACE" || $row_a["estado"] == "REC" ){
		$conexcepcion = $row_a["estado"];
	}else{
		$conexcepcion ="";
	}
	//ver si hay documentacion vencida
	//////////////////////////////////
	$sql_a="SELECT count(*) as nro
		FROM informes_legales_documentos
		WHERE fecha_vencimiento IS NOT NULL AND fecha_vencimiento < GETDATE()
		AND id_informe_legal = '$id'";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	if($row_a["nro"] > 0){
		$vencidos =$row_a["nro"];
	}else{
		$vencidos =0;
	}
		
	$informes[] = array('id' => $id,
						'habilitar_informe' => $row["habilitar_informe"],
						'usuario' => $row["nombres"],
						'tipo_bien' => $row["tipo_bien"],
						'bien' => $row["bien"],
						'usuario_asig' => $row["asesor"],
						'cliente' => $row["cliente"],
						'puede_operar' => $row["puede_operar"],
						'sol_fecha' => $row["fsolicita"],
						'ace_fecha' => $row["facepta"],
						'conexcepcion' => $conexcepcion,
						'vencidos' => $vencidos);

}

}// if($f_filtro == "*")

//	$smarty->assign('sol_il',$sol_il); // lista solicitados
	$smarty->assign('informes',$informes); //lista informes legales
		
	//$smarty->assign('id_almacen',$filtro_almacen);
	$smarty->assign('con_publicar',$con_publicar);
			
	$smarty->display('informe_legal_nal.html');
	die();

?>
