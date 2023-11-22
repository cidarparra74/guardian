<?php
require_once("../lib/setup.php");
//$smarty = new bd;	 
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
require_once("../lib/cargar_overlib.php");

	$id= $_REQUEST['id'];
	
	//leemos parametros especiales
	$sql= "SELECT TOP 1 enable_prop, il_estado_fin, enable_ws FROM opciones ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//vemos si se ppuede adicionar propietarios
	$enable_prop   = $resultado['enable_prop'];
	$smarty->assign('enable_prop', $enable_prop);
	// para el estado final del I.L (Rojo;amarillo;Verde)
	$il_estado=explode(';',$resultado["il_estado_fin"]);
	$smarty->assign('il_estado',$il_estado);
	//para identificar que banco es
	$enable_ws = $resultado["enable_ws"];
	$smarty->assign('enable_ws',$enable_ws);
	
	
	//recpueramos los datos del informe legal
	$sql= "SELECT il.*, tb.tipo_bien, tb.bien, pr.nombres, ci, emision FROM informes_legales  il ".
		" INNER JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien ".
		" LEFT JOIN propietarios pr ON pr.id_propietario = il.id_propietario ".
		" WHERE il.id_informe_legal='$id' ";
	//echo $sql; 
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	//datos principales
	$id_tipo_bien   = $resultado['id_tipo_bien'];
	$id_us_comun	= $resultado['id_us_comun'];
	$id_titular		= $resultado['id_titular'];
	$tipo_bien		= $resultado["tipo_bien"];
	$bien			= $resultado["bien"];
	$bandera		= $resultado['bandera'];
	$cliente		= $resultado['nombres'];
	$ci_cliente		= $resultado['ci'];
	$id_propietario		= $resultado['id_propietario'];
	$id_doc			= $resultado["emision"];
	$motivo			= $resultado['motivo'];
	//$importe = explode(" ", $resultado["montoprestamo"]);

	if($resultado["fecha"]!= ""){
		$aux_c = explode(" ",$resultado["fecha"]);
		$fecha = dateDMESY(dateDMY($aux_c[0]));
	}else{
		$fecha = "";
	}
	if($id_propietario==""){
		$smarty->assign('isblank', '1');
	}else{
		$smarty->assign('isblank', '0');
	}
	
	$fecha_actual= date("d/m/Y");
	
	$smarty->assign('id',		   $id);
	$smarty->assign('id_us_comun', $id_us_comun);
	$smarty->assign('nrocaso', $resultado['nrocaso']);
	$smarty->assign('id_tipo_bien',$id_tipo_bien);
	$smarty->assign('bien',$tipo_bien);
	$smarty->assign('bandera',$bandera);
	$smarty->assign('cliente',     $cliente);
	$smarty->assign('ci_cliente',  $ci_cliente);
	$smarty->assign('id_propietario',  $id_propietario);
	$smarty->assign('id_doc',		$id_doc);
	$smarty->assign('fechaold',		$fecha);
	$smarty->assign('fecha',		$fecha_actual);
	$smarty->assign('motivo',       $motivo);
	//$smarty->assign('montoprestamo',$importe[0]);
	//$smarty->assign('mone',			$importe[1]);
		
	// datos secundarios
	$otras_observaciones	= str_replace('<br />','',$resultado['otras_observaciones']);
	$tradicion				= $resultado["tradicion"];
	$garantia_contrato		= $resultado['garantia_contrato'];
	$nota					= $resultado['nota'];
	$conclusiones			= $resultado['conclusiones'];
	$numero_informe			= $resultado["numero_informe"];
	
	//sa ha cambiado la tabla tipos_bien ara que soporte mas bienes ue los definidos, 
	//utilizamos campo 'bien' para definir si es Inmueble (1),  maquinaria (2) o Vehiculo (3)
	if($bien==1) $tipo_bien = 'I';
	elseif($bien==2) $tipo_bien = 'M';
	elseif($bien==3) $tipo_bien = 'V';
	elseif($bien==4) $tipo_bien = 'N';
	elseif($bien==5) $tipo_bien = 'P';
	elseif($bien==6) $tipo_bien = 'S';
	
	//$tipo_bien = substr($tipo_bien,0,1);
	
	$smarty->assign('otras_observaciones',$otras_observaciones);
	$smarty->assign('tradicion',		  $tradicion);
	$smarty->assign('garantia_contrato',  $garantia_contrato);
	$smarty->assign('nota',				  $nota);
	$smarty->assign('conclusiones',		  $conclusiones);
	$smarty->assign('numero_informe',	  $numero_informe);
	$smarty->assign('tipo_bien',	      $tipo_bien);

	if(	$tipo_bien == 'I'){
		//recuperamos los datos del informe legal del inmueble
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
		$sidunea= $resultado['sidunea'];
		//$fsidunea= $resultado['fecha_sidunea'];
		
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
			
		}else{
			$fecha_registro="";
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
		$smarty->assign('fecha_escritura',$fecha_escritura);
		$smarty->assign('fecha_registro',$fecha_registro);
		$smarty->assign('poliza',$poliza);
		$smarty->assign('fpoliza',$fpoliza);
		$smarty->assign('sidunea',$sidunea);
		$smarty->assign('fsidunea',$fsidunea);
	}elseif($tipo_bien == 'S'){
		//recpueramo los datos del informe legal del semoviente
		$sql= "SELECT marca, clase, tipo, asiento, poliza, fecha_poliza, matricula
		FROM informes_legales_vehiculos WHERE id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$marca= $resultado['marca']; //Raza
		$clase= $resultado['clase']; //Ubicaci
		$tipo_a= $resultado['tipo']; //Descripci
		$asiento= $resultado['asiento']; //Cantidad
		$matricula= $resultado['matricula']; //observaciones
		$poliza= $resultado['poliza']; //Nro Cert
		
		//Fecha Cert
		if($resultado["fecha_poliza"] != null || $resultado["fecha_poliza"]!= ""){
			$aux_c= explode(" ",$resultado["fecha_poliza"]);
			$fpoliza= dateDMESY(dateDMY($aux_c[0]));	
		}else{
			$fpoliza="";
		}
		
		$smarty->assign('marca',$marca);
		$smarty->assign('clase',$clase);
		$smarty->assign('tipo_a',$tipo_a);
		$smarty->assign('asiento',$asiento);
		$smarty->assign('matricula',$matricula);
		$smarty->assign('poliza',$poliza);
		$smarty->assign('fpoliza',$fpoliza);
		
	}else{
		//recpueramo los datos del informe legal otros
		$sql= "SELECT matricula
		FROM informes_legales_vehiculos WHERE id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$matricula= $resultado['matricula']; //observaciones
		
		$smarty->assign('matricula',$matricula);
	}
	
	
	//****************************************************************************************************
	// REcuperamos los documentos correspondientes al tipo de bien y los que tenga ya guardados el I.L.
	$sql= " SELECT count(*) as ndocs from informes_legales_documentos
			WHERE id_informe_legal = $id ";
	$query = consulta($sql);

	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//para ver si NO_tomar _encuenta estara marcado o no; y si esprimera ves no lo estara
	if($row["ndocs"] > 0){$hay = 0;}else{$hay = 1;}
	//para ver si jalamos los docs del registro recepcion
	if($row["ndocs"] == 0){
		$sql= " SELECT din_doc_id, din_tip_doc, fojas FROM documentos_informe WHERE din_inf_id = $id ";
		$query = consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$id_documento		= $row["din_doc_id"];
			$fojas				= $row["fojas"];
			$id_tipo_documento	= $row["din_tip_doc"];
			$sql = "INSERT INTO informes_legales_documentos 
					(id_informe_legal, id_documento, id_tipo_documento, fojas) VALUES 
					($id, '$id_documento', '$id_tipo_documento', '$fojas')";
			ejecutar($sql);
		}
	}
	$sql= " SELECT lista1.*, lista2.*
			FROM 
				(SELECT doc.id_documento as iddoc1, doc.documento, doc.vencimiento, doc.meses_vencimiento, tiene_fecha, con_numero
					FROM (documentos doc
					INNER JOIN tipos_bien_documentos tbd ON tbd.id_documento = doc.id_documento )
				WHERE tbd.id_tipo_bien = $id_tipo_bien) lista1
			LEFT JOIN 
				(SELECT id_documento as iddoc2, id_tipo_documento, numero, fecha, fojas, observaciones, 
				fecha_vencimiento, tiene_observacion, tomar_en_cuenta FROM informes_legales_documentos
				WHERE id_informe_legal = $id ) lista2
			ON lista1.iddoc1 = lista2.iddoc2 ORDER BY lista1.documento";

	$query = consulta($sql);
	
		
	$docus = array();
	$infor = array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$docus[$i] = array( 'id_documento'		=> $row["iddoc1"],
							'documento' 		=> $row["documento"],
							'vencimiento' 		=> $row["vencimiento"],
							'meses_vencimiento'	=> $row["meses_vencimiento"],
							'tiene_fecha' 		=> $row["tiene_fecha"],
							'con_numero' 		=> $row["con_numero"] );
		
		if($row["id_tipo_documento"] != null){
			//pueden haber algunos valores
			$ids_tipo_documento	= $row["id_tipo_documento"];
			$numero				= $row["numero"];
			$NO_tomar_en_cuenta	= 0;
			$observaciones		= $row["observaciones"];
			$fojas				= $row["fojas"];
			$tiene_observacion	= $row["tiene_observacion"];
		}else{
			// no hay ningun valor para informes_legales_documentos
			$ids_tipo_documento	= 0;
			$numero				= '';
			$NO_tomar_en_cuenta	= (1 - $hay);
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
							'NO_tomar_en_cuenta' 	=> $NO_tomar_en_cuenta,
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
	
	$tipodocs= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipodocs[$i]= array( 'id'		=> $row["id_tipo_documento"],
							  'tipo'	=> $row["tipo"]);
		$i++;
	}
	$smarty->assign('tipodocs',$tipodocs);
	
	//recuperamos la lista de propietarios
	$sql= "SELECT pr.nombres, pr.ci, tp.id_propietario, tp.estitular 
	FROM informes_legales_propietarios tp INNER JOIN propietarios pr 
	ON tp.id_propietario = pr.id_propietario WHERE tp.id_informe_legal='$id' ORDER BY pr.nombres ";
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
									'ci'=>$row["ci"],
									'titular'=>$titu,);
		$i++;
	}
	$smarty->assign('lista_personas',$lista_personas);
	$smarty->assign('cantidad_lista',$i);
	
	
	//recuperando los tipos de indentificacion
	/*
	$sql= "SELECT * FROM tipos_identificacion ORDER BY identificacion ";
	$query = consulta($sql);
	$i=0;
	$identificacion1=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$identificacion1[$i] = array('id'  => $row["id_tipo"],
									'nro' => $row["identificacion"]);
		$i++;
	}
	$smarty->assign('identificacion1',$identificacion1);
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
	
	
	$smarty->display('informe_legal/elaborar_informe.html');
	die();
	
?>
