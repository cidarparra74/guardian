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
		$oficina = $_REQUEST['oficina'];
		
		$fec1 = $_REQUEST['fecha1Day'].'/'.$_REQUEST['fecha1Month'].'/'.$_REQUEST['fecha1Year'];
		$fec2 = $_REQUEST['fecha2Day'].'/'.$_REQUEST['fecha2Month'].'/'.$_REQUEST['fecha2Year'];
		$fecha1 = "CONVERT(DATETIME,'$fec1',103)";
		$fecha2 = "CONVERT(DATETIME,'$fec2 23:59:59',103)";
		
		//$ANDestado = "";
		$ANDusuario = "";
		$ANDoficina = "";
		
		//if($estado!='*'){
		//	$ANDestado = $estado;
		//}
		if($usuario!='*'){
			$ANDusuario = "AND il.id_us_comun = '$usuario'";
		}
		if($oficina!='*'){
			//ver si si es recinto u oficina
			if(substr($oficina,0,1)=='a'){
				$ida = substr($oficina,1);
				$ANDoficina = "AND ofi.id_almacen = '$ida'";
			}else{
				$ANDoficina = "AND ofi.id_oficina = '$oficina'";
			}
		}
		if($estado!='*'){
		//todos los docs
			$sql="select il.id_informe_legal, ba.banca, il.nrocaso, us.nombres, 
				ofi.nombre, il.cliente, convert(varchar,il.fecha_recepcion,103) as fecha   from informes_legales il
				inner join usuarios us on us.id_usuario = il.id_us_comun 
				inner join oficinas ofi on ofi.id_oficina = us.id_oficina
				inner join tipos_bien tb on tb.id_tipo_bien = il.id_tipo_bien
				inner join bancas ba on ba.id_banca = tb.id_banca
				WHERE il.fecha_recepcion>=$fecha1 AND il.fecha_recepcion <=$fecha2 $ANDusuario $ANDoficina
				ORDER BY il.fecha_recepcion";
		}elseif($estado!='0'){
		//docs incompletos
			$sql="SELECT il.id_informe_legal, ba.banca, il.nrocaso, us.nombres, ofi.nombre, il.cliente, 
				convert(varchar,il.fecha_recepcion,103) as fecha 
				FROM informes_legales il 
				INNER JOIN usuarios us on us.id_usuario = il.id_us_comun 
				INNER JOIN oficinas ofi on ofi.id_oficina = us.id_oficina 
				INNER JOIN tipos_bien tb on tb.id_tipo_bien = il.id_tipo_bien 
				INNER JOIN bancas ba on ba.id_banca = tb.id_banca 
				WHERE il.fecha_recepcion>=$fecha1 AND il.fecha_recepcion <=$fecha2 $ANDusuario $ANDoficina 
				AND il.id_informe_legal IN (
					SELECT il. id_informe_legal
					FROM informes_legales il INNER JOIN tipos_bien_documentos tb ON il.id_tipo_bien=tb.id_tipo_bien
					LEFT JOIN documentos_informe di ON di.din_doc_id = tb.id_documento AND di.din_inf_id = il. id_informe_legal
					WHERE tb.requerido = 1 AND di.din_doc_id IS NULL
					GROUP BY il. id_informe_legal
				)
				ORDER BY il.fecha_recepcion";
		}elseif($estado!='1'){
				//docs completos
			$sql="SELECT il.id_informe_legal, ba.banca, il.nrocaso, us.nombres, ofi.nombre, il.cliente, 
				convert(varchar,il.fecha_recepcion,103) as fecha 
				FROM informes_legales il 
				INNER JOIN usuarios us on us.id_usuario = il.id_us_comun 
				INNER JOIN oficinas ofi on ofi.id_oficina = us.id_oficina 
				INNER JOIN tipos_bien tb on tb.id_tipo_bien = il.id_tipo_bien 
				INNER JOIN bancas ba on ba.id_banca = tb.id_banca 
				WHERE il.fecha_recepcion>=$fecha1 AND il.fecha_recepcion <=$fecha2 $ANDusuario $ANDoficina 
				AND il.id_informe_legal IN (
					SELECT il. id_informe_legal
					FROM informes_legales il INNER JOIN tipos_bien_documentos tb ON il.id_tipo_bien=tb.id_tipo_bien
					LEFT JOIN documentos_informe di ON di.din_doc_id = tb.id_documento AND di.din_inf_id = il. id_informe_legal
					WHERE tb.requerido = 1 AND di.din_doc_id IS not NULL
					GROUP BY il. id_informe_legal
				)
				ORDER BY il.fecha_recepcion";
		}
		//echo $sql;
		$query = consulta($sql);
		$casos = array();
		while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
			
			$casos[] = array('id'=>$row['id_informe_legal'],
								'banca'=>$row['banca'],
								'nrocaso'=>$row['nrocaso'],
								'usuario'=>$row['nombres'],
								'oficina'=>$row['nombre'],
								'cliente'=>$row['cliente'],
								'fecha'=>$row['fecha']);
			
		}
		$carpeta_entrar="./_main.php?action=recepcion.php";
		$smarty->assign('carpeta_entrar',$carpeta_entrar);
		$smarty->assign('fec1',$fec1);
		$smarty->assign('fec2',$fec2);
		$smarty->assign('casos',$casos);
		$smarty->display('ver_informe_legal/recepcionrpt_imp.html');
		die();
	}
	//$id= $_REQUEST['id'];
	//href
	$carpeta_entrar="./_main.php?action=recepcionrpt.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//$smarty->assign('id',$id);
	
	//oficinas
	$sql= "SELECT al.id_almacen, al.nombre as almacen, ofi.id_oficina, ofi.nombre 
	FROM oficinas ofi
	INNER JOIN almacen al ON al.id_almacen = ofi.id_almacen 
	ORDER BY al.nombre, ofi.nombre ";
	$query = consulta($sql);

	$ida= '0';
	$f_oficina= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if($ida != $row["id_almacen"]){
			$f_oficina[]= array('id'=>'a'.$row["id_almacen"], 'nombre'=>$row["almacen"].' - ------------------------');
			$ida = $row["id_almacen"];
		}
		$f_oficina[]= array('id'=>$row["id_oficina"], 'nombre'=>$row["almacen"].' - '.$row["nombre"]);
	}
	$smarty->assign('f_oficina',$f_oficina);
	//usuarios del sistema
	$sql="SELECT id_usuario, nombres FROM usuarios ORDER BY nombres";
	$query = consulta($sql);
	$usuarios = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$usuarios[] = array('id'=>$row['id_usuario'],
							'nombres'=>$row['nombres']);
		
	}
	$smarty->assign('usuarios',$usuarios);
	
	$smarty->display('ver_informe_legal/recepcionrpt.html');
	die();
?>