<?php
	session_start();
	
	header("Cache-control: private"); // Arregla IE 6
	require_once('../lib/conexionMNU.php');
	require_once('../lib/codificar.php');
	//require_once("../lib/class.inputfilter.php");
	//$ifilter = new InputFilter();

	//Comprobacion del envio del nombre de usuario y password
	if ($_GET['logdata']) {
		$logdecode = explode(';', base64_decode($_GET['logdata']));
		//$login = $ifilter->process($_POST['username']);
		//echo $login; die();
	//	$login = str_replace("'","",strtoupper($login));
	//	$login = substr(str_replace(";","",$login),0,20);
		$login = $logdecode[0];
		$password = $logdecode[1];
		if ($password==NULL or $login =='') {
			echo "Credenciales inv&aacute;lidas (no pass or login)"; die();
		}else{
			//Aqui tenemos tanto el login como el pass
			//consultamos si el WS de logueo esta habilitado
			$sql="SELECT TOP 1 enable_ws, ws_url3 FROM opciones ";
			$query = consulta($sql);
			$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$ws_url3 = $data['ws_url3'];
			$enable_ws = $row["enable_ws"];
			// verificamos ingreso via WS
			//vemos si el login existe en guardian
			$sql="SELECT COUNT(us.login) as nro FROM usuarios us WHERE us.login = '$login'";
			$query = consulta($sql);
			$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($data['nro'] == '0')
				$existeU = 0;  // no existe
			else
				$existeU = 1;
				
			require_once('../code/ws_login.php');

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
				//
				$_SESSION["log_directo"] = 'ok';
				//
				//para ver la banca del usuario, consultamos el WS  (se valida en el WS si existe la banca en guardian)
				$id_banca = '0';
				require_once('../code/ws_cod_banca_baneco.php');
				$_SESSION["id_banca"] = $id_banca;
				
				
				//para preservar carpeta de trabajo
				$MainDir = getcwd();
				$_SESSION['MainDir'] = $MainDir;
				header("Location: ../templates/_frameset.html");
				die();
			}else{
				//la peticion no tuvo exito
				echo "Credenciales inv&aacute;lidas (rechazado por SFI)"; die();
			}
			
		}
	}else{
		echo "Credenciales inv&aacute;lidas (no logdata)"; die();
	}
?>