<?php
//session_start();

require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');


	//cargando para el overlib
	include("../lib/cargar_overlib.php");
	$id = $_REQUEST['id'];
	//echo $id;
	/*********************************operaciones de la ventana*************/
	/*********************************operaciones de la ventana*************/
	//adicionar
	if(isset($_REQUEST['adicionar_rep'])){
		include("adm/oficinas/adicionar_rep.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("adm/oficinas/adicionando_rep.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar_rep'])){
		include("adm/oficinas/modificar_rep.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("adm/oficinas/modificando_rep.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar_rep'])){
		include("adm/oficinas/eliminar_rep.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("adm/oficinas/eliminando_rep.php");
	}
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/ 
	
	// que oficinas
	$sql = "SELECT * FROM oficinas WHERE id_oficina = '$id' ";
	$query= consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$oficina =  $row["nombre"];
	
	//que bancas para esta oficina
	$sql = "SELECT bc.banca, re.* 
	FROM representa re LEFT JOIN bancas bc ON bc.id_banca = re.id_banca 
	WHERE id_oficina = $id
	ORDER BY bc.banca, re.nombre ";
	$query= consulta($sql);
	
	$representa = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$representa[] = array('id_oficina' => $row["id_oficina"],
				'id_banca' => $row["id_banca"],
							'banca' => $row["banca"], 
							'nombre' => $row["nombre"],
							'id_representa' => $row["id_representa"]);
	}
	
	$smarty->assign('representa',$representa);
	$smarty->assign('oficina',$oficina);
	$smarty->assign('id',$id);
	
	$smarty->display('adm/oficinas/representa.html');
	die();
	
?>
