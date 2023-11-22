<?php

require_once('../lib/lib/nusoap.php');

$sql = "SELECT TOP 1 ws_url5 FROM opciones";
$queryws = consulta($sql);
$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
if($rowws['ws_url5']==''){
	echo 'No se pudo completar la operación, URL no definida ';
	echo '<br>';
	echo 'Revise al configuraci&oacute;n del Servicio WEB.';
	die();
}

$idus = $_SESSION["idusuario"];
$sql = "SELECT login FROM usuarios WHERE id_usuario = '$idus'";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
if($row["login"]!='')
	$usuariodominio = $row["login"];
else
	$usuariodominio = '?';

$procedencia = 'GUARDIAN';
$tipoOperacion = 'ASPV';
$iNumeroOperacionWS = '0';
$xmlEntrada = "<xmlentrada>
		<servicio>
			<usuariodominio>$usuariodominio</usuariodominio>
			<fechasolicitud>".date("d/m/Y")."</fechasolicitud>
			<horasolicitud>".date("H:m:s")."</horasolicitud> 
			<sidoportunidad>$noportunidad</sidoportunidad>
			<peritos>
				<perito>
					<iidinforme>$id</iidinforme>
					<iidperito>$id_persona</iidperito>
				</perito>
			</peritos>
		</servicio>
		</xmlentrada>";
		
if($rowws['ws_url5']=='php:'){
	//para pruebas victor
	$estado = '1'; 
	$mensaje = 'Pruebas Victor';
}else{

	$WS_url=$rowws['ws_url5'];
	$oSoapClient = new nusoap_client($WS_url, true);
	//ncas permane en$parametros incluso para los proximos WS
	$parametros = array( 'procedencia' => $procedencia,
						'tipoOperacion' => $tipoOperacion,
						'iNumeroOperacionWS' => $iNumeroOperacionWS,
						'xmlEntrada' => $xmlEntrada);


	$oSoapClient->loadWSDL();
	$result = $oSoapClient->call("Execute", $parametros);

	if ($oSoapClient->fault) { // no responde
		echo 'No se pudo completar la operación '.$oSoapClient->getError();
		die();
	} else { // se ejecuto el ws
			$sError = $oSoapClient->getError();
			// Hay algun error ?
			if ($sError) { 
				echo 'Error!:'.$sError;
				die();
			}
	}
	
	$estado = $result["estado"];
	$mensaje = $result["mensaje"];
}
?>