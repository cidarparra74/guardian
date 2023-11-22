<?php

if(isset($_REQUEST['id'])){
	$id = $_REQUEST['id'];
}else{
	return;
}

//si es tipo producto verificamos que los documentos esten completos
if($cat != '0'){
	/*
	$sql="select count(*) cant from tipos_bien_documentos tb 
	WHERE tb.id_tipo_bien = (SELECT id_tipo_bien FROM informes_legales 
	WHERE id_informe_legal = '$id') and tb.id_documento not in (
	select di.din_doc_id from documentos_informe di where di.din_inf_id = '$id')";
	*/
	$sql="SELECT doc.documento FROM tipos_bien_documentos tb 
INNER JOIN documentos doc ON tb.id_documento = doc.id_documento  
WHERE tb.id_tipo_bien = (SELECT id_tipo_bien FROM informes_legales 
WHERE id_informe_legal = '$id') AND tb.requerido='1' AND tb.id_documento not in (
SELECT di.din_doc_id FROM documentos_informe di WHERE di.din_inf_id = '$id')";
//echo $sql;
		$query = consulta($sql);
	//$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$docsf = array();
	$faltan = 0;
	while($data = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$docsf[] = $data['documento'];
		$faltan++;
	}
	if($faltan>0){
		//tiene documentos en blanco que no ha excepcionado
		//no dejamos solicitar autorizacion
		$smarty->assign('incompletos','0');
		$smarty->assign('faltan',$faltan);
		$smarty->assign('docsf',$docsf);
		$smarty->assign('ndocs','1');
		$smarty->display('ver_informe_legal/faltan.html');
		die();
	}else{
		$fecha_actual= date("Y-m-d H:i:s");
		$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
		$sql= "UPDATE informes_legales SET estado='sol', fecha_solicitud=$fecha_actual  WHERE id_informe_legal=$id";
		$query = consulta($sql);
		
		//lamamos al WS
		//enviamos al web service si es baneco
	$sql = "SELECT TOP 1 enable_ws FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	if($enable_ws == 'A'){
		//OBTENEMOS NRO CASO
		$sql= "SELECT nrocaso FROM informes_legales WHERE id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$nrocaso= $resultado["nrocaso"];
		//DETERMINAMOS ESTADO
		$sql= "SELECT COUNT(*) cant FROM documentos_informe din 
		WHERE din.din_tip_doc = 0 AND din.fojas = '1' AND din.din_inf_id = '$id'";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$cant= $resultado["cant"];
		if($cant>0) 
			$estado = 2;
		else 
			$estado = 1;
		require_once('ws_estadopro_baneco.php');
	}
	
	}
	
}else{

	
	$fecha_actual= date("Y-m-d H:i:s");
	$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
	$sql= "UPDATE informes_legales SET estado='sol', fecha_solicitud=$fecha_actual  WHERE id_informe_legal=$id";
	$query = consulta($sql);
			
	
		//enviamos correo al responsable de la agencia
	
		if(enviaCorreo()){
		//el responsable es:
			$sql="SELECT us.correoe, us.nombres FROM oficinas ofi 
			INNER JOIN usuarios us ON us.id_usuario = ofi.id_responsable 
			WHERE ofi.id_oficina = '$id_oficina' ";
			$result = consulta($sql);
			$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
			$destinatario = $resultado["correoe"];
		//el asesor es	
			$sql="SELECT us.correoe, us.nombres FROM oficinas ofi 
			INNER JOIN usuarios us ON us.id_usuario = ofi.id_asesor 
			WHERE ofi.id_oficina = '$id_oficina' ";
			$result = consulta($sql);
			$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
			if($resultado["nombres"]!=''){
				$destinatario .= ', '.$resultado["correoe"];
			}
		//otros correos	
			$sql="SELECT correos FROM oficinas ofi  
			WHERE ofi.id_oficina = '$id_oficina' ";
			$result = consulta($sql);
			$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
			if($resultado["correos"]!=''){
				$destinatario .= ', '.$resultado["correos"];
			}
			//$aprueba = $resultado["nombres"];
			$sql="SELECT pr.nombres, convert(varchar,il.fecha_solicitud,103) as fecha, tb.tipo_bien FROM informes_legales il  
			INNER JOIN propietarios pr ON pr.id_propietario = il.id_propietario  
			inner join tipos_bien tb on tb.id_tipo_bien = il.id_tipo_bien 
			WHERE il.id_informe_legal = '$id' ";
			$result = consulta($sql);
			$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
			$fecha = $resultado["fecha"];
			$cliente = $resultado["nombres"];
			$tipo_bien = $resultado["tipo_bien"];
			if($destinatario!=''){
				//para el envío en formato HTML 
				$publica=$_SESSION["nombreusr"];
				$asunto="GUARDIAN PRO: Solicitud de envío a catastro. Cliente $cliente ";
				$cuerpo=" 
				<html> 
				<head> 
				   <title>GUARDIANPRO</title>
				</head> 
				<body> 
				<h1>Mensaje del Sistema Guardi&aacute;n</h1>
				<p> 
				<b>Solicitud de env&iacute;o a catastro.</b><br />
				Fecha de la solicitud: $fecha<br />
				Operaci&oacute;n a cargo de: $publica<br />
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
				//	echo $destinatario.'/'.$asunto.'/'.$cuerpo.'/'.$headers;
			}
		}
	}	
?>