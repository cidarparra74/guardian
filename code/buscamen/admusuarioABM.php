<?php
	session_start();
	// Todos los ABM requieren llamado a libreria de base de datos
	require_once('../lib/conexionMNU.php');
	require_once('../lib/verificar.php');
	
	// cargarmos libreria propias de este modulo y variables locales
	require_once('../lib/codificar.php');
	$noBorrar = 0;
	
	//Recuperamos la tarea a realizar
	if(isset($_REQUEST['task'])){
		$task = $_REQUEST['task'];
	}
	
	//LLamamos al procedimiento adecual a la tarea
	if($task == 'add'){
		// preparamos registros para ADICION
		$nombre 	= '';
		$login		= '';
		$password 	= '';
		$activo 	= 'S';
		$idperfil 	= 0;
		$titulomsg = "Adicionar nuevo usuario";
	}else{
		// preparamos registros para EDITAR O MOSTRAR
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$sql = "SELECT * FROM usuario WHERE idusuario = $id ";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$nombre 	= decode($row['nombre']);
			$USlogin	= decode($row['login']);
			$password 	= '*';  //no se puede cambiar el password en edicion
			$activo 	= $row['activo'];
			$idperfil 	= $row['idperfil'];
			$idpersona 	= $row['idpersona'];
		}
		if($task == 'mod'){
			$titulomsg = "Modificar Datos de usuario";
		}elseif($task == 'del'){
			$titulomsg = "Deshabilitar usuario";
			// no se puede deshabilitar a si mismo
			if($_SESSION["idusuario"] == $id){
				$noBorrar = 1;
			}
		}else{ //$task == 'pss'
			$titulomsg = "Cambiar Contraseña";
		}
	}
	//---------------------
	//---------------------
	// cargamos tablas de apoyo
	//---------------------
	//Leemos tabla de perfiles
	$sql = "SELECT idperfil, nombre 
			FROM perfil 
			WHERE activo = 'S' ORDER BY nombre";
	$query = consulta($sql);
	$perfiles = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$perfiles[] = array('idperfil'  => $row['idperfil'],
							'nombre'	=> $row['nombre']);
	}
	//--------------------------------------------------------------
	//Leemos tabla de empleados
	$sql = "SELECT idpersona, nombre  
			FROM persona
			WHERE idpersona in 
				(SELECT idpersona FROM empleado)
			ORDER BY nombre";
	$query = consulta($sql);
	$empleados = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$empleados[] = array('idpersona'  => $row['idpersona'],
							'nombre'	=> $row['nombre']);
	}
	//--------------------------------------------------------------
	
	require_once("../lib/setup.php");
	
	$smarty = new bd;
	
	//pasamos datos principales al form
	$smarty->assign('nombre'	,$nombre);
	$smarty->assign('USlogin'	,$USlogin);
	$smarty->assign('password'	,$password);
	$smarty->assign('activo'	,$activo);
	$smarty->assign('idperfil'	,$idperfil);
	$smarty->assign('idpersona'	,$idpersona);
			
	//pasamos datos generales
	$smarty->assign('perfiles', $perfiles);
	$smarty->assign('empleados',$empleados);
	$smarty->assign('id',		$id);
	$smarty->assign('task',		$task);
	$smarty->assign('status',   $status);
	$smarty->assign('noBorrar', $noBorrar);
	$smarty->assign('titulomsg',$titulomsg);
	$smarty->assign('pagina',	$_REQUEST['pagina']);
	
	$smarty->display('../templates/admusuarioABM.html');

?>
