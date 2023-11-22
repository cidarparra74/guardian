<?php


//echo $_REQUEST["fileName"].'o';
if(!isset($_REQUEST["fileName"])){
	$bien = substr($tipo_bien,0,1);
	if($tipo_bien == "3"){
		//vehiculos
		$smarty->assign('tipo_bien','Vehiculos');
		$reporte = "imp_informe_vehiculo.html";
	}elseif($tipo_bien == "1"){
		//inmuebles
		$smarty->assign('tipo_bien','Inmuebles');
		$reporte = "imp_informe_inmueble.html";
	}elseif($tipo_bien == "2"){
		//maquinaria
		$smarty->assign('tipo_bien','Maquinaria');
		$reporte = "imp_informe_maquinaria.html";
	}elseif($tipo_bien == "4"){
		//ninguno/otro
		$smarty->assign('tipo_bien','Otros');
		$reporte = "imp_informe_otros.html";
	}elseif($tipo_bien == "5"){
		//personeria
		$smarty->assign('tipo_bien','Personer&iacute;a Jur&iacute;dica');
		$reporte = "imp_informe_personeria.html";
	}elseif($tipo_bien == "6"){
		//semovientes
		$smarty->assign('tipo_bien','Semoviente');
		$reporte = "imp_informe_semoviente.html";
	}else{
		// ESTO NO SE DEBE DAR
		$smarty->assign('tipo_bien','Otros');
		$reporte = "imp_informe_otros.html";
	}

	$smarty->assign('id',$id);
	$smarty->assign('reporte',$reporte);
	$smarty->assign('tipo_bien',$tipo_bien);

	$smarty->display('informe_legal/imprimir_bien2.html');
	die();
}

if(!isset($_REQUEST["habilitando_informe"])){
	chdir('..');
}
//armamos reporte
//	require_once("../lib/dompdf/dompdf_config.inc.php");
	require_once("../lib/setup.php");
	require_once("../lib/fechas.php");
	$smarty = new bd;

	$sql = "SELECT TOP 1 enable_ws FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('enable_ws',$row["enable_ws"]);
	
	$reporte = $_REQUEST["fileName"];

	$id = $_REQUEST["id"];
	//recpueramos los datos del informe legal
	$sql= "SELECT il.*, tb.tipo_bien, tb.bien, pr.nombres, pr.ci, pr.emision
		FROM informes_legales  il ".
		" INNER JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien ".
		" LEFT JOIN propietarios pr ON pr.id_propietario = il.id_propietario ".
		" WHERE il.id_informe_legal='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	//datos principales
	$id_tipo_bien   = $resultado['id_tipo_bien'];
	$id_us_comun	= $resultado['id_us_comun'];
	$usr_acep		= $resultado['usr_acep'];
	$id_titular		= $resultado['id_titular'];
	$tipo_bien		= trim($resultado["tipo_bien"]);
	//$tipo_bien		= trim($resultado["tipo_bien"]);
	$bien			= $resultado["bien"];
	$nrobien		= $resultado["nrobien"];
	$nrocaso		= trim($resultado["nrocaso"]);
	$noportunidad		= trim($resultado["noportunidad"]);
	$bandera		= $resultado['bandera'];
	$cliente		= trim($resultado['nombres']);
	$ci_cliente		= $resultado['ci'];
	$id_propietario	= $resultado['id_propietario'];
	$id_doc			= $resultado["emision"];
	$motivo			= trim($resultado['motivo']);
	//$motivo			= trim($resultado['motivo']);
//	$importe = explode(" ", $resultado["montoprestamo"]);

	if($resultado["fecha_recepcion"]!= ""){
		$aux_c = explode(" ",$resultado["fecha_recepcion"]);
		$fecha = dateDMESY(dateDMY($aux_c[0])).'&nbsp;-&nbsp;'.substr($aux_c[1],0,5);
	}else{
		$fecha = "";
	}

	if($resultado["fecha_aceptacion"]!= ""){
		$aux_c = explode(" ",$resultado["fecha_aceptacion"]);
		$fecha_ace = dateDMESY(dateDMY($aux_c[0])).'&nbsp;-&nbsp;'.substr($aux_c[1],0,5);
	}else{
		$fecha_ace = "";
	} 
	if($resultado["fecha"]!= ""){
		$aux_c = explode(" ",$resultado["fecha"]);
		$fecha_ela = dateDMESY(dateDMY($aux_c[0])).'&nbsp;-&nbsp;'.substr($aux_c[1],0,5);
	}else{
		$fecha_ela = "";
	}
	if($resultado["fecha_habilitacion"]!= ""){
		$aux_c = explode(" ",$resultado["fecha_habilitacion"]);
		$fecha_pub = dateDMESY(dateDMY($aux_c[0])).'&nbsp;-&nbsp;'.substr($aux_c[1],0,5);
	}else{
		$fecha_pub = "";
	}
//	$fecha_actual= date("d/m/Y");
	
	$smarty->assign('id',		   $id);
	$smarty->assign('id_us_comun', $id_us_comun);
	//$smarty->assign('id_titular', $id_titular);
	$smarty->assign('id_tipo_bien',$id_tipo_bien);
	$smarty->assign('nrobien', $nrobien);
	$smarty->assign('nrocaso', $nrocaso);
	$smarty->assign('bien', $tipo_bien);
	$smarty->assign('bandera',$bandera);
	$smarty->assign('cliente',     $cliente);
	$smarty->assign('ci_cliente',  $ci_cliente);
	$smarty->assign('id_propietario',  $id_propietario);
	$smarty->assign('id_doc',		$id_doc);
	$smarty->assign('fecha_rec',		$fecha);
	$smarty->assign('fecha_ace',		$fecha_ace);
	$smarty->assign('fecha_ela',		$fecha_ela);
	$smarty->assign('fecha_pub',		$fecha_pub);
	$smarty->assign('motivo',       $motivo);
	//$smarty->assign('montoprestamo',$importe[0]);
	//$smarty->assign('mone',			$importe[1]);
		
	// datos secundarios //$texto=nl2br($texto);
	$otras_observaciones	= nl2br($resultado['otras_observaciones']);
	$tradicion				= nl2br($resultado["tradicion"]);
	$garantia_contrato		= $resultado['garantia_contrato'];
	$nota					= nl2br($resultado['nota']);
	$conclusiones			= nl2br($resultado['conclusiones']);
	$numero_informe			= $resultado["numero_informe"];
	
	//sa ha cambiado la tabla tipos_bien ara que soporte mas bienes ue los definidos, 
	//utilizamos campo 'bien' para definir si es Inmueble (1),  maquinaria (2) o Vehiculo (3)

	if($bien==1) $tipo_bien = 'I';
	elseif($bien==2) $tipo_bien = 'M';
	elseif($bien==3) $tipo_bien = 'V';
	elseif($bien==4) $tipo_bien = 'N';
	elseif($bien==5) $tipo_bien = 'P';
	elseif($bien==6) $tipo_bien = 'S';
	
	
	$smarty->assign('otras_observaciones',trim($otras_observaciones));
	$smarty->assign('tradicion',		  trim($tradicion));
	$smarty->assign('garantia_contrato',  trim($garantia_contrato));
	$smarty->assign('nota',				  trim($nota));
	$smarty->assign('conclusiones',		  trim($conclusiones));
	$smarty->assign('numero_informe',	  $numero_informe);
	$smarty->assign('tipo_bien',	      $tipo_bien);

	$sql= "SELECT ofi.nombre FROM informes_legales il ".
			" INNER JOIN oficinas ofi ON ofi.id_oficina = il.id_oficina ".
			" WHERE il.id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('oficina',$resultado['nombre']);
		
	//recuperamos estado final del i.l.
		$sql= "SELECT TOP 1 il_estado_fin FROM opciones ";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$il_estado=explode(';',$row["il_estado_fin"]);
		if($bandera=='r') $smarty->assign('bandera',    $il_estado[0]);
		elseif($bandera=='a') $smarty->assign('bandera',    $il_estado[1]);
		elseif($bandera=='v') $smarty->assign('bandera',    $il_estado[2]);
		elseif($bandera=='z') $smarty->assign('bandera',    $il_estado[3]);
		
	if(	$tipo_bien == 'I'){
		//recuperamos los datos del informe legal del inmueble
		$sql= "SELECT * FROM informes_legales_inmuebles WHERE id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$descripcion_bien	= trim($resultado['descripcion_bien']);
		$extension			= $resultado['extension'];
		$ubicacion			= trim($resultado['ubicacion']);
		$registro_dr		= $resultado['registro_dr'];
		$superficie_titulo	= $resultado['superficie_titulo'];
		$superficie_plano	= $resultado['superficie_plano'];
		$limite_este		= trim($resultado['limite_este']);
		$limite_oeste		= trim($resultado['limite_oeste']);
		$limite_norte		= trim($resultado['limite_norte']);
		$limite_sud			= trim($resultado['limite_sud']);
		$datos_documento	= trim($resultado["datos_documento"]);
		
		$smarty->assign('desc',	$descripcion_bien);
		$smarty->assign('sup',		$extension);
		$smarty->assign('ubica',		$ubicacion);
		$smarty->assign('regdr',		$registro_dr);
		$smarty->assign('supst',$superficie_titulo);
		$smarty->assign('supsp',	$superficie_plano);
		$smarty->assign('leste',		$limite_este);
		$smarty->assign('loeste',		$limite_oeste);
		$smarty->assign('lnorte',		$limite_norte);
		$smarty->assign('lsud',		$limite_sud);
		$smarty->assign('datdoc',	$datos_documento); //??

	}elseif($tipo_bien == 'V' || $tipo_bien == 'M'){
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
		//$fpoliza= $resultado['fecha_poliza'];
		$alcaldia= $resultado['alcaldia'];
		$crpva= $resultado["crpva"];
		$poliza= $resultado["poliza"];
		$sidunea= $resultado['sidunea'];
		
		if($resultado["fecha_vehiculo"] != null || $resultado["fecha_vehiculo"]!= ""){
			$aux_c= explode(" ",$resultado["fecha_vehiculo"]);
			
			$fecha_vehiculo= dateDMESY(dateDMY($aux_c[0]));
			
		}else{
			$fecha_vehiculo="";
		}

		if($resultado["fecha_poliza"] != null || $resultado["fecha_poliza"]!= ""){
			$aux_c= explode(" ",$resultado["fecha_poliza"]);
			
			$fpoliza= dateDMESY(dateDMY($aux_c[0]));
			
		}else{
			$fpoliza="";
		}
		if($resultado["fecha_sidunea"] != null || $resultado["fecha_sidunea"]!= ""){
			$aux_c= explode(" ",$resultado["fecha_sidunea"]);
			$fsidunea= dateDMESY(dateDMY($aux_c[0]));	
		}else{
			$fsidunea="";
		}
		// para maquinaria: fecha_escritura sirve para fecha poliza maquinaria
		if($resultado["fecha_escritura"] != null || $resultado["fecha_escritura"]!= ""){
			$aux_c= explode(" ",$resultado["fecha_escritura"]);

			$fecha_escritura= dateDMESY(dateDMY($aux_c[0]));
			
		}else{
			$fecha_escritura="";
		}
		// para maquinaria: fecha_registro sirve para fecha registro sedag
		if($resultado["fecha_registro"] != null || $resultado["fecha_registro"]!= ""){
			$aux_c= explode(" ",$resultado["fecha_registro"]);
			
			$fecha_registro= dateDMESY($aux_c[0]);
			
		}
		else{
			$fecha_registro="";
		}
		
		
		$smarty->assign('placa',$placa);
		$smarty->assign('marca',trim($marca));
		$smarty->assign('chasis',$chasis);
		$smarty->assign('modelo',$modelo);
		$smarty->assign('motor',$motor);
		$smarty->assign('clase',trim($clase));
		$smarty->assign('tipo_a',$tipo_a);
		$smarty->assign('color',$color);
		$smarty->assign('fpoliza',$fpoliza);
		$smarty->assign('alcaldia',trim($alcaldia));
		$smarty->assign('crpva',$crpva);
		$smarty->assign('fecha_vehiculo',$fecha_vehiculo);
		$smarty->assign('fecha_escritura',$fecha_escritura);
		$smarty->assign('fecha_registro',$fecha_registro);
		$smarty->assign('poliza',$poliza);
		$smarty->assign('fpoliza',$fpoliza);
		$smarty->assign('sidunea',$sidunea);
		$smarty->assign('fsidunea',$fsidunea);

	}elseif($tipo_bien == 'S' ){
		//recpueramo los datos del informe legal del semoviente
		$sql= "SELECT asiento, marca, clase, tipo, matricula, poliza, fecha_poliza FROM informes_legales_vehiculos WHERE id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$asiento= $resultado['asiento'];
		$marca= $resultado['marca'];
		$clase= $resultado['clase'];
		$tipo_a= $resultado['tipo'];
		$matricula= $resultado['matricula'];
		$poliza= $resultado["poliza"];
		
		if($resultado["fecha_poliza"] != null || $resultado["fecha_poliza"]!= ""){
			$aux_c= explode(" ",$resultado["fecha_poliza"]);
			$fpoliza= dateDMESY(dateDMY($aux_c[0]));
		}else{
			$fpoliza="";
		}
		
		$smarty->assign('asiento',$asiento);
		$smarty->assign('marca',trim($marca));
		$smarty->assign('clase',trim($clase));
		$smarty->assign('tipo_a',$tipo_a);
		$smarty->assign('matricula',$matricula);
		$smarty->assign('fpoliza',$fpoliza);
		$smarty->assign('poliza',$poliza);
		
	}elseif($tipo_bien == 'P' ){
	//recuperamos oficina
		
		//echo $sql; die();
		//leemos datos generales de la personeria
		$sql= "SELECT il.*, convert(varchar(10),fecha_vence,103) as fecha_venc 
		, convert(varchar(10),fecha_matri,103) as fecha_matr 
		, convert(varchar(10),fecha_escri,103) as fecha_escr 
		, so.sociedad
		FROM informes_legales_pj il 
		inner join sociedades so on so.id_sociedad = il.tipo_sociedad
		WHERE il.id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$smarty->assign('tipo_sociedad',  $resultado['sociedad']);
		$smarty->assign('actividad',      htmlentities(trim($resultado["actividad"])));  
		$smarty->assign('direccion',      htmlentities($resultado['direccion']));
		$smarty->assign('duracion',        htmlentities($resultado['duracion']));
		$smarty->assign('fecha_vence',    $resultado['fecha_venc']);
		$smarty->assign('fecha_matri',    $resultado['fecha_matr']);
		$smarty->assign('nro_escritura',  $resultado['nro_escritura']);
		$smarty->assign('fecha_escri',    $resultado['fecha_escr']);
		$smarty->assign('notario',        htmlentities($resultado['notario']));
		$smarty->assign('matricula',	  $resultado['matricula']);
		// para la nomina de directores
		$nomina = array();
		$nomina_dir = explode('|',$resultado['nomina_dir']);
		foreach($nomina_dir as $dir){
			if($dir != ''){
			$persona = explode(';',$dir);
			$nomina[] = array('nombre'=>$persona[0],
							   'cargo'=>$persona[1],
								  'ci'=>$persona[2]);
			}
		}
		$smarty->assign('nomina', $nomina);
		
		// para los poderes
		$sql= "select po.*, convert(varchar(10),po.fecha,103) as fechap, td.tipo from poderes po 
		inner join tipos_documentos td on td.id_tipo_documento = po.id_tipo_documento
		WHERE po.id_informe_legal='$id' ";
		$query = consulta($sql);
		$poderes=array();
		while($resultado= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$idp = $resultado['id_poder'];
			// para los apoderados //
			$sql= "SELECT * FROM apoderados WHERE id_poder = '$idp' ";
			//echo $sql;
			$query2 = consulta($sql);
			$apoderados = array();
			while($row= $query2->fetchRow(DB_FETCHMODE_ASSOC)){
				if($row['vigente']=='S') $estado = 'VIG'; else $estado = 'REV';
				$apoderados[]=array('apoderado'=> htmlentities($row['apoderado']),
									'ci'=> $row['ci'],
									'tipo'=> $row['tipo'],
									'vigente'=> $estado ,
									'porcentaje'=> $row['porcentaje'],
									'facultades'=> htmlentities($row['facultades']),
									'restricciones'=> htmlentities($row['restricciones']));
			}
			//------
			$poderes[]=array(	'numero'=> $resultado['numero'],
								'fechap'=> $resultado['fechap'],
								'notario'=> htmlentities($resultado['notario']),
								'otorgante'=> htmlentities($resultado['otorgante']),
								'registro'=> $resultado['registro'],
								'fojas'=> $resultado['fojas'],
								'tipo'=> $resultado['tipo'],
								'apoderados'=> $apoderados);
		}
		
		$smarty->assign('poderes', $poderes);
	}
	
	$sql= "SELECT nombres FROM usuarios WHERE id_usuario = $id_us_comun";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('nombres',$resultado['nombres']);
		
	$sql= "SELECT nombres FROM usuarios WHERE id_usuario = $usr_acep";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('usr_acep',$resultado['nombres']);
	
	$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('logo',$resultado['logo01']);
		
	//****************************************************************************************************
	// REcuperamos los documentos correspondientes al tipo de bien y los que tenga ya guardados el I.L.
	$sql= " SELECT lista1.*, lista2.*
			FROM 
				(SELECT doc.id_documento as iddoc1, doc.documento, doc.vencimiento, doc.meses_vencimiento, tiene_fecha, con_numero
					FROM (documentos doc
					INNER JOIN tipos_bien_documentos tbd ON tbd.id_documento = doc.id_documento )
				WHERE tbd.id_tipo_bien = $id_tipo_bien) lista1
			right JOIN 
				(SELECT ild.id_documento as iddoc2, ild.id_tipo_documento, ild.numero, ild.fecha, ild.fojas, ild.observaciones, 
				ild.fecha_vencimiento, ild.tiene_observacion, ild.tomar_en_cuenta, tdo.tipo
				FROM informes_legales_documentos ild
				LEFT JOIN tipos_documentos tdo ON tdo.id_tipo_documento = ild.id_tipo_documento
				WHERE id_informe_legal = $id ) lista2
			ON lista1.iddoc1 = lista2.iddoc2 ORDER BY lista1.documento";	
			
	
	$query = consulta($sql);
	
		
//	$docus = array();
	$infor1 = array();
	$infor2 = array();
	$infor3 = array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$aux_a = $row["fecha"];
		if($aux_a != null){
			$aux_c		= explode(" ",$aux_a);
			$fechas= dateDMESY(dateDMY($aux_c[0]));
		}else{
			$fechas		="";
		}
		$aux_a= $row["fecha_vencimiento"];
		if($aux_a != null){
			$aux_c				= explode(" ",$row["fecha_vencimiento"]);
			$fechas_vencimiento = dateDMESY(dateDMY($aux_c[0]));
		}else{
			$fechas_vencimiento	="";
		}
		if($row["id_tipo_documento"] > 0){
			$tiene='1';
		}else{
			$tiene='0';
		}
		$tiene_observacion=$row["tiene_observacion"];
		if($row["con_numero"]=='1')
			$numero = trim($row["numero"]);
		else
			$numero = '';
		//if($numero=='0') $numero = '';
		//echo $tiene.'.'.$tiene_observacion.'-';
		if($tiene_observacion=='0' && $tiene=='1'){
		$infor1[] = array( 'documento' 			=> trim($row["documento"]),
							'numero' 				=> $numero,
							'tipo' 					=> $row["tipo"],
							'tiene_observacion' 	=> $tiene_observacion,
							'tiene_eldoc' 			=> $tiene,
							'fechas' 				=> $fechas,
							'fechas_vencimiento' 	=> $fechas_vencimiento,
							'fojas' 				=> $row["fojas"]);
		}elseif($tiene_observacion=='1' && $tiene=='1'){
		$infor2[] = array( 'documento' 			=> trim($row["documento"]),
							'numero' 				=> $numero,
							'tipo' 					=> $row["tipo"],
							'tiene_observacion' 	=> $tiene_observacion,
							'tiene_eldoc' 			=> $tiene,
							'fechas' 				=> $fechas,
							'fechas_vencimiento' 	=> $fechas_vencimiento,
							'fojas' 				=> $row["fojas"],
							'obs' 					=> trim($row["observaciones"]));
		}else{
		$infor3[] = array( 'documento' 			=> trim($row["documento"]));
		}
		$i++;
	}
	
	if($tipo_bien != 'P' ){
		
		//recuperamos la lista de propietarios
		$sql= "SELECT pr.nombres, pr.ci, pr.direccion, pr.emision, tp.id_propietario, tp.estitular 
			FROM informes_legales_propietarios tp 
			INNER JOIN propietarios pr 
					ON tp.id_propietario = pr.id_propietario 
					WHERE tp.id_informe_legal='$id' ORDER BY tp.estitular DESC";
		$query = consulta($sql);
		//echo $sql;
		$lista_personas=array();
		$i=0;
		while($row=$query->fetchRow(DB_FETCHMODE_ASSOC)){
			//para compatibilidad de registros anteriores a esta modificacion
			//el titular estaba en campo id_titular de informer_legales
			//ahora soporta varios titulares por i.l.
			if($row["estitular"]==''){
				if($id_titular == $row["id_propietario"]) 
					$titu = "S"; 
				else 
					$titu = "N";
			}else{
				$titu = $row["estitular"];
			}
			$lista_personas[$i]= array('id'=>$row["id_propietario"],
										'nombre'=>$row["nombres"],
										'direccion'=> trim($row["direccion"]),
										'emision'=>$row["emision"],
										'ci'=>$row["ci"],
										'titular'=>$titu,);
			$i++;
		}
		$smarty->assign('lista_personas',$lista_personas);
		//	$smarty->assign('cantidad_lista',$i);
	}
	
	$smarty->assign('cantidad_documentos',$i);
	$smarty->assign('infor1',$infor1);
	$smarty->assign('infor2',$infor2);
	$smarty->assign('infor3',$infor3);
	

if(isset($_REQUEST["habilitando_informe"])){	
	//guardamos en fechas
	$el_html = $smarty->fetch('informe_legal/'.$reporte);
	$el_html = str_replace("'","''",$el_html);
	$sql= "UPDATE informes_legales_fechas SET html='$el_html' ";
	$sql.="WHERE id_informe_legal='$id' AND fecha_publicacion IS NOT null AND html IS null";
	ejecutar_sin_filter($sql);
}else{
	$smarty->display('informe_legal/'.$reporte);
	/*  
	$el_html = $smarty->fetch('informe_legal/'.$reporte);
	include("../mpdf/mpdf.php");
	$mpdf=new mPDF(); 
	$mpdf->WriteHTML($el_html);
	$mpdf->Output();
	*/  
	die();
}

?>