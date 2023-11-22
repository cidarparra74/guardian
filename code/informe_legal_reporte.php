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
	$carpeta_entrar="_main.php?action=informe_legal_reporte.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);

	//filtro de la ventana
	if(!isset($_REQUEST['filtro_sol'])){
		$id_almacen = $_SESSION["id_almacen"];
		$aux1 = date("d/m/Y");
		$filtro_fecha= $aux1;
		$filtro_fech2= $aux1;
		$filtro_sol= '*';
		//$filtro_abo= '*';
		$reporte= '1';
		$estado= '*';
	}else{
		//$filtro_abo= $_REQUEST['filtro_abo'];
		$filtro_sol= $_REQUEST['filtro_sol'];
		$filtro_fecha= $_REQUEST['filtro_fecha'];
		$filtro_fech2= $_REQUEST['filtro_fech2'];
		$id_almacen= $_REQUEST['id_almacen'];
		$reporte= $_REQUEST['reporte'];
		$estado= $_REQUEST['estado'];
	}
	
	$del_filtro='';	
	
if(isset($_REQUEST['buscar_boton'])){
	//usuario sol
	if($filtro_sol != '*'){
		$del_filtro .= "AND il.id_us_comun = '$filtro_sol' ";
	}
	
	//usuario abo
//	if($filtro_abo != '*'){
//		$del_filtro .= "AND il.usr_acep = '$filtro_abo' ";
//	}
	
	//fechas
	if($filtro_fecha != '' && $filtro_fech2 == ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), il.fecha_aceptacion, 103), 103) = '$filtro_fecha' ";
	}
	if($filtro_fecha != '' && $filtro_fech2 != ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), il.fecha_aceptacion, 103), 103) >= CONVERT(DATETIME, '$filtro_fecha', 103) AND CONVERT(DATETIME, CONVERT(VARCHAR(10), il.fecha_aceptacion, 103), 103) <= CONVERT(DATETIME, '$filtro_fech2', 103) ";
	}
	//estadozs
	if($estado != '*'){
		$del_filtro .= "AND estado = '$estado' ";
	}
}	

	$smarty->assign('filtro_sol',$filtro_sol);
//	$smarty->assign('filtro_abo',$filtro_abo);
	$smarty->assign('filtro_fecha',$filtro_fecha);
	$smarty->assign('filtro_fech2',$filtro_fech2);
	$smarty->assign('id_almacen',$id_almacen);
	$smarty->assign('reporte',$reporte);
	$smarty->assign('estado',$estado);

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	// recintos
	$sql= "SELECT id_almacen, nombre FROM almacen ORDER BY nombre ";
	$query = consulta($sql);
	$recintos= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$recintos[]= array('id_almacen' =>$row["id_almacen"],
								'nombre' =>$row["nombre"]);
		
	}
	$smarty->assign('recintos',$recintos);
	//usuarios sol
	$sql= "SELECT us.id_usuario, us.nombres 
		FROM usuarios us, oficinas ofi 
		WHERE us.id_oficina = ofi.id_oficina AND ofi.id_almacen = $id_almacen AND us.activo='S'
		ORDER BY nombres ";
	$query = consulta($sql);
	$usuariosol= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$usuariosol[]= array('id_usuario' =>$row["id_usuario"],
								'nombres' =>$row["nombres"]);
		
	}
	$smarty->assign('usuariosol',$usuariosol);
	/*
	//usuarios abo
	$sql= "select id_perfil_abo from opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_perfil = $row["id_perfil_abo"];
	
	$sql= "SELECT us.id_usuario, us.nombres 
		FROM usuarios us
		INNER JOIN oficinas ofi ON us.id_oficina = ofi.id_oficina
		WHERE ofi.id_almacen = $id_almacen AND us.id_perfil='$id_perfil'
		ORDER BY nombres ";
	$query = consulta($sql);
	$usuarioabo= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$usuarioabo[]= array('id_usuario' =>$row["id_usuario"],
								'nombres' =>$row["nombres"]);
		
	}
	$smarty->assign('usuarioabo',$usuarioabo);
	*/
/**********************valores por defecto*************************/
/******************************************************************/


//recuperando los datos para la ventana
$listado= array();
if($del_filtro!=''){
	if($reporte=='1'){
		$sql= "SELECT il.id_informe_legal, convert(varchar(10),
		il.fecha_solicitud,103) as fsol, il.estado, 
		convert(varchar(10),il.fecha_aceptacion,103) as face, 
		us.nombres as solicita, ab.nombres as abogado,
		pr.nombres as cliente, pr.ci, fi.nombre as oficina, 
		tb.tipo_bien, al.nombre as almacen
		FROM informes_legales il
		inner join tipos_bien tb on tb.id_tipo_bien = il.id_tipo_bien
		inner join usuarios us on us.id_usuario = il.id_us_comun
		inner join usuarios ab on ab.id_usuario = il.usr_acep
		inner join oficinas fi on fi.id_oficina = us.id_oficina
		inner join almacen al on al.id_almacen = fi.id_almacen
		inner join propietarios pr on pr.id_propietario = il.id_propietario
		WHERE fi.id_almacen = '$id_almacen' ".$del_filtro.
		"ORDER BY al.nombre, fi.nombre, ab.nombres, il.estado, il.fecha_solicitud ";  
		//echo $sql;
		$query= consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
				if($row["estado"] == 'ace')
					$estadolit = 'En elaboración';
				elseif($row["estado"] == 'pub')
					$estadolit = 'Publicado';
				elseif($row["estado"] == 'npu')
					$estadolit = 'Despublicado';
				elseif($row["estado"] == 'sol')
					$estadolit = 'Solicitado';
				elseif($row["estado"] == 'apr')
					$estadolit = 'Autorizado';
				else 
					$estadolit = '?';
				$listado[]= array(  'id' => $row["id_informe_legal"],
									'abogado' => $row["abogado"],
									'estado' => $estadolit,
									'face' => $row["face"],
									'solicita' => $row["solicita"],
									'cliente' => $row["cliente"],
									'ci' => $row["ci"],
									'tipo_bien' => $row["tipo_bien"],
									'fsol' => $row["fsol"],
									'almacen' => $row["almacen"],
									'oficina' => $row["oficina"]);
		}

	}elseif($reporte=='2'){
	//resumido
				$sql= "SELECT al.nombre AS almacen, fi.nombre as oficina, ab.nombres, il.estado, count(*) as casos
		FROM informes_legales il
		inner join usuarios us on us.id_usuario = il.id_us_comun
		inner join usuarios ab on ab.id_usuario = il.usr_acep
		inner join oficinas fi on fi.id_oficina = us.id_oficina
		inner join almacen al on al.id_almacen = fi.id_almacen
		WHERE fi.id_almacen = '$id_almacen' ".$del_filtro.
		"GROUP BY al.nombre, fi.nombre, ab.nombres, il.estado
		ORDER BY al.nombre, fi.nombre, ab.nombres, il.estado";  
		
		$sql= "SELECT almacen, oficina, nombres, SUM(ace) a, SUM(pub) b, SUM(npu) c FROM (
		SELECT al.nombre AS almacen, fi.nombre as oficina, 
			ab.nombres, count(*) as ace, 0 as pub, 0 as npu
		FROM informes_legales il 
		inner join usuarios us on us.id_usuario = il.id_us_comun 
		inner join usuarios ab on ab.id_usuario = il.usr_acep 
		inner join oficinas fi on fi.id_oficina = us.id_oficina 
		inner join almacen al on al.id_almacen = fi.id_almacen 
		WHERE fi.id_almacen = '$id_almacen' and estado = 'ace'  ".$del_filtro.
		"GROUP BY al.nombre, fi.nombre, ab.nombres, il.estado 
		union
		SELECT al.nombre AS almacen, fi.nombre as oficina, 
			ab.nombres, 0 as ace, count(*) as pub, 0 as npu
		FROM informes_legales il 
		inner join usuarios us on us.id_usuario = il.id_us_comun 
		inner join usuarios ab on ab.id_usuario = il.usr_acep 
		inner join oficinas fi on fi.id_oficina = us.id_oficina 
		inner join almacen al on al.id_almacen = fi.id_almacen 
		WHERE fi.id_almacen = '$id_almacen' and estado = 'pub'  ".$del_filtro.
		"GROUP BY al.nombre, fi.nombre, ab.nombres, il.estado 
		union
		SELECT al.nombre AS almacen, fi.nombre as oficina, 
			ab.nombres, 0 as ace, 0 as pub, count(*) as npu
		FROM informes_legales il 
		inner join usuarios us on us.id_usuario = il.id_us_comun 
		inner join usuarios ab on ab.id_usuario = il.usr_acep 
		inner join oficinas fi on fi.id_oficina = us.id_oficina 
		inner join almacen al on al.id_almacen = fi.id_almacen 
		WHERE fi.id_almacen = '$id_almacen' and estado = 'npu'  ".$del_filtro.
		"GROUP BY al.nombre, fi.nombre, ab.nombres, il.estado 
		 ) resulta 
		GROUP BY almacen, oficina, nombres
		ORDER BY almacen, oficina, nombres";
		
		$query= consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$listado[]= array('almacen' => $row["almacen"],
								'oficina' => $row["oficina"],
								'nombres' => $row["nombres"],
								'A' => $row["a"],
								'B' => $row["b"],
								'C' => $row["c"],
								'total' => $row["a"]+$row["b"]+$row["c"]);
		}

	}elseif($reporte=='3'){
		$sql= "select ba.banca, tb.tipo_bien, estado, count(*) as cant 
		from informes_legales il
		inner join tipos_bien tb on tb.id_tipo_bien = il.id_tipo_bien
		inner join (
		select distinct nrocaso, id_banca 
		from ncaso_cfinal where idfinal <> -1 and idfinal<>999
		) nc
		 on nc.nrocaso =il.nrocaso
		inner join bancas ba on ba.id_banca = nc.id_banca
		inner join usuarios us on us.id_usuario = il.usr_acep 
		inner join oficinas ofi on ofi.id_oficina = us.id_oficina 
		where ofi.id_almacen = $id_almacen ".$del_filtro.
		"group by ba.banca, tb.tipo_bien, estado
		order by tb.tipo_bien ";  
		//echo $sql;
		$query= consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
				if($row["estado"] == 'ace')
					$estadolit = 'En elaboración';
				elseif($row["estado"] == 'pub')
					$estadolit = 'Publicado';
				elseif($row["estado"] == 'npu')
					$estadolit = 'Despublicado';
				else 
					$estadolit = '?';
				$listado[]= array('banca' => $row["banca"],
									'tipo_bien' => $row["tipo_bien"],
									'estado' => $estadolit,
									'cant' => $row["cant"]);
		}
	}
}
	$smarty->assign('listado',$listado);
	$smarty->display('informe_legal_reporte.html');
	die();

?>