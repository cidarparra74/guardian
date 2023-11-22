<?php

	chdir('../../');
	require_once("../lib/setup.php");
	$smarty = new bd;
	
	if(isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
	}else{
		$id = '';
	}

	//recpueramos el documento
	$sql= "SELECT doc.documento, doc.requerido, doc.imagen ".
		"FROM documentos doc WHERE doc.id_documento = $id ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('doc',$row["documento"]);
	$smarty->assign('img',$row["imagen"]);
	
	$smarty->display('adm/documentos/verimagen.html');
	die();
?>
