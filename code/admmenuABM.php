<?php
	session_start();
	// Todos los ABM requieren llamado a libreria de base de datos
	//require_once('../lib/conexionMNU.php');
	require_once("../lib/setup.php");
	require_once('../lib/verificar.php');
	
	// cargarmos libreria propias de este modulo y variables locales
	$noBorrar = 0;

	//Recuperamos la funcion a realizar en este modulo (insertar, editar o eliminar)
	if(isset($_REQUEST['task'])){
		$task = $_REQUEST['task'];
	}
	//Recuperamos el nivel para determinar si es modulo, menu u opcion
	if(isset($_REQUEST['subniv'])){
		$subniv = $_REQUEST['subniv'];
	}
	$id='';
	//LLamamos al procedimiento adecual a la tarea
	if($task == 'add'){
		// preparamos registros para ADICION
		//vemos que codigo es el que sigue
		if(isset($_REQUEST['idniv'])){
			$idniv = $_REQUEST['idniv'];
		}else{$idniv = 0;}
		if($subniv =='1'){
		$sql = "SELECT MAX(codigo) AS maxid FROM barramenu WHERE nivel = '1'";
		$inc = 1000;
		}elseif($subniv =='2'){
		$sql = "SELECT MAX(codigo) AS maxid FROM barramenu WHERE codigo >= $idniv AND codigo < ($idniv + 1000) AND nivel = $subniv ";
		$inc = 100;
		}else{
		$sql = "SELECT MAX(codigo) AS maxid FROM barramenu WHERE codigo >= $idniv AND codigo < ($idniv + 100) AND nivel = $subniv ";
		$inc = 1;
		}
		//echo $sql;
		$query = consulta($sql);
		$row=$query->fetchRow(DB_FETCHMODE_ASSOC);
		$nextID = $row['maxid'];
		//echo $row['maxid']." ?";
		if ($nextID == ''){
			$nextID = $idniv;
		}
		$codigo 	= $nextID + $inc;;
		$descripcion 	= '';
		$imagen 	= '';
		$comando 	= '';
		$verimagen 	= '';
		$vertexto 	= '';
		$activo 	= 'S';
		$titulomsg	= "Adicionar nuevo perfil";
	}else{
		// preparamos registros para EDITAR O MOSTRAR
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$sql = "SELECT * FROM barramenu WHERE codigo = $id ";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$codigo = $row['codigo'];
			$descripcion = $row['descripcion'];
			$imagen = $row['imagen'];
			$comando = $row['comando'];
			$verimagen = $row['verimagen'];
			$vertexto = $row['vertexto'];
			$activo = $row['activo'];
		}
		if($task == 'mod'){
			$titulomsg = "Modificar Descripci&oacute;n";
		}else{
			$titulomsg = "Eliminar Opci&oacute;n";
		}
	}
	
	$smarty = new bd;
	
	//pasamos datos principales al form
	$smarty->assign('codigo'	,$codigo);
	$smarty->assign('descripcion'	,$descripcion);
	$smarty->assign('imagen'	,$imagen);
	$smarty->assign('comando'	,$comando);
	$smarty->assign('verimagen'	,$verimagen);
	$smarty->assign('vertexto'	,$vertexto);
	$smarty->assign('activo'	,$activo);
	
	//pasamos datos generales
	$smarty->assign('id',		$id);
	$smarty->assign('task',		$task);
	$smarty->assign('subniv',	$subniv);
	$smarty->assign('noBorrar', $noBorrar);
	$smarty->assign('titulomsg',$titulomsg);
	$smarty->assign('moduloSel',	$_REQUEST['moduloSel']);
	
	$smarty->display('../templates/admmenuABM.html');

?>
