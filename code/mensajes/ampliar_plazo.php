<?php

	$id = $_REQUEST['id'];
	require_once("../lib/fechas.php");
	if(isset($_REQUEST['ampliar_plazo_x'])){
		
		//$fecha = "CONVERT(DATETIME,'".$_REQUEST["fecha"]."',103)";
		$anios= $_REQUEST['fechaYear'];
		$meses= $_REQUEST['fechaMonth'];
		$dias= $_REQUEST['fechaDay'];
		$horas= $_REQUEST['horaHour'];
		$minutos= $_REQUEST['horaMinute'];
		
		$fecha = "CONVERT(DATETIME,'".$dias.'/'.$meses.'/'.$anios.' '.$horas.':'.$minutos.':00'."',103)";
		
		$sql = "UPDATE movimientos_carpetas SET fecha_prorroga= $fecha WHERE id_movimiento_carpeta= $id ";
		ejecutar($sql);
		if(enviaCorreo()){
			//mandamos email al asesor legal de la agencia
			$id_almacen = $_SESSION["id_almacen"];
			//$sql= "SELECT correoe FROM usuarios WHERE id_usuario = $idusuario";
			$sql= "SELECT us.correoe FROM almacen mc 
			INNER JOIN usuarios us ON mc.id_usautoriza = us.id_usuario
			WHERE mc.id_almacen = $id_almacen";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$destinatario = $row['correoe'];
			if($destinatario!=''){
				//recuperamos datos de la carpeta
				$sql= "SELECT pr.nombres as cliente, cm.corr_auto, tb.tipo_bien, us.nombres, 
				cm.arch_corr_plazo , cm.fecha_prorroga
				FROM movimientos_carpetas cm
				INNER JOIN carpetas ca ON ca.id_carpeta = cm.id_carpeta 
				LEFT JOIN usuarios us ON us.id_usuario = cm.id_us_corriente 
				LEFT JOIN propietarios pr ON pr.id_propietario = ca.id_propietario
				LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = ca.id_tipo_carpeta 
				WHERE cm.id_movimiento_carpeta='$id' ";	
				$query = consulta($sql);
				$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
				$cliente = $row['cliente'];
				$fecha = fechaDMYh($row['corr_auto']);
				$fecha_dev = fechaDMYh($row['arch_corr_plazo']);
				$fecha_sol = fechaDMYh($row['fecha_prorroga']);
				$tipo_bien = $row['tipo_bien'];
				$asesor = $row['nombres'];
				$asunto="GUARDIAN PRO: Prórroga de devolución de documentos. Cliente $cliente";
				$cuerpo=" 
				<html> 
				<head> 
				   <title>GUARDIAN</title> 
				</head> 
				<body> 
				<h1>Mensaje del Sistema Guardi&aacute;n</h1> 
				<p> 
				<b>Se ha realizado una solicitud de pr&oacute;rroga para su autorizaci&oacute;n</b><br /><br />
				Fecha del pr&eacute;stamo: $fecha<br />
				Fecha de la devoluci&oacute;n: $fecha_dev<br />
				Fecha solicitada para la devoluci&oacute;n: <b>$fecha_sol</b><br />
				Operaci&oacute;n a cargo de: $asesor<br />
				Nombre Cliente: $cliente<br />
				Tipo de Garant&iacute;a: $tipo_bien<br />
				<br />
				</p> 
				</body> 
				</html> 
				";
				$headers = "MIME-Version: 1.0\r\n"; 
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
				//dirección del remitente
				$headers .= "From: GUARDIAN <$mailSender>\r\n";
				mail($destinatario,$asunto,$cuerpo,$headers);
			}
		
		}
		//fin envia correo
	}else{
	
	$sql= "SELECT auto_arch_plazo, arch_corr_plazo FROM movimientos_carpetas WHERE id_movimiento_carpeta= $id";
	$query = consulta($sql);

	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$fecha= $row["arch_corr_plazo"];

	$smarty->assign('fecha',$fecha);
	$smarty->assign('id',$id);

	$smarty->display('mensajes/ampliar_plazo.html');
	die();
	
	}
?>