<?php

require_once('../lib/lib/nusoap.php');

// ver que url usar! el ws_url2 es conta

$sql = "SELECT TOP 1 ws_url2 FROM opciones";
$queryws = consulta($sql);
$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
if($rowws['ws_url2']==''){
	echo 'No se pudo completar la operación, URL no definida ';
	echo '<br>';
	echo 'Revise al configuraci&oacute;n del Servicio WEB.';
    die();
}
$WS_url=$rowws['ws_url2'];
     
//echo "entra";
$WS_url="http://21.10.0.93/wspm/mensaje.asmx";

/*
REGISTRAR_ESTADO_GUARDIAN(int ncas, string usrn, int stat, int ctar)
Ncas =  numero de caso
Usrn = usuario ejecutando (iniciales ej: DRC, ZRC)
Stat = estado (inicial 1 cerrado 9)
Ctar =  código de la tarea 
*/

$oSoapClient = new nusoap_client($WS_url, true);
//ncas permane en$parametros incluso para los proximos WS
$parametros = array('ncas' => $nrocaso,
					'usrn' => $login,
					'stat' => $estado,
					'ctar' => $tarea);
/*
echo "<pre>";
print_r($parametros);
echo "</pre>";
*/
$resulta = array();
$oSoapClient->loadWSDL();
$result = $oSoapClient->call("registrar_estado_guardian", $parametros);
if ($oSoapClient->fault) { // Si
        die('fault');
}else{ // No
        $sError = $oSoapClient->getError();
        // Hay algun error ?
        if ($sError) { // Si
			die('Error en WS execute: '.$sError);
        }else{
		//$resulta = $result["estado"];

		}
}
/*
echo "<pre>";
print_r($result);
echo "</pre>";
*/
/*
if(isset($resulta)){
	$mensaje = $resulta;
}
*/
?>