<?php
	$id = $_REQUEST['id'];	
if (isset($_POST['enviar'])){

	require_once('../lib/class.phpmailer.php');

	ini_set('odbc.defaultlrl','1048576');
	//require_once('../lib/conexionSEC.php');
	$idfinal=$_REQUEST["id"];
	$sql = "SELECT contenido_final FROM contrato_final WHERE idfinal=$idfinal";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	// La imagen, que de hecho es codigo RTF
	$imagen = $row["contenido_final"];
	//definimos la cabecera para corregir los margenes y dar el mismo formato del SEC
	$cabecera = "{\\rtf1\\ansi
{\\fonttbl
{\\f0 \\fcharset0 Arial;}
}
{\\colortbl;
\\red0\\green0\\blue0;
\\red255\\green255\\blue255;
}
\\pgnstart0
\\paperw11907\\paperh16840\\margl1134\\margr1134\\margt1417\\margb1134
\\f0 \\fs22 \\b0 \\i0 \\ulnone";
$pie="\n}";
//unimos todo
$imagen=$cabecera.$imagen.$pie;


$archivo = fopen("../compilado/contrato".$id.".rtf","w");
if(fputs($archivo,$imagen) == TRUE){
	//echo "Se a creado con exito el archivo";
}else {
	echo "No se pudo crear el archivo temporal"; die();
}

require('../lib/conexionMNU.php');
	$id_almacen = $_SESSION['id_almacen'];
	$id_persona = $_REQUEST["id_persona"];
	//para el correo del notario
	$sql= "SELECT correoe FROM personas WHERE id_persona='$id_persona'";  
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$correoe = trim($row["correoe"]);
	if($correoe==''){ echo 'Correo del notario vacio!';}
	//para el correo del mismo usuario
	$id_usuario = $_SESSION["idusuario"];
	$sql= "SELECT correoe, nombres FROM usuarios WHERE id_usuario='$id_usuario'";  
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$correou = $row["correoe"];
	//$destina = $row["nombres"];
	if($correou==''){ echo 'Correo del remitente vacio!';}
	$sql= "SELECT top 1 mail_remite, mail_smtp FROM opciones ";  
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$mail_smtp = $row["mail_smtp"];
	$mail_remite = $row["mail_remite"];
	
	$varname = 'contrato'.$id.'.rtf';
    $vartemp = "../compilado/contrato".$id.".rtf";
    
	
	
	ini_set(SMTP,$mail_smtp);
	
    $mail = new PHPMailer();
	
	$mail->IsSMTP();
    $mail->Host = $mail_smtp;
	//$mail->SetFrom('name@yourdomain.com', 'First Last'); 
    $mail->From = $mail_remite;
    $mail->FromName = '';
    $mail->Subject = 'Contrato '.$_REQUEST["titulo"].' del cliente '.$_REQUEST["cliente"];
    $mail->AddAddress($correoe);
	$mail->AddCC($correou);
    $mail->AddAttachment($vartemp, $varname);

	$varname = $_FILES['archivo1']['name'];
    $vartemp = $_FILES['archivo1']['tmp_name'];
	if ($varname != "") {
        $mail->AddAttachment($vartemp, $varname);
    }
	$varname = $_FILES['archivo2']['name'];
    $vartemp = $_FILES['archivo2']['tmp_name'];
	if ($varname != "") {
        $mail->AddAttachment($vartemp, $varname);
    }
	
    $body = "<strong>Mensaje del sistema Guardián</strong><br><br>";
	$body.= "El usuario ".$_SESSION["nombreusr"]."<br>";
    $body.= "Envía un archivo adjunto con el contrato<br>";
	$body.= "<b>".$_REQUEST["titulo"]."</b><br>";
	$body.= "Del cliente ";
	$body.= "<b>".$_REQUEST["cliente"]."</b><br>";
	$body.= "Elaborado en fecha ";
	$body.= "<b>".$_REQUEST["fecha"]."</b><br>";
	
    $mail->Body = $body;
    $mail->IsHTML(true);
    $result = $mail->Send();
	
	//limpiamos archivo
	$vartemp = "../compilado/contrato".$id.".rtf";
	if(file_exists($vartemp)){
		if(!unlink($vartemp)){
		$ruta = $_SERVER['SCRIPT_FILENAME'];
		$ruta  = str_replace('\code\contratos\enviarmail.php','',$ruta);
		$vartemp = $ruta."\compilado\contrato".$id.".rtf";
			if(file_exists($vartemp)){
				if(!unlink($vartemp))
					echo "No se pudo borrar archivo temporal. $vartemp ";
			}
		}
	}else echo '-e';
	
	//se envio el correo?
	if(!$result) {
		// There was an error
		// Do some error handling things here
		echo "No se pudo enviar el correo. ". $mail->ErrorInfo;
		$status = "Message was not sent <br> Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Email successful";
		$status = "";
	}

	require('../lib/conexionSEC.php');
	
	//echo $varname.'<br>';
	//echo $vartemp.'<br>';
	//echo $mail_remite.'<br>';
	//echo $correoe.'<br>';
	//echo $body.'<br>';
	//die();
}else{
	
	$sql= "SELECT co.titulo, 
(CASE WHEN PATINDEX('%<personas>%', cf.contenido_sec) > 0 THEN substring(cf.contenido_sec, patindex('%<nombre>%', cf.contenido_sec)+ 8, (patindex('%</nombre>%', cf.contenido_sec)-patindex('%<nombre>%', cf.contenido_sec)-8)) ELSE '' END) cliente,
CONVERT(VARCHAR(10), cf.fechahora, 103) AS fecha , 
CONVERT(VARCHAR(10), cf.fechahora, 108) AS hora, 
cf.ultimo_login AS modifica
FROM contrato_final cf LEFT JOIN contrato co 
ON cf.idcontrato = co.idcontrato 
WHERE idfinal ='$id'"; 
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);

	$smarty->assign('titulo', $row["titulo"]);
	$smarty->assign('cliente', $row["cliente"]);
	$smarty->assign('fecha', $row["fecha"]);
	$smarty->assign('hora', substr($row["hora"],0,5));
	$smarty->assign('modifica', $row["modifica"]);
	
	//
	require('../lib/conexionMNU.php');
	$id_almacen = $_SESSION['id_almacen'];
	//en personas el id_oficina corresponde al id_almacen, notarios = 'N'
	//solo los q tengan correo
	$sql= "SELECT id_persona, nombres, apellidos FROM personas 
		WHERE tipo_rol = 'N' AND correoe <> '' AND id_oficina='$id_almacen'"; 
	$query = consulta($sql);
	$notarios = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$notarios[] = array('id'=>$row["id_persona"], 
		'nombre'=>trim($row["apellidos"]).' '.trim($row["nombres"])); 
	}
	require('../lib/conexionSEC.php');
	$smarty->assign('notarios',$notarios);
	$smarty->assign('id',$id);
	$smarty->display('contratos/enviarmail.html');
	die();
}
	
?>
