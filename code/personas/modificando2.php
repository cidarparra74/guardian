<?php

$id= $_REQUEST['id'];
/*
//datos originales antes de la modificacion
$sql = "SELECT * FROM propietarios WHERE id_propietario='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
$xnombres= strtoupper($resultado['nombres']);
$xemision= $resultado['emision'];
$xtelefonos= $resultado['telefonos'];
$xdireccion= $resultado['direccion'];
$xestado_civil= $resultado['estado_civil'];
$xnit= $resultado['nit'];
*/
$control = $_REQUEST['control'];
//fecha actual
	$fecha_actual= date("Y-m-d H:i:s");
	$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
	
if($control == '0'){
	// si $control=0 => es persona natural
	$nombres= strtoupper($_REQUEST['txtNombre']);
	$direccion= $_REQUEST['txtDireccion'];
	$estado_civil= $_REQUEST['selEstCivil'];
	$ci= $_REQUEST['txtCI'];
	$emision= $_REQUEST['selEmi'];
	$profesion= $_REQUEST['txtOcupa'];
	$nacionalidad= $_REQUEST['txtProcede'];
	$pais= $_REQUEST['selPais'];
	$tipo_identificacion= $_REQUEST['selTipo'];
	$telefonos= $_REQUEST['txtTelef'];
	$sqlINS= "UPDATE propietarios SET nombres='$nombres', mis='$ci', ci='$ci', direccion='$direccion', 
		telefonos='$telefonos', id_tipo_identificacion='$tipo_identificacion', estado_civil='$estado_civil',
		emision='$emision', profesion='$profesion', nacionalidad='$nacionalidad', pais='$pais' 
		 WHERE id_propietario='$id'";
}else{
	// si $control=1 => es persona juridica
	$nit= $_REQUEST['txtNIT'];
	$elNit= $_REQUEST['elNit'];
	$ci= $nit;
	$emision= '';
	$pais= $_REQUEST['selPais2'];
	$razonsocial= strtoupper($_REQUEST['txtRSocial']);
	$nombres= $razonsocial;
	$nromatricula= $_REQUEST['txtMatricula'];
	$direccion= $_REQUEST['txtDomicilio'];
	$telefonos= $_REQUEST['txtTelef2'];
	$representante= $_REQUEST['txtRepresenta'];
	$estado_civil= $_REQUEST['selEstCivil'];
	$sqlINS= "UPDATE propietarios SET nombres='$nombres', mis='$ci', ci='$ci', direccion='$direccion', 
		telefonos='$telefonos',  nit='$nit', pais='$pais', razonsocial='$razonsocial',
		nromatricula='$nromatricula', representante='$representante', estado_civil='$estado_civil' 
		 WHERE id_propietario='$id'";
}
ejecutar($sqlINS); 
//verificar si esta habilitado el WS

// modificar los I.L. con ese nombre
$xnombres= $_REQUEST['xnombres'];
$xci= $_REQUEST['xci'];
if($xnombres != $nombres){
	$sql="UPDATE informes_legales SET cliente = '$nombres' WHERE id_propietario='$id'";
	ejecutar($sql);
}
if($xci != $ci){
	$sql="UPDATE informes_legales SET ci_cliente = '$ci' WHERE id_propietario='$id'";
	ejecutar($sql);
}


/*
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
*/
?>
