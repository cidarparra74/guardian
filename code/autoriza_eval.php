<?php
//elaborar_informe
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="../code/_main.php?action=autoriza_eval.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "autoriza_eval";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	$id_oficina = $_SESSION["id_oficina"];
	
	//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//$enable_ws = $row["enable_ws"];
	$smarty->assign('enable_ws',$row["enable_ws"]);
	
	//recuperando la lista de usuarios corrientes ///WHERE id_perfil='2'
	$sql= "SELECT id_usuario, nombres 
			FROM usuarios 
			WHERE id_oficina = $id_oficina AND activo='S'
			ORDER BY nombres ";
	$query = consulta($sql);
	$i=0;
	$f_ids_usuario= array();
	//$f_usuario= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_ids_usuario[$i]  = array('id'=>$row["id_usuario"],
									'nombres'=> $row["nombres"]);
		$i++;
	}
	
	//recuperando los tipos de bien
	$sql= "SELECT * FROM tipos_bien WHERE categoria = 1 ORDER BY id_tipo_bien ";
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
		//$_SESSION["inf_puede_operar"]="ninguno";
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
	$smarty->assign('f_id_tipo_bien',$f_id_tipo_bien); 
	$smarty->assign('f_cliente',$f_cliente);
	$smarty->assign('f_ci_cliente',$f_ci_cliente);
	
	$smarty->assign('f_ids_usuario',$f_ids_usuario);
	//$smarty->assign('f_usuario',$f_usuario);
	
	//armando la consulta
	$armar_consulta="";
	if($f_id_usuario != "ninguno"){
		$armar_consulta.= "AND il.id_us_comun='$f_id_usuario' ";
	}else{
		$armar_consulta.= "AND il.id_us_comun IN (SELECT id_usuario FROM usuarios WHERE id_oficina = $id_oficina )";
	}
	
	if($f_id_tipo_bien != "ninguno"){
		$armar_consulta.= "AND il.id_tipo_bien='$f_id_tipo_bien' ";
	}
	if($f_cliente != ""){
		$armar_consulta.= "AND il.cliente LIKE '%$f_cliente%' ";
	}
	if($f_ci_cliente != ""){
		$armar_consulta.= "AND il.ci_cliente LIKE '%$f_ci_cliente%' ";
	}

	/*
	//DOCUMENTOS FALTANTES
	select count(*) cant from tipos_bien_documentos tb
left join (informes_legales il 
inner join documentos_informe di on di.din_inf_id = il.id_informe_legal and il.id_informe_legal = 2885) 
ON tb.id_tipo_bien = il.id_tipo_bien  and tb.id_documento = di.din_doc_id 
 where tb.id_tipo_bien = (select id_tipo_bien from informes_legales where id_informe_legal = 2885) 
and di.din_id is null and tb.requerido = 1
	*/
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	//---------------------
	//esto mismo usamos para la aceptacion de la elab de i.l.
	//aceptar la solicitud del informe legal
	if(isset($_REQUEST['aceptar_informe'])){
		$cat = 1;
		include("./informe_legal/aceptar_solicitud.php");
	}
	//aceptando
	if(isset($_REQUEST['aceptar_boton_x'])){
		$aprobando = 'pro';
		include("./informe_legal/aceptando_solicitud.php");
	}
	//------------------

	//modificacion de informe legal ... Ahora modificar usa codigo de Adicionar. Victor
	//print_r($_REQUEST);
	if(isset($_REQUEST['modificar'])){
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
		}
		$smarty->assign('alerta','NO');
		$id_us_actual = $_SESSION["idusuario"];
		$nombre_us_actual= $_SESSION["nombreusr"];
		$smarty->assign('id_us_actual',$id_us_actual);
		$smarty->assign('nombre_us_actual',$nombre_us_actual);
		//adicionar.php tambien permite modificar. Victor
		include("./ver_informe_legal/adicionar.php");	
	}
	
	//adicionando
	if(isset($_REQUEST["adicionar_boton_x"])){
		$esRecepcion = 0; //para saber si adicionando.php es llamado desde recepcion.php 0=No 1=Si
		if($_REQUEST["estado_formulario"] == "Adicionar"){
			include("./ver_informe_legal/adicionando.php");
			include("./ver_informe_legal/documentos1.php");
		}else{
			include("./ver_informe_legal/modificando.php");
			include("./ver_informe_legal/documentos1.php");  
		}
	}
	
	//quitando un documento ya recepcionado
	if(isset($_REQUEST["quitar_doc"])){
		$esRecepcion = 0; //para saber si adicionando.php es llamado desde recepcion.php
		include("./ver_informe_legal/documentos1.php");  
		
	}
	
	//Guardar Informe con sus documentos
	if(isset($_REQUEST["guardar_doc_infor"])){
 	include("./ver_informe_legal/guardar_infordocu.php");
	}
	
	//imprimir informe  y sus documentos	
	if(isset($_REQUEST["impri_doc_infor"])){
 	include("ver_informe_legal/imprimir_recepcion.php");
	}
	
	//impresion
	if(isset($_REQUEST['imprimir_recepcion'])){

		include("ver_informe_legal/imprimir_recepcion.php");
		
	}
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/

//recuperando los datos para la ventana


// solo se muestran los datos de la oficina correspondiente y al responsable


/*
if($armar_consulta == ""){
	$sql= "SELECT * FROM informes_legales WHERE estado='sol' AND id_us_comun IN ( 
SELECT id_usuario FROM usuarios WHERE id_oficina = $id_oficina ) ORDER BY id_informe_legal DESC ";
}
else{
	$sql= "SELECT * FROM informes_legales WHERE estado='sol' AND id_us_comun IN ( 
SELECT id_usuario FROM usuarios WHERE id_oficina = $id_oficina ) $armar_consulta ORDER BY id_informe_legal DESC ";
*/
$sql= "SELECT il.id_informe_legal, il.id_us_comun, il.fecha_solicitud, il.motivo, il.fecha_recepcion, 
il.nrocaso, il.cliente, tb.tipo_bien 
FROM informes_legales il
INNER JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien
WHERE estado='sol' AND tb.con_inf_legal = 'S' $armar_consulta ORDER BY id_informe_legal DESC ";
//}
//echo "$sql";

$query = consulta($sql);

$solicitudes= array();

$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	
	$sql_a="SELECT nombres FROM usuarios WHERE id_usuario='".$row["id_us_comun"]."' ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	$sol_usuario= $row_a["nombres"];
	/*
	$sql_a="SELECT * FROM tipos_bien WHERE id_tipo_bien='".$row["id_tipo_bien"]."' ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	$sol_tipo_bien= $row_a["tipo_bien"];
	*/
	$aux_1= explode(" ",$row["fecha_solicitud"]);
	$sol_fecha= dateDMESY(dateDMY($aux_1[0]));
	$aux= $row["fecha_recepcion"];
	$aux_1= explode(" ",$aux);
	$rec_fecha=dateDMESY(dateDMY($aux_1[0]));
	
	$solicitudes[$i]= array('id_informe_legal'=>$row["id_informe_legal"],
							'motivo'=>$row["motivo"],
							'sol_usuario'=>$sol_usuario,
							'sol_tipo_bien'=>$row["tipo_bien"],
							'sol_cliente'=>$row["cliente"],
							'nrocaso'=>trim($row["nrocaso"]),
							'sol_fecha'=>$sol_fecha,
							'rec_fecha'=>$rec_fecha);
	$i++;
}
	//$smarty->assign('usuario',$usuario);
	$smarty->assign('solicitudes',$solicitudes);

	$smarty->display('autoriza_eval.html');
	die();

?>
