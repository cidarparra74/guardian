<?php
//elaborar_informe
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="../code/_main.php?action=repperitosdet.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "repperitosdet";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	
$armar_consulta="";
	//si se presiona el boton de buscar
	if(isset($_REQUEST['imprimir_boton'])){
		$f_nini= $_REQUEST['filtro_nini'];
		$f_nfin= $_REQUEST['f_nfin'];
		$f_cliente= $_REQUEST['filtro_cliente'];
		$f_ci_cliente= $_REQUEST['filtro_ci_cliente'];
		$id_perito= $_REQUEST['id_perito'];
		$id_oficina= $_REQUEST['id_oficina'];

	//armando la consulta
	
	if($f_nini != "" && $f_nfin != ""){
		$armar_consulta.= "AND il.id_informe_legal >= '$f_nini' AND il.id_informe_legal <= '$f_nfin' ";
	}elseif($f_nini != ""){
		$armar_consulta.= "AND il.id_informe_legal >= '$f_nini' ";
	}
	
	
	if($id_oficina != "*"){
		$armar_consulta.= "AND us.id_oficina='$id_oficina' ";
	}elseif($id_oficina == "*"){
		$armar_consulta.= "AND us.id_oficina > '0' ";
	}
	
	if($id_perito != "*"){
		$armar_consulta.= "AND il.id_perito='$id_perito' ";
	}elseif($id_perito == "*"){
		$armar_consulta.= "AND il.id_perito > '0' ";
	}
	
	if($f_cliente != ""){
		$armar_consulta.= "AND cliente LIKE '%$f_cliente%' ";
	}
	if($f_ci_cliente != ""){
		$armar_consulta.= "AND ci_cliente LIKE '%$f_ci_cliente%' ";
	}

		$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('logo',$resultado['logo01']);
	
	}else{
	$id_oficina = $_SESSION["id_oficina"];
	
	//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//$enable_ws = $row["enable_ws"];
	$smarty->assign('enable_ws',$row["enable_ws"]);
	/*
	//recuperando los tipos de bien
	$sql= "SELECT * FROM tipos_bien ORDER BY tipo_bien ";
	$query = consulta($sql);
	$tiposbien=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tiposbien[]= array('id' => $row["id_tipo_bien"],
							'descri' => $row["tipo_bien"]);
	}
	$smarty->assign('tiposbien',$tiposbien);
	*/
	$id_almacen = $_SESSION["id_almacen"];
	//buscamos peritos que sepan de la garantia y sean de la oficina
	$sql = "SELECT pe.id_persona, pe.apellidos, pe.nombres
	FROM personas pe WHERE tipo_rol='P' ORDER BY pe.apellidos";
	$query = consulta($sql);
	$peritos=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$peritos[]=array('id' => $row["id_persona"],
							'nombres' => $row["apellidos"].' '.$row["nombres"]);
	}
	$smarty->assign('peritos',$peritos);
	}
	
	//recuperando los tipos de bien
	$sql= "SELECT * FROM oficinas WHERE id_almacen = '$id_almacen' ORDER BY nombre ";
	$query = consulta($sql);
	$oficinas=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$oficinas[]= array('id' => $row["id_oficina"],
							'oficina' => $row["nombre"]);
	}
	$smarty->assign('oficinas',$oficinas);
	
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/

//recuperando los datos para la ventana use

// solo se muestran los datos de la oficina correspondiente y al responsable
	
$asignados= array();
if($armar_consulta != ""){
	$sql= "SELECT convert(varchar, il.fecha,103) as fecha, 
	convert(varchar,fecha_aceptacion,103) as fecha_ace, 
	il.id_informe_legal, il.ci_cliente, il.perito_obs, us.id_oficina,
	il.cliente, il.nrocaso, tb.tipo_bien, us.nombres as asesor,
	pe.nombres, pe.apellidos, il.id_perito, ofi.nombre as oficina
	FROM informes_legales il
	INNER JOIN tipos_bien tb ON il.id_tipo_bien = tb.id_tipo_bien
	INNER JOIN personas pe ON il.id_perito = pe.id_persona 
	INNER JOIN usuarios us ON us.id_usuario = il.id_us_comun 
	LEFT JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina
	WHERE  il.id_perito<>0 AND tb.con_perito='S' $armar_consulta
	ORDER BY ofi.nombre, il.id_perito, il.nrocaso ";
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$asignados[]= array('id_informe_legal'=>$row["id_informe_legal"],
							'tipo_bien'=>$row["tipo_bien"],
							'cliente'=>$row["cliente"],
							'nrocaso'=>trim($row["nrocaso"]),
							'id_perito'=>$row['id_perito'],
							'fecha'=>$row['fecha'],
							'fecha_ace'=>$row['fecha_ace'],
							'ci'=>$row['ci_cliente'],
							'obs'=>$row['perito_obs'],
							'oficina'=>$row['oficina'],
							'id_oficina'=>$row['id_oficina'],
							'asesor'=>$row['asesor'],
							'perito'=>$row['apellidos'].' '.$row['nombres']);
	}
	$smarty->assign('asignados',$asignados);
	$smarty->display('reportes/repperitosdet2.html');
	die();
}
	$smarty->assign('asignados',$asignados);

	$smarty->display('repperitosdet.html');
	die();

?>
