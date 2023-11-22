<?php

	$id= 0;
	$sql= "SELECT tipo_bien, bien FROM tipos_bien WHERE id_tipo_bien = $id_tipo_bien ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$tipo_bien = $row["tipo_bien"];
	$bien = $row["bien"];
	
	$fecha_actual= date("d/m/Y"); 
	
	$smarty->assign('isblank', '1');
	$smarty->assign('id', $id);
	$smarty->assign('bien',$tipo_bien);
	$smarty->assign('fecha', $fecha_actual);

	$tipo_bien = $bien;
	
	$smarty->assign('tipo_bien', $tipo_bien);
	$smarty->assign('id_tipo_bien', $id_tipo_bien);
	
	//vemos si se ppuede adicionar propietarios
	$sql= "SELECT TOP 1 enable_prop FROM opciones ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
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

	
		// sociedades
		$sql= "SELECT * FROM sociedades ";
		//if($tipoPer =='1' or $tipoPer == '2') $sql .= " WHERE tipo = '$tipoPer'";
		$query = consulta($sql);
		$sociedades=array();
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$sociedades[] = array('id'  => $row["id_sociedad"],
									'sociedad' => $row["sociedad"]);
		}
		$smarty->assign('sociedades',$sociedades);
		/*
		//poderes
		$sql= "SELECT po.id_poder, po.numero, po.fojas, td.tipo, convert(varchar(10),po.fecha,103) as fechap
			FROM poderes po 
			INNER JOIN tipos_documentos td ON td.id_tipo_documento  = po.id_tipo_documento
			WHERE po.id_informe_legal = '$id' ORDER BY po.fecha ";
		$query = consulta($sql);
		*/
		$poderes= array();
		/*
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$poderes[]= array( 'id'		=> $row["id_poder"],
							  'numero'	=> $row["numero"],
							  'fecha'	=> $row["fechap"],
							  'tipo'	=> $row["tipo"],
							  'fojas'	=> $row["fojas"]);
		}
		*/
		$smarty->assign('poderes',$poderes);
	
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
	
	
	
	// para el estado final del I.L (Rojo;amarillo;Verde)
	$sql= "SELECT TOP 1 il_estado_fin FROM opciones ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$il_estado=explode(';',$row["il_estado_fin"]);
	
	$smarty->assign('il_estado',$il_estado);
	
	$smarty->display('informe_legal/elaborar_informe_pj.html');
	die();
	
?>