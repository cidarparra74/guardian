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
	$carpeta_entrar="_main.php?action=contratosrep.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "contratosrep";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	
	//filtro de la ventana
	$filtro_alma= '*';
	$filtro_ban= '*';
	$filtro_tip= '1';
	
	if(isset($_REQUEST['listar_boton'])){

		$filtro_alma= $_REQUEST['filtro_alma'];
		$filtro_ban= $_REQUEST['filtro_ban'];
		$filtro_tip= $_REQUEST['filtro_tip'];

	}//fin del if de buscar_boton

	$del_filtro='';	
	
	//almacen
	if($filtro_alma != '*'){
		$del_filtro= $del_filtro."AND al.id_almacen = '$filtro_alma' ";
	}
	
	//oficina
	if($filtro_ban != '*' ){
		$del_filtro= $del_filtro."AND ba.id_banca = '$filtro_ban' ";
	}
	
	//fecha
	/*
	if($filtro_fecha != '' && $filtro_fech2 == ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), cf.fechahora, 103), 103) = '$filtro_fecha' ";
	}
	if($filtro_fecha != '' && $filtro_fech2 != ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), cf.fechahora, 103), 103) >= CONVERT(DATETIME, '$filtro_fecha', 103) AND CONVERT(DATETIME, CONVERT(VARCHAR(10), cf.fechahora, 103), 103) <= CONVERT(DATETIME, '$filtro_fech2', 103) ";
	}
	*/	
		//variables de sesion
		
	//filtro de la ventana

	$smarty->assign('filtro_alma',$filtro_alma);
	$smarty->assign('filtro_ban',$filtro_ban);
	$smarty->assign('filtro_tip',$filtro_tip);
	//$smarty->assign('filtro_fecha',$filtro_fecha);
	//$smarty->assign('filtro_fech2',$filtro_fech2);

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	
	require_once('../lib/conexionMNU.php');

	//datos para los combos, desde guardian
	
	//recintos
	$sql= "SELECT id_almacen, nombre FROM almacen ";  
	$query= consulta($sql);
	$almacens = array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$almacens[]= array('id' => $row["id_almacen"],
							'titulo' => $row["nombre"]);
	}
	$smarty->assign('almacens',$almacens);

	$bancas = array();
	$sql= "SELECT id_banca, banca FROM bancas ";  
		$query= consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$bancas[]= array('id' => $row["id_banca"],
								'banca' => $row["banca"]);
		}
	$smarty->assign('bancas',$bancas);
	
if(!isset($_REQUEST['listar_boton'])){
	
		//$smarty->assign('miscontratos',$miscontratos);
		$smarty->display('contratosrep.html');
		die();
}

/****************fin de valores para la ventana*************************/
/***********************************************************************/


/**********************valores por defecto*************************/
/******************************************************************/

//recuperando los datos para la ventana
$miscontratos= array();

if($filtro_tip==1){
	$sql= "select al.nombre as recinto, ba.banca, us.nombres as asesor, ofi.nombre as oficina ,
	count(*) as cantidad
	from informes_legales il 
	inner join usuarios us on us.id_usuario = il.id_us_comun
	inner join oficinas ofi on ofi.id_oficina = us.id_oficina
	inner join ncaso_cfinal nc on il.nrocaso = nc.nrocaso
	inner join almacen al on al.id_almacen = ofi.id_almacen
	left join bancas ba on ba.id_banca = nc.id_banca
	where nc.idfinal > 0 $del_filtro 
	group by al.nombre, ofi.nombre, ba.banca, us.nombres  "; 
}elseif($filtro_tip==2){ 
	$sql= "select al.nombre as recinto, ba.banca, ofi.nombre as oficina, '(todos)' as asesor, 
	count(*) as cantidad
	from informes_legales il 
	inner join usuarios us on us.id_usuario = il.id_us_comun
	inner join oficinas ofi on ofi.id_oficina = us.id_oficina
	inner join ncaso_cfinal nc on il.nrocaso = nc.nrocaso
	inner join almacen al on al.id_almacen = ofi.id_almacen
	left join bancas ba on ba.id_banca = nc.id_banca
	where nc.idfinal > 0 $del_filtro
	group by al.nombre, ofi.nombre, ba.banca"; 
}else{
	$sql= "select al.nombre as recinto, ba.banca, '(todas)' as oficina, '(todos)' as asesor, 
	count(*) as cantidad 
	from informes_legales il 
	inner join usuarios us on us.id_usuario = il.id_us_comun
	inner join oficinas ofi on ofi.id_oficina = us.id_oficina
	inner join ncaso_cfinal nc on il.nrocaso = nc.nrocaso
	inner join almacen al on al.id_almacen = ofi.id_almacen
	left join bancas ba on ba.id_banca = nc.id_banca
	where nc.idfinal > 0 $del_filtro
	group by al.nombre, ba.banca"; 
}
// echo $sql;
	
	$query= consulta($sql);
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

		$miscontratos[$i]= array('recinto' => $row["recinto"],
							'banca' => $row["banca"],
							'asesor' => $row["asesor"],
							'oficina' => $row["oficina"],
							'cantidad' => $row["cantidad"]);
		$i++;
	}

	$smarty->assign('miscontratos',$miscontratos);
	$smarty->display('contratosrep.html');
	die();

?>