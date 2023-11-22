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
$tipoOperacion = 'EDG';
$iNumeroOperacionWS = '0';
$xmlEntrada = "<xmlentrada>
		<servicio>
			<usuariodominio>$usuariodominio</usuariodominio>
			<fechasolicitud>".date("d/m/Y")."</fechasolicitud>
			<horasolicitud>".date("H:m:s")."</horasolicitud> 
			<sidoportunidad>$nrocaso</sidoportunidad>
		</servicio>
		</xmlentrada>";
		
if($rowws['ws_url5']=='php:'){
	//para pruebas victor
	$documento = '3204124'; 
	$emision = 'SC'; 
	$nombres = 'Pruebas Victor';
}else{

	$WS_url=$rowws['ws_url5'];
	$oSoapClient = new nusoap_client($WS_url, true);
	//ncas permane en$parametros incluso para los proximos WS
	$parametros = array( 'procedencia' => $procedencia,
						'tipoOperacion' => $tipoOperacion,
						'iNumeroOperacionWS' => $iNumeroOperacionWS,
						'xmlEntrada' => $xmlEntrada);

	$resulta = array();
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
	
/*
<Participantes>
	<Participante>
	<TipoParticipante>Titular</TipoParticipante>
	<NroDocumento></NroDocumento>
	<Complemento>J5</Complemento>
	<Extension>CH</Extension>
	<TipoDocumento></TipoDocumento>
	<Nombres>ok prueba</Nombres>
	<PrimerApellido>condonaciones</PrimerApellido>
	<SegundoApellido></SegundoApellido>
	<ApellidoCasada></ApellidoCasada>
	<PaisDocumento></PaisDocumento>
	<TipoPersona>F</TipoPersona>
	<EstadoCivil></EstadoCivil>
	<Agencia></Agencia>
	<Asesor></Asesor>
	<TelefonoFijo></TelefonoFijo>
	<Celular></Celular>
	<Direccion></Direccion>
	</Participante>
</Participantes>
<Participantes>
	<Participante>
		<TipoParticipante>Titular</TipoParticipante>
		<NroDocumento>4501209</NroDocumento>
		<Complemento></Complemento>
		<Extension>CB</Extension>
		<TipoDocumento>1</TipoDocumento>
		<Nombres>CELESTINO </Nombres>
		<PrimerApellido>MAYTA</PrimerApellido>
		<SegundoApellido>GUARACHI</SegundoApellido>
		<ApellidoCasada></ApellidoCasada>
		<PaisDocumento>1</PaisDocumento>
		<TipoPersona>F</TipoPersona>
		<EstadoCivil>S</EstadoCivil>
		<Agencia>702</Agencia>
		<Asesor>XRIOS</Asesor>
		<TelefonoFijo></TelefonoFijo>
		<Celular>3643993</Celular>
		<Direccion>Zona El Alto Av. Los Rosales 10021</Direccion>
	</Participante>
	<Participante>
		<TipoParticipante>Codeudor</TipoParticipante>
		<NroDocumento>8685856</NroDocumento>
		<Complemento></Complemento>
		<Extension>CB</Extension>
		<TipoDocumento>1</TipoDocumento>
		<Nombres>AURORA </Nombres>
		<PrimerApellido>KAPAICO</PrimerApellido>
		<SegundoApellido>VENTURA</SegundoApellido>
		<ApellidoCasada></ApellidoCasada>
		<PaisDocumento>1</PaisDocumento>
		<TipoPersona>F</TipoPersona>
		<EstadoCivil>S</EstadoCivil>
		<Agencia>318</Agencia>
		<Asesor>BGONZALES</Asesor>
		<TelefonoFijo></TelefonoFijo>
		<Celular>79391155</Celular>
		<Direccion>Sopocachi C/ Jaime Freyre 15</Direccion>
	</Participante>
</Participantes>

*/

	$estado = $result["estado"];
	$mensaje = $result["mensaje"];
	$xmlSalida = utf8_encode($result["xmlSalida"]);
	
	/*
	echo $mensaje;
	echo "<pre>";
	echo $xmlSalida;
	echo "</pre>";
	
	*/
	$xcnt = '0';
	$nombres1 = '';
	$nombres2 = '';
	
	if($estado == '1'){
		//formateamos salida
		$Participantes = new SimpleXMLElement($xmlSalida);
		//$Participantes = new simplexml_load_string($xmlSalida);

		//echo $Participantes->Participante[0]->TipoParticipante;
		foreach ($Participantes->Participante as $cliente) {
		   //echo $cliente->TipoParticipante;
		   $documento1 = $cliente->NroDocumento.$cliente->Complemento;
		   $emision1 = $cliente->Extension;
		   //echo $cliente->TipoDocumento;
		   $nombres0 = (string) $cliente->Nombres;
		   $paterno0 = (string) $cliente->PrimerApellido;
		   $materno0 = (string) $cliente->SegundoApellido;
		   $apecas = (string) $cliente->ApellidoCasada;
		   $nombres1 = $paterno0.' '.$materno0.' '.$nombres0;
		   if($apecas!='') $nombres1 .= ' de '.$apecas;
		   $personanatural = $cliente->TipoPersona;
		   $ecivil = $cliente->EstadoCivil;
		   $telefonos = $cliente->TelefonoFijo.' - '.$cliente->Celular;
			$direccion = (string) $cliente->Direccion;
			$direccion = str_replace("'","",$direccion);
		   //$aclientes = get_object_vars($cliente);
		   // print_r($aclientes);
		  
			// insertamos el cliente
		  
			// existe, buscamos el propietario por CI
			//$ci_cliente = $documento;
			$sql = "SELECT ci, id_propietario, nombres FROM propietarios WHERE ci = '$documento1'";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($documento1 != $row["ci"]){
				// no existe, lo insertamos directamente en tabla propietarios
					$fecha_actual= date("Y-m-d H:i:s");
					$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
					//limpiamos $direccion para caracter no valido 17/09/2014
				//	echo $direccion;
						if($personanatural != 'J')
							$sql= "INSERT INTO propietarios (nombres, ci, direccion,
							telefonos, creacion_propietario, estado_civil, nit, emision, mis, 
							personanatural, profesion, nacionalidad, pais)
							VALUES('$nombres1', '$documento1', '$direccion',
							'$telefonos', $fecha_actual, '$ecivil', '', '$emision1', '$nrocaso', 
							'1', '', '', '') ";
						else
							//persona juridica
							$sql= "INSERT INTO propietarios (nombres, ci, direccion,
							telefonos, creacion_propietario, estado_civil, nit, emision, mis, razonsocial, personanatural)
							VALUES('$nombres1', '$documento1', '$direccion',
							'$telefonos', $fecha_actual, '$ecivil', '$documento1', '', '$nrocaso','$nombres1','2') ";
				//	echo $sql;	
					ejecutar($sql);
					//pero necesitamos el idpropietario!!
					//para asegurarnos de obtener el id correcto temporalmente ponemos
					//en el nit el nro de cuenta y luego consultamos:
					$sql = "SELECT MAX(id_propietario) as idp 
						FROM propietarios WHERE ci='$documento1' AND mis = '$nrocaso'";
					$query = consulta($sql);
					$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
					$id_propietario1 = $row["idp"];
					//ponemos en vacio el nit para dejar como estaba
					$sql = "UPDATE propietarios SET mis = '$documento1' WHERE id_propietario = '$id_propietario1' ";
					ejecutar($sql);
			}else{
				$smarty->assign('alerta','OK');
				$id_propietario1 = $row["id_propietario"];
				//aqui ver de hacer un update a datos del cliente!!
				$sql= "UPDATE propietarios SET nombres = '$nombres1' WHERE id_propietario = '$id_propietario1' and nombres <> '$nombres1'";
				ejecutar($sql);
				$sql= "UPDATE propietarios SET direccion = '$direccion' WHERE id_propietario = '$id_propietario1' and direccion <> '$direccion'";
				ejecutar($sql);
				$sql= "UPDATE propietarios SET estado_civil = '$ecivil' WHERE id_propietario = '$id_propietario1' and estado_civil <> '$ecivil'";
				ejecutar($sql);
				//$sql= "UPDATE propietarios SET profesion = '$profesion' WHERE id_propietario = '$id_propietario1' and profesion <> '$profesion'";
				//ejecutar($sql);
				//unset($nrocaso); //ahora se usa cuenta para BSOL
				
			}
			// guardamos titular
			if($cliente->TipoParticipante=='Titular' and $xcnt == 0){
				$documento = $cliente->NroDocumento;
				$emision   = $cliente->Extension;
				$nombres   = $cliente->PrimerApellido.' '.$cliente->SegundoApellido.' '.$cliente->Nombres;
				$nombres1   = $cliente->PrimerApellido;
				$nombres2   = $cliente->Nombres;
				//$personanatural = $cliente->TipoPersona;
				//$ecivil    = $cliente->EstadoCivil;
				//$telefonos = $cliente->TelefonoFijo.' - '.$cliente->Celular;
				//$direccion = str_replace("'","",$cliente->Direccion);
				$id_propietario = $id_propietario1;
				$ci_cliente = $documento;
				$xcnt = 1;
			}
		}
		
		if($xcnt == 0){
			//no se encontro titular, 
			$estado = '2';
		}elseif($documento=='' or $nombres1=='' or $nombres2==''){ //vemos si los datos estan bien
			//no se encontro titular, 
			$estado = '3';
		}
		
	}
	
}
?>