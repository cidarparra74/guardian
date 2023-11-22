<?php

/*

CREATE TABLE [dbo].[propietarios](
	[id_propietario] [int] IDENTITY(1,1) NOT NULL,
	[nombres] [varchar](150) NOT NULL,
	[mis] [varchar](50) NOT NULL DEFAULT (' '),
	[ci] [varchar](20) NOT NULL,
	[direccion] [varchar](250) NOT NULL,
	[telefonos] [varchar](50) NOT NULL,
	[creacion_propietario] [datetime] NOT NULL,
	[nit] [varchar](30) NOT NULL DEFAULT (' '),
	[estado_civil] [varchar](1) NOT NULL DEFAULT (' '),
	[id_tipo_identificacion] [int] NOT NULL DEFAULT (' '),
	[emision] [char](2) NULL,
	[pais] [smallint] NULL DEFAULT ((0)),
	[dom_especial] [nvarchar](250) NULL,
	[nacionalidad] [nvarchar](15) NULL,
	[profesion] [nvarchar](100) NULL,
	[razonsocial] [nvarchar](100) NULL,
	[nromatricula] [nvarchar](50) NULL,
	[personanatural] [int] NULL DEFAULT ((1)),
	[representante] [text] NULL,
	[edocivil_aclar] [varchar](100) NULL)

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
	$direccion = str_replace("'","''",$direccion);
	$sqlINS= "INSERT INTO propietarios (nombres, mis, ci, direccion, 
		telefonos, id_tipo_identificacion, creacion_propietario, estado_civil, emision,
		personanatural, profesion, nacionalidad, pais, nit) 
		VALUES('$nombres', '$ci', '$ci', '$direccion', 
		'$telefonos', '$tipo_identificacion', $fecha_actual, '$estado_civil', '$emision',
		'1', '$profesion', '$nacionalidad', '$pais', '') ";
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
	$direccion = str_replace("'","''",$direccion);
	$telefonos= $_REQUEST['txtTelef2'];
	$representante= $_REQUEST['txtRepresenta'];
	$estado_civil= $_REQUEST['selEstCivil2'];
	$sqlINS= "INSERT INTO propietarios (nombres, mis, ci, direccion, 
		telefonos, id_tipo_identificacion, creacion_propietario,  nit, emision,
		personanatural, pais, razonsocial, nromatricula, representante, estado_civil) 
		VALUES('$nombres', '$ci', '$ci', '$direccion', 
		'$telefonos', '$elNit', $fecha_actual,  '$nit', '$emision',
		'2', '$pais', '$razonsocial', '$nromatricula', '$representante', '$estado_civil') ";
}


// verificar si ya existe CI
$sql = "SELECT nombres FROM propietarios WHERE ci = '$ci'";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
if($row["nombres"]==''){
	
	ejecutar($sqlINS);

}else{
	$smarty->assign('alerta',"El nro documento ingresado ya esta registrado a nombre de ".$row["nombres"]);
	$smarty->assign('nombres',$nombres);
	$smarty->assign('emision',$emision);
	$smarty->assign('telefonos',$telefonos);
	$smarty->assign('direccion',$direccion);
	$smarty->assign('ci',$ci);
	include("./personas/adicionar.php");
}
?>
