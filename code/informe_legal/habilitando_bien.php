<?php

	//preparamos datos
	$fecha_actual= date("Y-m-d H:i:s");
	$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
	
	// caso GARRIDO en BSOL :
	/*

	//generamos imagen html del I.L.
	require_once("imprimir_bien2.php");
	
	// si es bsol enviar al ws el i.l. que esta en $el_html
	//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	if($enable_ws == 'S'){
		// es bsol
		$estado = '1';
		$sql= "SELECT noportunidad FROM informes_legales WHERE id_informe_legal='$id'";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$noportunidad=$row["noportunidad"];
		if($noportunidad!='0' and $noportunidad!='')
			require_once("ws_enviainforme_bsol.php");
		if($estado <> '1'){
			echo $mensaje;
			return;
		}
	}
	*/
	
	//actualizamos estado del informe (publicamos)
	$sql= "UPDATE informes_legales SET habilitar_informe='1', fecha_habilitacion=$fecha_actual, estado='pub' WHERE id_informe_legal='$id' ";
	ejecutar($sql);

	
	//guardamos en fechas
	$sql= "UPDATE informes_legales_fechas SET fecha_publicacion=$fecha_actual ";
	$sql.="WHERE id_informe_legal='$id' AND fecha_publicacion is null";
	ejecutar($sql);
	
	
     //generamos imagen html del I.L.
     require_once("imprimir_bien2.php");

	if(enviaCorreo()){
	// **
	// para el estado final del I.L (Rojo;amarillo;Verde)
	$sql= "SELECT TOP 1 il_estado_fin FROM opciones ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$il_estado=explode(';',$row["il_estado_fin"]);
	//$smarty->assign('il_estado',$il_estado);
	
	//recuperamos el id del solicitante
	$sql= "SELECT id_us_comun, cliente, fecha_solicitud, bandera FROM informes_legales WHERE id_informe_legal='$id' ";	
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_us_comun = $row['id_us_comun'];
	$cliente = trim($row['cliente']);
	$fecha = $row['fecha_solicitud'];
	$bandera = $row['bandera'];
	$color = $il_estado[0];
	if($bandera=='r') $color = $il_estado[0];
	elseif($bandera=='a') $color = $il_estado[1];
	elseif($bandera=='v') $color = $il_estado[2];
	elseif($bandera=='z') $color = $il_estado[3];
	//el nombre del que publica esta en $_SESSION["nombreusr"]
	//recuperamos correo del solicitante
		$sql = "SELECT correoe, nombres FROM usuarios WHERE id_usuario = $id_us_comun ";
		$query = consulta($sql);
		$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
		$destinatario = $row['correoe'];
		$solicita = $row['nombres'];
		$idofi = $_SESSION["id_oficina"];
	
	
	$con_o = ' con ';
	if($bandera=='v') $con_o = ' sin ';
	//elseif($bandera=='a') $con_o = ' con ';
	
	$url="http://".$_SERVER['HTTP_HOST']."/guardianpro";
	if($destinatario!=''){
		//para el env�o en formato HTML 
		$publica=$_SESSION["nombreusr"];
		$asunto="GUARDIAN PRO: Publicaci�n I.L. $cliente ($color)";
		$cuerpo=" 
	<html> 
	<head> 
	   <title>GUARDIAN</title> 
	</head> 
	<body> 
	<h1>Mensaje del Sistema Guardi&aacute;n</h1> 
	<p> 
	<b>Su solicitud de Informe Legal ha sido publicada $con_o observaciones.</b><br />
	Fecha de la solicitud: $fecha<br />
	Operaci&oacute;n a cargo de: $solicita<br />
	Nombre Cliente: $cliente<br />
	<br />
	I.L. publicado por: $publica<br />
	<br />
	<br />Acceso directo: $url
	 </p> 
	</body> 
	</html> 
	";
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

			//direcci�n del remitente 
			$headers .= "From: GUARDIAN <$mailSender>\r\n"; 
			mail($destinatario,$asunto,$cuerpo,$headers);
	}
	//enviamos al encargado de agencia
	//-----------------------------
	//recuperamos oficina del us solicitante
	
	$sql= "SELECT usu.id_oficina, ofi.id_responsable, ofi.id_asesor, ofi.correos FROM usuarios usu 
	LEFT JOIN oficinas ofi ON usu.id_oficina=ofi.id_oficina 
	WHERE usu.id_usuario = $id_us_comun ";	
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_responsable = $row['id_responsable'];
	$id_asesor = $row['id_asesor'];
	//$id_oficina = $row['id_oficina'];
	$correos = $row["correos"];
	
	//recuperamos correo del resp
	$sql = "SELECT correoe FROM usuarios WHERE id_usuario = '$id_responsable' ";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$destinatario = $row['correoe'];
	//el encargado de cre	
	$sql = "SELECT correoe FROM usuarios WHERE id_usuario = '$id_asesor' ";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	if($row["correoe"]!=''){
		$destinatario .= ', '.$row['correoe'];
	}
	//otros correos
	if($correos!='')
		$destinatario .= ', '.$correos;		

	if($destinatario!=''){
		//para el env�o en formato HTML
		//$publica=$_SESSION["nombreusr"];
		//$asunto="GUARDIANPRO: Publicaci�n I.L. $cliente ($color)";
		$headers = "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

			//direcci�n del remitente 
			$headers .= "From: GUARDIAN <$mailSender>\r\n";
			mail($destinatario,$asunto,$cuerpo,$headers);
	}
	

}

?>