<?php

require_once('../lib/lib/nusoap.php');

if($ws_url3==''){
	echo 'No se pudo completar la operación, URL del WS no esta definido ';
	//	echo '<br>Revise al configuraci&oacute;n del Servicio WEB o habilite el inicio de sesi&oacute;n directo' ;
        die();
}
//$WS_url=$row['ws_url1'];

$oSoapClient = new nusoap_client($ws_url3, true);
//$oSoapClient->setHTTPProxy('10.10.1.2',8080,'cdiaz','cdiaz');

$oSoapClient->loadWSDL();
$parametros = array( 'usrn' => $login,
					'clav' 	=> $password);
$respuesta = 'false';
$result = $oSoapClient->call("ClaveCorrecta", $parametros);
if (!$oSoapClient->fault) { // Si
        $sError = $oSoapClient->getError();
        // Hay algun error ?
        if (!$sError) { // Si
             $respuesta = $result["ClaveCorrectaResult"];
		}
}

if($respuesta == 'true'){
	//recuperamos el nombre solo si no existe en guardian
	if($existeU == 0){ //no existe en guardian
		//aqui llamar al WS de recuperacion de nombre de usuario
		$parametros = array( 'usrn' => $login);
		$nombres = '';
		$result = $oSoapClient->call("NombreUsuario", $parametros);
		if (!$oSoapClient->fault) { // no hay error
			$sError = $oSoapClient->getError();
			if (!$sError) {// Hay algun error ?
				$nombres = $result["NombreUsuarioResult"];
			}
		}
		//aqui llamar al WS de recuperacion de la oficina
		//print_r($parametros);  //para ver que el array no salga afectado luego de llamar al WS
		//$parametros = array( 'usrn' => $login);
		$oficina = '';
		$result = $oSoapClient->call("AgenciaUsuario", $parametros);
		if (!$oSoapClient->fault) { // no hay error
			$sError = $oSoapClient->getError();
			if (!$sError) {// Hay algun error ?
				$oficina = $result["AgenciaUsuarioResult"];
				
				//separamos el nro del nombre 
				$datos = explode('-',$oficina);
				//datos posicion cero debe tener el nro de ofi
				$oficina = $datos[0];
				unset($datos);
			}
		}
		//aqui llamar al WS de recuperacion del correo-e
		//$parametros = array( 'usrn' => $login);
		$email = '';
		$result = $oSoapClient->call("getCorreoElectronico", $parametros);
		if (!$oSoapClient->fault) { // no hay error
			$sError = $oSoapClient->getError();
			if (!$sError) {// Hay algun error ?
				$email = $result["getCorreoElectronicoResult"];
			}
		}
	}
	// si resulta que devuelven un nombre vacio, hacemos:
	if(trim($nombres)==''){
		$existeU = 1; //para que no se guarde en blanco en guardian
		$nombres ='(desconocido)'; //para que pueda loguearse ya que es user valido
	}
	//vemos si hay que actualizar guardian
	if($existeU == 0){
		//como el user ha sido validado guardamos el usuario
		$pass = crypt($password,"vic");
		//ver si la oficina existe
		$sql="SELECT id_oficina FROM oficinas where id_oficina = '$oficina'";
		$queryws = consulta($sql);
		$data = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
		if($data['id_oficina']!=$oficina){
			//no existe definida, le ponemos cual?
			$oficina = 0;
		}
		//insertamos usuario, sin perfil para que lo definan posteriormente
		$sql= "INSERT INTO usuarios(id_perfil, nombres, login, password, activo, id_oficina, correoe, ci, telefono, direccion, ingresos) ";
		$sql.= "VALUES('0', '$nombres', '$login', '$pass', 'S', '$oficina', '$email', '', '', '', 0) ";
		ejecutar($sql);
	}
	
}else{
	$nombres = ''; //para que el login de error
}

?>