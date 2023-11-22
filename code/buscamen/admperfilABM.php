<?php
	session_start();
	// Todos los ABM requieren llamado a libreria de base de datos
	require_once('../lib/conexionMNU.php');
	require_once('../lib/verificar.php');
	
	// cargarmos libreria propias de este modulo y variables locales
	$noBorrar = 0;

	//Recuperamos la funcion a realizar en este modulo (insertar, editar o eliminar)
	if(isset($_REQUEST['task'])){
		$task = $_REQUEST['task'];
	}
	
	$id='';
	//LLamamos al procedimiento adecual a la tarea
	if($task == 'add'){
		// preparamos registros para ADICION
		$nombre 	= '';
		$idperfil 	= 0;
		$titulomsg = "Adicionar nuevo perfil";
	}else{
		// preparamos registros para EDITAR O MOSTRAR
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$sql = "SELECT perfil FROM perfiles WHERE id_perfil = $id ";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$nombre = $row['perfil'];
		//$id 	= $row['idperfil'];
		}
		if($task == 'mod'){
			$titulomsg = "Modificar nombre de perfil";
		}else{
			$titulomsg = "Eliminar perfil";
			// verificamos Integridad Referencial
			// no se puede eliminar si al menos un usuario tiene este perfil
			$sql = "SELECT COUNT(*) as cantidad FROM usuarios WHERE id_perfil = $id ";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($row['cantidad'] > 0){
				$noBorrar = 1;
			}
		}
	}
	
	require_once("../lib/setup.php");
	$smarty = new bd;
	
	//pasamos datos principales al form
	$smarty->assign('nombre'	,$nombre);

	//pasamos datos generales
	$smarty->assign('id',		$id);
	$smarty->assign('task',		$task);
	$smarty->assign('noBorrar', $noBorrar);
	$smarty->assign('titulomsg',$titulomsg);
	$smarty->assign('pagina',	$_REQUEST['pagina']);
	
	$smarty->display('../templates/admperfilABM.html');

?>
