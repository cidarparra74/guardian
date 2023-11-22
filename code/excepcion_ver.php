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

	if(isset($_REQUEST['buscar_boton'])){
		
		$filtro_texto= $_REQUEST['filtro_texto'];
		$filtro_estado= $_REQUEST['filtro_estado'];
		$filtro_fecha= $_REQUEST['filtro_fecha'];
		$filtro_fech2= $_REQUEST['filtro_fech2'];
		$filtro_ofi= $_REQUEST['filtro_ofi'];
		$filtro_usr= $_REQUEST['filtro_usr'];
	}else{
		$aux1 = date("d/m/Y");
		$filtro_texto= '';
		$filtro_estado= 'P';
		$filtro_fecha= $aux1;
		$filtro_fech2= $aux1;
		$filtro_ofi= '*';
		$filtro_usr= '*';
	}	
	$del_filtro='';	
	//oficina
	if($filtro_ofi != '*'){
		$del_filtro= $del_filtro."AND ofi.id_oficina = '$filtro_ofi' ";
	}
	//usuario sol
	if($filtro_usr != '*'){
		$del_filtro= $del_filtro."AND il.id_us_comun = '$filtro_usr' ";
	}
	//texto
	if($filtro_texto != ''){
		$del_filtro= $del_filtro."AND il.cliente LIKE '%$filtro_texto%' ";
	}
	//estado
	if($filtro_estado == 'P'){
		$del_filtro= $del_filtro."AND (datalength(il.exe_aprobar)=0 OR il.exe_aprobar is null)  ";
	}else{
		$del_filtro= $del_filtro."AND datalength(il.exe_aprobar)>0  ";
	}
	
	//fecha
	if($filtro_fecha != '' && $filtro_fech2 == ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), il.Fecha, 103), 103) = '$filtro_fecha' ";
	}
	if($filtro_fecha != '' && $filtro_fech2 != ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), il.Fecha, 103), 103) >= CONVERT(DATETIME, '$filtro_fecha', 103) AND CONVERT(DATETIME, CONVERT(VARCHAR(10), il.Fecha, 103), 103) <= CONVERT(DATETIME, '$filtro_fech2', 103) ";
	}

		
	//filtro de la ventana

	$smarty->assign('filtro_estado',$filtro_estado);
	$smarty->assign('filtro_texto',$filtro_texto);
	$smarty->assign('filtro_fecha',$filtro_fecha);
	$smarty->assign('filtro_fech2',$filtro_fech2);
	$smarty->assign('filtro_ofi',$filtro_ofi);
	$smarty->assign('filtro_usr',$filtro_usr);

/**********************valores para la ventana*************************/
	
	if(isset($_REQUEST['levantar'])){
		
		require_once("ver_informe_legal/levantar_exe.php");
		die();
	}
	
	if(isset($_REQUEST['excepcion_boton'])){
		
		require_once("ver_informe_legal/levantando_exe.php");

	}
	
/**********************valores para la ventana*************************/
	
	
	//oficinas
	$id_almacen = $_SESSION['id_almacen'];
	$sql="SELECT id_oficina, nombre FROM oficinas WHERE id_almacen = '$id_almacen'";
	$query= consulta($sql);
	$oficinas = array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$oficinas[]= array('id' => $row["id_oficina"],
							'oficina' => $row["nombre"]);
	}
	$smarty->assign('oficinas',$oficinas);
	
	//usuarios que solicitaron excepcion
	$id_almacen = $_SESSION['id_almacen'];
	$sql="SELECT id_usuario, nombres FROM usuarios us
	INNER JOIN oficinas fi ON fi.id_oficina = us.id_oficina 
	WHERE fi.id_almacen = '$id_almacen' AND us.activo='S'";
	$query= consulta($sql);
	$usuarios = array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$usuarios[]= array('id' => $row["id_usuario"],
							'nombres' => $row["nombres"]);
	}
	$smarty->assign('usuarios',$usuarios);
	
/****************fin de valores para la ventana*************************/
/***********************************************************************/

//leemos parametros especiales
	$sql= "SELECT TOP 1 il_estado_fin  FROM opciones ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	// para el estado final del I.L (Rojo;amarillo;Verde)
	$il_estado=explode(';',$row["il_estado_fin"]);
/**********************valores por defecto*************************/
/******************************************************************/

//recuperando los datos para la ventana
$excepcions= array();

//$id_almacen = $_SESSION['id_almacen'];

$sql= "SELECT il.id_informe_legal, il.nrocaso, ofi.nombre, il.cliente, ex.clase, ex.obs, do.documento, il.bandera,
CONVERT(VARCHAR,ex.exce_limite,103) as limite, il.exe_justifica, il.exe_aprobar , us.nombres
FROM informes_legales il  
LEFT JOIN excepciones ex ON ex.id_informe_legal = il.id_informe_legal
LEFT JOIN documentos do ON do.id_documento = ex.id_documento
LEFT JOIN usuarios us ON us.id_usuario = ex.exce_resp
LEFT JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina AND ofi.id_almacen = '$id_almacen'
WHERE (ex.clase is not null or il.bandera = 'r' or il.bandera = 'a') $del_filtro
ORDER BY il.cliente, il.id_informe_legal, clase ";  
//echo $sql;
	$query= consulta($sql);
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		if($row["bandera"]=='r')     $bandera = $il_estado[0];
		elseif($row["bandera"]=='a') $bandera = $il_estado[1];
		elseif($row["bandera"]=='v') $bandera = $il_estado[2];
		elseif(isset($il_estado[3])) $bandera = $il_estado[3];
		
		$excepcions[$i]= array('id' => $row["id_informe_legal"],
							'documento' => $row["documento"],
							'obs' => $row["obs"],
							'nrocaso' => $row["nrocaso"],
							'usuario' => $row["nombres"],
							'oficina' => $row["nombre"],
							'propietario' => $row["cliente"],
							'fecha' => $row["limite"],
							'bandera' => $bandera,
							'clase' => $row["clase"],
							'justifica' => $row["exe_justifica"],
							'aprobar' => $row["exe_aprobar"]);
		$i++;
	}


	$smarty->assign('excepcions',$excepcions);
	$smarty->display('excepcion_ver.html');
	die();

?>