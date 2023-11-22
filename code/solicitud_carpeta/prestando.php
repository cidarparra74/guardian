<?php

$id_carpeta= $_REQUEST['id'];
$id_autoriza= $_REQUEST['usuario'];

//fecha actual
$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";;

$observacion= $_REQUEST['observacion'];

$id_corriente = $_SESSION["idusuario"];

$sql= "INSERT INTO movimientos_carpetas( 
id_carpeta, corr_auto, id_us_inicio, id_us_corriente, id_us_autoriza, id_estado, flujo, obs_1) VALUES(
'$id_carpeta', $fecha_actual, '$id_corriente', '$id_corriente', '$id_autoriza', '1', '0', '$observacion') ";
//echo $sql; die();
ejecutar($sql);
//enviar correo a id_autoriza

if(enviaCorreo()){
	//el responsable es:
	$sql="SELECT us.correoe, us.nombres FROM usuarios us WHERE us.id_usuario = '$id_autoriza' ";
	$result = consulta($sql);
	$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
	$destinatario = $resultado["correoe"];
	//el q solicita es:
	$sql="SELECT us.nombres FROM usuarios us WHERE us.id_usuario = '$id_corriente' ";
	$result = consulta($sql);
	$resultado = $result->fetchRow(DB_FETCHMODE_ASSOC);
	$solicita = $resultado["nombres"];

	if($destinatario!=''){
		//para el envío en formato HTML 
		$publica=$_SESSION["nombreusr"];
		//ciente
		$sql = "SELECT nombres FROM propietarios pr, carpetas ca 
		WHERE ca.id_propietario = pr.id_propietario and id_carpeta = '$id_carpeta'" ;
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$cliente = $row["nombres"];

		$asunto="GUARDIAN PRO: Solicitud de préstamo de carpeta $solicita ";
		$cuerpo=" 
		<html> 
		<head> 
		   <title>GUARDIAN</title> 
		</head> 
		<body> 
		<h1>Mensaje del Sistema Guardi&aacute;n</h1> 
		<p> 
		<b>Se ha realizado una solicitud de pr&eacute;stamo de carpeta para su autorizaci&oacute;n</b><br />
		Fecha de la solicitud: ".date("d-m-Y H:i")."<br />
		Nombre Cliente: $cliente<br />
		<br />
		Obs: $observacion<br />
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
?>