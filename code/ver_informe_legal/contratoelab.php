<?php
	$nrocaso = $_REQUEST["nrocaso"];
	//para banco sol el nro de caso es id de informe legal ver solcontra.php
	if(isset($_REQUEST["btn_enviar"])){
	if($nrocaso!='0'){
		//se supone que este nro de caso ya esta guardado en ncaso_cfinal de guardian
		//asi que lo actualizamos
		//$sql="INSERT INTO ncaso_cfinal (nrocaso,idfinal) VALUES ('$nrocaso','$idfinal')";
		$sql="UPDATE ncaso_cfinal SET idfinal='999' WHERE id_informe = '$nrocaso' AND idfinal = '0'";
		ejecutar($sql);
		$id_notario = $_REQUEST["id_notario"];
		if(enviaCorreo()){
			//mandamos correo al asesor de credito y gerente de agencia
			// es decir al q solicito el il y al resp de la ofi
			//solicitante:
			
			$sql="SELECT DISTINCT us.correoe, us.nombres, ofi.id_responsable, 
			il.cliente, left(CONVERT(VARCHAR(10), il.fecha_solicitud, 103)  +' '+ CONVERT(VARCHAR(10), 
			il.fecha_solicitud, 108),16) AS fecha , so.tipo_bien, ofi.id_oficina
			FROM informes_legales il
				LEFT JOIN usuarios us ON us.id_usuario = il.id_us_comun
				LEFT JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
				LEFT JOIN tipos_bien so ON so.id_tipo_bien = il.id_tipo_bien
				WHERE il.id_informe_legal = '$nrocaso'";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$correo1= $row['correoe'];
			$asesor= $row['nombres'];
			$id_responsable = $row['id_responsable'];
			$cliente= trim($row['cliente']);
			$fecha= $row['fecha'];
			$tipo_bien = $row['tipo_bien'];
			$idofi = $row['id_oficina'];
			//responsable
			$sql="SELECT us.correoe FROM usuarios us
				WHERE US.id_usuario = '$id_responsable'";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$correo2= $row['correoe'];
			//asesor de cred
			$sql="SELECT us.correoe FROM oficinas ofi 
			INNER JOIN usuarios us ON us.id_usuario = ofi.id_asesor 
			WHERE ofi.id_oficina = '$idofi' ";
			$result = consulta($sql);
			$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
			if($resultado["correoe"]!=''){
				$correo2 .= ', '.$resultado["correoe"];
			}
		//otros correos	
			$sql="SELECT correos FROM oficinas ofi  
			WHERE ofi.id_oficina = '$idofi' ";
			$result = consulta($sql);
			$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
			if($resultado["correos"]!=''){
				$correo2 .= ', '.$resultado["correos"];
			}
			//notario
			$sql = "SELECT * FROM personas WHERE id_persona = '$id_notario' ";
			$query= consulta($sql);
			$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$nombres= $resultado["nombres"];
			$apellidos= $resultado["apellidos"];
			$direccion= $resultado["direccion"];
			
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
				Tipo de Garant&iacute;a: $tipo_bien<br /><br />
				Notario/a: $nombres $apellidos ($direccion)<br />
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
		}
	}
	}else{
	$id_oficina = $_SESSION["id_oficina"];
	//notarios
	$sql= "SELECT pe.id_persona, pe.nombres, pe.apellidos 
	FROM personas pe
	INNER JOIN oficina_persona op 
				ON id_responsable = pe.id_persona
				WHERE pe.tipo_rol = 'N' AND op.id_oficina = $id_oficina ";
		
	$query = consulta($sql);
	$i=0;
	$notarios=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$notarios[]= array ('id'=>$row["id_persona"],
							'nombre'=> $row["apellidos"] . ' ' . $row["nombres"]);
		$i++;
	}
	
	$smarty->assign('notarios',$notarios);
	$smarty->assign('nrocaso',$nrocaso);
	$smarty->display('ver_informe_legal/contratoelab.html');
	die();
	}
?>