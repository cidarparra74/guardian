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
	$carpeta_entrar="_main.php?action=canceladas_ver.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);

	//filtro de la ventana
	if(!isset($_SESSION['filtro_texto'])){
		//ponemos por defecto contratos de hoy
		$aux1 = date("d/m/Y");
		$_SESSION["filtro_fecha"]= $aux1;
		$_SESSION["filtro_fech2"]= $aux1;
		$_SESSION["filtro_texto"]= '';
	}
	
	if(isset($_REQUEST['buscar_boton'])){
		
		$filtro_texto= $_REQUEST['filtro_texto'];
		$filtro_fecha= $_REQUEST['filtro_fecha'];
		$filtro_fech2= $_REQUEST['filtro_fech2'];
	}//fin del if de buscar_boton
	else{
		$filtro_texto= $_SESSION["filtro_texto"];
		$filtro_fecha= $_SESSION["filtro_fecha"];
		$filtro_fech2= $_SESSION["filtro_fech2"];
	}	
	$del_filtro='';	
	//firma

	
	//texto
	if($filtro_texto != ''){
		$del_filtro= $del_filtro."AND pr.nombres LIKE '%$filtro_texto%' ";
	}
	
	//fecha
	if($filtro_fecha != '' && $filtro_fech2 == ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), oc.FechaCan, 103), 103) = '$filtro_fecha' ";
	}
	if($filtro_fecha != '' && $filtro_fech2 != ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), oc.FechaCan, 103), 103) >= CONVERT(DATETIME, '$filtro_fecha', 103) AND CONVERT(DATETIME, CONVERT(VARCHAR(10), oc.FechaCan, 103), 103) <= CONVERT(DATETIME, '$filtro_fech2', 103) ";
	}
		
		//variables de sesion
		$_SESSION["filtro_texto"]= $filtro_texto;
		$_SESSION["filtro_fecha"]= $filtro_fecha;
		$_SESSION["filtro_fech2"]= $filtro_fech2;
		
	//filtro de la ventana

	$smarty->assign('filtro_texto',$filtro_texto);
	$smarty->assign('filtro_fecha',$filtro_fecha);
	$smarty->assign('filtro_fech2',$filtro_fech2);

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	
	//adicionar Paso 0, elegir contrato nuevo por primera vez
//	if(isset($_REQUEST['adicionar'])){
//		include("./contratos/adicionar.php");
//	}
	
/****************fin de valores para la ventana*************************/
/***********************************************************************/


/**********************valores por defecto*************************/
/******************************************************************/

//recuperando los datos para la ventana
$miscancelads= array();
//$idusuario = $_SESSION['idusuario'];   
//cf.idusuario=$idusuario

//$id_oficina = $_SESSION['id_oficina'];
$id_almacen = $_SESSION['id_almacen'];
// mostrar las operaciones dadas de baja en un rango de fecha (oc.fechabaja) 
// que no esten devueltas (cf.fecha_devolucion is NULL)
$sql= "SELECT TOP 100 cf.carpeta, cf.operacion, cf.cuenta, tb.tipo_bien, 
co.nombres as usuario, pr.nombres as propietario, oc.instancia, 
CONVERT(VARCHAR(10), oc.FechaCan, 103) AS fecha , 
CONVERT(VARCHAR(10), oc.FechaCan, 108) AS hora, ofi.nombre as oficina
FROM carpetas cf 
INNER JOIN usuarios co ON cf.id_usuario = co.id_usuario 
INNER JOIN oficinas ofi ON ofi.id_oficina = cf.id_oficina 
INNER JOIN tipos_bien tb ON tb.id_tipo_bien = cf.id_tipo_carpeta AND tb.con_inf_legal = 'S' 
INNER JOIN propietarios pr ON pr.id_propietario = cf.id_propietario 
INNER JOIN operacionescan oc ON convert(varchar,oc.operacion) = cf.operacion 
WHERE ofi.id_almacen = $id_almacen AND cf.fecha_devolucion is NULL ".$del_filtro.
" ORDER BY cf.id_oficina, pr.nombres ";  //, cf.contenido_secAND oc.cuenta = cf.cuenta
//cf.id_oficina in (select id_oficina from oficinas where id_almacen=$id_almacen)
//$id_oficina
//echo $sql;
	$query= consulta($sql);
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

		$miscancelads[$i]= array('carpeta' => $row["carpeta"],
							'operacion' => $row["operacion"],
							'cuenta' => $row["cuenta"],
							'nrocaso' => $row["instancia"],
							'usuario' => $row["usuario"],
							'oficina' => $row["oficina"],
							'propietario' => $row["propietario"],
							'fecha' => $row["fecha"],
							'tipo_bien' => $row["tipo_bien"],
							'hora' => substr($row["hora"],0,5));
		$i++;
	}
//}

	$smarty->assign('miscancelads',$miscancelads);
	$smarty->display('canceladas_ver.html');
	die();

?>