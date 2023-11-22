<?php
//elaborar_informe
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="../code/_main.php?action=informe_legal_perito.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "informe_legal_perito";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	$id_oficina = $_SESSION["id_oficina"];
	
	//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//$enable_ws = $row["enable_ws"];
	$smarty->assign('enable_ws',$row["enable_ws"]);
	
	//recuperando la lista de usuarios corrientes ///WHERE id_perfil='2'
	$sql= "SELECT id_usuario, nombres FROM usuarios WHERE id_oficina = $id_oficina AND activo='S' ORDER BY nombres ";
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
	$sql= "SELECT * FROM tipos_bien ORDER BY id_tipo_bien ";
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
	//	$f_id_usuario= "ninguno";
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
	//	$f_id_usuario= $_REQUEST['filtro_usuario'];
	//	$f_id_tipo_bien= $_REQUEST['filtro_tipo_bien'];
		$f_cliente= $_REQUEST['filtro_cliente'];
		$f_ci_cliente= $_REQUEST['filtro_ci_cliente'];
		
	//	$_SESSION["inf_id_usuario"]=$f_id_usuario;
	//	$_SESSION["inf_id_tipo_bien"]=$f_id_tipo_bien;
		$_SESSION["inf_cliente"]=$f_cliente;
		$_SESSION["inf_ci_cliente"]=$f_ci_cliente;
	}
	else{
	//	$f_id_usuario=$_SESSION["inf_id_usuario"];
	//	$f_id_tipo_bien=$_SESSION["inf_id_tipo_bien"];
		$f_cliente=$_SESSION["inf_cliente"];
		$f_ci_cliente=$_SESSION["inf_ci_cliente"];
	}

	//$smarty->assign('f_id_usuario',$f_id_usuario);
	//$smarty->assign('f_id_tipo_bien',$f_id_tipo_bien); 
	$smarty->assign('f_cliente',$f_cliente);
	$smarty->assign('f_ci_cliente',$f_ci_cliente);
	
	$smarty->assign('f_ids_usuario',$f_ids_usuario);
	$smarty->assign('f_usuario',$f_usuario);
	
	//armando la consulta
	$armar_consulta="";
/*	if($f_id_usuario != "ninguno"){
		$armar_consulta.= "AND id_us_comun='$f_id_usuario' ";
	}else{
		$armar_consulta.= "AND id_us_comun IN (SELECT id_usuario FROM usuarios WHERE id_oficina = $id_oficina )";
	}
	
	if($f_id_tipo_bien != "ninguno"){
		$armar_consulta.= "AND id_tipo_bien='$f_id_tipo_bien' ";
	}
	*/
	if($f_cliente != ""){
		$armar_consulta.= "AND cliente LIKE '%$f_cliente%' ";
	}
	if($f_ci_cliente != ""){
		$armar_consulta.= "AND ci_cliente LIKE '%$f_ci_cliente%' ";
	}

	
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	//---------------------
	if(isset($_REQUEST['asignar_perito'])){
		
		include("./informe_legal/asignar_perito.php");
	}
	//asignando perido a un i.l.
	if(isset($_REQUEST['asignar_perito_x'])){
		include("./informe_legal/asignando_perito.php");
	}
	//------------------ 
	if(isset($_REQUEST['asignar_todos'])){
		
		include("./informe_legal/asignar_todos.php");
	}
	//asignando perito a varios i.l.
	if(isset($_REQUEST['asignar_todos_x'])){
		include("./informe_legal/asignando_todos.php");
	}
	
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/

//recuperando los datos para la ventana use

// solo se muestran los datos de la oficina correspondiente y al responsable

//sin perito, sin contrato ni que este en archivo
//estado IN ('apr','ace','npu') AND
//id_us_comun IN ( SELECT id_usuario FROM usuarios WHERE id_oficina = $id_oficina )
/*
if($armar_consulta == ""){
	$sql= "SELECT id_informe_legal, id_us_comun, il.id_tipo_bien, fecha_solicitud, fecha_recepcion, 
	motivo, cliente, nrocaso, tb.tipo_bien FROM informes_legales il, tipos_bien tb
	WHERE il.id_tipo_bien = tb.id_tipo_bien AND id_perito=0 AND tb.con_perito='S' ORDER BY id_informe_legal DESC ";
}
else{
*/
$solicitudes= array();
if($armar_consulta != ""){
	$id_oficina = $_SESSION['id_oficina'];
	//
	$sql= "SELECT id_informe_legal, id_us_comun, il.id_tipo_bien, 
	fecha_solicitud, fecha_recepcion, motivo, cliente, nrocaso, tb.tipo_bien 
	FROM informes_legales il, tipos_bien tb, usuarios us
	WHERE il.id_tipo_bien = tb.id_tipo_bien AND il.id_us_comun= us.id_usuario AND (id_perito=0  OR id_perito is null)
	AND tb.con_perito='S' $armar_consulta ORDER BY id_informe_legal DESC";
//echo $sql; //AND us.id_oficina = '$id_oficina' 
	$query = consulta($sql);

	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		$sql_a="SELECT nombres FROM usuarios WHERE id_usuario='".$row["id_us_comun"]."' ";
		$result_a= consulta($sql_a);
		$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
		$sol_usuario= $row_a["nombres"];
		
		
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
}
	$smarty->assign('armar_consulta',$armar_consulta);
	$smarty->assign('solicitudes',$solicitudes);
/*	
$asignados= array();
if($armar_consulta != ""){
	$sql= "SELECT id_informe_legal,  cliente, nrocaso, tb.tipo_bien, pe.nombres, pe.apellidos, il.id_perito 
	FROM informes_legales il, tipos_bien tb, personas pe
	WHERE il.id_tipo_bien = tb.id_tipo_bien AND il.id_perito = pe.id_persona
	AND id_perito<>0 AND tb.con_perito='S' $armar_consulta
	ORDER BY id_informe_legal DESC ";
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$asignados[]= array('id_informe_legal'=>$row["id_informe_legal"],
							'sol_tipo_bien'=>$row["tipo_bien"],
							'sol_cliente'=>$row["cliente"],
							'nrocaso'=>trim($row["nrocaso"]),
							'id_perito'=>$row['id_perito'],
							'perito'=>$row['apellidos'].' '.$row['nombres']);
	}
}
	$smarty->assign('asignados',$asignados);
*/
	$smarty->display('informe_legal_perito.html');
	die();

?>
