<?php

$id= $_REQUEST["id"];
$smarty->assign('id',$id);


	//recuperamos datos basicos del I.L.
$sql =  "SELECT ile.cliente, ile.ci_cliente, tii.identificacion, tbi.tipo_bien, ".
			"ile.otras_observaciones, ile.conclusiones ".
			"FROM informes_legales ile ".
			"LEFT JOIN tipos_identificacion tii ON id_tipo = id_tipo_identificacion ".
			"LEFT JOIN tipos_bien tbi ON tbi.id_tipo_bien = ile.id_tipo_bien ".
			"WHERE id_informe_legal = $id ";

	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('cliente',$resultado["cliente"]);
	$smarty->assign('ci_cliente',$resultado["ci_cliente"]);
	$smarty->assign('identificacion',$resultado["identificacion"]);
	$smarty->assign('tipo_bien',$resultado["tipo_bien"]);
	$smarty->assign('otras_observaciones',$resultado["otras_observaciones"]);
	$smarty->assign('conclusiones',$resultado["conclusiones"]);

//Recuperamos lista de documentos que tiene observaciones
$sql= "SELECT
    CONVERT(nvarchar(10),informes_legales_documentos.fecha,103) as fecha, 
	informes_legales_documentos.fojas, informes_legales_documentos.observaciones,
	CONVERT(nvarchar(10),informes_legales_documentos.fecha_vencimiento,103) as fecha_vencimiento,
    documentos.documento,
    tipos_documentos.tipo
FROM
    { oj (informes_legales_documentos INNER JOIN tipos_documentos ON
        informes_legales_documentos.id_tipo_documento = tipos_documentos.id_tipo_documento)
     INNER JOIN documentos documentos ON
        informes_legales_documentos.id_documento = documentos.id_documento}
WHERE
    informes_legales_documentos.tiene_observacion = 1 AND
    informes_legales_documentos.id_informe_legal = $id AND
    informes_legales_documentos.tomar_en_cuenta = 1 AND
    informes_legales_documentos.fojas <> 0
	";

	$query = consulta($sql);
	$docsConObs = array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$docsConObs[]=array('fecha' => $row["fecha"] ,
							'fojas' => $row["fojas"] ,
							'observaciones' => $row["observaciones"] ,
							'fecha_vencimiento' => $row["fecha_vencimiento"] ,
							'documento' => $row["documento"] ,
							'tipo' => $row["tipo"]);
	}
	$smarty->assign('docsConObs',$docsConObs);
	
	//Recuperamos lista de documentos que son requeridos pero faltan
$sql= "SELECT
    documentos.documento
FROM
    { oj informes_legales_documentos INNER JOIN documentos ON
        informes_legales_documentos.id_documento = documentos.id_documento}
WHERE
    informes_legales_documentos.fojas = 0 AND
    informes_legales_documentos.id_informe_legal = $id AND
    informes_legales_documentos.tomar_en_cuenta = 1
	";
	$query = consulta($sql);
	//$result= $link->query($sql);
	$docsFaltan = array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$docsFaltan[$i] = $row["documento"];
		$i++;
	}
	$smarty->assign('docsFaltan',$docsFaltan);
	
	// Recuperamos usuarios que pueden responder excepciones
	$sql =  "SELECT id_usuario, (apellidos+' '+nombres) as nombre FROM usuarios ".
			"WHERE excepciones = 1 AND activo='S' ";
	$query = consulta($sql);
	//$result= $link->query($sql);
	$revisores = array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$revisores[$i] = array('id_usuario' => $row["id_usuario"],
								'nombre' => $row["nombre"] );
		$i++;
	}
	$smarty->assign('revisores',$revisores);
		
	// Recuperamos la solicitud y/o respuesta que  pueda tener
	//if ($excepciones == 1){
		//este usuario responde solicitudes
		$sql =  "SELECT ile.*, (us.apellidos+' '+us.nombres) as nombresol FROM informes_legales_excepciones ile ".
			"LEFT JOIN usuarios us ON ile.id_us_solicita = us.id_usuario WHERE id_informe_legal = $id ";
	
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('fecha',$resultado["fecha"]);
	$smarty->assign('estado',$resultado["estado"]);
	$smarty->assign('solicitud',$resultado["solicitud"]);
	$smarty->assign('respuesta',$resultado["respuesta"]);
	$smarty->assign('nombresol',$resultado["nombresol"]);
	$smarty->assign('plazo',$resultado["plazo"]);
	
	//este usuario es el destiantario de la sol de excep
		$sql =  "SELECT (us.apellidos+' '+us.nombres) as nombredes FROM informes_legales_excepciones ile ".
			"LEFT JOIN usuarios us ON ile.id_us_destino = us.id_usuario WHERE id_informe_legal = $id ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('nombredes',$resultado["nombredes"]);
	
	//este usuario es el que respondio la  de excep
		$sql =  "SELECT (us.apellidos+' '+us.nombres) as nombrerev FROM informes_legales_excepciones ile ".
			"LEFT JOIN usuarios us ON ile.id_us_revisa = us.id_usuario WHERE id_informe_legal = $id ";
		$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('nombrerev',$resultado["nombrerev"]);
	
	$smarty->display('informe_legal/excepcion.html');
	die();

?>
