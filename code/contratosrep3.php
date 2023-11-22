<?php
/*
   contrato para ver a nivel usuario
*/
require_once("../lib/setup.php");
$smarty = new bd;

require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	//href
	$carpeta_entrar="_main.php?action=contratosrep3.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "contratosrep3";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	
	//filtro de la ventana
	$aux1 = date("d/m/Y");
	
	$filtro_alma= '*';
	$filtro_fecha= $aux1;
	$filtro_fech2= $aux1;
	
	if(isset($_REQUEST['listar_boton'])){

		$filtro_alma= $_REQUEST['filtro_alma'];
		$filtro_fecha= $_REQUEST['filtro_fecha'];
		$filtro_fech2= $_REQUEST['filtro_fech2'];

	}

	$del_filtro='';	
	
	//almacen
	if($filtro_alma != '*'){
		$del_filtro= "AND ofi.id_almacen = '$filtro_alma' ";
	}
	
	/*
	//oficina
	if($filtro_ban != '*' ){
		$del_filtro= $del_filtro."AND ba.id_banca = '$filtro_ban' ";
	}
	*/
/*	
	//fecha
	if($filtro_fecha != '' && $filtro_fech2 == ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), cf.fechahora, 103), 103) = '$filtro_fecha' ";
	}
	if($filtro_fecha != '' && $filtro_fech2 != ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), cf.fechahora, 103), 103) >= CONVERT(DATETIME, '$filtro_fecha', 103) AND CONVERT(DATETIME, CONVERT(VARCHAR(10), cf.fechahora, 103), 103) <= CONVERT(DATETIME, '$filtro_fech2', 103) ";
	}
*/
	//filtro de la ventana

	$smarty->assign('filtro_alma',$filtro_alma);
	//$smarty->assign('filtro_ban',$filtro_ban);
	//$smarty->assign('filtro_tip',$filtro_tip);
	$smarty->assign('filtro_fecha',$filtro_fecha);
	$smarty->assign('filtro_fech2',$filtro_fech2);

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	
	/*
	require_once('../lib/conexionMNU.php');
	
	$bancas = array();
	$sql= "SELECT id_banca, banca FROM bancas ";  
		$query= consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$bancas[]= array('id' => $row["id_banca"],
								'banca' => $row["banca"]);
		}
	$smarty->assign('bancas',$bancas);
	*/
	//recintos
	$sql= "SELECT id_almacen, nombre FROM almacen ";  
	$query= consulta($sql);
	$almacens = array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$almacens[]= array('id' => $row["id_almacen"],
							'titulo' => $row["nombre"]);
	}
	$smarty->assign('almacens',$almacens);
	
	
if(!isset($_REQUEST['listar_boton'])){
	
		//$smarty->assign('miscontratos',$miscontratos);
		$smarty->display('contratosrep3.html');
		die();
}

/****************fin de valores para la ventana*************************/
/***********************************************************************/


/**********************valores por defecto*************************/
/******************************************************************/

//recuperando los datos para la ventana
$miscontratos= array();

$sql= "SELECT DISTINCT ofi.nombre, nc.nrocaso, il.cliente, il.ci_cliente, tb.tipo_bien,
max(nc.importeprestamo) as importe, max(nc.monedaprestamo) as moneda
FROM ncaso_cfinal nc 
INNER JOIN informes_legales il ON il.nrocaso = nc.nrocaso
INNER JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien
INNER JOIN usuarios us ON us.id_usuario = il.id_us_comun
INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina
WHERE nc.idfinal > 0 $del_filtro
GROUP BY ofi.nombre, nc.nrocaso, il.cliente, il.ci_cliente, tb.tipo_bien, ofi.id_almacen
ORDER BY ofi.nombre, tb.tipo_bien, il.ci_cliente  "; 

// echo $sql;
	
	$query= consulta($sql);
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$mon = $row["moneda"];
		$miscontratos[$i]= array('oficina' => $row["nombre"],
							'nrocaso' => $row["nrocaso"],
							'cliente' => $row["cliente"],
							'ci' => $row["ci_cliente"],
							'tipo' => $row["tipo_bien"],
							'monto' => $row["importe"].' '. $mon);
		$i++;
	}

	$smarty->assign('miscontratos',$miscontratos);
	$smarty->display('contratosrep3.html');
	die();

?>