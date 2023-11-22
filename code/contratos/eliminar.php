<?php
	$id= $_REQUEST['id'];
	
	$sql = "SELECT co.titulo, 
CONVERT(VARCHAR(10), cf.fechahora, 103) AS fecha , 
CONVERT(VARCHAR(10), cf.fechahora, 108) AS hora, 
CASE cf.firmado WHEN 0 THEN 'No' ELSE 'Si' END firma,
cf.ultimo_login AS modifica, 
(CASE WHEN PATINDEX('%<personas>%', cf.contenido_sec) > 0 THEN substring(cf.contenido_sec, patindex('%<nombre>%', cf.contenido_sec)+ 8, (patindex('%</nombre>%', cf.contenido_sec)-patindex('%<nombre>%', cf.contenido_sec)-8)) ELSE '' END) cliente
FROM contrato_final cf LEFT JOIN contrato co 
ON cf.idcontrato = co.idcontrato WHERE cf.idfinal='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$titulo= $resultado["titulo"];
	$modifica= $resultado["modifica"];
	$hora= $resultado["hora"];
	$fecha= $resultado["fecha"];
	$firma= $resultado["firma"];
	$cliente= $resultado["cliente"];
	
	$smarty->assign('cliente',$cliente);
	$smarty->assign('titulo',$titulo);
	$smarty->assign('modifica',$modifica);
	$smarty->assign('hora',$hora);
	$smarty->assign('fecha',$fecha);
	$smarty->assign('firma',$firma);
	$smarty->assign('id',$id);
	
	$smarty->display('contratos/eliminar.html');
	die();
?>
