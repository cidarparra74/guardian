<?php

//*****require('setup.php');
	//18/07/2015
	require_once('../lib/verificar.php');
require_once("../lib/setup.php");
$smarty = new bd;	

//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
$id_almacen = $_SESSION["id_almacen"];
$id_oficina = $_SESSION["id_oficina"];
	//href
	$carpeta_entrar="../code/_main.php?action=pendiente_archivo.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "pendiente_archivo";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//vemos si catastro es por oficina
		$sql = "SELECT TOP 1 enable_catofi FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_catofi = $row["enable_catofi"];
	$smarty->assign('enable_catofi',$enable_catofi);

	if($enable_catofi == 'S'){
		$filtrofi = "ofi.id_oficina = $id_oficina";
	}else{
		$filtrofi = "ofi.id_almacen = $id_almacen";
	}
	//recuperando la lista de usuarios corrientes  
	$sql= "SELECT us.id_usuario, us.nombres 
			FROM usuarios us, oficinas ofi 
			WHERE us.id_oficina = ofi.id_oficina AND $filtrofi AND us.activo='S'
			ORDER BY nombres ";
	$query = consulta($sql);
	$i=0;
	//$f_ids_usuario= array();
	$f_usuario= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		//$f_ids_usuario[$i]= $row["id_usuario"];
		$f_usuario[$i]= array('id'=>$row["id_usuario"], 'nombres'=>$row["nombres"]);
		$i++;
	}
	//$smarty->assign('f_ids_usuario',$f_ids_usuario);
	$smarty->assign('f_usuario',$f_usuario);
	
				
	//recuperando la lista de oficinas, para este usuario
	$sql= "SELECT id_oficina, nombre FROM oficinas ofi WHERE $filtrofi ORDER BY nombre ";
	$query = consulta($sql);
	$i=0;
	//$f_ids_oficina= array();
	$f_oficina= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_oficina[$i]= array('id'=>$row["id_oficina"], 'nombre'=>$row["nombre"]);
		$i++;
	}
	$smarty->assign('f_oficina',$f_oficina);
	
	//filtro de la ventana
	
	//si se presiona el boton de buscar
	if(isset($_REQUEST['buscar_boton'])){
		$f_id_usuario= $_REQUEST['filtro_usuario'];
		$f_id_oficina= $_REQUEST['filtro_oficina'];
		//$_SESSION["f_id_usuario"] = $f_id_usuario;
		//$_SESSION["f_id_oficina"] = $f_id_oficina;
	}else{
		$f_id_usuario= 'ninguno';
		$f_id_oficina= 'ninguno';
		if(isset($_SESSION['f_id_usuario'])){
			//$f_id_usuario= $_SESSION['f_id_usuario'];
			//$f_id_oficina= $_SESSION['f_id_oficina'];
		}
	}
	$smarty->assign('f_id_usuario',$f_id_usuario);
	$smarty->assign('f_id_oficina',$f_id_oficina);
	
	//armando la consulta
	$armar_consulta="";
	if($f_id_usuario != "ninguno"){
		if($f_id_usuario == "*")
			$armar_consulta= " AND il.id_us_comun > 0 ";
		else
			$armar_consulta= " AND il.id_us_comun='$f_id_usuario' ";
		
	}

	if($f_id_oficina != "ninguno"){	
		if($f_id_oficina == "*")
			$armar_consulta.=" AND ofi.id_oficina > 0 ";
		else
			$armar_consulta.=" AND ofi.id_oficina='$f_id_oficina' ";
	}

	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	

	/**************************************impresion***********************/
	/**************************************impresion***********************/
	if(isset($_REQUEST['ingresar'])){
		include("carpetas/ingresar.php");
	}
	
	if(isset($_REQUEST['eliminar'])){
		include("carpetas/quitarlista.php");
	}
	
	if(isset($_REQUEST['crea_carpeta'])){
		include("carpetas/crear_carpeta.php");
	}
	
	/***************************fin de las impresion***********************/
	/***************************fin de las impresion***********************/
		
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana

//$id_us_actual = $_SESSION['idusuario'];
/* ***************************************************************************************************************************** */

//$id_almacen = $_SESSION["id_almacen"];
//lista de carpetas para archivo
$rec_lista=array();
if($armar_consulta!=''){
	$sql = "SELECT TOP 200 il.id_informe_legal, pr.nombres as propietario, 
	il.cliente, tb.tipo_bien, tb.con_inf_legal, u.nombres , 
	CONVERT(VARCHAR(10),il.fecha_recepcion,103) AS fecha , ofi.nombre as oficina
	FROM informes_legales il 
	LEFT JOIN propietarios pr ON pr.id_propietario = il.id_propietario
	INNER JOIN tipos_bien tb ON  tb.id_tipo_bien = il.id_tipo_bien 
	INNER JOIN usuarios u ON id_us_comun=u.id_usuario 
	INNER JOIN oficinas ofi ON u.id_oficina = ofi. id_oficina 
	WHERE $filtrofi 
	AND (il.estado='cat' OR il.estado='apr' OR il.estado='ace'  OR il.estado='pub' OR il.estado='npu') 
	$armar_consulta 
	AND (il.sincarpeta = '?' OR il.sincarpeta = '')
	ORDER BY il.fecha_recepcion DESC ";	

	$query = consulta($sql);
	
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$rec_lista[] = array('id_inf' => $row["id_informe_legal"],
							'clien' => $row["cliente"],
							'propi' => $row["propietario"],
							'tbien' => $row["tipo_bien"],
							'con_il' => $row["con_inf_legal"],
							'nombu' => $row["nombres"],
							'oficina' => $row["oficina"],
							'fecha' => $row["fecha"]);
		$i++;
	}
	
}
$smarty->assign('rec_lista',$rec_lista);
	$smarty->display('pendiente_archivo.html');
	die();

?>
