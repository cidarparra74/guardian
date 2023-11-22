<?php
	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM usuario WHERE login = '$id' ";
	//echo $sql;
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('id',$id);
	$smarty->assign('nombres',$resultado["appaterno"].' '.$resultado["apmaterno"].' '.$resultado["nombres"]);
	
	
	if($_REQUEST['contrato']=='add'){
		$idcontrato =  $_REQUEST['idcontrato'];
		$sql = "INSERT INTO contratousuario (idusuario, idcontrato) VALUES ('$id', '$idcontrato')";
		ejecutar($sql);
	}else
	if($_REQUEST['contrato']=='del'){
		$idcontrato =  $_REQUEST['idcontrato'];
		$sql = "DELETE contratousuario WHERE idcontrato = '$idcontrato' AND idusuario = '$id'";
		ejecutar($sql);
	}
	
	$sql = " SELECT co.idcontrato, co.titulo 
	FROM contrato co 
	WHERE habilitado = '1' 
	ORDER BY co.titulo ";
	$query= consulta($sql);
	$contratos = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$contratos[] = array('id' => $row["idcontrato"],
							'titulo' => $row["titulo"]);
	}
	$smarty->assign('contratos',$contratos);
	
	$sql = " SELECT co.idcontrato, co.titulo  
	FROM contratousuario cu 
	INNER JOIN contrato co ON cu.idcontrato = co.idcontrato 
	WHERE idusuario = '$id'
	ORDER BY co.titulo ";
	$query= consulta($sql);
	$contratous = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$contratous[] = array('id' => $row["idcontrato"],
							'titulo' => $row["titulo"]);
	}
	
	$smarty->assign('contratous',$contratous);
	
	$smarty->display('sec/usuariosec/contratousr.html');
	die();
?>
