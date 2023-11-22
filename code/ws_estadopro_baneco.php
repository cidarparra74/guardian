<?php
require_once('../lib/lib/nusoap.php');

$ssql = "SELECT TOP 1 ws_url1 FROM opciones";
$queryws = consulta($ssql);
$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
if($rowws['ws_url1']==''){
	echo 'No se pudo completar la operación, URL no definida ';
		echo '<br>';
		echo 'Revise al configuraci&oacute;n del Servicio WEB.';
        die();
}
$WS_url=$rowws['ws_url1']; 
//
$guser = $_SESSION["idusuario"];
	//leemos del guardian
	$ssql = "SELECT login FROM usuarios WHERE id_usuario=$guser";
	$query = consulta($ssql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$glogin = $row["login"];
//$oSoapClient = new nusoap_client('http://21.10.0.12/wsbec/flujocredito.asmx?wsdl', true);
$oSoapClient = new nusoap_client($WS_url, true);

//lo siguiente ya esta definido en ws_nrocaso.php
/*
NumerodeCaso:  
Status:  
FechaRegistro:  
HoradeRegistro:  
Usuario: 
 

*/ 
$parametros = array( 'NumerodeCaso' => $nrocaso,
					'Status' 	=> $estado,
					'IdRecepcionGuardian' 	=> $id,
					'FechaRegistro' 	=> date("d/m/Y"),
					'HoradeRegistro' 	=> date("H:i:s"),
					'Usuario' 	=> $glogin);
//

//print_r ($parametros);

$oSoapClient->loadWSDL();
//recuperamos nombre
$resulta = false;
$result = $oSoapClient->call("RegRecepcionDocs", $parametros);
if (!$oSoapClient->fault) { // no hay error
	$sError = $oSoapClient->getError();
	if (!$sError){
		$resulta = $result["RegRecepcionDocsResult"];
	}else{
		echo 'Error!:'.$sError;
	}
	
}

?>