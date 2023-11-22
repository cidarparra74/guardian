<?php

$id = $_REQUEST['id'];
$nrocaso= $_REQUEST["nrocaso"]; //ahora es nro de cuenta para bsol
//refinanciar

if($_REQUEST['refinanciar']=='ini'){
//es llamado desde registro recepcion

	// RECUPERAMOS DATOS DEL INF LEGAL
	$sql = "SELECT il.id_tipo_bien, il.cliente, il.ci_cliente, ".
			"il.numero_informe,   il.nrobien,  ".
			" il.motivo, il.nrocaso, tb.tipo_bien FROM informes_legales il ".
			"LEFT JOIN tipos_bien tb ON tb.id_tipo_bien=il.id_tipo_bien WHERE id_informe_legal = $id " ;
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('id', $id);
	$smarty->assign('cliente', $row["cliente"]);
	$smarty->assign('ci_cliente', $row["ci_cliente"]);
	//$smarty->assign('id_tipo_id', $row["id_tipo_identificacion"]);
	$smarty->assign('tipo_bien', $row["tipo_bien"]);
	$smarty->assign('motivo', $row["motivo"]);
	$smarty->assign('nrobien', $row["nrobien"]);
	$smarty->assign('nrocaso0', $row["nrocaso"]);
	$smarty->assign('nrocaso', $nrocaso);
		
	//vemos si tiene documentos en registro de carpeta	
	$sql = "SELECT id_informe_legal FROM carpetas WHERE id_informe_legal = $id" ;
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$documentos= array();
	if($row["id_carpeta"] != ''){
		//tiene carpeta
		$idc = $row["id_carpeta"];
	
		//recuperando datos de la carpeta, los documentos que tiene el propietario
		$sql= "SELECT dp.id_documento, dp.numero_hojas, dp.observacion ,td.tipo, doc.documento 
		FROM documentos_propietarios dp 
		INNER JOIN documentos doc ON doc.id_documento = dp.id_documento 
		INNER JOIN tipos_documentos td ON td.id_tipo_documento = dp.id_tipo_documento 
		WHERE dp.id_carpeta='$id' ORDER BY doc.requerido DESC, doc.documento ";
	//	echo $sql.'1';
		$query = consulta($sql);
		$i=0;
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$documentos[$i]= array('id_documento' => $row["id_documento"],
									'documento' => $row["documento"],
									'tipo' => $row["tipo"],
									'fojas' => $row["numero_hojas"],
									'obs' => $row["observacion"]);
			$i++;
		}
	}else{
		//vemos si tiene documentos en registro de i.l.
		$sql= "SELECT doc.id_documento, doc.documento, td.tipo, dp.fojas, dp.observaciones 
		FROM informes_legales_documentos dp
		INNER JOIN documentos doc ON doc.id_documento = dp.id_documento 
		INNER JOIN tipos_documentos td ON td.id_tipo_documento = dp.id_tipo_documento
		WHERE id_informe_legal = '$id'";
	//	echo $sql.'2';
		$query = consulta($sql);
		$i=0;
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$documentos[$i]= array('id_documento' => $row["id_documento"],
									'documento' => $row["documento"],
									'tipo' => $row["tipo"],
									'fojas' => $row["fojas"],
									'obs' => $row["observaciones"]);
			$i++;
		}
		//si no tiene docs en I.L. vemos docs en recepcion
		$sql= "SELECT din_id, din_doc_id, din_tip_doc, fojas, obs, comentario, CONVERT(varchar,fechareg,103) AS fechareg, do.documento 
		FROM documentos_informe di LEFT JOIN documentos do ON do.id_documento=di.din_doc_id 
		WHERE din_inf_id='$id' ORDER BY do.documento";
		//echo $sql;
		$query = consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$documentos[]= array('id_documento' => $row["din_doc_id"],
									'documento' => $row["documento"],
									'tipo' => $row["din_tip_doc"],
									'fojas' => $row["fojas"],
									'obs' => $row["obs"]);
		}
		
	}
	$smarty->assign('documentos',$documentos);
	$smarty->assign('id',$id);

	//$smarty->display('ver_informe_legal/refinanciar_cat.html');

		
		$smarty->display('ver_informe_legal/refinanciar.html');
		die();
}else{
		//
		$nrobien = $_REQUEST["nrobien"];
		$motivo = $_REQUEST["motivo"];
		$sql = "SELECT id_tipo_bien, id_propietario
			,cliente ,ci_cliente
			,fecha ,numero_informe
			,nrocaso
			,id_titular
			,otras_observaciones
			,garantia_contrato
			,nota
			,conclusiones
			,puede_operar
			,tradicion
			,id_perito
			,id_oficina
			,instancia
		  FROM informes_legales
		  WHERE id_informe_legal = '$id'";
		
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$id_tipo_bien= $row["id_tipo_bien"];
		$id_propietario= $row["id_propietario"];
		$cliente= $row["cliente"];
		$ci_cliente= $row["ci_cliente"];
		//$nrocaso= es otro caso/instancia/cuenta
		$id_titular= $row["id_titular"];
		$otras_observaciones= $row["otras_observaciones"];
		$garantia_contrato= $row["garantia_contrato"];
		$nota= $row["nota"];
		$conclusiones= $row["conclusiones"];
		$puede_operar= $row["puede_operar"];
		$tradicion= $row["tradicion"];
		$id_perito= $row["id_perito"];
		$id_oficina= $row["id_oficina"];
		$cuenta= $row["nrocaso"];
		
		//recuperamos el siguiente numero de informe
		$sqlm= "SELECT MAX(id_informe_legal) AS maximo FROM informes_legales ";
		$query = consulta($sqlm);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$numero_informe=bcadd($resultado["maximo"],1,0);
		
		$fecha= date("Y-m-d H:i");
		$fecha="CONVERT(DATETIME,'$fecha',102)";
		
		$id_us_comun = $_SESSION['idusuario'];
		
		//para casos en que no se use caso/instancia
		if($nrocaso=='')
			$nrocaso = $numero_informe;

			
		$sql="INSERT INTO informes_legales (id_informe_legal, id_tipo_bien
			  ,id_propietario, id_us_comun
			  ,cliente
			  ,ci_cliente
			  ,motivo
			  ,fecha
			  ,fecha_recepcion
			  ,numero_informe
			  ,estado
			  ,bandera
			  ,nrobien
			  ,nrocaso
			,id_titular
			,otras_observaciones
			,garantia_contrato
			,nota
			,conclusiones
			,puede_operar
			,tradicion
			,id_perito
			,id_oficina
			,sincarpeta) VALUES($numero_informe
			,$id_tipo_bien
			,$id_propietario, $id_us_comun
			,'$cliente'
			,'$ci_cliente'
			,'$motivo'
			,$fecha
			,$fecha
			,$numero_informe
			,'rec'
			,'x'
			,'$nrobien'
			,'$nrocaso'
			,'$id_titular'
			,'$otras_observaciones'
			,'$garantia_contrato'
			,'$nota'
			,'$conclusiones'
			,'$puede_operar'
			,'$tradicion'
			,'$id_perito'
			,'$id_oficina'
			,'?')";
		ejecutar($sql);
		//ponemos a campo bandera valor x para diferenciar cuando este en recepcion
		//jalamos los docs 
		$sql= "SELECT * FROM documentos_informe WHERE din_inf_id='$id'";
		$query = consulta($sql);
		////
		WHILE($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
			$din_doc_id= $row["din_doc_id"];
			$din_tip_doc= $row["din_tip_doc"];
			$fojas= $row["fojas"];
			$fechareg= "CONVERT(DATETIME,'".$row["fechareg"]."',102)";
			$obs= $row["obs"];
			$obs = str_replace("'","''",$obs);
			
			$comentario= $row["comentario"];
			$comentario = str_replace("'","''",$comentario);
			
			$sql= "INSERT INTO documentos_informe (din_inf_id, din_doc_id, din_tip_doc, fojas, fechareg, obs, comentario)
			VALUES ($numero_informe, $din_doc_id, $din_tip_doc, '$fojas', $fechareg, '$obs', '$comentario') ";
			ejecutar($sql);
		}
		// -- para los tipos INMUEBLE
		$sql= "SELECT id_informe_legal_inmueble, id_informe_legal, descripcion_bien, extension, ubicacion, 
		registro_dr, superficie_titulo, superficie_plano, limite_este, limite_oeste, limite_norte, limite_sud, datos_documento
		FROM informes_legales_inmuebles WHERE id_informe_legal = '$id'";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		if($row["id_informe_legal_inmueble"]!=''){
			$descripcion_bien= $row["descripcion_bien"];
			$extension= $row["extension"];
			$ubicacion= $row["ubicacion"];
			$registro_dr= $row["registro_dr"];
			$superficie_titulo= $row["superficie_titulo"];
			$superficie_plano= $row["superficie_plano"];
			$limite_este= $row["limite_este"];
			$limite_oeste= $row["limite_oeste"];
			$limite_norte= $row["limite_norte"];
			$limite_sud= $row["limite_sud"];
			$datos_documento= $row["datos_documento"];
			
			//No existe, recuperamos el maximo id de informe legal inmueble
			$sql= "SELECT MAX(id_informe_legal_inmueble) AS maximo FROM informes_legales_inmuebles ";
			$query = consulta($sql);
		    $resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
			
			$id_informe_legal_inmueble= $resultado["maximo"] + 1; 
			
			$sql= "INSERT INTO informes_legales_inmuebles(id_informe_legal_inmueble, id_informe_legal, descripcion_bien, extension, ubicacion, 
			registro_dr, superficie_titulo, superficie_plano, limite_este, limite_oeste, limite_norte, limite_sud, datos_documento) 
			VALUES($id_informe_legal_inmueble, '$numero_informe', '$descripcion_bien', '$extension', '$ubicacion', '$registro_dr', '$superficie_titulo', 
			'$superficie_plano', '$limite_este', '$limite_oeste', '$limite_norte', '$limite_sud', '$datos_documento') ";
			ejecutar($sql);
		}
		
		// -- para los tipos VEHICULO
		$sql= "SELECT id_informe_legal_vehiculo, id_informe_legal, placa, marca, chasis, modelo,
		motor, clase, tipo, color, alcaldia, crpva, fecha_vehiculo, poliza, fecha_poliza, sidunea, fecha_sidunea 
		FROM informes_legales_vehiculos WHERE id_informe_legal = '$id'";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		if($row["id_informe_legal_vehiculo"]!=''){
			$placa= $row["placa"];
			$marca= $row["marca"];
			$chasis= $row["chasis"];
			$modelo= $row["modelo"];
			$motor= $row["motor"];
			$clase= $row["clase"];
			$tipo= $row["tipo"];
			$color= $row["color"];
			$alcaldia= $row["alcaldia"];
			$crpva= $row["crpva"];
			$fecha_vehiculo= $row["fecha_vehiculo"];
			$poliza= $row["poliza"];
			$sidunea= $row["sidunea"];
			$fpoliza= $row["fecha_poliza"];
			if($fpoliza != null && trim($fpoliza) != ''){
				$fechapo = dateYMD($fpoliza);
				$fechapo = "CONVERT(DATETIME,'$fechapo',102)";
			}else{
				$fechapo="null";
			}
			if($fecha_vehiculo != null && trim($fecha_vehiculo) != ''){
				$fecha_vehiculo = dateYMD($fecha_vehiculo);
				$fecha_vehiculo = "CONVERT(DATETIME,'$fecha_vehiculo',102)";
			}else{
				$fecha_vehiculo="null";
			}
			$fsidunea= $row["fecha_sidunea"];
			if($fsidunea != null && trim($fsidunea) != ''){
				$fechasi = dateYMD($fsidunea);
				$fechasi = "CONVERT(DATETIME,'$fechasi',102)";
			}else{
				$fechasi="null";
			}
			$sql= "INSERT INTO informes_legales_vehiculos( id_informe_legal, placa, marca, chasis, modelo, 
			motor, clase, tipo, color, alcaldia, crpva, fecha_vehiculo, poliza, fecha_poliza, sidunea, fecha_sidunea) 
			VALUES('$numero_informe', '$placa', '$marca', '$chasis', '$modelo', 
			'$motor', '$clase', '$tipo', '$color', '$alcaldia', '$crpva', $fecha_vehiculo, 
			'$poliza', $fechapo, '$sidunea', $fechasi) ";
			ejecutar($sql);
		}
		
		// -- para los tipos PERSONERIA
		$sql= "SELECT * FROM informes_legales_pj WHERE id_informe_legal = '$id'";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		if($row["id_informe_legal"]!=''){
			$tiposociedad= $row["tiposociedad"];
			$actividad= $row["actividad"];
			$duracion= $row["duracion"];
			$nominadir= $row["nominadir"];
			$matricula= $row["matricula"];
			$nro_escritura= $row["nro_escritura"];
			$nro_resol= $row["nro_resol"];
			$notario= $row["notario"];
			$direccion= $row["direccion"];
			$fecha_vence= $row["fecha_vence"];
			if($fecha_vence != null && trim($fecha_vence) != ''){
				$fecha_vence = dateYMD($fecha_vence);
				$fecha_vence = "CONVERT(DATETIME,'$fecha_vence',102)";
			}else{
				$fecha_vence="null";
			}
			$fecha_matri= $row["fecha_matri"];
			if($fecha_matri != null && trim($fecha_matri) != ''){
				$fecha_matri = dateYMD($fecha_matri);
				$fecha_matri = "CONVERT(DATETIME,'$fecha_matri',102)";
			}else{
				$fecha_matri="null";
			}
			$fecha_resol= $row["fecha_resol"];
			if($fecha_resol != null && trim($fecha_resol) != ''){
				$fecha_resol = dateYMD($fecha_resol);
				$fecha_resol = "CONVERT(DATETIME,'$fecha_resol',102)";
			}else{
				$fecha_resol="null";
			}
			$fecha_escri= $row["fecha_escri"];
			if($fecha_escri != null && trim($fecha_escri) != ''){
				$fecha_escri = dateYMD($fecha_escri);
				$fecha_escri = "CONVERT(DATETIME,'$fecha_escri',102)";
			}else{
				$fecha_escri="null";
			}
			$sql= "INSERT INTO informes_legales_pj ( id_informe_legal, tiposociedad, actividad, duracion, nominadir, 
			fecha_vence, matricula, fecha_matri, nro_escritura, fecha_escri, nro_resol, fecha_resol, notario, direccion)
			VALUES('$numero_informe', '$tiposociedad', '$actividad', '$duracion', '$nominadir', 
			$fecha_vence, '$matricula', $fecha_matri, '$nro_escritura', $fecha_escri, '$nro_resol', $fecha_resol, 
			'$notario', '$direccion') ";
			ejecutar($sql);
			//poderes
			$sql= "SELECT * FROM poderes WHERE id_informe_legal = '$id'";
			$query = consulta($sql);
			while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
				$numero= $row["numero"];
				$notario= $row["notario"];
				$fojas= $row["fojas"];
				$id_tipo_documento= $row["id_tipo_documento"];
				$otorgante= $row["otorgante"];
				$registro= $row["registro"];
				$fecha= $row["fecha"];
				if($fecha != null && trim($fecha) != ''){
					$fecha = dateYMD($fecha);
					$fecha = "CONVERT(DATETIME,'$fecha',102)";
				}else{
					$fecha="null";
				}
				$sql= "INSERT INTO poderes ( id_informe_legal, numero, notario, fojas, id_tipo_documento, 
				fecha, otorgante, registro)
				VALUES('$numero_informe', '$numero', '$notario', '$fojas', '$id_tipo_documento', 
				$fecha, '$otorgante', '$registro') ";
				ejecutar($sql);
				//id del poder nuevo
				$sql1= "SELECT MAX(id_poder) AS siguiente FROM poderes WHERE id_informe_legal = '$numero_informe'";
				$query1 = consulta($sql1);
				$row1= $query1->fetchRow(DB_FETCHMODE_ASSOC);
				$id_poder = $row1["siguiente"];
				//apoderados
				$sql2= "SELECT * FROM apoderados WHERE id_poder = '".$row["id_poder"]."'";
				$query2 = consulta($sql2);
				while($row2= $query2->fetchRow(DB_FETCHMODE_ASSOC)){
					$apoderado= $row2["apoderado"];
					$ci= $row2["ci"];
					$tipo= $row2["tipo"];
					$vigente= $row2["vigente"];
					$porcentaje= $row2["porcentaje"];
					$facultades= $row2["facultades"];
					$restricciones= $row2["restricciones"];
					$sql= "INSERT INTO apoderados ( id_poder, apoderado, ci, tipo, vigente, 
					porcentaje, facultades, restricciones)
					VALUES('$id_poder', '$apoderado', '$ci', '$tipo', '$vigente', 
					$porcentaje, '$facultades', $restricciones) ";
					ejecutar($sql);
				}
			}
			//fin personeria
		}
		
		$sql= "SELECT id_informe_legal, id_tipo_bien, id_documento, id_tipo_documento, numero, fecha, fojas, 
		observaciones, fecha_vencimiento, tiene_observacion, tomar_en_cuenta 
		FROM informes_legales_documentos WHERE id_informe_legal = '$id'";
		$query = consulta($sql);
		
		WHILE($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$id_tipo_bien= $row["id_tipo_bien"];
			$id_documento= $row["id_documento"];
			$id_tipo_documento= $row["id_tipo_documento"];
			$numero= $row["numero"];
			//$fecha= $row["fecha"];
			if($row["fecha"]!='')
				$fecha= "CONVERT(DATETIME,'".$row["fecha"]."',102)";
			else
				$fecha= 'NULL';
			$fojas= $row["fojas"];
			$observaciones= $row["observaciones"];
			//$fecha_vencimiento= $row["fecha_vencimiento"];
			if($row["fecha_vencimiento"]!='')
				$fecha_vencimiento= "CONVERT(DATETIME,'".$row["fecha_vencimiento"]."',102)";
			else
				$fecha_vencimiento= 'NULL';
			$tiene_observacion= $row["tiene_observacion"];
			$tomar_en_cuenta= $row["tomar_en_cuenta"];
			
			$sql= "INSERT INTO informes_legales_documentos
			(id_informe_legal, id_tipo_bien, id_documento, id_tipo_documento, numero, fecha, fojas, 
			observaciones, fecha_vencimiento, tiene_observacion, tomar_en_cuenta) 
			VALUES('$numero_informe', '$id_tipo_bien', '$id_documento', '$id_tipo_documento', 
			'$num', $fecha, '$foj', 
			'$obs', $fecha_vencimiento, '$tiene_observacion', '$tomar_en_cuenta') ";
			ejecutar($sql);
		}
}	
	

?>