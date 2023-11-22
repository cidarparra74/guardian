<?php
	require_once("../lib/fechas.php");

	$idN = $_REQUEST["id_notario"];
	$idT = $_REQUEST["id_tramitador"];
	$nrocaso = $_REQUEST["nrocaso"];
	
	
	$inf_fch_ini = "NULL";
	$inf_fch_fin = "NULL";
	if($_REQUEST["inf_fch_ini"]!='' && $_REQUEST["inf_fch_ini"]!='--'){
		$inf_fecha_ini = dateYMD($_REQUEST["inf_fch_ini"]);
		$inf_fch_ini = "CONVERT(DATETIME,'$inf_fecha_ini',102)";
	}
	if($_REQUEST["inf_fch_fin"]!='' && $_REQUEST["inf_fch_fin"]!='--'){
		$inf_fecha_fin = dateYMD($_REQUEST["inf_fch_fin"]);
		$inf_fch_fin = "CONVERT(DATETIME,'$inf_fecha_fin',102)";
	}
	if($idN=='ninguno')
		$idN = '0';
	if($idT=='ninguno')
		$idT = '0';
		
	$inf_nro_esc	= $_REQUEST["inf_nro_esc"];
	$inf_nro_asi	= $_REQUEST["inf_nro_asi"];
	$inf_nro_mat	= $_REQUEST["inf_nro_mat"];
	$inf_fch_grav	= $_REQUEST["inf_fch_grav"];
	$inf_fch_esc	= $_REQUEST["inf_fch_esc"];
	$inf_obs	= $_REQUEST["inf_obs"];
	$inf_gravmonto	= $_REQUEST["inf_gravmonto"];
	$moneda	= $_REQUEST["moneda"];
	$inf_plazo	= $_REQUEST["inf_plazo"];
	$id_entidad	= $_REQUEST["id_entidad"];
	
	if($nrocaso==''){
		$sql = "UPDATE informes_legales SET inf_nro_esc= '$inf_nro_esc".
		"', inf_nro_asi='$inf_nro_asi".
		"', inf_nro_mat='$inf_nro_mat".
		"', inf_fch_grav='$inf_fch_grav".
		"', inf_fch_esc='$inf_fch_esc".
		"', inf_fch_ini=$inf_fch_ini".
		", inf_fch_fin=$inf_fch_fin".
		", inf_obs= '$inf_obs".		
		"', id_notario= '".$idN.
		"', id_tramitador= '".$idT.
		"', inf_gravmonto= '$inf_gravmonto $moneda".
		"', inf_plazo= '$inf_plazo".
		"', id_entidad= '$id_entidad".
	    "' WHERE id_informe_legal= $id ";
		ejecutar($sql);
	}else{
		$sql = "UPDATE informes_legales SET inf_nro_esc= '".
		$_REQUEST["inf_nro_esc"]."',inf_fch_esc= '$inf_fch_esc".
		"',id_notario= '$idN', id_tramitador= '$idT' WHERE nrocaso = $nrocaso ";
		ejecutar($sql);
		$sql = "UPDATE informes_legales SET ".
		" inf_nro_asi='$inf_nro_asi".
		"', inf_nro_mat='$inf_nro_mat".
		"', inf_fch_grav='$inf_fch_grav".
		"', inf_fch_ini=$inf_fch_ini".
		", inf_fch_fin=$inf_fch_fin".
		", inf_obs= '$inf_obs".		
		"', inf_gravmonto= '$inf_gravmonto $moneda".
		"', inf_plazo= '$inf_plazo".
		"', id_entidad= '$id_entidad".
	    "' WHERE id_informe_legal= $id ";
		ejecutar($sql);
	}

if(enviaCorreo()){
	//mandamos email al correo de almacen
	$id_almacen = $_SESSION["id_almacen"];
	$sql= "SELECT correoe FROM almacen WHERE id_almacen = $id_almacen";	
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$destinatario = $row['correoe'];
	if($destinatario!=''){
		//recuperamos el solicitante

		$sql= "SELECT il.cliente, il.fecha_solicitud, so.tipo_bien, us.nombres, en.entidad
		FROM informes_legales il 
		LEFT JOIN tipos_bien so ON so.id_tipo_bien = il.id_tipo_bien 
		LEFT JOIN usuarios us ON us.id_usuario = il.id_us_comun 
		LEFT JOIN entidades en ON en.id = il.id_entidad 
		WHERE id_informe_legal='$id' ";	
		$query = consulta($sql);
		$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
		$cliente = $row['cliente'];
		$fecha = $row['fecha_solicitud'];
		$tipo_bien = $row['tipo_bien'];
		$asesor = $row['nombres'];
		$entidad = $row['entidad'];
		//revcuperamos notario
		$sql= "SELECT nombres, apellidos FROM personas WHERE id_persona = '$idN' ";
		$query = consulta($sql);
		$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
		$notario = $row['apellidos'].' '.$row['nombres'];
		
		// recuperamos tramitador
		$sql= "SELECT nombres, apellidos FROM personas WHERE id_persona = '$idT' ";
		$query = consulta($sql);
		$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
		$tramita = $row['apellidos'].' '.$row['nombres'];
		
		$inf_fecha_ini = dateDMY($inf_fecha_ini);
		$inf_fecha_fin = dateDMY($inf_fecha_fin);
		
		$asunto="GUARDIAN PRO: Documentos revisados por el Area Legal. Cliente $cliente";
		$cuerpo=" 
		<html> 
		<head> 
		   <title>GUARDIAN</title> 
		</head> 
		<body> 
		<h1>Mensaje del Sistema Guardi&aacute;n</h1> 
		<p> 
		<b>Datos del INFORME FINAL</b><br /><br />
		Fecha de la solicitud: $fecha<br />
		Operaci&oacute;n a cargo de: $asesor<br />
		Nombre Cliente: $cliente<br />
		Tipo de Garant&iacute;a: $tipo_bien<br />
		<br />
		Nro Escritura :&nbsp;$inf_nro_esc<br />
		Notario :&nbsp;$notario<br />
		Tramitador :&nbsp;$tramita<br />
		Fecha de la Escritura :&nbsp;$inf_fch_esc<br />
		Nro Matrícula :&nbsp;$inf_nro_mat<br />
		Nro Asiento :&nbsp;$inf_nro_asi<br />
		Fecha Grav&aacute;men :&nbsp;$inf_fch_grav<br />
		Importe de la P&oacute;liza :&nbsp;$inf_gravmonto $moneda <br />
		Plazo :&nbsp;$inf_plazo meses <br />
		Fecha Seguro del: $inf_fecha_ini&nbsp;Al:&nbsp;$inf_fecha_fin<br />
		Cia. Aseguradora :&nbsp;$entidad <br />
		Observaciones :&nbsp;$inf_obs<br />
		</p> 
		</body> 
		</html> 
		";
		$headers = "MIME-Version: 1.0\r\n"; 
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
		//dirección del remitente
		$headers .= "From: GUARDIAN <$mailSender>\r\n";
		//echo htmlentities($cuerpo); die();
		mail($destinatario,$asunto,$cuerpo,$headers);
	}

}
	
?>