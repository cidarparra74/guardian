<?php
	session_start();
	
	header("Cache-control: private"); // Arregla IE 6
	require_once('../lib/conexionMNU.php');
	require_once('../lib/codificar.php');
	require_once("../lib/class.inputfilter.php");
	$ifilter = new InputFilter();

	//Comprobacion del envio del nombre de usuario y password
	if ($_REQUEST['username']) {
		//$login=addslashes($_REQUEST['username']);
		$login = $ifilter->process($_REQUEST['username']);
		//$login = str_replace("'","",strtoupper($login));
		//$login = substr(str_replace(";","",$login),0,20);
		$password=$_REQUEST['password'];
		if ($password==NULL) {
			$txtaviso = "No ingres&oacute; la contrase&ntilde;a!";
			require_once('../code/_login.php');
			die();
		}else{
			//Aqui tenemos tanto el login como el pass
			//verificar que tipo de logueo usamos
			//consultamos si el WS de logueo esta habilitado
			$sql="SELECT TOP 1 enable_login, ws_url3 FROM opciones ";
			$query = consulta($sql);
			$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$enable_login = $data['enable_login'];
			$ws_url3 = $data['ws_url3'];
			if($enable_login == 'S'){
				// verificamos ingreso via WS
				//vemos si el login existe en guardian
				$sql="SELECT COUNT(us.login) as nro FROM usuarios us WHERE us.login = '$login'";
				$query = consulta($sql);
				$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
				if($data['nro'] == '0')
					$existeU = 0;  // no existe
				else
					$existeU = 1;
				//hacemos a peticion al WS
					require_once('../code/ws_login.php');
					//---------------------------------
					//si la peticion tuvo exito $resulta = 1, o $nombres<>''
					if($nombres<>''){
						//tenemos el nombre del user, por lo tanto existe
						$sql="SELECT us.id_usuario, us.id_perfil, us.nombres, us.id_oficina, ofi.id_almacen 
						FROM usuarios us LEFT JOIN oficinas ofi ON us.id_oficina = ofi.id_oficina WHERE us.login = '$login'";
						$query = consulta($sql);
						$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
						//ingresamos Aca el login y password estan bien, creamos las sesiones				
						$_SESSION["idusuario"] = $data['id_usuario'];
						$_SESSION["idperfil"] = $data['id_perfil'];
						$_SESSION["nombreusr"] = $data['nombres'];
						$_SESSION["id_oficina"] = $data['id_oficina'];
						$_SESSION["id_almacen"] = $data['id_almacen'];
						
						//para preservar carpeta de trabajo
						$MainDir = getcwd();
						$_SESSION['MainDir'] = $MainDir;
						header("Location: ../templates/_frameset.html");
						die();
					}else{
						//la peticion no tuvo exito
						$txtaviso = "Inv&aacute;lido Nombre de Usuario o Contrase&ntilde;a! (ws)";
						require_once('../code/_login.php');
						die();
					}
			}else{
				//ingresamos de manera habitual directamente desde guardian
				$sql="SELECT us.password, us.id_usuario, us.id_perfil, us.nombres, us.id_oficina, ofi.id_almacen 
				FROM usuarios us LEFT JOIN oficinas ofi ON us.id_oficina = ofi.id_oficina 
				WHERE us.login = '$login'";
				$query = consulta($sql);
				$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
				//ingresamos 
				if($data['password'] != crypt($password,"vic")) {
					$txtaviso = "Inv&aacute;lido Nombre de Usuario o Contrase&ntilde;a!";
					require_once('../code/_login.php');
					die();
				}else{
					//Aca el login y password estan bien, creamos las sesiones				
					$_SESSION["idusuario"] = $data['id_usuario'];
					$_SESSION["idperfil"] = $data['id_perfil'];
					$_SESSION["nombreusr"] = $data['nombres'];
					
					$_SESSION["id_oficina"] = $data['id_oficina'];
					$_SESSION["id_almacen"] = $data['id_almacen'];
					//echo $_SESSION["idperfil"].$_SESSION["nombreusr"];
					
					//para preservar carpeta de trabajo
					$MainDir = getcwd();
					$_SESSION['MainDir'] = $MainDir;
					/// ////////////////////////////////////////////	
					/// armamos el menu principal
					header("Location: ../templates/_frameset.html");
					die();
				}
			}
		}
	}else{
		$txtaviso = "No ingres&oacute el nombre de usuario!";
		require_once('../code/_login.php');
		die();
	}
?>