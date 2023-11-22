<?php
	// $id = $_REQUEST['id'];
	require_once("../lib/fechas.php");
	
	if(isset($_REQUEST["id_mc"])){
		echo $_REQUEST["estado"];
	}
	
	// RECUPERAMOS DATOS DEL credito y propietario
	$sql = "SELECT il.nrocaso, convert(varchar,il.fecha,103) as fechare, il.estado, 
	convert(varchar,il.fecha_aceptacion,103) as fechaace,  
	us1.nombres as recepciona, us2.nombres as aceptail,
	pr.nombres, pr.ci, pr.emision, pr.id_propietario, ofi.nombre as oficina, 
	tb.tipo_bien, tb.con_inf_legal, convert(varchar,il.fecha_habilitacion,103) as publica
	FROM informes_legales il 
	INNER JOIN propietarios pr ON il.id_propietario = pr.id_propietario
	INNER JOIN usuarios us1 ON us1.id_usuario = il.id_us_comun
	LEFT JOIN usuarios us2 ON us2.id_usuario = il.usr_acep
	INNER JOIN oficinas ofi ON ofi.id_oficina = us1.id_oficina
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien
	WHERE id_informe_legal = $id " ;
	//echo $sql;
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_propietario = $row["id_propietario"];
	$estado = $row["estado"];
	$con_il = $row["con_inf_legal"];
	$smarty->assign('ci_cliente', $row["ci"]);
	$smarty->assign('emision', $row["emision"]);
	$smarty->assign('cliente', $row["nombres"]);
	$smarty->assign('nrocaso', $row["nrocaso"]);
	$smarty->assign('fechare', $row["fechare"]);
	$smarty->assign('estado', $row["estado"]);
	$smarty->assign('fechaace', $row["fechaace"]);
	$smarty->assign('recepciona', $row["recepciona"]);
	$smarty->assign('aceptail', $row["aceptail"]);
	$smarty->assign('oficina', $row["oficina"]);
	$smarty->assign('tipo_bien', $row["tipo_bien"]);
	$smarty->assign('con_il', $row["con_inf_legal"]);
	$smarty->assign('publica', $row["publica"]);

	//vemos el estado
	$milestone = 0;
	if($estado=='rec') $milestone = 1;
	elseif($estado=='sol') $milestone = 2;
	if($con_il == 'S'){
		if($estado=='apr') $milestone = 3;
		elseif($estado=='ace') $milestone = 4;
		elseif($estado=='pub') $milestone = 5;
		elseif($estado=='npu') $milestone = 6;
	}else{
		if($estado=='arc') $milestone = 3;
		elseif($estado=='cat') $milestone = 4;
	}
	$smarty->assign('milestone', $milestone);
	
	
	//recuperando las carpetas del cliente
	$sql= "SELECT c.id_carpeta, CONVERT(VARCHAR,c.creacion_carpeta,103) as fecha, 
	c.carpeta, c.operacion, c.cuenta, ofi.nombre as oficina
	FROM carpetas c
	INNER JOIN informes_legales il ON il.id_informe_legal = c.id_informe_legal
	INNER JOIN oficinas ofi ON ofi.id_oficina = c.id_oficina
	WHERE il.id_informe_legal='$id' ";
	$query = consulta($sql);
	$id_carpeta = 0;
	if($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$id_carpeta = $row["id_carpeta"];
		
		$fecha = $row["fecha"];
		$carpeta = $row["carpeta"];
		$operacion = $row["operacion"];
		$cuenta = $row["cuenta"];
		$oficina = $row["oficina"];

		$smarty->assign('fecha',$fecha);
		$smarty->assign('carpeta',$carpeta);
		$smarty->assign('operacion',$operacion);
		$smarty->assign('cuenta',$cuenta);
		$smarty->assign('oficina',$oficina);
	}else{
		$smarty->assign('fecha','');
	}
	
	if($id_carpeta != 0){
		//seguimiento a la Carpeta
		$sql= "SELECT mc.id_estado, mc.corr_auto, mc.auto_arch, mc.arch_corr_prest, 
		mc.arch_corr_conf, mc.corr_arch_ret, mc.corr_arch_ret_conf, 
		mc.corr_dev, mc.corr_adj, mc.id_movimiento_carpeta, 
		u1.nombres as solicita, u2.nombres as autoriza, u3.nombres as catastro
		FROM movimientos_carpetas mc
		LEFT JOIN usuarios u1 ON u1.id_usuario = mc.id_us_corriente
		LEFT JOIN usuarios u2 ON u2.id_usuario = mc.id_us_autoriza
		LEFT JOIN usuarios u3 ON u3.id_usuario = mc.id_us_archivo
		WHERE (mc.flujo = 0 OR mc.id_estado>7) AND mc.id_carpeta = '$id_carpeta'";
		$query = consulta($sql);
		//teoricamente siempre debe devolver un registro
		$cnt=0;
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$id_mc = $row["id_movimientos_carpetas"];
			$estado = $row["id_estado"]; //del 1 al 7 en mov, 8=dev, 9=adju
			$hrs = ' <b>hrs:</b> ';
			$auxil = explode(' ',$row["corr_auto"]);
			$fec_solicita = dateDMY($auxil[0]) .$hrs. substr($auxil[1],0,5); //la fecha que solicita la carpeta
			
			$auxil = explode(' ',$row["auto_arch"]);
			$fec_autoriza = dateDMY($auxil[0]) .$hrs. substr($auxil[1],0,5); //fecha de autorizacion del prestamo
			
			$auxil = explode(' ',$row["arch_corr_prest"]);
			$fec_prestasin = dateDMY($auxil[0]) .$hrs. substr($auxil[1],0,5); //fecha de envio prest sin conf
			
			$auxil = explode(' ',$row["arch_corr_conf"]);
			$fec_prestacon = dateDMY($auxil[0]) .$hrs. substr($auxil[1],0,5); //fecha de envio prest conf, en poder sel sol
			
			$auxil = explode(' ',$row["corr_arch_ret"]);
			$fec_devuelve = dateDMY($auxil[0]) .$hrs. substr($auxil[1],0,5); //fecha de devol sin confir
			
			$auxil = explode(' ',$row["corr_arch_ret_conf"]);
			$fec_retorna = dateDMY($auxil[0]) .$hrs. substr($auxil[1],0,5); //fecha de devol sin confir
			
			$solicita = $row["solicita"];
			$autoriza = $row["autoriza"];
			$catastro = $row["catastro"];
			
			$auxil = explode(' ',$row["corr_dev"]);
			$fec_devolu = dateDMY($auxil[0]) .$hrs. substr($auxil[1],0,5); //fecha devolucion
			$auxil = explode(' ',$row["corr_adj"]);
			$fec_adjudi = dateDMY($auxil[0]) .$hrs. substr($auxil[1],0,5); //fecha de adjudicacion
			
			$cnt++;
		}
		$smarty->assign('id_mc',$id_mc);
		$smarty->assign('fec_solicita',$fec_solicita);
		$smarty->assign('fec_autoriza',$fec_autoriza);
		$smarty->assign('fec_prestasin',$fec_prestasin);
		$smarty->assign('fec_prestacon',$fec_prestacon);
		$smarty->assign('fec_devuelve',$fec_devuelve);
		$smarty->assign('fec_retorna',$fec_retorna);
		$smarty->assign('fec_devolu',$fec_devolu);
		$smarty->assign('fec_adjudi',$fec_adjudi);
		
		$smarty->assign('solicita',$solicita);
		$smarty->assign('autoriza',$autoriza);
		$smarty->assign('catastro',$catastro);
		
		
		$smarty->assign('estado',$estado);
		$smarty->assign('cnt',$cnt); //si $cnt==0 en catastro
	}
	$smarty->assign('id',$id);
	$smarty->display('ver_informe_legal/flujo.html');
	die();

?>
