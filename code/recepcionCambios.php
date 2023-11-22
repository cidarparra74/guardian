<?php
//PERCY: Oct 2018 para cambiar agencia
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="../code/_main.php?action=recepcionCambios.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "recepcionCambios";
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
		$f_cliente= $_REQUEST['filtro_cliente'];
		$f_ci_cliente= $_REQUEST['filtro_ci_cliente'];
		
		$_SESSION["inf_cliente"]=$f_cliente;
		$_SESSION["inf_ci_cliente"]=$f_ci_cliente;
	}
	else{
		$f_cliente=$_SESSION["inf_cliente"];
		$f_ci_cliente=$_SESSION["inf_ci_cliente"];
	}

	$smarty->assign('f_cliente',$f_cliente);
	$smarty->assign('f_ci_cliente',$f_ci_cliente);
	
	$smarty->assign('f_ids_usuario',$f_ids_usuario);
	$smarty->assign('f_usuario',$f_usuario);
	
	//armando la consulta
	$armar_consulta="";

	if($f_cliente != ""){
		$armar_consulta.= "AND cliente LIKE '%$f_cliente%' ";
	}
	if($f_ci_cliente != ""){
		$armar_consulta.= "AND ci_cliente LIKE '%$f_ci_cliente%' ";
	}

	
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	//---------------------
	if(isset($_REQUEST['recepcionCambiar'])){
		
		include("./informe_legal/recepcionCambiar.php");
	}
	//asignando agencia a un i.l.
	if(isset($_REQUEST['asignar_perito_x'])){
		include("./informe_legal/recepcionCambiando.php");
	}

	
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/

//recuperando los datos para la ventana use

// solo se muestran los datos de la oficina correspondiente y al responsable


$solicitudes= array();
if($armar_consulta != ""){
	$id_oficina = $_SESSION['id_oficina'];
	//
	$sql= "SELECT id_informe_legal, id_us_comun, il.id_tipo_bien, 
	fecha_solicitud, fecha_recepcion, motivo, cliente, nrocaso, tb.tipo_bien 
	FROM informes_legales il, tipos_bien tb, usuarios us
	WHERE il.id_tipo_bien = tb.id_tipo_bien AND il.id_us_comun= us.id_usuario $armar_consulta 
	ORDER BY id_informe_legal DESC";
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
	$smarty->display('recepcionCambios.html');
	die();

?>
