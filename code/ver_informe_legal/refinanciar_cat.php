<?php
$id = $_REQUEST['id'];
$nrocaso= $_REQUEST["nrocaso"];  //bsol es nro de cuenta
//refinanciar
if($_REQUEST['refinanciar_cat']=='dos'){
	//refinanciar desde catastro
	// RECUPERAMOS DATOS DE la carpeta
	$sql = "SELECT pr.id_propietario, pr.nombres, pr.ci, pr.emision, ca.carpeta, tb.tipo_bien 
	FROM carpetas ca  
		INNER JOIN propietarios pr ON ca.id_propietario = pr.id_propietario
		INNER JOIN tipos_bien tb ON tb.id_tipo_bien = ca.id_tipo_carpeta
		WHERE ca.id_carpeta = $id" ;
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('ci_cliente', $row["ci"]);
	$smarty->assign('emision', $row["emision"]);
	$smarty->assign('cliente', $row["nombres"]);
	$smarty->assign('carpeta', $row["carpeta"]);
	$smarty->assign('tipo_bien', $row["tipo_bien"]);
	$smarty->assign('id_propietario', $row["id_propietario"]);
	$smarty->assign('nrocaso', $nrocaso);
	
	//recuperando los datos para la ventana, los documentos que tiene el propietario
	$sql= "SELECT dp.id_documento, dp.numero_hojas, dp.observacion ,td.tipo, doc.documento 
	FROM documentos_propietarios dp 
	INNER JOIN documentos doc ON doc.id_documento = dp.id_documento 
	INNER JOIN tipos_documentos td ON td.id_tipo_documento = dp.id_tipo_documento 
	WHERE dp.id_carpeta='$id' ORDER BY doc.requerido DESC, doc.documento ";
	
	$query = consulta($sql);
	$documentos= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$documentos[$i]= array('id_documento' => $row["id_documento"],
								'documento' => $row["documento"],
								'tipo' => $row["tipo"],
								'fojas' => $row["numero_hojas"],
								'obs' => $row["observacion"]);
		$i++;
	}
	$smarty->assign('documentos',$documentos);
	$smarty->assign('id',$id);
	// jalamos la descripcion del WS para mostrar antes
	$motivo = '';
	//este WS busca por instancia queno tenemos, usamos cuenta ahora, entonces el motivo no lo ponemos ya.
	//require_once('ws_nrocaso_bsol.php');
	$smarty->display('ver_informe_legal/refinanciar_cat.html');
	die();
}else{
		//$id_propietario = $_REQUEST['id_propietario'];
		//llevamos de catastro a recepcion
		$sql = "SELECT ca.id_tipo_carpeta, ca.id_oficina, pr.nombres as cliente, pr.ci as ci_cliente, pr.id_propietario 
		FROM carpetas ca INNER JOIN propietarios pr ON ca.id_propietario = pr.id_propietario 
		WHERE id_carpeta = '$id'";
		
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$id_tipo_bien= $row["id_tipo_carpeta"];
		$id_propietario= $row["id_propietario"];
		$cliente= $row["cliente"];
		$ci_cliente= $row["ci_cliente"];
		$id_oficina= $row["id_oficina"];
		$motivo = '';
		//require('ws_nrocaso_bsol.php');
		//recuperamos el siguiente numero de informe
		$sqlm= "SELECT MAX(id_informe_legal) AS maximo FROM informes_legales ";
		$query = consulta($sqlm);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$numero_informe=bcadd($resultado["maximo"],1,0);
		
		$fecha= date("Y-m-d H:i");
		$fecha="CONVERT(DATETIME,'$fecha',102)";
		
		$id_us_comun = $_SESSION['idusuario'];
		
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
			,sincarpeta
			,id_oficina
			,id_perito) VALUES($numero_informe
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
			,''
			,'$nrocaso'
			,''
			,''
			,''
			,''
			,''
			,'1'
			,''
			,'?'
			,$id_oficina
			,'0')";
			
		ejecutar($sql);
		//jalamos los docs 
		$sql= "SELECT dp.*, ca.id_tipo_carpeta, ca.creacion_carpeta
		FROM documentos_propietarios dp LEFT JOIN carpetas ca ON ca.id_carpeta = dp.id_carpeta
		WHERE dp.id_carpeta = '$id'";
		$query = consulta($sql);
		$comentario= '';
		//$fechareg= $fecha;
		
		WHILE($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
			$id_documento= $row["id_documento"];
			$id_tipo_documento= $row["id_tipo_documento"];
			$fojas= $row["numero_hojas"];
			
			$id_tipo_bien= $row["id_tipo_carpeta"];
			$nro_documento= $row["nro_documento"];
			if($row["fecha_documento"]!='')
				$fecha_documento= "CONVERT(DATETIME,'".$row["fecha_documento"]."',102)";
			else
				$fecha_documento= 'NULL';
			if($row["fecha_vencimiento"]!='')
				$fecha_vencimiento= "CONVERT(DATETIME,'".$row["fecha_vencimiento"]."',102)";
			else
				$fecha_vencimiento= 'NULL';
			if($row["creacion_carpeta"]!='')
				$fechareg= "CONVERT(DATETIME,'".$row["creacion_carpeta"]."',102)";
			else
				$fechareg= 'NULL';
/*
			//verificamos las observaciones, si en catastro se excluye del reporte de obs entonces 
			//pasamos como comentario
			if($row["noobs"]=='1'){ //se excluye del reporte de obs, es comentario
				$obs= '';
				$comentario= $row["observacion"];
			}else{
				$obs= $row["observacion"];
				$comentario= '';
			}
*/			
			//otra opcion pasar todo como comentario:
			
			$obs= '';
			$comentario= substr($row["observacion"], 0, 199);
			$comentario = str_replace("'","''",$comentario);
			
			$sql= "INSERT INTO documentos_informe (din_inf_id, din_doc_id, din_tip_doc, fojas, fechareg, obs, comentario)
					VALUES ($numero_informe, $id_documento, $id_tipo_documento, '$fojas', $fechareg, '$obs', '$comentario') ";
		
			ejecutar($sql);
			
			$sql= "INSERT INTO informes_legales_documentos
			(id_informe_legal, id_tipo_bien, id_documento, id_tipo_documento, numero, fecha, fojas, 
			observaciones, fecha_vencimiento, tiene_observacion, tomar_en_cuenta) 
			VALUES('$numero_informe', '$id_tipo_bien', '$id_documento', '$id_tipo_documento', '$nro_documento', $fecha_documento, '$fojas', 
			'$obs', $fecha_vencimiento, '0', '1') ";
			ejecutar($sql);
			
		}
	//no hay datos completos de descripcion de inmueble o vehiculo
}	
	

?>