<?php

	$id= $_REQUEST['id'];
	
	$smarty->assign('id',$id);
		
		
		
		// -----------------  esto para el html
		//vemos si se ppuede adicionar propietarios
	$sql= "SELECT TOP 1 logo01 FROM opciones ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//datos principales
	$logo01   = $resultado['logo01'];
	$smarty->assign('logo01', $logo01);
	
		$id_carpeta= $_REQUEST["id"];
		//nombre del propietario y codigo mis
	$sql= "SELECT p.nombres, p.mis, ca.carpeta, td.tipo_bien, ca.operacion, ofi.nombre 
	FROM carpetas ca 
	INNER JOIN propietarios p ON p.id_propietario=ca.id_propietario
	LEFT JOIN oficinas ofi ON ca.id_oficina = ofi.id_oficina 
	LEFT JOIN tipos_bien td ON ca.id_tipo_carpeta=td.id_tipo_bien 
	WHERE id_carpeta='$id' ";

	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$carpeta= $resultado["carpeta"];
	$operacion= $resultado["operacion"];
	$oficina= $resultado["nombre"];
	$tipo= $resultado["tipo_bien"];
//Aumentado por Percy 2017/12/16
	$titulo_nombre= $resultado["nombres"];
	$titulo_mis= $resultado["mis"];
//	
	$smarty->assign('titulo_nombre',$titulo_nombre);
	$smarty->assign('titulo_mis',$titulo_mis);
		$smarty->assign('carpeta',$carpeta);
		$smarty->assign('operacion',$operacion);
		$smarty->assign('oficina',$oficina);
		$smarty->assign('tipo',$tipo);
//recuperando los datos para la ventana, los documentos que tiene el propietario
$sql= "SELECT do.documento, td.tipo, dp.* FROM documentos_propietarios dp  
LEFT JOIN documentos do ON do.id_documento = dp.id_documento 
LEFT JOIN tipos_documentos td ON td.id_tipo_documento = dp.id_tipo_documento 
WHERE dp.id_carpeta='$id_carpeta' ORDER BY dp.id_documento_propietario ";
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
	$mis_documentos[$i]= array('documento' => $row["documento"],
								'tipo' => $row["tipo"],
								'numero' => $row["nro_documento"],
								'fecha' => $fechas,
								'vence' => $fechas_vencimiento,
								'numero_hojas' => $row["numero_hojas"],
								'observacion' => $row["observacion"],
								'estado' => $row["id_estado"],
								'id_dp' => $row["id_documento_propietario"]);
	$i++;
}
$cant_documentos=$i;
$smarty->assign('cant_documentos',$cant_documentos);
$smarty->assign('mis_documentos',$mis_documentos);

// ---- hasta aqui para el html
	$smarty->display('carpetas/carpetas_html.html');
	//$smarty->display('carpetas/reporte_docs_imp.html');
	die();
?>