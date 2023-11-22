<?php
/*
   reporte de IL de un usuario o agencia por rango de fechas
*/
require_once("../lib/setup.php");
$smarty = new bd;

require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");



	//href
	$carpeta_entrar="_main.php?action=repinformes.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "repinformes";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	

		$filtro_usuario=  '*';
		$filtro_oficina=  '*';
		$aux1 = date("d/m/Y");
		$filtro_fecha= $aux1;
		$filtro_fech2= $aux1;

	$del_filtro='';	
	if(isset($_REQUEST['buscar_boton'])){
		$filtro_oficina= $_REQUEST['filtro_oficina'];
		$filtro_usuario= $_REQUEST['filtro_usuario'];
		$filtro_fecha= $_REQUEST['filtro_fecha'];
		$filtro_fech2= $_REQUEST['filtro_fech2'];
		//fechas
		if($filtro_fecha != '' && $filtro_fech2 == ''){
			$del_filtro= "  CONVERT(DATETIME, CONVERT(VARCHAR(10), il.fecha_aceptacion, 103), 103) = '$filtro_fecha' ";
		}
		if($filtro_fecha != '' && $filtro_fech2 != ''){
			$del_filtro= "  CONVERT(DATETIME, CONVERT(VARCHAR(10), il.fecha_aceptacion, 103), 103) >= CONVERT(DATETIME, '$filtro_fecha', 103) AND CONVERT(DATETIME, CONVERT(VARCHAR(10), il.fecha_aceptacion, 103), 103) <= CONVERT(DATETIME, '$filtro_fech2', 103) ";
		}
		//oficcina
		if($filtro_oficina != "*"){
			$del_filtro .= " AND ag.id_oficina = '$filtro_oficina' ";
		}
		
		//texto
		if($filtro_usuario != '*'){
			$del_filtro .= "AND il.id_us_comun = '$filtro_usuario' ";
			$smarty->assign('f_id_usuario',$filtro_usuario);
		}
	}//fin del if de buscar_boton
	

	//filtro de la ventana

	$smarty->assign('filtro_usuario',$filtro_usuario);
	$smarty->assign('filtro_oficina',$filtro_oficina);
	$smarty->assign('filtro_fecha',$filtro_fecha);
	$smarty->assign('filtro_fech2',$filtro_fech2);


/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	
	
/****************fin de valores para la ventana*************************/
/***********************************************************************/


/**********************valores por defecto*************************/
/******************************************************************/

//recuperando la lista de oficinas, para este usuario
	$sql= "SELECT id_oficina, nombre FROM oficinas WHERE id_almacen = ".$_SESSION["id_almacen"]." ORDER BY nombre ";
	$query = consulta($sql);
	$i=0;
	//$f_ids_oficina= array();
	$f_oficina= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_oficina[$i]= array('id'=>$row["id_oficina"], 'nombre'=>$row["nombre"]);
		$i++;
	}
	$smarty->assign('f_oficina',$f_oficina);
	
	$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('logo',$resultado['logo01']);
	
	
	$id_almacen = $_SESSION["id_almacen"];
	//$id_oficina = $_SESSION["id_oficina"];
	$sql= "SELECT us.id_usuario, us.nombres 
			FROM usuarios us, oficinas ofi 
			WHERE  us.id_oficina = ofi.id_oficina AND ofi.id_almacen = $id_almacen AND us.activo='S'
			ORDER BY nombres ";
	//$sql= "SELECT us.id_usuario, us.nombres FROM usuarios us WHERE us.id_oficina = $id_oficina ORDER BY nombres ";
	$query = consulta($sql);
	$i=0;
	$f_ids_usuario= array();
	//$f_usuario= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_ids_usuario[$i]= array('id_usuario' =>$row["id_usuario"],
								'nombres' =>$row["nombres"]);
		
		$i++;
	}
	$smarty->assign('f_ids_usuario',$f_ids_usuario);
	//
//recuperando los datos para la ventana
$listado= array();

if($del_filtro != ''){
 
$sql= "SELECT pr.nombres, tb.tipo_bien, CONVERT(VARCHAR(10), il.fecha_aceptacion, 103) as fecha, 
ag.nombre as oficina, us.nombres as usuario, il.nrocaso 
FROM informes_legales  il
LEFT JOIN propietarios pr on pr.id_propietario = il.id_propietario 
INNER JOIN oficinas ag on ag.id_oficina = il.id_oficina
INNER JOIN tipos_bien tb on tb.id_tipo_bien = il.id_tipo_bien
INNER JOIN usuarios us on us.id_usuario = il.id_us_comun
WHERE  $del_filtro
ORDER BY fecha";
	$query= consulta($sql);
	
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

		$listado[]= array('nombres' => $row["nombres"],
							'tipo_bien' => $row["tipo_bien"],
							'fecha' => $row["fecha"],
							'oficina' => $row["oficina"],
							'usuario' => $row["usuario"],
							'nrocaso' => $row["nrocaso"]);
		
	}

	$smarty->assign('listado',$listado);
	$smarty->display('reportes\repinformes2.html');
	die();
}
	
	$smarty->display('repinformes.html');
	die();

?>