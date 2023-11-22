<?php

//
//     Victor Rivas
//

//if (session_status() == PHP_SESSION_NONE) 
//if(!isset($_SESSION)){
//    session_start();
//}
	$_SESSION = array();
	ini_set("session.gc_maxlifetime", 18000); 
	require_once("../lib/setup.php");
	require_once('../lib/codificar.php');
	require_once("../lib/class.inputfilter.php");
	$ifilter = new InputFilter();
	
	/*
	if (isset($_REQUEST['RESET'])){
		//$_SESSION = array();
	}
	
	if(isset($_SESSION['intento']))
		$_SESSION['intento'] = $_SESSION['intento'] - 1;
	else
		$_SESSION['intento'] = 3;
	$intento = $_SESSION['intento'];
	*/
	$smarty = new bd;

	
	//consultamos si el WS de logueo esta habilitado o si LDAP esta activo
	$sql="SELECT TOP 1 enable_login FROM opciones ";
	$query = consulta($sql);
	$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_login = $data['enable_login'];

	//si quiere iniciar sesion con cuenta de guardian
	
	if (isset($_REQUEST['ctaguardian'])){
		//esto para bisa
		$enable_login = $_REQUEST['ctaguardian'];
	}
	
	
	//si la autenticacion es mediante cuenta guardian y esta cambiando pass
	if($enable_login == 'N' and isset($_REQUEST['chlog'])){
		//estamos cambiando la contrase�a
		$password0 = $ifilter->process($_POST['password0']);
		$id_usuario = $ifilter->process($_POST['id_usuario']);
		//$password0 = trim($_REQUEST['password0']);
		//$id_usuario = trim($_REQUEST['id_usuario']);
		//
		$sql="SELECT us.password FROM usuarios us WHERE id_usuario='$id_usuario'";
		$query = consulta($sql);
		$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
		//verificamos 
		if($row['password'] == crypt($password0,"vic")) {
			$password1 = trim($_REQUEST['password1']);
			$password1 = crypt($password1,"vic");
			$sql= "UPDATE usuarios SET password='$password1', cambia_pass='N' 
			WHERE id_usuario='$id_usuario'";
			ejecutar($sql);
			$txtaviso = "Contrase&ntilde;a cambiada, verifique su ingreso al sistema.";
		}else{
			$txtaviso = "No se ha podido cambiar su Contrase&ntilde;a, intente de nuevo.";
		}
	}
	if($enable_login == 'L' and isset($_REQUEST['chlog'])){
	//echo "in";
		//esta enenlazando sus cuentas win y guardian
		$password = trim($_REQUEST['passldap']);
		$login_ldap = strtoupper(trim($_REQUEST['username']));
		$username = strtoupper(trim($_REQUEST['username2']));
		$password2 = trim($_REQUEST['password2']);
		//verificamos parametros para ldap
		//echo $login_ldap.' '.$password. ' '.$username.' '.$password2;
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
				$ldaprdn = $ldap_dominio . "\\" . $login_ldap;
				ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
				$bind = @ldap_bind($ldap, $ldaprdn, $password);
				//----------------------------------------------------------------------
				if ($bind) {
					$sql="SELECT us.login, us.password, us.id_usuario
					FROM usuarios us WHERE us.login = '$username'";
					//echo $sql;
					$query = consulta($sql);
					$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
					//echo $data['login'] .' '. $data['password'].'  '.crypt($password2,"vic");
					if($data['login'] == $username and $data['password'] == crypt($password2,"vic")){
						$sql = "UPDATE usuarios SET login_ldap = '$login_ldap' where id_usuario = ".$data['id_usuario'];
						ejecutar($sql);
						$txtaviso = "Cuenta enlazada, verifique su ingreso al sistema.";
					}else{
						//usuario y/o passs guardian incorrectos o NO existe en guardian
							$txtaviso = "Inv&aacute;lido nombre de usuario o contrase&ntilde;a!";
							require_once('../code/_login_up.php');
							die();
					}
				}
	}
	//
	if($enable_login == 'L'){
			
		if(file_exists("../confildap.ini")){
			$smarty->assign('ldap','S');
		}else{
			$txtaviso = "No se ha encontrado la configuraci&oacute;n para inicio de sesi&oacute;n en windows.";
			$smarty->assign('ldap','');
		}
	}else{
			$smarty->assign('ldap','');
	}
	
	if(!isset($txtaviso))
		$txtaviso = "";

	
	if(isset($intento))
		$intento = $intento + 1;
	else
		$intento = 0;
		
	//para evitar incremento desde el navegador
//	if($intento > 3) $intento = 1;
	//if($intento < 1) $txtaviso .= "ERROR EN INICIO DE SESION.";
	$smarty->assign('txtaviso',$txtaviso);
	$smarty->assign('intento',4-$intento);
	$smarty->assign('ctaguardian',$enable_login);
	$smarty->display('../templates/_login.html');
	die();
	
?>