<?php

require_once("../lib/setup.php");
require_once("../lib/fechas.php");
$smarty = new bd;	
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
//cargando para el overlib
require_once("../lib/cargar_overlib.php");
//
if(isset($_REQUEST['aceptar_prorroga']) || isset($_REQUEST['rechaza_prorroga'])){
		
	$cantidad = $_REQUEST['cantidad'];
	for($i=0 ; $i<$cantidad; $i++){
		$marca = 'marcado_'.$i;
		if(isset($_REQUEST["$marca"])){
			$id = $_REQUEST["$marca"];
			$respuesta = 'RECHAZ&Oacute;';
			if(isset($_REQUEST['aceptar_prorroga'])){
				$sql = "UPDATE movimientos_carpetas SET arch_corr_plazo=fecha_prorroga WHERE id_movimiento_carpeta= $id ";
				$respuesta = 'ACEPT&Oacute;';
				ejecutar($sql);
			}
			$sql = "UPDATE movimientos_carpetas SET fecha_prorroga=NULL WHERE id_movimiento_carpeta= $id ";
			ejecutar($sql);
		}
		if(enviaCorreo()){
			//mandamos email al solicitante
			//$idusuario = $_SESSION["idusuario"];
			//$sql= "SELECT correoe FROM usuarios WHERE id_usuario = $idusuario";
			$sql= "SELECT us.correoe FROM movimientos_carpetas mc 
			INNER JOIN usuarios us ON mc.id_us_corriente = us.id_usuario
			WHERE mc.id_movimiento_carpeta = $id";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$destinatario = $row['correoe'];
			if($destinatario!=''){
				//recuperamos datos de la carpeta
				$sql= "SELECT pr.nombres as cliente, cm.corr_auto, tb.tipo_bien, us.nombres, cm.arch_corr_plazo 
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
				<b>Se $respuesta la modificaci&oacute;n de la fecha de devoluci&oacute;n de Carpeta</b><br /><br />
				Fecha de la solicitud: $fecha<br />
				Fecha de la devoluci&oacute;n: <b>$fecha_dev</b><br />
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
	}
}
	//else{
		$id_almacen = $_SESSION["id_almacen"];
		$sql= "SELECT mc.id_movimiento_carpeta, 
		convert(varchar,mc.arch_corr_plazo, 103) as fecha, 
		convert(varchar,mc.fecha_prorroga, 103) as nueva, 
		us.nombres, ca.carpeta, pr.nombres as cliente
		FROM movimientos_carpetas mc 
		LEFT JOIN usuarios us ON mc.id_us_corriente = us.id_usuario 
		LEFT JOIN carpetas ca ON mc.id_carpeta = ca.id_carpeta
		LEFT JOIN propietarios pr ON ca.id_propietario = pr.id_propietario
		WHERE fecha_prorroga IS NOT null";
		$query = consulta($sql);
		$listado = array();
		$i=0;
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$listado[$i] = array('id' => $row["id_movimiento_carpeta"],
						'fecha' => $row["fecha"],
						'nueva' => $row["nueva"],
						'nombre' => $row["nombres"],
						'cliente' => $row["cliente"],
						'carpeta' => $row["carpeta"]);
			$i++;
		}
		$smarty->assign('listado',$listado);
		$smarty->assign('cantidad',$i);
		
		$smarty->display('ampliar_plazo_aut.html');
		die();
	
	//}
?>