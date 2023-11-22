<?php

require_once('../lib/fechas.php');
	
	if(isset($_REQUEST["tipo_bien"])){
		$id_tipo_bien = $_REQUEST["tipo_bien"];
	}else{
		$id_tipo_bien ="0";
	}
	
	$ci = $_REQUEST["ci"];
	$emision = $_REQUEST["emision"];
	$telefono = $_REQUEST["telefono"];
	$cliente = strtoupper($_REQUEST["cliente"]);
	if($ci!='' && $cliente!=''){
		$fecha = date("d-m-Y H:i:s");
		$fecha = "CONVERT(DATETIME,'$fecha',103)";
		//$ultima_fecha = $fecha;
		$id_usuario = $_SESSION["idusuario"];
		// guardamos en presolicitudes, o actualizamos si es nuevo
		//buscar si existe para el mismo tipo de garantia y fecha (reimpresion)
		$sql = "SELECT ci FROM presolicitud WHERE ci='$ci' AND emision = '$emision' AND CONVERT(VARCHAR(10),ultima_fecha,103)=CONVERT(VARCHAR(10),$fecha,103) AND id_tipo_bien='$id_tipo_bien'";
		//echo $sql;
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		if($row["ci"]!=''){
			//existe, actualizamos 
			$sql="UPDATE presolicitud SET ultima_fecha=$fecha WHERE ci='$ci' AND emision = '$emision' AND id_tipo_bien='$id_tipo_bien'";
			ejecutar($sql);
		}else{
			//insertamos
			$sql="INSERT INTO presolicitud (fecha, ci, emision, nombre, telefono, id_tipo_bien, id_usuario, ultima_fecha) VALUES ($fecha, '$ci', '$emision', '$cliente', '$telefono', '$id_tipo_bien', '$id_usuario', $fecha)";
		
			ejecutar($sql);
			//obtenemos el id generado
			$sql = "SELECT id_presol FROM presolicitud WHERE ci='$ci' AND emision = '$emision' AND CONVERT(VARCHAR(10),ultima_fecha,103)=CONVERT(VARCHAR(10),$fecha,103) AND id_tipo_bien='$id_tipo_bien'";
			
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($row["id_presol"]>0){
				$id_presol = $row["id_presol"];
				//enviamos al web service si es baneco
				$sql = "SELECT TOP 1 enable_ws FROM opciones";
				$query = consulta($sql);
				$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
				$enable_ws = $row["enable_ws"];
				if($enable_ws == 'A'){
					require_once('ws_req_baneco.php');
				}

			}
		}
		
	}else{
		$emision = '';
	}
	$smarty->assign('ci',$ci);
	$smarty->assign('emision',$emision);
	$smarty->assign('cliente',$cliente);
	$smarty->assign('telefono',$telefono);
	
	//recuperando tipo de bien
	$sql= "SELECT tipo_bien FROM tipos_bien WHERE id_tipo_bien = $id_tipo_bien ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('tipobien',$row["tipo_bien"]);
	
	//recuperando los tipos de documentos
	$sql= "SELECT * FROM tipos_documentos ORDER BY tipo ";
	$query = consulta($sql);
	$tiposDocs= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tiposDocs[$i]= array('idTipo' => $row["id_tipo_documento"] ,
							  'descri' => $row["tipo"]);
		$i++;
	}
	$smarty->assign('tiposDocs',$tiposDocs);


	//recuperamos la lista total de documentos
	$sql= "SELECT tip.id_documento, doc.documento, doc.requerido ".
		"FROM tipos_bien_documentos tip ".
		"INNER JOIN documentos doc ".
		"ON tip.id_documento = doc.id_documento ".
		"WHERE tip.id_tipo_bien = $id_tipo_bien AND tip.imprimir='1'".
		"ORDER BY doc.requerido DESC, doc.documento ASC";
	
	$query = consulta($sql);

	$documentos = array();

	$i = 0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		// recuperamos el ID del doc
		$idDoc = $row["id_documento"];
		// buscamos el idDoc en los docs del informe, para determinar su tipo de doc
		
		//$tipDoc = 0 ;

		//almacenamos el doc completado
		$documentos[$i]= array( 'iddoc' => $idDoc,
								'docu' => $row["documento"],
								'idgru' => $row["requerido"]);
		
		$i++;
	}
	$cantidad_total=$i;
	$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('logo',$resultado['logo01']);
	
	$smarty->assign('documentos',$documentos);
	$smarty->assign('cantidad_total',$cantidad_total);

	$smarty->display('requisito/documentosver.html');
	die();


?>
