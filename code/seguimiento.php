<?php

//require_once('../lib/lib/nusoap.php');
require_once("../lib/setup.php");
$smarty = new bd;	
//require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//recuperamos los datos del usuario

	//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
//	$enable_ncaso = $row["enable_ncaso"];
	$smarty->assign('enable_ws',$enable_ws);
//	$smarty->assign('enable_ncaso',$enable_ncaso);
	
	$id_us_actual = $_SESSION["idusuario"];
	$nombre_us_actual= $_SESSION["nombreusr"];
	$smarty->assign('id_us_actual',$id_us_actual);
	$smarty->assign('nombre_us_actual',$nombre_us_actual);
	
	//href
	$carpeta_entrar="./_main.php?action=seguimiento.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "seguimiento";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//filtro de la ventana


	//si se presiona el boton de buscar
	if(isset($_REQUEST['buscar_boton'])){
		$f_cliente= $_REQUEST['filtro_cliente'];
		$f_nrocaso= $_REQUEST['filtro_nrocaso'];

	}
	else{
		$f_cliente='';
		$f_nrocaso= '';
	}

	$smarty->assign('f_cliente',$f_cliente);
	$smarty->assign('f_nrocaso',$f_nrocaso);
	
	//armando la consulta
	$armar_consulta = "";
	if($f_cliente != ""){
		$armar_consulta.= "AND ile.cliente LIKE '%$f_cliente%' ";
	}
	if($f_nrocaso != ""){
		$armar_consulta.= "AND ile.nrocaso = '$f_nrocaso' ";
	}


/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	//eliminacion de informe legal
	//print_r($_REQUEST);
	if(isset($_REQUEST['eliminar'])){
		include("./ver_informe_legal/eliminar.php");	
	}
	
	//eliminando
	if(isset($_REQUEST["eliminar_boton_x"])){
		$acc = $_REQUEST["eliminar_boton_x"];
		include("./ver_informe_legal/eliminando.php");
	}

	
	//ver el flujo del credito	
	if(isset($_REQUEST["verflujo"])){
		$id = $_REQUEST['id'];
		include("ver_informe_legal/flujo.php");
	}
	
	
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/


//para la lista 
$rec_lista=array();
if($armar_consulta != ""){
	//$id_almacen = $_SESSION['id_almacen'];
$sql = "SELECT TOP 20 ile.id_informe_legal, ile.cliente, tb.tipo_bien, tb.con_inf_legal, ile.fecha_recepcion, 
	ile.estado, ile.nrocaso, ofi.nombre as oficina
	FROM informes_legales ile 
	INNER JOIN usuarios us ON us.id_usuario  =ile.id_us_comun 
	inner join oficinas ofi ON ofi.id_oficina = us.id_oficina 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	WHERE  ile.id_informe_legal > 0 $armar_consulta ORDER BY ile.id_informe_legal DESC";
	//echo $sql;
	$query = consulta($sql);

$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$aux= $row["fecha_recepcion"];
	$aux_1= explode(" ",$aux);
	$aux=dateDMESY(dateDMY($aux_1[0]));
	$estado = $row["estado"];
	if($estado=='sol') $estadolit = 'Con solicitud de I.L.';
	elseif($estado=='apr') $estadolit = 'Aprobado para I.L.';
	elseif($estado=='ace') $estadolit = 'Aceptado para elaboracion de I.L.';
	elseif($estado=='arc') $estadolit = 'Pendiente de archivo';
	elseif($estado=='pub') $estadolit = 'Habilitado/Publicado';
	elseif($estado=='npu') $estadolit = 'No Habilitado';
	elseif($estado=='cat') $estadolit = 'En catastro';
	elseif($estado=='aut') $estadolit = 'Por autorizar revisión';
	elseif($estado=='ref') $estadolit = 'Refinanciado'; //este no se llega a mostrar
	else $estadolit = '???';
	$rec_lista[] = array('id_inf' => $row["id_informe_legal"],
						'clien' => $row["cliente"],
						'tbien' => $row["tipo_bien"],
						'con_il' => $row["con_inf_legal"],
						'estado' => $estado,
						'estadolit' => $estadolit,
						'nrocaso' => trim($row["nrocaso"]),
						'oficina' => trim($row["oficina"]),
						'fecha' => $aux);
	$i++;
}

}
	$smarty->assign('rec_lista',$rec_lista);
	
	
	$smarty->display('seguimiento.html');
	die();

?>
	
