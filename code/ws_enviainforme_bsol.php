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
//<img src="../../imagenes/SOL_2.bmp">
//se requiere quitar la imagen del informe
	
	$el_html = str_replace("<img src","<xxx name",$el_html);
	$el_html = str_replace("<title>","<title>&",$el_html);
	$el_html = trim($el_html);
	$procedencia = 'GUARDIAN';
	$tipoOperacion = 'RIL';
	$iNumeroOperacionWS = '0';
	$xmlEntrada = "<xmlentrada>
		<servicio>
			<usuariodominio>$usuariodominio</usuariodominio>
			<fechasolicitud>".date("d/m/Y")."</fechasolicitud>
			<horasolicitud>".date("H:m:s")."</horasolicitud> 
			<sidoportunidad>$noportunidad</sidoportunidad>
			<informe>".$el_html."</informe>
			<itipoinforme>$bien</itipoinforme>
			<iidinforme>$id</iidinforme>
		</servicio>
		</xmlentrada>";
		
	//echo htmlentities($xmlEntrada); die();
if($rowws['ws_url5']=='php:'){
	//para pruebas victor
	$xmlSalida = "";
	echo htmlentities($xmlEntrada);
}else{

	$WS_url=$rowws['ws_url5'];
	$oSoapClient = new nusoap_client($WS_url, true);
	//ncas permane en$parametros incluso para los proximos WS
	$parametros = array( 'procedencia' => $procedencia,
						'tipoOperacion' => $tipoOperacion,
						'iNumeroOperacionWS' => $iNumeroOperacionWS,
						'xmlEntrada' => $xmlEntrada,);

	$resulta = array();
	$oSoapClient->loadWSDL();
	$result = $oSoapClient->call("Execute", $parametros);
	if ($oSoapClient->fault) { // Si
			echo 'No se pudo completar la operación '.$oSoapClient->getError();
			die();
	} else { // No
			$sError = $oSoapClient->getError();
			// Hay algun error ?
			if ($sError) { // Si
					echo 'Error!:'.$sError;
				   die();
			}else{
			$resulta = $result["ExecuteResult"];
			}
	}
	
	$estado = $result["estado"];
	$mensaje = $result["mensaje"];
}
?>