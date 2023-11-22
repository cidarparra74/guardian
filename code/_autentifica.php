<?php
	session_start();
	session_regenerate_id();
	header("Cache-control: private"); // Arregla IE 6
	require_once('../lib/conexionMNU.php');
	require_once('../lib/codificar.php');
	require_once("../lib/class.inputfilter.php");
	$ifilter = new InputFilter();

	//Comprobacion del envio del nombre de usuario y password
	if (isset($_POST['username'])) {
		//$login=addslashes($_REQUEST['username']);
		$login = $ifilter->process($_POST['username']);
		//xss prevent
		$login = str_replace("'","",strtoupper($login));
		$login = substr(str_replace(";","",$login),0,20);
		$password=$_POST['password'];
		if ($password==NULL) {
			$txtaviso = "No ingres&oacute; la contrase&ntilde;a!";
			require_once('../code/_login.php');
			die();
		}else{
			//Aqui tenemos tanto el login como el pass
			//verificar que tipo de logueo usamos
			//consultamos si el WS de logueo esta habilitado o si LDAP esta activo
			$sql="SELECT TOP 1 enable_login, ws_url3, enable_ws FROM opciones ";
			$query = consulta($sql);
			$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$enable_login = $data['enable_login'];
			$enable_ws = $data["enable_ws"];
			$ws_url3 = $data['ws_url3'];
			
		if (isset($_REQUEST['ctaguardian'])){
			//esto para bisa
			$enable_login = $_REQUEST['ctaguardian'];
		}
	
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
					//$nombres = 'o';
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
						//para BEC ver banca
						if($enable_ws == 'A'){
							//buscamos el codigo banca el en web service (se valida en el WS si existe la banca en guardian)
							$id_banca = '0';
							require_once('../code/ws_cod_banca_baneco.php');
							$_SESSION["id_banca"] = $id_banca;
						}else{
							$_SESSION["id_banca"] = '0';
						}
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
			}elseif($enable_login == 'N'){
				//ingresamos de manera habitual directamente desde guardian
				$sql="SELECT us.login, us.password, us.id_usuario, us.id_perfil, us.nombres, 
					us.id_oficina, ofi.id_almacen, us.activo, us.cambia_pass, us.ingresos
				FROM usuarios us LEFT JOIN oficinas ofi ON us.id_oficina = ofi.id_oficina 
				WHERE us.login = '$login'";
				$query = consulta($sql);
				$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
				$intento=$data['ingresos'];
				//esta bloqueado?
					if($data['login']==$login and $data['activo']!='S'){
						$txtaviso = "Usuario inhabilitado. Contacte al administrador.";
						require_once('../code/_login.php');
						die();
					}
				//ingresamos 
//echo crypt($password,"vic"); die();
				if($data['password'] != crypt($password,"vic")) {
					$txtaviso = "Inv&aacute;lido Nombre de Usuario o Contrase&ntilde;a! <br />";
					if($intento>=3){
						$txtaviso .= " Se ha bloqueado esta cuenta. <br />";
						$sql="UPDATE usuarios SET activo='N', ingresos=1 WHERE login ='$login' ";
						ejecutar($sql);
					}else{
						$sql="UPDATE usuarios SET ingresos = ingresos+1 WHERE login ='$login' ";
						ejecutar($sql);
					
					}
					require_once('../code/_login.php');
					die();
				}else{
						$sql="UPDATE usuarios SET ingresos = 1 WHERE login ='$login' ";
						ejecutar($sql);
					
					if($data['activo']!='S'){
						$txtaviso = "Usuario inhabilitado. Contacte al administrador.";
						require_once('../code/_login.php');
						die();
					}
					
						if($data['cambia_pass']=='S'){
							//forzamos a cambiar password
							$txtaviso = "Debe cambiar su contraseña!";
							require_once('../code/_login_ch.php');
							die();
						}else{
						//-------------------------------------------------------------------
						//-------------------------------------------------------------------
						
						//-------------------------------------------------------------------
						//-------------------------------------------------------------------
						//Aca el login y password estan bien, creamos las sesiones				
						$_SESSION["idusuario"] = $data['id_usuario'];
						$_SESSION["idperfil"] = $data['id_perfil'];
						$_SESSION["nombreusr"] = $data['nombres'];
						$_SESSION["id_banca"] = '0'; //para bec, aplica cuando se loguea por WS
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
			}else{
				//Login mediante LDAP - active directory
				//verificamos parametros para ldap
				if(file_exists("../confildap.ini")){
					$laConfig=parse_ini_file("../confildap.ini");
					$ldap_server  = $laConfig['server']; 
					$ldap_dominio = $laConfig['dominio']; 
					$ldap_params  = $laConfig['parameters'];
				}else{
					echo "No se defini&oacute; par&aacute;metros del servidor ldap!";
					//header("Location: ../code/_genldap.php");
					die();
				}
				//verificamos autenticacion ------------------------------------- LDAP
				
				$ldap = ldap_connect($ldap_server);
				$ldaprdn = $ldap_dominio . "\\" . $login;
				ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
				$bind = @ldap_bind($ldap, $ldaprdn, $password);
				//----------------------------------------------------------------------
				if ($bind) {
					//verificamos si elusuario existe y se actualizo en guardian 
					$sql="SELECT us.login_ldap, us.password, us.id_usuario, us.id_perfil, us.nombres, 
						us.id_oficina, ofi.id_almacen, us.activo, us.cambia_pass
					FROM usuarios us LEFT JOIN oficinas ofi ON us.id_oficina = ofi.id_oficina 
					WHERE us.login_ldap = '$login'";
					//echo $sql;
					$query = consulta($sql);
					$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
					if($data['login_ldap'] == $login){
					//Aca el login y password estan bien, creamos las sesiones				
						$_SESSION["idusuario"] = $data['id_usuario'];
						$_SESSION["idperfil"] = $data['id_perfil'];
						$_SESSION["nombreusr"] = $data['nombres'];
						$_SESSION["id_banca"] = '0'; //para bec, aplica cuando se loguea por WS
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
					}else{
						//el usuario no existe o no es el mismo login
						
							$txtaviso = "Usuario no encontrado en Guardian, relacione sus cuentas.";
							require_once('../code/_login_up.php');
							die();
					}
				}else{
					//no se pasaron los parametros LDAP correctos o no ingreso credenciales correctas
					
						$txtaviso = "No se reconoce el nombre de usuario o contrase&ntilde;a!.";
						require_once('../code/_login.php');
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