<?php

	require_once("../lib/setup.php");
	$smarty = new bd;
	require_once("../lib/fechas.php");
	//18/07/2015
	require_once('../lib/verificar.php');
	//si acepta el reporte procesmos registros
	if(isset($_REQUEST['boton_reportar'])){
		$estado = $_REQUEST['estado'];
		$usuario = $_REQUEST['usuario'];
		$fec1 = $_REQUEST['fecha1Day'].'/'.$_REQUEST['fecha1Month'].'/'.$_REQUEST['fecha1Year'];
		$fec2 = $_REQUEST['fecha2Day'].'/'.$_REQUEST['fecha2Month'].'/'.$_REQUEST['fecha2Year'];
		
		$fecha1 = "CONVERT(DATETIME,'$fec1',103)";
		$fecha2 = "CONVERT(DATETIME,'$fec2 23:59:59',103)";
		$ANDestado = "";
		$ANDusuario = "";
		if($estado!='todos'){
			$ANDestado = "AND bi.consultasql LIKE '%$estado%'";
		}
		if($usuario!='todos'){
			$ANDusuario = "AND bi.idusuario = '$usuario'";
		}
		$sql="SELECT convert(datetime,bi.fecha,103) as fechab, bi.consultasql, us.nombres FROM bitacora bi
		LEFT JOIN usuarios us ON us.id_usuario = bi.idusuario
		WHERE bi.fecha>=$fecha1 AND bi.fecha <=$fecha2 $ANDestado $ANDusuario
		ORDER BY bi.fecha";
		//echo $sql;
		$query = consulta($sql);
		$bitacora = array();
		while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$_sql = substr($row['consultasql'],0,3);
			$_sql_all = $row['consultasql'];
			$_fecha = explode(' ',$row['fechab']);
			//$_fechab = $row['fechab'];
			
			$bitacora[] = array('fecha'=>dateDMY($_fecha[0]).' '.substr($_fecha[1],0,5),
								'nombres'=>$row['nombres'],
								'sql'=>$_sql,
								'sql_all'=>$_sql_all);
			
		}
		$smarty->assign('fec1',$fec1);
		$smarty->assign('fec2',$fec2);
		$smarty->assign('bitacora',$bitacora);
		$smarty->display('bitacora/reporte_bitacora_imp.html');
		die();
	}
	//$id= $_REQUEST['id'];
	//href
	$carpeta_entrar="./_main.php?action=rptbitacora.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//$smarty->assign('id',$id);
	
	//usuarios del sistema
	$sql="SELECT id_usuario, nombres FROM usuarios ORDER BY nombres";
	$query = consulta($sql);
	$usuarios = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$usuarios[] = array('id'=>$row['id_usuario'],
							'nombres'=>$row['nombres']);
		
	}
	$smarty->assign('usuarios',$usuarios);
	
	$smarty->display('bitacora/reporte_bitacora.html');
	die();
?>