<?php
session_start();
$id = $_REQUEST["id"];   //id_informe_legal

// nrocaso es cuenta
$sql = "SELECT nrocaso, ci_cliente, noportunidad FROM informes_legales WHERE id_informe_legal = '$id'";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
$nrocaso = $row["nrocaso"];
//esta es una copia de una porcion de codigo del archivo elaborar_contrato.php
//que pone al contrato como elaborado y envia un correo

	//siempre debe entrar:
	//if($nrocaso!='0' && $nrocaso!='' ){
	if($nrocaso=='0' or $nrocaso=='' ){
		//en recepcion no se asigno nro de cuenta, sino nro de oportunidad. bsol
		//
		$ci_cliente = $row["ci_cliente"];
		$noportunidad = $row["noportunidad"];
		$TipoDoc = 1;
		$Pais =1;
		require_once('ws_getcuenta_bsol.php');
		if($nrocaso!=''){
			$sql = "UPDATE informes_legales SET nrocaso=$nrocaso WHERE id_informe_legal = '$id'";
			ejecutar($sql);
		}else{
			echo "No se encontró nro de cuenta cliente para este caso!";
			return;
		}
	}
		//se supone que este nro de caso ya esta guardado en ncaso_cfinal de guardian? -> NO deberia
		$sql="SELECT nrocaso FROM ncaso_cfinal WHERE nrocaso = '$nrocaso'  AND id_informe = '$id' AND idfinal <> '-1'";
		$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$ver= $row['nrocaso'];

		if($ver!=$nrocaso){
			$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal, id_informe) VALUES 
			($nrocaso, '0', '$id')";
			//0 significa que hay una solicitud de contrato
			ejecutar($sql);
		}else{ 									// 999??
		//nunca deberia entrar aqui
			$sql="UPDATE ncaso_cfinal SET idfinal='0', id_informe='$id' WHERE nrocaso = '$nrocaso' AND id_informe = '$id'";
			ejecutar($sql);
		}
		//echo $sql;
/*		if(enviaCorreo()){
			//mandamos correo al asesor de credito y gerente de agencia
			// es decir al q solicito el il y al resp de la ofi
			//solicitante:
			$sql="SELECT DISTINCT us.correoe, us.nombres, ofi.id_responsable, 
			il.cliente, left(CONVERT(VARCHAR(10), il.fecha_solicitud, 103)  +' '+ CONVERT(VARCHAR(10), il.fecha_solicitud, 108),16) AS fecha , so.tipo_bien
			FROM informes_legales il
				LEFT JOIN usuarios us ON us.id_usuario = il.id_us_comun
				LEFT JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
				LEFT JOIN tipos_bien so ON so.id_tipo_bien = il.id_tipo_bien
				WHERE il.id_informe_legal = '$id'";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$correo1= $row['correoe'];
			$asesor= $row['nombres'];
			$id_responsable = $row['id_responsable'];
			$cliente= trim($row['cliente']);
			$fecha= $row['fecha'];
			$tipo_bien = $row['tipo_bien'];
			//responsable
			$sql="SELECT us.correoe FROM usuarios us
				WHERE US.id_usuario = '$id_responsable'";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$correo2= $row['correoe'];
			
			//mandamos email al correo de almacen
			
			
				//recuperamos el solicitante
				$asunto="GUARDIAN PRO: Contrato de Crédito en Notaría ".$cliente;
				$cuerpo=" 
				<html> 
				<head> 
				   <title>GUARDIAN</title> 
				</head> 
				<body> 
				<h1>Mensaje del Sistema Guardi&aacute;n</h1> 
				<p> 
				<b>Se ha enviado el contrato de cr&eacute;dito a Notar&iacute;a de F&eacute; P&uacute;blica, favor comunicar al cliente.</b><br />
				Fecha de la solicitud: $fecha<br />
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
				if($correo1!=''){			
					mail($correo1,$asunto,$cuerpo,$headers);
					//echo $correo1.$asunto.$cuerpo.$headers;
				}
				if($correo2!=''){
					mail($correo2,$asunto,$cuerpo,$headers);
					//echo $correo2.$asunto.$cuerpo.$headers;
				}
		}*/
//	}else{
		//echo "falta asignar nro de cuenta!";
		
//	}
	
?>