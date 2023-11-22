<?php

//vemos si MAIL esta habilitado


/*
	* if not isset($aprobando) se esta solicitando aprobacion de i.l. (informe_legal.php    mensajeua.php)
	* if isset($aprobando) and $aprobando = 'vrb' se esta aprobando la elab. de i.l.
	* if isset($aprobando) and $aprobando = 'aut'   //esto para saber si es solicitud de archivo
	* if isset($aprobando) and $aprobando = 'arc'   //esto para saber si aprobacion de archivo
	* if isset($aprobando) and $aprobando = 'pro'   //esto para saber si aprobacion de productos enviar web service BEC
*/

$id= $_REQUEST["id"];

$idus = $_SESSION["idusuario"];
//fecha de acptacion

$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

//vemos si estamos aprobando o aceptando
if(isset($aprobando)){
///// estos son para catatrodirectamente sin informe legal
	if($aprobando=='arc')   //aceptando desde autorizar a catastro catastro_aprob&task=arc
	$sql= "UPDATE informes_legales SET estado='cat', fecha_aprob=$fecha_actual WHERE id_informe_legal='$id' ";	
	elseif($aprobando=='aut')   ////aceptando desde autorizar envio a catastro catastro_aprob&task=aut
	//$sql= "UPDATE informes_legales SET estado='aut' WHERE id_informe_legal='$id' ";
		//if($Tipo=='S')
		//	$sql= "UPDATE informes_legales SET estado='apr' WHERE id_informe_legal='$id' ";	
		//else
			$sql= "UPDATE informes_legales SET estado='arc' WHERE id_informe_legal='$id' ";	
	elseif($aprobando=='vrb')
		//aprobando la sol del i.l. 
		$sql= "UPDATE informes_legales SET estado='apr', fecha_aprob=$fecha_actual WHERE id_informe_legal='$id' ";	
	elseif($aprobando=='pro') 
		//aprobando producto, enviamos WS ver al final del archivo
		$sql= "UPDATE informes_legales SET estado='arc' WHERE id_informe_legal='$id' ";	
}else{
	//aceptando la elaboracion de i.l.
	$sql= "INSERT INTO informes_legales_fechas(id_informe_legal, fecha_quitar, usr_acep) ";
	$sql.="VALUES( '$id', $fecha_actual, '$idus') ";
	ejecutar($sql);
	
	$sql= "UPDATE informes_legales SET estado='ace', fecha_aceptacion=$fecha_actual, usr_acep=$idus WHERE id_informe_legal='$id' ";
}

ejecutar($sql);

//1 sale aut --autorizar envio a catastro --catastro_aprob&task=aut --correo 
//2 sale arc --
if(enviaCorreo()){
if(isset($aprobando) && $aprobando=='arc'){ //aut

	//mandamos email al encargado de op, ya que se ha aceptado el envio a catastro
	//recuperamos el id del solicitante
	$sql= "SELECT id_us_comun, cliente, fecha_solicitud FROM informes_legales WHERE id_informe_legal='$id' ";	
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_us_comun = $row['id_us_comun'];
	$cliente = $row['cliente'];
	$fecha = $row['fecha_solicitud'];
	
	$id_oficina = $_SESSION["id_oficina"];
	// recup coreo del gerente de agencia
	$sql= "SELECT us.correoe FROM oficinas ofi 
	LEFT JOIN usuarios us ON ofi.id_responsable=us.id_usuario WHERE ofi.id_oficina = $id_oficina";	
	//echo $sql;
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$destinatario = $row['correoe'];

	//Encargado de Operacione ses es  enviar a Catastro una vez desembolsado el crédito 

//echo "  Su solicitud de envío a catastro ha sido aprobada";
	if($destinatario!=''){
		//para el envío en formato HTML 
		$acepta=$_SESSION["nombreusr"];
		$asunto="GUARDIAN PRO: Aprobación de envío a catastro. Cliente: $cliente";
		$cuerpo=" 
	<html> 
	<head> 
	   <title>GUARDIAN</title> 
	</head> 
	<body> 
	<h1>Mensaje del Sistema Guardi&aacute;n</h1> 
	<p> 
	<b>Su solicitud de env&iacute;o a catastro ha sido aprobada</b><br />
	Fecha de la solicitud: $fecha<br />
	Nombre Cliente: $cliente<br />
	<br />
	Aceptado por: $acepta<br />
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

}elseif(isset($aprobando) && $aprobando == 'aut'){ //cat
//de catastro_aprob & cat
	// se esta autorizando
//mandamos email al solicitante

	//recuperamos el id del solicitante
	$sql= "SELECT id_us_comun, cliente, fecha_solicitud FROM informes_legales WHERE id_informe_legal='$id' ";	
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_us_comun = $row['id_us_comun'];
	$cliente = $row['cliente'];
	$fecha = $row['fecha_solicitud'];
	//el nombre del que acepta esta en $_SESSION["nombreusr"]
	//recuperamos correo del solicitante
	
$sql= "SELECT id_perfil_ope FROM opciones"; 
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_perfil_ope = $row["id_perfil_ope"];
	if($id_perfil_ope =='') $smarty->assign('alert',"Falta configurar el perfil para Enc. de operaciones.");
	
	//seleccionamos el usuario de la oficina q tenga ese perfil
	$sql= "SELECT us.correoe FROM usuarios us
	INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina
	WHERE ofi.id_oficina='$id_oficina' AND us.id_perfil = '$id_perfil_ope' AND us.activo='S'";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$destinatario = $row['correoe'];
	// al gerente de agencia es Su solicitud de envío a catastro ha sido aprobada  

//echo "  Enviar a Catastro una vez desembolsado el crédito";
	if($destinatario!=''){
		//recuperamos el solicitante
	$sql= "SELECT il.cliente, il.fecha_solicitud, so.nombres 
	FROM informes_legales il LEFT JOIN usuarios so ON so.id_usuario = il.id_us_comun 
	WHERE id_informe_legal='$id' ";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$cliente = $row['cliente'];
	$fecha = $row['fecha_solicitud'];
	$nombres = $row['nombres'];
	$asunto="GUARDIAN PRO: Documentos revisados para envío a catastro. Cliente $cliente";
	$cuerpo="
	<html> 
	<head> 
	   <title>GUARDIAN PRO</title> 
	</head> 
	<body> 
	<h1>Mensaje del Sistema Guardi&aacute;n</h1> 
	<p> 
	<b>Enviar a catastro una vez desembolsado el cr&eacute;dito</b><br />
	Fecha de la solicitud: $fecha<br />
	Nombre Cliente: $cliente<br />
	<br />
	Solicitud realizada por: $nombres<br />
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
}
/*
if($aprobando=='pro'){
	//enviamos datos al WS
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
		$sql= "SELECT COUNT(*) cant FROM tipos_bien_documentos tb
		LEFT JOIN (informes_legales il 
		INNER JOIN documentos_informe di ON di.din_inf_id = il.id_informe_legal AND il.id_informe_legal = '$id') 
		ON tb.id_tipo_bien = il.id_tipo_bien  AND tb.id_documento = di.din_doc_id 
		WHERE tb.id_tipo_bien = (SELECT id_tipo_bien FROM informes_legales WHERE id_informe_legal = '$id') 
		AND di.din_id is null AND tb.requerido = 1";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$cant= $resultado["cant"];
		if($cant>0) $estado = 2; $estado = 1;
		require_once('ws_estadopro_baneco.php');
	}
	
}
*/
?>
