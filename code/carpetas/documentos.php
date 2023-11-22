<?php


	$id_carpeta = $_REQUEST["id_cpd"];
	$id_tipo_carpeta = $_REQUEST["id_tcarp"];
	//echo $titulo_nombre;
	//filtro de busqueda para la ventana
	if(!isset($_SESSION["documentos_id"])){
		$_SESSION["documentos_id"]= $_REQUEST["id_cpd"];
	}
	$edoc = 'N';
	if(isset($_SESSION["eliminar_docs"])){
		if ($_SESSION["eliminar_docs"]=='S')
			$edoc = 'S';
	}
	$smarty->assign('edoc',$edoc);
	//$id_carpeta = $filtro_id_documento;
	//recuperando el nombre de la carpeta y el nombre del propietario
	$sql= "SELECT tipo_bien FROM tipos_bien WHERE id_tipo_bien= '$id_tipo_carpeta' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo_carpeta= $resultado["tipo_bien"];
	
	$smarty->assign('titulo_carpeta',$titulo_carpeta);
	$smarty->assign('titulo_nombre',$titulo_nombre);
	
	$smarty->assign('editar',$_REQUEST['documentos']);
	//$smarty->assign('editar','ver');

	$sql = "SELECT TOP 1 rutadoc,extension FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('rutadoc',$row["rutadoc"]);
	$formatos_permitidos =  explode(',',$row["extension"]);
	$extension = "";
	$coma = '';
	foreach($formatos_permitidos as $ext){
		$extension .= $coma.".".$ext;
		$coma = ',';
	}
	$smarty->assign('extension',$extension);
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/
/**********************valores por defecto*************************/
 
//recuperando los datos para la ventana, los documentos que tiene el propietario
$sql= "SELECT dp.* FROM documentos_propietarios dp ";
$sql.= "WHERE dp.id_carpeta='$id_carpeta' and dp.id_estado is NULL ORDER BY dp.id_documento_propietario ";
//echo "$sql<br>";
$query = consulta($sql);

$mis_documentos= array();

$i=0;
//primera vez que se cargan los documentos

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$aux_a = $row["fecha_documento"];
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
	$mis_documentos[$i]= array('id_documento' => $row["id_documento"],
								'id_tipo_documento' => $row["id_tipo_documento"],
								'numero' => $row["nro_documento"],
								'fecha' => $fechas,
								'vence' => $fechas_vencimiento,
								'numero_hojas' => $row["numero_hojas"],
								'observacion' => $row["observacion"],
								'noobs' => $row["noobs"],
								'id_dp' => $row["id_documento_propietario"],
								'archivo' => $row["archivo"]);
	$i++;
}
$cant_documentos=$i;
$smarty->assign('cant_documentos',$cant_documentos);
$smarty->assign('mis_documentos',$mis_documentos);
$smarty->assign('id_carpeta',$id_carpeta);
$smarty->assign('id_tc',$id_tipo_carpeta);

//recuperando todos los documentos de este tipo de carpeta
$sql= "SELECT d.*  
FROM (documentos d 
INNER JOIN tipos_bien_documentos tbd ON tbd.id_documento = d.id_documento 
inner join tipos_bien tb ON tb.id_tipo_bien=tbd.id_tipo_bien
) 
WHERE tb.id_tipo_bien = '$id_tipo_carpeta' ORDER BY d.requerido DESC, d.documento";
$query = consulta($sql);
//echo $sql;
$los_documentos= array();

$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

	//verificando si el propietario tiene el documento
	$aux= $row["id_documento"];
	$falta=1;
	$id_tipo_documento = '';
	$p_numero_hojas = '';
	$p_observacion = '';
	$noobs = '0';
	$id_doc_pro = 0;
	$numero = '';
	$fecha = '';
	$vence = '';
	$archivo = '';
	for($a=0; $a<$cant_documentos; $a++){
		//buscamos si tiene el doc
		if($aux == $mis_documentos[$a]['id_documento']){
			$falta=0; //tiene el documento
			$id_tipo_documento = $mis_documentos[$a]['id_tipo_documento'];
			$p_numero_hojas = $mis_documentos[$a]['numero_hojas'];
			$p_observacion = $mis_documentos[$a]['observacion'];
			$numero = $mis_documentos[$a]['numero'];
			$fecha = $mis_documentos[$a]['fecha'];
			$vence = $mis_documentos[$a]['vence'];
			$noobs = $mis_documentos[$a]['noobs'];
			$id_doc_pro = $mis_documentos[$a]['id_dp'];
			$archivo = $mis_documentos[$a]['archivo'];
		}
	}
	$los_documentos[$i]= array('id_documento' => $row["id_documento"],
								'documento' =>  $row["documento"],
								'grupo' =>  $row["requerido"],
								'requerido' =>  $row["requerido"],
								'seguro' =>  $row["seguro"],
								'id_tipo' =>  $id_tipo_documento,
								'numero' =>  $numero,
								'fecha' =>  $fecha,
								'vence' =>  $vence,
								'fojas' =>  $p_numero_hojas,
								'observacion' =>  $p_observacion,
								'falta' =>  $falta,
								'noobs' =>  $noobs,
								'id_doc_pro' =>  $id_doc_pro,
								'archivo' =>  $archivo,
								'vencimiento' 		=> $row["vencimiento"],
								'meses_vencimiento'	=> $row["meses_vencimiento"],
								'tiene_fecha' 		=> $row["tiene_fecha"],
								'con_numero' 		=> $row["con_numero"]);
	$i++;
}
$cantidad_total=$i;
$smarty->assign('los_documentos',$los_documentos);
$smarty->assign('cantidad_total',$cantidad_total);

//recuperando lso tipos de documentos
$sql= "SELECT * FROM tipos_documentos ORDER BY tipo ";
$query = consulta($sql);
$tipos_doc= array();
$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$tipos_doc[$i]  = array('id_tipo' => $row["id_tipo_documento"],
							'tipo' => $row["tipo"]);
	$i++;
}
$smarty->assign('tipos_doc',$tipos_doc);
$fecha_actual= date("d/m/Y");
	
	$smarty->assign('fecha_actual',$fecha_actual);
	
	$smarty->display('carpetas/documentos.html');

	die();

?>
