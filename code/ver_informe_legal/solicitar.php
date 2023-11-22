<?php
	
	//necesitaremos el id de el tipo de garantia de este I.L.
	$sql = "SELECT il.id_tipo_bien, il.cliente, us.nombres, us.id_oficina, tb.tipo_bien FROM informes_legales il
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=il.id_tipo_bien
	LEFT JOIN usuarios us ON us.id_usuario = il.id_us_comun
	WHERE id_informe_legal='$id' ";
	$result = consulta($sql);
	$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
	$id_tipo_bien = $resultado["id_tipo_bien"];
	$id_oficina = $resultado["id_oficina"];
	$cliente = $resultado["cliente"];
	$tipo_bien = $resultado["tipo_bien"];
	
	//validar que tenga documentos, y que los mandatorios esten completos
	$sql = "SELECT count(*) as docs FROM documentos_informe WHERE din_inf_id='$id' ";
	//echo $sql;
	$result = consulta($sql);
	$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
	$ndocs = $resultado["docs"];
	$incompletos = 0;
	$faltan = 0;
	if($ndocs > 0){
		
		//vemos los documentos mandatorios que faltan
		$sql= "SELECT count(*) faltan 
				FROM tipos_bien_documentos tip 
				INNER JOIN documentos doc 
				ON tip.id_documento = doc.id_documento 
				WHERE tip.id_tipo_bien = $id_tipo_bien AND doc.requerido=1
				AND doc.id_documento not in 
		(SELECT di.din_doc_id from documentos_informe di where di.din_inf_id='$id') ";
		$result = consulta($sql);
		$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
		$faltan = $resultado["faltan"];
		
		$sql= "SELECT count(*) as ok FROM documentos_informe WHERE din_inf_id='$id' and fojas>'0' ";
		$query = consulta($sql);
		$resultado = $query->fetchRow(DB_FETCHMODE_ASSOC);
		$incompletos = $resultado["ok"];
		
	}
	
	// vemos si se hace la solicitud o no
	
	//------------ desactivado temporalmente
//	if($ndocs > 0 && $incompletos==0 && $faltan==0){
	
	if($ndocs > 0 ){
		$fecha_actual= date("Y-m-d H:i:s");
		$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
		$sql= "UPDATE informes_legales SET estado='sol', fecha_solicitud=$fecha_actual WHERE id_informe_legal=$id";
		ejecutar($sql);
		
		//enviamos correo al responsable de la agencia

		if(enviaCorreo()){
			$idofi = $_SESSION["id_oficina"];
		//el responsable es:
			$sql="SELECT us.correoe, us.nombres FROM oficinas ofi 
			INNER JOIN  usuarios us ON us.id_usuario = ofi.id_responsable 
			WHERE ofi.id_oficina = '$idofi' ";
			$result = consulta($sql);
			$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
			$destinatario = $resultado["correoe"];
			//$aprueba = $resultado["nombres"];
		//el asesor es	
			$sql="SELECT us.correoe FROM oficinas ofi 
			INNER JOIN usuarios us ON us.id_usuario = ofi.id_asesor 
			WHERE ofi.id_oficina = '$idofi' ";
			$result = consulta($sql);
			$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
			if($resultado["correoe"]!=''){
				$destinatario .= ', '.$resultado["correoe"];
			}
		//otros correos	
			$sql="SELECT correos FROM oficinas ofi  
			WHERE ofi.id_oficina = '$idofi' ";
			$result = consulta($sql);
			$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
			if($resultado["correos"]!=''){
				$destinatario .= ', '.$resultado["correos"];
			}
			
			if($destinatario!=''){
				//para el envío en formato HTML 
				$publica=$_SESSION["nombreusr"];
				$asunto="GUARDIAN PRO: Solicita aprobación envío documentos $cliente ";
				$cuerpo=" 
				<html> 
				<head> 
				   <title>GUARDIAN</title> 
				</head> 
				<body> 
				<h1>Mensaje del Sistema Guardi&aacute;n</h1> 
				<p> 
				<b>Se ha realizado una solicitud de env&iacute;o de documentos al &aacute;rea legal para su aprobaci&oacute;n:</b><br />
				Fecha de la solicitud: ".date("d-m-Y H:i")."<br />
				Solicitante: $publica<br />
				Nombre Cliente: $cliente<br />
				<br />
				Tipo de Garant&iacute;a: $tipo_bien<br />
				 </p> 
				</body> 
				</html> 
				";
				$headers = "MIME-Version: 1.0\r\n"; 
					$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

					//dirección del remitente 
					$headers .= "From: GUARDIAN <$mailSender>\r\n"; 

					mail($destinatario,$asunto,$cuerpo,$headers);
					//echo $destinatario.'/'.$asunto.'/'.$cuerpo.'/'.$headers;
			}
		}
		
	}else{
		$smarty->assign('incompletos',$incompletos);
		$smarty->assign('faltan',$faltan);
		$smarty->assign('ndocs',$ndocs);
		$smarty->display('ver_informe_legal/faltan.html');
		die();
	}

?>
