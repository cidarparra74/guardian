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
	$carpeta_entrar="_main.php?action=excepcion_ver.php";
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
		$del_filtro= $del_filtro."AND il.cliente LIKE '%$filtro_texto%' ";
	}
	
	//fecha
	if($filtro_fecha != '' && $filtro_fech2 == ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), il.Fecha, 103), 103) = '$filtro_fecha' ";
	}
	if($filtro_fecha != '' && $filtro_fech2 != ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), il.Fecha, 103), 103) >= CONVERT(DATETIME, '$filtro_fecha', 103) AND CONVERT(DATETIME, CONVERT(VARCHAR(10), il.Fecha, 103), 103) <= CONVERT(DATETIME, '$filtro_fech2', 103) ";
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
$excepcions= array();

$id_almacen = $_SESSION['id_almacen'];

$sql= "select il.id_informe_legal, convert(varchar(10),il.fecha_recepcion,103) as fecha, il.exe_justifica, il.exe_aprobar,  
	pr.nombres as cliente, fi.nombre as oficina, us.nombres as usuario, ex.casos0, ex.casos1, ex.casos2
from informes_legales il
inner join propietarios pr on pr.id_propietario = il.id_propietario
inner join usuarios us on us.id_usuario = il.id_us_comun
inner join oficinas fi on fi.id_oficina = us.id_oficina
inner join 
( select id_informe_legal, sum(case when exce_tipo='P' then 1 end) casos0, sum(case when exce_tipo='T' then 1 end) casos1, sum(case when exce_tipo='-' then 1 end) casos2
  from excepciones group by id_informe_legal)
ex on ex.id_informe_legal = il.id_informe_legal
order by il.fecha_recepcion, us.nombres ";  
//echo $sql;
	$query= consulta($sql);
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

		$excepcions[$i]= array('documento' => $row["documento"],
							'obs' => $row["obs"],
							'nrocaso' => $row["nrocaso"],
							'usuario' => $row["nombres"],
							'oficina' => $row["nombre"],
							'propietario' => $row["cliente"],
							'fecha' => $row["limite"],
							'clase' => $row["clase"]);
		$i++;
	}
//}

	$smarty->assign('excepcions',$excepcions);
	$smarty->display('excepcion_ver.html');
	die();

?>