<?php
//id_tipo_bien
	$id= 0;
	$sql= "SELECT tipo_bien, bien FROM tipos_bien WHERE id_tipo_bien = $id_tipo_bien ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$fecha_actual= date("d/m/Y"); 
	$tipo_bien = $row["tipo_bien"];
	$bien = $row["bien"];
	
	$smarty->assign('isblank', '1');
	$smarty->assign('id', $id);
	$smarty->assign('bien',$tipo_bien);
	$smarty->assign('fecha', $fecha_actual);

	if($bien==1) $tipo_bien = 'I';
	elseif($bien==2) $tipo_bien = 'M';
	elseif($bien==3) $tipo_bien = 'V';
	elseif($bien==4) $tipo_bien = 'N';
	elseif($bien==5) $tipo_bien = 'P';
	elseif($bien==6) $tipo_bien = 'S';
	//$tipo_bien = substr($row["tipo_bien"],0,1);

	$smarty->assign('tipo_bien', $tipo_bien);
	$smarty->assign('id_tipo_bien', $id_tipo_bien);
	//vemos si se ppuede adicionar propietarios
	
	$sql= "SELECT TOP 1 enable_prop FROM opciones ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//datos principales
	$enable_prop   = $resultado['enable_prop'];
	$smarty->assign('enable_prop', $enable_prop);
	//*************************************************************
	// REcuperamos los documentos correspondientes al tipo de bien 
	
	$sql= "SELECT doc.id_documento as iddoc1, doc.documento, doc.vencimiento, doc.meses_vencimiento, tiene_fecha, con_numero FROM (documentos doc
	INNER JOIN tipos_bien_documentos tbd ON tbd.id_documento = doc.id_documento )
	WHERE tbd.id_tipo_bien = $id_tipo_bien ORDER BY doc.documento";

	$query = consulta($sql);
	
	$infor = array();
	$docus = array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$docus[$i] = array( 'id_documento'		=> $row["iddoc1"],
							'documento' 		=> $row["documento"],
							'vencimiento' 		=> $row["vencimiento"],
							'meses_vencimiento'	=> $row["meses_vencimiento"],
							'tiene_fecha' 		=> $row["tiene_fecha"],
							'con_numero' 		=> $row["con_numero"]  );

		
			// no hay ningun valor para informes_legales_documentos
			$ids_tipo_documento	= 0;
			$numero				= '';
			$tomar_en_cuenta	= 0;
			$observaciones		= '';
			$fojas				= 0;
			$tiene_observacion	= 0;
			$fechas		="";
			$fechas_vencimiento	= "";
		
		
		$infor[$i] = array( 'ids_tipo_documento'	=> $ids_tipo_documento,
							'numero' 				=> $numero,
							'tomar_en_cuenta' 		=> $tomar_en_cuenta,
							'tiene_observacion' 	=> $tiene_observacion,
							'fechas' 				=> $fechas,
							'fechas_vencimiento' 	=> $fechas_vencimiento,
							'fojas' 				=> $fojas,
							'observaciones' 		=> $observaciones);
		$i++;
	}
	$smarty->assign('cantidad_documentos',$i);
	$smarty->assign('docus',$docus);
	$smarty->assign('infor',$infor);

	//recuperamos los tipos de documentos
	$sql= "SELECT id_tipo_documento, tipo FROM tipos_documentos ORDER BY tipo ";
	$query = consulta($sql);
	//$result= $link->query($sql);
	$tipodocs= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipodocs[$i]= array( 'id'		=> $row["id_tipo_documento"],
							  'tipo'	=> $row["tipo"]);
		$i++;
	}
	$smarty->assign('tipodocs',$tipodocs);
	
	
	// lista de personas
	$i=0;
	$lista_personas=array();
	$smarty->assign('lista_personas',$lista_personas);
	$smarty->assign('cantidad_lista',$i);
	
	/*
	//recuperando los tipos de indentificacion
	$sql= "SELECT * FROM tipos_identificacion ORDER BY identificacion ";
	$query = consulta($sql);
	//$result= $link->query($sql);
	$i=0;
	$identificacion=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$identificacion[$i] = array('id'  => $row["id_tipo"],
									'nro' => $row["identificacion"]);
		$i++;
	}
	$smarty->assign('identificacion',$identificacion);
	*/
	// emision --- cambiamos para nomostrar tipos de id
	$sql= "SELECT * FROM emisiones ";
	$query = consulta($sql);
	$i=0;
	$identificacion=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$identificacion[$i] = array('id'  => $row["emision"],
									'nro' => $row["emision"]);
		$i++;
	}
	$smarty->assign('identificacion',$identificacion);
	
	// para el estado final del I.L (Rojo;amarillo;Verde)
	$sql= "SELECT TOP 1 il_estado_fin FROM opciones ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$il_estado=explode(';',$row["il_estado_fin"]);
	
	$smarty->assign('il_estado',$il_estado);
	
	$smarty->display('informe_legal/elaborar_informe.html');
	die();
	
?>