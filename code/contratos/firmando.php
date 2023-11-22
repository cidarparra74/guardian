<?php
//esto para la primera vez que ingresa a crear un contrato
		
	$id = $_REQUEST['id'];
	$firma = $_REQUEST['firmando'];
	$glogin = $_SESSION['glogin'];
	$sql= "UPDATE contrato_final SET firmado='$firma', ultimo_login = '$glogin' WHERE idfinal = '$id'";
	//echo $sql;
	ejecutar($sql);
	
	if(isset($_REQUEST['id_usuario'])){
		if($_REQUEST['id_usuario'] !='0'){
			
			
			require('../lib/class.phpmailer.php');
			require '../lib/class.smtp.php';
			//ini_set('odbc.defaultlrl','1048576');
			
			require('../lib/conexionMNU.php');
//para el correo del revisor
			$id_usuario = $_REQUEST['id_usuario'];
			$ccopia = $_REQUEST['ccopia'];
			$sql= "SELECT correoe FROM usuarios WHERE id_usuario='$id_usuario'";  
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$correoe = $row["correoe"];
			//echo $correoe;
			$sql= "SELECT top 1 mail_remite, mail_smtp FROM opciones ";  
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$mail_smtp = $row["mail_smtp"];
			$mail_remite = $row["mail_remite"];
			require('../lib/conexionSEC.php');
			ini_set(SMTP,$mail_smtp);
		//	echo $mail_smtp;
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->Host = $mail_smtp;
			$mail->From = $mail_remite;
			$mail->FromName = ''; //$_SESSION['nombreusr'];
			$mail->Subject = 'Revisión de Contrato ';
			$mail->AddAddress($correoe);
			if($ccopia!='')
				$mail->AddCC($ccopia);
			
			$body = "<strong>Mensaje del sistema Guardián</strong><br><br>";
			$body.= "El usuario ".$_SESSION["nombreusr"]."<br>";
			$body.= "Ha solicitado la revisión de su contrato<br>";
			$body.= "<b>".$_REQUEST["titulo"]."</b><br>";
			$body.= "Del cliente ";
			$body.= "<b>".$_REQUEST["cliente"]."</b><br>";
			$body.= "Elaborado en fecha ";
			$body.= "<b>".$_REQUEST["fecha"]."</b><br>";
			
			$mail->Body = $body;
			$mail->IsHTML(true);
			$result = $mail->Send();
			
			//se envio el correo?
			if(!$result) {
				// There was an error
				// Do some error handling things here
				echo "No se pudo enviar el correo. ". $mail->ErrorInfo;
				//$status = "Message was not sent <br> Mailer Error: " . $mail->ErrorInfo;
			} else {
				//echo "Email successful";
				//$status = "";
			}
	
		
		}
	}

?>
