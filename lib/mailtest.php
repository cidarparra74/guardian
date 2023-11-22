<?php
// cargarmos funciones propias
	require('../lib/conexionSEC.php');
	require_once('../lib/class.phpmailer.php');
	
	if(isset($_GET['smtp']) && isset($_GET["mail"])){
		$smtp = $_GET['smtp'];
		$mailSender = $_GET["mail"];
		$destinatario = $_GET["dest"];
		$usuario = $_GET["user"];
		$password = $_GET["pass"];
		//ini_set(SMTP,$smtp);
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = $smtp;
		$mail->Username = $usuario;
		$mail->Password = $password;
		//$mail->SetFrom('name@yourdomain.com', 'First Last'); 
		$mail->From = $mailSender;
		$mail->FromName = '';
		$mail->Subject = "GUARDIAN PRO: Test de correo";
		$mail->AddAddress($destinatario);
		$body=" 
	<html> 
	<head> 
	   <title>GUARDIAN</title> 
	</head> 
	<body> 
	<h1>Mensaje del Sistema Guardi&aacute;n</h1> 
	<p> 
	<b>Si logra leer este mensaje la prueba de env&iacute;o de correos ha sido existosa.</b><br />
	IP del Servidor: $smtp<br />
	Correo origen: $mail<br />
	Correo destino: $dest<br />
	 </p> 
	</body> 
	</html> 
	";
		$mail->Body = $body;
		$mail->IsHTML(true);
		$result = $mail->Send();
		
		if($result)
			echo "Enviado exitosamente.";
		else
			echo "No se pudo enviar el correo";
	}else{
		echo "Falta datos.";
	}
	
?>