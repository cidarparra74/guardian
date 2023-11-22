<?php
	$id= $_REQUEST['id'];
	$que= $_REQUEST['modificar'];
	
	$sql = "SELECT * FROM clausula WHERE idclausula = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('id',$id);
	$smarty->assign('que',$que);
	$smarty->assign('titulo',$resultado["titulo"]);
	
if($que=='dat'){
	$smarty->assign('descri',$resultado["descri"]);
	$smarty->assign('tipo',$resultado["codtipo"]);
	$smarty->assign('entidad',$resultado["codentidad"]);
	$smarty->assign('materia',$resultado["codmateria"]);
	
	$sql = "SELECT * 
	FROM tipo
	ORDER BY descripcion ";
	$query= consulta($sql);
	$tipo = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipo[] = array('id' => $row["codigo"],
							'desc' => $row["descripcion"]);
	}
	$smarty->assign('tipo',$tipo);
	
	$sql = "SELECT * 
	FROM materia
	ORDER BY descripcion ";
	$query= consulta($sql);
	$materia = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$materia[] = array('id' => $row["codigo"],
							'desc' => $row["descripcion"]);
	}
	$smarty->assign('materia',$materia);
	
	$sql = "SELECT * 
	FROM entidad
	ORDER BY descripcion ";
	$query= consulta($sql);
	$entidad = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$entidad[] = array('id' => $row["codigo"],
							'desc' => $row["descripcion"]);
	}
	$smarty->assign('entidad',$entidad);
	
	$smarty->display('sec/clausulas/modificar.html');
}else{
	
	$smarty->assign('contenido',$resultado["contenido"]);
	$smarty->display('sec/clausulas/modificarc.html');
}
	die();
?>
