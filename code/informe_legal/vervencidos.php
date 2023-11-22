<?php

//$id= $_REQUEST["id"];
$smarty->assign('id',$id);

//recuperamos datos basicos del I.L.
$sql =  "SELECT ile.cliente, ile.ci_cliente, convert(varchar,ile.fecha,103) fecha, tii.identificacion, tbi.tipo_bien, us.nombres ".
		"FROM informes_legales ile LEFT JOIN usuarios us on ile.id_us_comun = us.id_usuario ".
		"LEFT JOIN tipos_identificacion tii ON id_tipo = id_tipo_identificacion ".
		"LEFT JOIN tipos_bien tbi ON tbi.id_tipo_bien = ile.id_tipo_bien ".
		"WHERE id_informe_legal = $id ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	/*$result= $link->query($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);*/
	
	$smarty->assign('cliente',$resultado["cliente"]);
	$smarty->assign('ci_cliente',$resultado["ci_cliente"]);
	$smarty->assign('identificacion',$resultado["identificacion"]);
	$smarty->assign('fecha',$resultado["fecha"]);
	$smarty->assign('tipo_bien',$resultado["tipo_bien"]);
	$smarty->assign('nombre', $resultado["nombres"]) ;
							
	//recuperamos documentos del I.L.
$sql =  "SELECT ild.id_informe_legal, observaciones, 
doc.documento, tdoc.tipo, numero, convert(varchar,ild.fecha,103) fechadoc, fojas, 
convert(varchar,fecha_vencimiento,103) vencimiento 
FROM  informes_legales_documentos ild 
LEFT JOIN documentos doc 
on doc.id_documento = ild.id_documento 
INNER JOIN tipos_documentos tdoc 
on tdoc.id_tipo_documento = ild.id_tipo_documento 
WHERE fecha_vencimiento < getdate() 
AND id_informe_legal = $id ";
	$query = consulta($sql);
	//$result= $link->query($sql);
	$docsConObs = array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$docsConObs[]=array('documento' => $row["documento"] ,
							'numero' 	=> $row["numero"],
							'tipo' 		=> $row["tipo"],
							'fechadoc' 	=> $row["fechadoc"],
							'fojas' 	=> $row["fojas"],
							'vencimiento' => $row["vencimiento"],
							'obs' 		=> $row["observaciones"]);
	}
	$smarty->assign('docsConObs',$docsConObs);
	
	$smarty->display('informe_legal/vervencidos.html');
	die();

?>
