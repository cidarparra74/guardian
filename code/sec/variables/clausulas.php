<?php
	//clausulas donde est ala variable
	
	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM var_texto WHERE idtexto = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	
	$smarty->assign('id',$id);
	$smarty->assign('idtexto',$resultado["idtexto"]);
	$smarty->assign('contenido',$resultado["contenido"]);
	$smarty->assign('esglobal',$resultado["esglobal"]);
	$smarty->assign('descri',$resultado["descripcion"]);
	$smarty->assign('eslista',$resultado["eslista"]);
	$smarty->assign('lineas',$resultado["lineas"]);
	$smarty->assign('tipo',$resultado["tipo"]);
	$smarty->assign('contenido2','');
	$sql = "SELECT * FROM var_texto_valores WHERE idtexto = '$id' ";
	$query= consulta($sql);
	$valores= $query->fetchRow(DB_FETCHMODE_ASSOC);
	if($valores["valor"]!=''){
		$smarty->assign('contenido',$valores["valor"]);
		$smarty->assign('contenido2',$valores["adicional"]);
	}
	
	//para pasar a html 

	
	$sql = "SELECT idclausula,titulo, descri FROM clausula WHERE contenido LIKE '%<<$id%' ";
	$query= consulta($sql);
	$clausulas = array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		$clausulas[] = array('id'  => $row['idclausula'],
							'titulo' => $row['titulo'],
							'descri' => $row['descri']);
	}
	$smarty->assign('clausulas',$clausulas);
	
	$smarty->display('sec/variables/clausulas.html');
	die();
?>
