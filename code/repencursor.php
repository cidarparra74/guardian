<?php
/*
   contrato para ver a nivel usuario
*/
require_once("../lib/setup.php");
$smarty = new bd;
//require_once('../lib/conexionSEC.php');
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//echo $glogin;
	//preparamos datos del usuario


	//href
	$carpeta_entrar="_main.php?action=repencursor.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "repencursor";
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
		//oficcina
		if($filtro_oficina != "*"){
			$del_filtro= " AND ofi.id_oficina = '$filtro_oficina' ";
		}
		
		//texto
		if($filtro_usuario != '*'){
			$del_filtro .= "AND ile.id_us_comun = '$filtro_usuario' ";
			$smarty->assign('f_id_usuario',$filtro_usuario);
		}
		//fechas
		if($filtro_fecha != '' && $filtro_fech2 == ''){
			$del_filtro= $del_filtro." AND CONVERT(DATETIME, CONVERT(VARCHAR(10), ile.fecha_aceptacion, 103), 103) = '$filtro_fecha' ";
		}
		if($filtro_fecha != '' && $filtro_fech2 != ''){
			$del_filtro= $del_filtro." AND CONVERT(DATETIME, CONVERT(VARCHAR(10), ile.fecha_aceptacion, 103), 103) >= CONVERT(DATETIME, '$filtro_fecha', 103) AND CONVERT(DATETIME, CONVERT(VARCHAR(10), ile.fecha_aceptacion, 103), 103) <= CONVERT(DATETIME, '$filtro_fech2', 103) ";
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
 /*
$sql= "SELECT ofi.nombre as oficina, ofi.id_oficina, us.nombres as usuario, tb.tipo_bien, count(*) as cant 
FROM informes_legales ile 
inner join usuarios us on us.id_usuario = ile.id_us_comun
inner join oficinas ofi on ofi.id_oficina =  us.id_oficina
inner join tipos_bien tb on tb.id_tipo_bien = ile.id_tipo_bien
WHERE  ofi.id_almacen = ".$_SESSION["id_almacen"]." $del_filtro 
GROUP BY ofi.id_oficina, ofi.nombre, tb.tipo_bien, us.id_usuario, us.nombres
ORDER BY ofi.id_oficina, us.id_usuario, tb.tipo_bien "; 

$sql= "SELECT ofi.id_oficina, ofi.nombre as oficina, us.nombres as usuario, tb.tipo_bien, 
CASE ile.estado
WHEN 'rec' THEN '01'
WHEN 'sol' THEN '02'
WHEN 'apr' THEN '03'
WHEN 'ace' THEN '04'
WHEN 'npu' THEN '05'
WHEN 'pub' THEN '06'
ELSE ile.estado
END as nro , 
COUNT(*) as cant 
FROM informes_legales ile 
inner join usuarios us on us.id_usuario = ile.id_us_comun
inner join oficinas ofi on ofi.id_oficina =  us.id_oficina
inner join tipos_bien tb on tb.id_tipo_bien =  ile.id_tipo_bien  
WHERE tb.con_inf_legal = 'S' $del_filtro
GROUP BY ofi.id_oficina, ofi.nombre, us.nombres, tb.tipo_bien, ile.estado 
ORDER BY ofi.nombre, us.nombres, tb.tipo_bien, nro";
*/
$sql= "SELECT id_oficina, oficina, usuario, tipo_bien, nro, COUNT(*) as cant
FROM
(SELECT ofi.id_oficina, ofi.nombre as oficina, us.nombres as usuario, tb.tipo_bien, 
CASE  
WHEN ile.estado = 'rec' THEN '01' 
WHEN ile.estado = 'sol' THEN '02' 
WHEN ile.estado = 'apr' THEN '03' 
WHEN ile.estado = 'ace' THEN '04' 
WHEN ile.estado = 'npu' THEN '05' 
WHEN ile.estado = 'pub' and ile.bandera = 'r' THEN '06' 
WHEN ile.estado = 'pub' and ile.bandera = 'a' THEN '07'
WHEN ile.estado = 'pub' and ile.bandera = 'v' THEN '08'
ELSE '09' END as nro 
FROM informes_legales ile 
inner join usuarios us on us.id_usuario = ile.id_us_comun 
inner join oficinas ofi on ofi.id_oficina = us.id_oficina 
inner join tipos_bien tb on tb.id_tipo_bien = ile.id_tipo_bien AND tb.con_inf_legal = 'S' 
WHERE tb.con_inf_legal = 'S' $del_filtro ) lista 
GROUP BY id_oficina, oficina, usuario, tipo_bien, nro 
ORDER BY oficina, usuario, tipo_bien, nro ";
	$query= consulta($sql);
	
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

		$listado[]= array('id' => $row["id_oficina"],
							'nro' => $row["nro"],
							'oficina' => $row["oficina"],
							'usuario' => $row["usuario"],
							'tipo_bien' => $row["tipo_bien"],
							'cant' => $row["cant"]);
		
	}
	$listado[]= array('id' => '00',
							'nro' => 0,
							'oficina' => '-x-',
							'usuario' => '',
							'cant' => 0); //esto para q salga la ultima suma
	$smarty->assign('listado',$listado);
	$smarty->display('reportes\repencurso2.html');
	die();
}
	
	$smarty->display('repencursor.html');
	die();

?>