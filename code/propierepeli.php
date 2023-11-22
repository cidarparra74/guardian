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
	$carpeta_entrar="_main.php?action=propierepeli.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);

	//filtro de la ventana

	if(isset($_REQUEST['buscar_boton'])){
		
		$filtro_texto= $_REQUEST['filtro_texto'];
		$filtro_fecha= $_REQUEST['filtro_fecha'];
		$filtro_fech2= $_REQUEST['filtro_fech2'];

	}else{
		$aux1 = date("d/m/Y");
		$filtro_texto= '';
		$filtro_fecha= $aux1;
		$filtro_fech2= $aux1;

	}	
	$del_filtro='';	

	//texto
	if($filtro_texto != ''){
		$del_filtro= $del_filtro." nombres LIKE '%$filtro_texto%' ";
	}
	
	//fecha
	if($filtro_fecha != '' && $filtro_fech2 == ''){
		if($del_filtro != ''){
		$del_filtro= $del_filtro." AND ";
		}
		$del_filtro= $del_filtro." CONVERT(DATETIME, CONVERT(VARCHAR(10), fechaeli, 103), 103) = '$filtro_fecha' ";
	}
	if($filtro_fecha != '' && $filtro_fech2 != ''){
		if($del_filtro != ''){
		$del_filtro= $del_filtro." AND ";
		}
		$del_filtro= $del_filtro." CONVERT(DATETIME, CONVERT(VARCHAR(10), fechaeli, 103), 103) >= CONVERT(DATETIME, '$filtro_fecha', 103) AND CONVERT(DATETIME, CONVERT(VARCHAR(10), fechaeli, 103), 103) <= CONVERT(DATETIME, '$filtro_fech2', 103) ";
	}

		
	//filtro de la ventana

	$smarty->assign('filtro_texto',$filtro_texto);
	$smarty->assign('filtro_fecha',$filtro_fecha);
	$smarty->assign('filtro_fech2',$filtro_fech2);

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	

	
/****************fin de valores para la ventana*************************/
/***********************************************************************/

/**********************valores por defecto*************************/
/******************************************************************/

//recuperando los datos para la ventana
$listado= array();

$sql= "SELECT id_propietario, nombres,  ci, 
	convert(varchar,fechaeli,103) as fecha,  motivoeli 
	FROM carpetas_bk  
	WHERE  $del_filtro  
	ORDER BY fecha, nombres ";  
//echo $sql;
	$query= consulta($sql);

	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		
		$listado[]= array('id' => $row["id_propietario"],
							'fecha' => $row["fecha"],
							'nombres' => $row["nombres"],
							'ci' => $row["ci"],
							'motivo' => $row["motivoeli"]);

	}


	$smarty->assign('listado',$listado);
	$smarty->display('propierepeli.html');
	die();

?>