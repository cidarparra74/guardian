<?php

	$id= $_REQUEST['id'];
	
	//recpueramo los datos del informe legal
	$sql= "SELECT il.*, tb.tipo_bien, tb.id_tipo_bien, tb.bien FROM informes_legales  il ".
		" INNER JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien ".
		" WHERE il.id_informe_legal='$id' ";
	//echo $sql;
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	//datos principales
	$id_tipo_bien   = $resultado['id_tipo_bien'];
	$id_us_comun	= $resultado['id_us_comun'];
	$tipo_bien		= $resultado["tipo_bien"];
	$tipo		= $resultado["bien"];

	$cliente		= $resultado['cliente'];
	$ci_cliente		= $resultado['ci_cliente'];
	$id_doc			= $resultado["id_tipo_identificacion"];
	$motivo			= $resultado['motivo'];
//	$importe = explode(" ", $resultado["montoprestamo"]);
	
	if($resultado["fecha"]!= ""){
		$aux_c = explode(" ",$resultado["fecha"]);
		$fecha= dateDMESY($aux_c[0]);
	}else{
		$fecha = "";
	}
	//$fecha_actual= date("d/m/Y");

	$smarty->assign('id',		   $id);
	$smarty->assign('id_us_comun', $id_us_comun);
	$smarty->assign('id_tipo_bien',$id_tipo_bien);
	$smarty->assign('cliente',     $cliente);
	$smarty->assign('ci_cliente',  $ci_cliente);
	$smarty->assign('id_doc',		$id_doc);
	//$smarty->assign('fechaold',		$fecha);
	$smarty->assign('fecha',		$fecha);
	$smarty->assign('motivo',       $motivo);
	//$smarty->assign('montoprestamo',$importe[0]);
	//$smarty->assign('mone',			$importe[1]);
		
	// datos secundarios
	$otras_observaciones	= $resultado['otras_observaciones'];
	$tradicion				= $resultado["tradicion"];
	$garantia_contrato		= $resultado['garantia_contrato'];
	$nota					= $resultado['nota'];
	$conclusiones			= $resultado['conclusiones'];
	$numero_informe			= $resultado["numero_informe"];
	
	$smarty->assign('otras_observaciones',$otras_observaciones);
	$smarty->assign('tradicion',		  $tradicion);
	$smarty->assign('garantia_contrato',  $garantia_contrato);
	$smarty->assign('nota',				  $nota);
	$smarty->assign('conclusiones',		  $conclusiones);
	$smarty->assign('numero_informe',	  $numero_informe);
	$smarty->assign('tipo_bien',	      $tipo_bien);
	$smarty->assign('tipo',	      		  $tipo);

	if(	$tipo == '1'){
		//recuperamo los datos del informe legal del inmueble
		$sql= "SELECT * FROM informes_legales_inmuebles WHERE id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$descripcion_bien	= $resultado['descripcion_bien'];
		$extension			= $resultado['extension'];
		$ubicacion			= $resultado['ubicacion'];
		$registro_dr		= $resultado['registro_dr'];
		$superficie_titulo	= $resultado['superficie_titulo'];
		$superficie_plano	= $resultado['superficie_plano'];
		$limite_este		= $resultado['limite_este'];
		$limite_oeste		= $resultado['limite_oeste'];
		$limite_norte		= $resultado['limite_norte'];
		$limite_sud			= $resultado['limite_sud'];
		$datos_documento	= $resultado["datos_documento"];
		
		$smarty->assign('descripcion_bien',	$descripcion_bien);
		$smarty->assign('extension',		$extension);
		$smarty->assign('ubicacion',		$ubicacion);
		$smarty->assign('registro_dr',		$registro_dr);
		$smarty->assign('superficie_titulo',$superficie_titulo);
		$smarty->assign('superficie_plano',	$superficie_plano);
		$smarty->assign('limite_este',		$limite_este);
		$smarty->assign('limite_oeste',		$limite_oeste);
		$smarty->assign('limite_norte',		$limite_norte);
		$smarty->assign('limite_sud',		$limite_sud);
		$smarty->assign('datos_documento',	$datos_documento); //??

	}elseif($tipo == '2' || $tipo == '3'){
		//recpueramo los datos del informe legal del vehiculo
		$sql= "SELECT * FROM informes_legales_vehiculos WHERE id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$placa= $resultado['placa'];
		$marca= $resultado['marca'];
		$chasis= $resultado['chasis'];
		$modelo= $resultado['modelo'];
		$motor= $resultado['motor'];
		$clase= $resultado['clase'];
		$tipo_a= $resultado['tipo'];
		$color= $resultado['color'];
		$alcaldia= $resultado['alcaldia'];
		
		if($resultado["fecha_vehiculo"] != null || $resultado["fecha_vehiculo"]!= ""){
			$aux_c= explode(" ",$resultado["fecha_vehiculo"]);
			$aux_d= $aux_c[0];
			$fecha_vehiculo= dateDMY($aux_d);
		}
		else{
			$fecha_vehiculo="";
		}
		$crpva= $resultado["crpva"];
		$poliza= $resultado["poliza"];

		$smarty->assign('placa',$placa);
		$smarty->assign('marca',$marca);
		$smarty->assign('chasis',$chasis);
		$smarty->assign('modelo',$modelo);
		$smarty->assign('motor',$motor);
		$smarty->assign('clase',$clase);
		$smarty->assign('tipo_a',$tipo_a);
		$smarty->assign('color',$color);
		$smarty->assign('alcaldia',$alcaldia);
		$smarty->assign('crpva',$crpva);
		$smarty->assign('fecha_vehiculo',$fecha_vehiculo);
		$smarty->assign('poliza',$poliza);

	}
	
	
	//****************************************************************************************************
	// REcuperamos los documentos correspondientes al tipo de bien y los que tenga ya guardados el I.L.
	
	$sql= " SELECT lista1.*, lista2.*
			FROM 
				(SELECT doc.id_documento as iddoc1, doc.documento, doc.vencimiento, doc.meses_vencimiento, tiene_fecha
					FROM (documentos doc
					INNER JOIN tipos_bien_documentos tbd ON tbd.id_documento = doc.id_documento )
				WHERE tbd.id_tipo_bien = $id_tipo_bien) lista1
			INNER JOIN 
				(SELECT id_documento as iddoc2, id_tipo_documento, numero, fecha, fojas, observaciones, 
				fecha_vencimiento, tiene_observacion, tomar_en_cuenta FROM informes_legales_documentos
				WHERE id_informe_legal = $id ) lista2
			ON lista1.iddoc1 = lista2.iddoc2 ORDER BY lista1.documento";
	//echo $sql; 
	$query = consulta($sql);
	
	$docus = array();
	$infor = array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$docus[$i] = array( 'id_documento'		=> $row["iddoc1"],
							'documento' 		=> $row["documento"],
							'vencimiento' 		=> $row["vencimiento"],
							'meses_vencimiento'	=> $row["meses_vencimiento"],
							'tiene_fecha' 		=> $row["tiene_fecha"] );
		//if($row["iddoc2"] == null) {echo "no hay ".$row["iddoc1"];}
		if($row["id_tipo_documento"] != null){
			//pueden haber algunos valores
			$ids_tipo_documento	= $row["id_tipo_documento"];
			$numero				= $row["numero"];
			$tomar_en_cuenta	= $row["tomar_en_cuenta"];
			$observaciones		= $row["observaciones"];
			$fojas				= $row["fojas"];
			$tiene_observacion	= $row["tiene_observacion"];
		}else{
			// no hay ningun valor para informes_legales_documentos
			$ids_tipo_documento	= 0;
			$numero				= '';
			$tomar_en_cuenta	= 0;
			$observaciones		= '';
			$fojas				= 0;
			$tiene_observacion	= 0;
		}
		$aux_a = $row["fecha"];
		//echo $aux_a;
		if($aux_a != null){
			$aux_c		= explode(" ",$aux_a);
			$fechas= dateDMESY(dateDMY($aux_c[0]));
		}
		else{
			$fechas		="";
		}
		$aux_a= $row["fecha_vencimiento"];
		if($aux_a != null){
			$aux_c				= explode(" ",$row["fecha_vencimiento"]);
			$fechas_vencimiento = dateDMESY(dateDMY($aux_c[0]));
		}
		else{
			$fechas_vencimiento	="";
		}
		
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
	//die();
	
	//print_r($forma);
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
	
	
	//recuperamos la lista de personas
	$sql= "SELECT * FROM informes_legales_propietarios tp LEFT JOIN propietarios pr 
				ON tp.id_propietario = pr.id_propietario
	WHERE id_informe_legal='$id' ORDER BY pr.id_propietario ";
	$query = consulta($sql);
	//$result= $link->query($sql);
	$lista_personas=array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$lista_personas[$i]=$row["nombres"]."|".
							$row["ci"]."|".
							$row["direccion"]."|".
							$row["estado_civil"];
		$i++;
	}
	$smarty->assign('lista_personas',$lista_personas);
	$smarty->assign('cantidad_lista',$i);
	/*
	//recuperando los tipos de indentificacion
	$sql= "SELECT * FROM tipos_identificacion ORDER BY identificacion ";
	$query = consulta($sql);
	$i=0;
	$identificacion=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$identificacion[$i] = array('id'  => $row["id_tipo"],
									'nro' => $row["identificacion"]);
		$i++;
	}
	$smarty->assign('identificacion',$identificacion);
*/
	
	$smarty->display('informe_legal/eliminar_informe.html');
	die();
	
?>
