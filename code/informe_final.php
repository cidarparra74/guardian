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
	$carpeta_entrar="../code/_main.php?action=informe_final.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "informe_final";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	$id_us_actual = $_SESSION["idusuario"];
	$id_almacen = $_SESSION["id_almacen"];
	//recuperando la lista de usuarios corrientes ///SOLO DE LA MISMA CIUDAD
	$sql= "SELECT id_usuario, nombres FROM usuarios us
	INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
	WHERE ofi.id_almacen = $id_almacen AND us.activo='S'";
	$query = consulta($sql);
	$i=0;
	$f_ids_usuario= array();
	$f_usuario= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_ids_usuario[$i]= $row["id_usuario"];
		$f_usuario[$i]= $row["nombres"];
		$i++;
	}
	
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
		$_SESSION["inf_id_usuario"]="ninguno";
		$_SESSION["inf_id_tipo_bien"]="ninguno";
		$_SESSION["inf_cliente"]="";
		$_SESSION["inf_ci_cliente"]="";
	}
	
	//si se presiona el boton de buscar
	if(isset($_REQUEST['buscar_boton'])){
		$f_id_usuario= $_REQUEST['filtro_usuario'];
		$f_id_tipo_bien= $_REQUEST['filtro_tipo_bien'];
		$f_cliente= $_REQUEST['filtro_cliente'];
		$f_ci_cliente= $_REQUEST['filtro_ci_cliente'];
		
		$_SESSION["inf_id_usuario"]=$f_id_usuario;
		$_SESSION["inf_id_tipo_bien"]=$f_id_tipo_bien;
		$_SESSION["inf_cliente"]=$f_cliente;
		$_SESSION["inf_ci_cliente"]=$f_ci_cliente;
	}
	else{
		$f_id_usuario=$_SESSION["inf_id_usuario"];
		$f_id_tipo_bien=$_SESSION["inf_id_tipo_bien"];
		$f_cliente=$_SESSION["inf_cliente"];
		$f_ci_cliente=$_SESSION["inf_ci_cliente"];
	}

	$smarty->assign('f_id_usuario',$f_id_usuario);
	$smarty->assign('f_id_tipo_bien',$f_id_tipo_bien); //1->vehiculos, 2->inmuebles
	$smarty->assign('f_cliente',$f_cliente);
	$smarty->assign('f_ci_cliente',$f_ci_cliente);
	$smarty->assign('f_ids_usuario',$f_ids_usuario);
	$smarty->assign('f_usuario',$f_usuario);

	
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

	
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	
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

	
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/

//recuperando los datos para la ventana
$id_us_actual = $_SESSION["idusuario"];
$id_almacen = $_SESSION["id_almacen"];


// informes legales en elaboracion
if($armar_consulta == ""){

$sql= "SELECT ile.id_informe_legal,
ile.habilitar_informe,
ile.puede_operar,
ile.cliente,
ile.fecha, ile.fecha_aceptacion,
ile.usr_acep, us.nombres, tb.tipo_bien, tb.bien 
	FROM informes_legales ile 
	INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
	inner join oficinas ofi ON ofi.id_oficina = us.id_oficina 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	WHERE ile.estado in ('ace', 'npu')
	AND ofi.id_almacen = $id_almacen ORDER BY ile.id_informe_legal DESC ";

}else{

$sql= "SELECT ile.id_informe_legal,
ile.habilitar_informe,
ile.puede_operar,
ile.cliente,
ile.fecha, convert(varchar(16),ile.fecha_aceptacion) as facepta,
ile.usr_acep, us.nombres, tb.tipo_bien, tb.bien 
	FROM informes_legales ile 
	INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
	inner join oficinas ofi ON ofi.id_oficina = us.id_oficina 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	WHERE ofi.id_almacen = $id_almacen $armar_consulta 
ORDER BY ile.id_informe_legal DESC ";
	
}
//echo $sql;
$query = consulta($sql);

$informes = array();

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$id = $row["id_informe_legal"];
	$aux= $row["fecha"];
	$aux_1= explode(" ",$aux);
	$sol_fecha = dateDMESY($aux_1[0]);
	
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
						'sol_fecha' => $sol_fecha,
						'conexcepcion' => $conexcepcion,
						'vencidos' => $vencidos);
}

	$smarty->assign('sol_il',$sol_il); // lista solicitados
	$smarty->assign('informes',$informes); //lista informes legales
		
	$smarty->assign('id_almacen',$id_almacen);
	$smarty->assign('con_publicar',$con_publicar);

	$smarty->display('informe_final.html');
	die();

?>
