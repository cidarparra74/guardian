<?php
	session_start();
	// Todos los ABM requieren llamado a libreria de base de datos
	require_once('../lib/conexionMNU.php');
	require_once('../lib/verificar.php');
	
	// cargarmos libreria propias de este modulo y variables locales


	if(isset($_REQUEST['moduloSel'])){
		$moduloSel = $_REQUEST['moduloSel'];
	}else{ $moduloSel='1000'; }
	
	//  MOSTRAR
	if(isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
		$sql = "SELECT TOP 1 perfil FROM perfiles WHERE id_perfil = $id ";
		$query = consulta($sql);
		$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
		$nombre = $row['perfil'];
		$titulomsg = "Definir permisos para el perfil " . $nombre ;
	}else{die();}

	//Guardamos los cambios
	if(isset($_REQUEST['ejecutaABM'])){
		//Borramos todas las asignaciones previas
		$sql="DELETE FROM permiso WHERE codigo >= $moduloSel AND codigo < ($moduloSel + 1000) AND idperfil = $id ";
		ejecutar($sql);
		//echo $sql;
		$opciones = $_REQUEST['opcion'];

		if(count($opciones)>0){
			//Hay al menos una opcion marcada para este modulo
			//Insertamos el modulo
			$sql="INSERT INTO permiso (codigo, idperfil) VALUES ($moduloSel, $id)";
			ejecutar($sql);
			//echo "---".$sql;
			//insertamos las opciones marcadas para este modulo
			$Menu = '0000';
			$MenuAnt = '0000';
			foreach($opciones as $indice => $valor) {
				$Menu = substr($valor, 0, 2)."00";
				if($Menu != $MenuAnt){
					//Insertamos el menu
					$sql="INSERT INTO permiso (codigo, idperfil) VALUES ($Menu, $id)";
					ejecutar($sql);
					//echo "<br> ".$sql;
					$MenuAnt = $Menu;
				}
				//Insertamos la Opcion 
			   $sql="INSERT INTO permiso (codigo, idperfil, insertar, editar, eliminar) VALUES ($valor, $id,'S', 'S', 'S')";
			   ejecutar($sql);
			   //echo "<br> ".$sql;
			} 
			//die();
		}
	}
	
	// Lista demodulos existentes y disponibles
	$sql =  "SELECT descripcion, imagen, codigo ".
			"FROM barramenu WHERE activo ='S' and nivel = 1";
	$query = consulta($sql);
	$Modulos = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$Modulos[] =  array('codigo'	=> $row['codigo'],
							'nombre'	=> $row['descripcion'],
							'imagen'	=> $row['imagen']);
	}
	
	// listado de todas las opciones y las que ya se asignaron al perfil
	$sql =  "SELECT bm.nivel, bm.descripcion, bm.imagen, bm.codigo, ".
			"      pe.codigo as tiene, pe.insertar, pe.editar, pe.eliminar ".
			"FROM barramenu bm LEFT JOIN ". 
			"(SELECT codigo, insertar, eliminar, editar FROM permiso ".
			"  WHERE idperfil = $id) pe ON bm.codigo = pe.codigo ".
			"WHERE bm.codigo > $moduloSel AND bm.codigo < ($moduloSel + 1000) ".
			"ORDER BY bm.codigo";
	//echo $sql;
	$query = consulta($sql);
	$TablaDatos = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if($row['tiene'] != NULL){
			$tiene = 1 ; 
			$ins = $row['insertar'];
			$edi = $row['editar'];
			$eli = $row['eliminar'];
		}else{ 
			$tiene = 0 ;
			$ins = 'N';
			$edi = 'N';
			$eli = 'N';
		}
		$TablaDatos[] = array('codigo'	=> $row['codigo'],
							'nombre'	=> $row['descripcion'],
							'imagen'	=> $row['imagen'],
							'nivel'		=> $row['nivel'],
							'tiene'		=> $tiene,
							'insertar'	=> $ins,
							'editar'	=> $edi,
							'eliminar'	=> $eli);
	}
	
	
	
	require_once("../lib/setup.php");
	$smarty = new bd;
	
	//pasamos datos principales al form
	$smarty->assign('nombre'	,$nombre);
	$smarty->assign('Modulos'	,$Modulos);
	$smarty->assign('TablaDatos',$TablaDatos);
	
	//pasamos datos generales
	$smarty->assign('id',		$id);
	$smarty->assign('moduloSel',$moduloSel);
	$smarty->assign('titulomsg',$titulomsg);
	$smarty->assign('pagina',	$_REQUEST['pagina']);  //Para paginacion de admperfil.php
	
	$smarty->display('../templates/admpermisoABM.html');

?>
