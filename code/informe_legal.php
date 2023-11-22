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
		$con_publicar = $_SESSION['con_publicar'];
	}
	$carpeta_entrar="../code/_main.php?action=informe_legal.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "informe_legal";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	$id_us_actual = $_SESSION["idusuario"];
	$id_almacen = $_SESSION["id_almacen"];
	//recuperando la lista de usuarios corrientes ///SOLO DE LA MISMA CIUDAD
	$sql= "SELECT id_usuario, nombres FROM usuarios us
	INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
	WHERE ofi.id_almacen = $id_almacen AND us.activo='S' ORDER BY us.nombres";
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
	
	//recuperando los tipos de bien
	$sql= "SELECT * FROM tipos_bien WHERE con_inf_legal ='S' ORDER BY tipo_bien";
	$query = consulta($sql);
	$i=0;
	$tiposbien=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tiposbien[$i]= array('id' => $row["id_tipo_bien"],
							'descri' => $row["tipo_bien"]);
		$i++;
	}
	
	$smarty->assign('tiposbien',$tiposbien);
	
		//filtro de la ventana
	if(isset($_REQUEST["filtro"])){
		$f_filtro = $_REQUEST["filtro"];
	}else{$f_filtro ="ninguno";}
	
	if($f_filtro == "ninguno"){
		$f_id_usuario= "ninguno";
		$f_id_tipo_bien= "ninguno";
		$f_cliente= "";
		$f_ci_cliente="";
		$f_nro_il="";
		$_SESSION["inf_id_usuario"]="ninguno";
		$_SESSION["inf_id_tipo_bien"]="ninguno";
		$_SESSION["inf_cliente"]="";
		$_SESSION["inf_ci_cliente"]="";
		$_SESSION["inf_nro_il"]="";
	}
	
	//si se presiona el boton de buscar
	if(isset($_REQUEST['buscar_boton'])){
		$f_id_usuario= $_REQUEST['filtro_usuario'];
		$f_id_tipo_bien= $_REQUEST['filtro_tipo_bien'];
		$f_cliente= $_REQUEST['filtro_cliente'];
		$f_ci_cliente= $_REQUEST['filtro_ci_cliente'];
		$f_nro_il= $_REQUEST['filtro_nro_il'];

		$_SESSION["inf_id_usuario"]=$f_id_usuario;
		$_SESSION["inf_id_tipo_bien"]=$f_id_tipo_bien;
		$_SESSION["inf_cliente"]=$f_cliente;
		$_SESSION["inf_ci_cliente"]=$f_ci_cliente;
		$_SESSION["inf_nro_il"]=$f_nro_il;
	}
	else{
		$f_id_usuario=$_SESSION["inf_id_usuario"];
		$f_id_tipo_bien=$_SESSION["inf_id_tipo_bien"];
		$f_cliente=$_SESSION["inf_cliente"];
		$f_ci_cliente=$_SESSION["inf_ci_cliente"];
		$f_nro_il=$_SESSION["inf_nro_il"];
	}

	$smarty->assign('f_id_usuario',$f_id_usuario);
	$smarty->assign('f_id_tipo_bien',$f_id_tipo_bien); //1->vehiculos, 2->inmuebles
	$smarty->assign('f_cliente',$f_cliente);
	$smarty->assign('f_ci_cliente',$f_ci_cliente);
	

	
	//armando la consulta
	$armar_consulta="";
	if($f_id_usuario != "ninguno"){
		$armar_consulta.= "AND ile.id_us_comun='$f_id_usuario' ";
	}
	if($f_id_tipo_bien != "ninguno"){
		$armar_consulta.= "AND ile.id_tipo_bien='$f_id_tipo_bien' ";
	}
	if($f_cliente != ""){
		$armar_consulta.= "AND ile.cliente LIKE '%$f_cliente%' ";
	}
	if($f_ci_cliente != ""){
		$armar_consulta.= "AND ile.ci_cliente LIKE '%$f_ci_cliente%' ";
	}
	if($f_nro_il != ""){
		$armar_consulta.= "AND ile.id_informe_legal='$f_nro_il' ";
	}

	
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	//ver copia guardada del informe legal / crear informe a partir de este
	if(isset($_REQUEST['copyil'])){
		include("./informe_legal/copiar_il.php");
	}

	//ver copia guardada del informe legal / crear informe a partir de este
	if(isset($_REQUEST['ver_bk'])){
		include("./informe_legal/ver_copia_il.php");
	}
	//copiar el informe guardado
	if(isset($_REQUEST['copiar_boton_x'])){
		include("./informe_legal/aceptando_solicitud_il.php");
	}
	
	//adicionar sin recepcion, acc_tipo_bien especifica el bien
	if(isset($_REQUEST['acc_tipo_bien'])){
		$id_tipo_bien = $_REQUEST['acc_tipo_bien'];
		//echo $id_tipo_bien;
		if($id_tipo_bien != "ninguno"){
			//vemos si es normal o de p.juridica
			$sql = "SELECT bien FROM tipos_bien WHERE id_tipo_bien = '$id_tipo_bien'";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($row["bien"]!='5')
				include("./informe_legal/adicionar_informe.php");
			else
				include("./informe_legal/adicionar_informe_pj.php");
		}
	}
	
	//registrar poderes del i.l. pj
	if(isset($_REQUEST['poderes'])){
		include("./informe_legal/poderes.php");
	}
	//guardar poderes del i.l. pj
	if(isset($_REQUEST['poderes_guardar'])){
		include("./informe_legal/poderes_guardar.php");
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
		include("./informe_legal/aceptar_solicitud_il.php");
	}
	//aceptando
	if(isset($_REQUEST['aceptar_boton_x'])){
		include("./informe_legal/aceptando_solicitud.php");
	}
	

	
	//modificar
	if(isset($_REQUEST['modificar'])){
		$id=$_REQUEST['id'];
		// TIPO BIEN: 1=Inmueble   2=Maquinaria   3=Vehiculo 4=Otro  5=PJuridi  6=Semovientes
		$tipo_bien = $_REQUEST['modificar'];
		if($tipo_bien!='5')
			include("./informe_legal/elaborar_informe.php");
		else
			include("./informe_legal/elaborar_informe_pj.php");
	}
	//modificando
	if(isset($_REQUEST['modificar_boton_x'])){
		$id=$_REQUEST['id'];
		$tipo_bien = $_REQUEST['modificar_boton_x'];
		//echo $_REQUEST['modificar_boton_x'];
		if($tipo_bien!='5')
			include("./informe_legal/elaborar_guardar.php");
		else{
			$volver=$_REQUEST['volver'];  //para volver al informe cuando es PJ y esta actualiz. poderes
			include("./informe_legal/elaborar_guardar_pj.php");
			if($volver=='S')
				include("./informe_legal/elaborar_informe_pj.php");
			if($volver=='E'){
				$idpoder=$_REQUEST['idpoder'];
				$sql="DELETE FROM apoderados WHERE id_poder = '$idpoder'";
				ejecutar($sql);
				$sql="DELETE FROM poderes WHERE id_poder = '$idpoder'";
				ejecutar($sql);
				include("./informe_legal/elaborar_informe_pj.php");
			}
		}
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

//---- ver si esta habilitado la adicion de i.l. sin recepcion
$sql="SELECT TOP 1 enable_ilsin FROM opciones ";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
if($row['enable_ilsin']=='S')
	$smarty->assign('enable_ilsin',1);
else
	$smarty->assign('enable_ilsin',0);

//recuperando los datos para la ventana
//$id_us_actual = $_SESSION["idusuario"];
$id_almacen = $_SESSION["id_almacen"];
//para los aprobados

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
	AND ofi.id_almacen = $id_almacen $armar_consulta ORDER BY ile.id_informe_legal DESC ";
//echo $sql;
$query = consulta($sql);

$sol_il= array();
$sol_tipo_bien=array();

$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

	$aux= $row["fecha_solicitud"];
	$aux_1= explode(" ",$aux);
	$sol_fecha= dateDMESY(dateDMY($aux_1[0]));
	
	$aux= $row["fecha_aprob"];
	$aux_1= explode(" ",$aux);
	$apr_fecha= dateDMESY(dateDMY($aux_1[0]));
/*
	$aux= $row["fecha_aceptacion"];
	$aux_1= explode(" ",$aux);
	$ace_fecha= dateDMESY(dateDMY($aux_1[0]));
	'ace_fecha' =>	$ace_fecha,
*/
	$sol_il[$i]= array( 'id' => $row["id_informe_legal"],
						'habilitar' =>	$row["habilitar_informe"],
						'cliente' =>	$row["cliente"] ,
						'sol_fecha' =>	$sol_fecha,
						'apr_fecha' =>	$apr_fecha,
						'usuario' =>	$row["nombres"],
						'catastro' =>	$row["catastro"],
						'prestado' =>	$row["prestado"],
						'tipo_bien' =>	$row["tipo_bien"]);
	
	$i++;
}


// informes legales en elaboracion
if($armar_consulta == ""){

$sql= "SELECT ile.id_informe_legal,
ile.habilitar_informe,
ile.puede_operar,
p.nombres cliente,
convert(varchar(16),ile.fecha_solicitud,103) as fsolicita, 
convert(varchar(16),ile.fecha_aceptacion,103) as facepta,
ab.nombres as asesor, us.nombres, tb.tipo_bien, tb.bien 
	FROM informes_legales ile 
	INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
	INNER JOIN usuarios ab   ON ab.id_usuario  =ile.usr_acep
	INNER JOIN oficinas ofi ON ofi.id_oficina = ile.id_oficina 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	INNER JOIN propietarios p ON p.id_propietario=ile.id_propietario
	WHERE ile.estado in ('ace', 'npu')
	AND ofi.id_almacen = $id_almacen ORDER BY ile.id_informe_legal DESC ";
//(select id_almacen from oficinas o1 inner join usuarios u1 on o1.id_oficina = u1.id_oficina 
//where u1.id_usuario = $id_us_actual)
}else{

$sql= "SELECT ile.id_informe_legal,
ile.habilitar_informe,
ile.puede_operar,
p.nombres cliente,
convert(varchar(16),ile.fecha_solicitud,103) as fsolicita, 
convert(varchar(16),ile.fecha_aceptacion,103) as facepta,
ab.nombres as asesor, us.nombres, tb.tipo_bien, tb.bien 
	FROM informes_legales ile 
	INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
	INNER JOIN oficinas ofi ON ofi.id_oficina = ile.id_oficina 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	INNER JOIN propietarios p ON p.id_propietario=ile.id_propietario
	LEFT JOIN usuarios ab   ON ab.id_usuario  =ile.usr_acep
	WHERE ile.estado='pub'
AND ofi.id_almacen = $id_almacen $armar_consulta ORDER BY ile.id_informe_legal DESC ";
	
}
//echo $sql;
$smarty->assign('sql',$sql);
$query = consulta($sql);

$informes = array();

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

	$smarty->assign('sol_il',$sol_il); // lista solicitados
	$smarty->assign('informes',$informes); //lista informes legales
		
	$smarty->assign('id_almacen',$id_almacen);
	$smarty->assign('con_publicar',$con_publicar);

	$smarty->display('informe_legal.html');
	die();

?>
