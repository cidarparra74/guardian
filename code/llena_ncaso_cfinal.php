<?php
//elaborar_informe
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
//require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="../code/_main.php?action=llena_ncaso_cfinal.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	$alert = '';
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
//verificar si esta habilitado el WS
		$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$enable_ws = $row["enable_ws"];
		$smarty->assign('enable_ws',$row["enable_ws"]);
	

	//si se presiona el boton de buscar
	if(isset($_REQUEST['buscar_boton'])){
		$f_cliente= $_REQUEST['filtro_cliente'];
		$f_ci_cliente= $_REQUEST['filtro_ci_cliente'];
		$_SESSION["inf_cliente"]=$f_cliente;
		$_SESSION["inf_ci_cliente"]=$f_ci_cliente;
	}else{
		if(isset($_SESSION["inf_cliente"])){
		$f_cliente=$_SESSION["inf_cliente"];
		$f_ci_cliente=$_SESSION["inf_ci_cliente"];
		}else{
		$f_cliente='';
		$f_ci_cliente='';
		}
	}

	$smarty->assign('f_cliente',$f_cliente);
	$smarty->assign('f_ci_cliente',$f_ci_cliente);
	
	//armando la consulta
	$armar_consulta="";
	if($f_cliente != ""){
		$armar_consulta.= "AND ile.cliente LIKE '%$f_cliente%' ";
	}
	if($f_ci_cliente != ""){
		$armar_consulta.= "AND ile.ci_cliente LIKE '%$f_ci_cliente%' ";
	}
/****************fin de valores para la ventana*************************/
//---------------------
	if(isset($_REQUEST['idc'])){
		include("./informe_legal/llena_variables.php");
	}
	if(isset($_REQUEST['idcaso'])){
		include("./informe_legal/llenando_variables.php");
	}
	
	
/**********************valores por defecto*************************/
//buscar todos los nrocaso existentes en informes_legales y 
		//que esten en tabla NCASO_CFINAL(NROCASO, IDFINAL) con idfinal=0

	$id_almacen = $_SESSION["id_almacen"];

	if($armar_consulta=="")	{
		$sql= "SELECT DISTINCT convert(int,ile.nrocaso) nro, ile.cliente
		FROM informes_legales ile INNER JOIN usuarios us ON us.id_usuario = ile.id_us_comun 
		INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
		INNER JOIN ncaso_cfinal nc ON ile.nrocaso = nc.nrocaso
		WHERE nc.idfinal='0' AND ile.nrocaso<>'' AND ofi.id_almacen = '$id_almacen' ORDER BY nro";
		//echo $sql;
		$query = consulta($sql);
		$nrocasos= array();
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$nrocasos[] = array('nrocaso'=>$row["nro"],
								'cliente'=>$row["cliente"]);
		}
		//numeros sin informe legal
		/* $sql= "SELECT DISTINCT convert(int,nrocaso) nro
		FROM ncaso_cfinal 
		WHERE idfinal='0' AND nrocaso NOT IN (
			SELECT nrocaso FROM informes_legales WHERE nrocaso IS NOT NULL ) ORDER BY nro";
		$query = consulta($sql);
		while($row2= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$nombres='';
			$nrocaso = $row2["nro"];
			if($enable_ws=='C'){
				require("../code/ws_datoscliente.php");
			}
			if($nombres==''){
				$nombres = "(Sin informe legal)";
			}
			$nrocasos[] = array('nrocaso'=>$nrocaso,
								'cliente'=>$nombres,
								'tipoope'=>'');
		} */
	}else{
		//filtro de busqueda de cliente activado
		$sql= "SELECT DISTINCT convert(int,ile.nrocaso) nro, ile.cliente
		FROM informes_legales ile INNER JOIN usuarios us ON us.id_usuario = ile.id_us_comun 
		INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
		INNER JOIN ncaso_cfinal nc ON ile.nrocaso = nc.nrocaso
		WHERE nc.idfinal='0' AND ile.nrocaso<>'' $armar_consulta
		AND ofi.id_almacen = '$id_almacen' ORDER BY nro";
		//echo $sql;
		$query = consulta($sql);
		$nrocasos= array();
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$nrocasos[] = array('nrocaso'=>$row["nro"],
								'cliente'=>$row["cliente"]);
		}
	}
		$smarty->assign('nrocasos',$nrocasos);
//echo 	$sql;
	$smarty->assign('nrocasos',$nrocasos);
	$smarty->assign('alert',$alert);

	$smarty->display('llena_ncaso_cfinal.html');
	die();

?>