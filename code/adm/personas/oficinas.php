<?php
	
	// Todos los ABM requieren llamado a libreria de base de datos
	
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/conexionMNU.php');

	if(isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
		$ido = $_REQUEST['ido']; //id del almacen
	}else{die();}
$sql =  "SELECT apellidos, nombres FROM personas WHERE id_persona = '$id'";
$query= consulta($sql);
$row = $query->fetchRow(2);
if($row["apellidos"]!=''){
	$smarty->assign('nombres',$row["apellidos"].' '.$row["nombres"]);
	
}
	// datos de la persona
	$sql =  "";

	$sql = "SELECT al.nombre as ciudad, ofi.nombre, ofi.id_oficina, op.id_oficina as asigna
			FROM oficinas ofi 
			LEFT JOIN almacen al 
				ON ofi.id_almacen = al.id_almacen
			LEFT JOIN oficina_persona op 
				ON op.id_oficina = ofi.id_oficina AND op.id_responsable = '$id' 
			WHERE ofi.id_almacen = '$ido' 
			ORDER BY al.nombre, ofi.nombre";

	$query= consulta($sql);
	$listaofis = array();
	$i=0;

	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if($row['asigna'] != ''){
			$tiene = 1 ; 
		}else{
			$tiene = 0 ;
		}
		$listaofis[$i] = array('id'	  => $row["id_oficina"],
							'almacen' => $row["ciudad"],
							'nombre'  => $row["nombre"],
							'tiene'	  => $tiene);
		$i++;
	}

	//pasamos datos principales al form
	$smarty->assign('listaofis',$listaofis);
	$smarty->assign('id',$id);
		
	$smarty->display('./adm/personas/oficinas.html');
	die();
?>
