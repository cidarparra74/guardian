<?php


// este scrip es para enviar el correo e

require_once("../lib/conexionMNU.php");

$id = $_REQUEST['id'];
$destinatario = $_REQUEST['correoe'];

$sql =  "SELECT ile.cliente, ile.ci_cliente, tii.identificacion, tbi.tipo_bien, 
ile.exe_justifica, ofi.nombre, us.nombres as usuario
			FROM informes_legales ile
			LEFT JOIN tipos_identificacion tii ON id_tipo = id_tipo_identificacion 
			LEFT JOIN tipos_bien tbi ON tbi.id_tipo_bien = ile.id_tipo_bien 
			LEFT JOIN oficinas ofi on ofi.id_oficina = ile.id_oficina
LEFT JOIN usuarios us on us.id_usuario = ile.id_us_comun
			WHERE id_informe_legal = $id ";
//
	$query = consulta($sql);
	
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$cliente = $resultado["cliente"];
	$ci_cliente = $resultado["ci_cliente"];
	$tipo_bien = $resultado["tipo_bien"];
	$exe_justifica = $resultado["exe_justifica"];
	$oficina = $resultado["nombre"];
	$usuario = $resultado["usuario"];
	//$url="http://".$_SERVER['HTTP_HOST']."/guardianpro/code/_anonimo.php?action=ver_informe_legal/excepcion.php&flag=$id";
	//echo $url;
if(enviaCorreo()){

	$fecha = date("d/m/Y");
	
	$url="http://".$_SERVER['HTTP_HOST']."/guardianpro/code/_anonimo.php?action=ver_informe_legal/excepcion.php&flag=$id";
	if($destinatario!=''){
		//para el envío en formato HTML 
		$asunto="GUARDIAN PRO: Solicitud de excepción ($cliente)";
		$cuerpo=" 
	<html> 
	<head> 
	   <title>GUARDIAN</title> 
	</head>
	<body> 
	<h1>Mensaje del Sistema Guardi&aacute;n</h1> 
	<p> 
	<b>Ud. tiene una solicitud de aprobaci&oacute;n de excepci&oacute;n.</b><br />
	Fecha de la solicitud: $fecha<br />
	Solicitante: $usuario<br />
	Oficina: $oficina<br />
	Nombre Cliente: $cliente<br />
	C.I. Cliente: $$ci_cliente<br />
	Tipo Garant&iacute;a: $tipo_bien<br />
	Justificaci&oacute;n: $exe_justifica<br />
	<br />
	<br />Acceso directo: $url
	 </p> 
	</body> 
	</html> 
	";
	//echo $cuerpo; 
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

			//dirección del remitente 
			$headers .= "From: GUARDIAN <$mailSender>\r\n"; 
			mail($destinatario,$asunto,$cuerpo,$headers);
	}
	
}else{
	echo "El env&iacute;o de correos esta deshabilitado o no se ha configurado correctamente.";
}
	


?>