<?php

$id= $_REQUEST['id'];
$nombres= strtoupper($_REQUEST['nombres']);
//$mis= $_REQUEST['mis'];
$telefonos= $_REQUEST['telefonos'];
$direccion= $_REQUEST['direccion'];
$estado_civil= $_REQUEST['estado_civil'];
$nit= $_REQUEST['nit'];
$emision= $_REQUEST['emision'];
//$tipo_identificacion= $_REQUEST['tipo_identificacion'];

$xnombres= $_REQUEST['xnombres'];
$xemision= $_REQUEST['xemision'];
//$xmis= $_REQUEST['xmis'];
$xtelefonos= $_REQUEST['xtelefonos'];
$xdireccion= $_REQUEST['xdireccion'];
$xestado_civil= $_REQUEST['xestado_civil'];
$xnit= $_REQUEST['xnit'];

	$direccion = str_replace("'","''",$direccion);
if(isset($_REQUEST['ci'])){
	$ci= $_REQUEST['ci'];
	$id_tipo_id= $_REQUEST['tipo_identificacion'];
	$xci= $_REQUEST['xci'];
	$xid_tipo_id= $_REQUEST['xid_tipo_id'];
	$sql= "UPDATE propietarios SET ci='$ci', id_tipo_identificacion='$id_tipo_id', nombres='$nombres', direccion='$direccion', 
	telefonos='$telefonos', estado_civil='$estado_civil', nit='$nit', mis='$ci', emision='$emision' WHERE id_propietario='$id' ";
	ejecutar($sql);
}else{
	//esto cuando no se deja modificar el CI
	$sql= "UPDATE propietarios SET nombres='$nombres', direccion='$direccion', 
	telefonos='$telefonos', estado_civil='$estado_civil', nit='$nit' WHERE id_propietario='$id' ";
	ejecutar($sql);
	$ci= '(sin cambios)';
	$id_tipo_id= '';
	$xci= '(sin cambios)';
	$xid_tipo_id= '';
}
	 
//verificar si esta habilitado el WS

if(enviaCorreo()){
$idof = $_SESSION["id_oficina"];
$sql = "SELECT us.correoe FROM usuarios us 
LEFT JOIN oficinas ofi ON us.id_oficina = ofi.id_oficina 
WHERE ofi.id_oficina = $idof AND ofi.id_responsable = us.id_usuario";
$query = consulta($sql);
$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
$destinatario = $row['correoe'];

if($destinatario!=''){
	//para el envío en formato HTML 
	$asunto='GUARDIAN PRO: Actualización de datos ';
	$cuerpo=" 
<html> 
<head> 
   <title>GUARDIAN</title> 
</head> 
<body> 
<h1>Mensaje del Sistema Guardi&aacute;n</h1> 
<p> 
<b>Se ha realizado una modificaci&oacute;n en los siguientes datos:</b><br />
C.I: $xci $xemision<br />
Nombre: $xnombres<br />
Tel&eacute;fono: $xtelefonos<br />
Direcci&oacute;n: $xdireccion<br />
Estado Civil: $xestado_civil<br />
NIT: $xnit<br />
 </p> 
 <p> 
<b><font color='red'>Cambios Realizados:</b></font><br />";
if($xci!=$ci || $emision!=$xemision)
	$cuerpo=$cuerpo."C.I: $ci $emision<br />";
if($xnombres!=$nombres)
	$cuerpo=$cuerpo."Nombre: $nombres<br />";
if($xtelefonos!=$telefonos)
	$cuerpo=$cuerpo."Tel&eacute;fono: $telefonos<br />";
if($xdireccion!=$direccion)
	$cuerpo=$cuerpo."Direcci&oacute;n: $direccion<br />";
if($xestado_civil!=$estado_civil)
	$cuerpo=$cuerpo."Estado Civil: $estado_civil<br />";
if($xnit!=$nit)
	$cuerpo=$cuerpo."NIT: $nit<br />";
$cuerpo=$cuerpo." </p> 
</body> 
</html> 
";
	$headers = "MIME-Version: 1.0\r\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

	//dirección del remitente 
	$headers .= "From: GUARDIAN <$mailSender>\r\n"; 

	mail($destinatario,$asunto,$cuerpo,$headers);
	//echo "Reportado a: $destinatario";
}
}
?>
