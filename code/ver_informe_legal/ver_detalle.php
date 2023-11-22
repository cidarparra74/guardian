<?php
	$id= $_REQUEST["id"];

	//recuperamos el detalle de fechas de este informe legal
	$sql= "SELECT fecha, fecha_aceptacion, fecha_habilitacion, fecha_solicitud, fecha_aprob, cliente FROM informes_legales WHERE id_informe_legal='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('id',$id);
	$smarty->assign('cliente',$resultado["cliente"]);
	
	//fecha de solicitud
	$aux_b= $resultado["fecha_solicitud"];
	$aux_c= explode(" ",$aux_b);
	$fecha_a= dateDMESY(dateDMY($aux_c[0]));
	$aux= $fecha_a." ".substr($aux_c[1],0,5);
		$smarty->assign('fecha_solicitud',$aux);
	
	//fecha de aprobacion
	$aux_b= $resultado["fecha_aprob"];
	$aux_c= explode(" ",$aux_b);
	$fecha_a= dateDMESY(dateDMY($aux_c[0]));
	$aux= $fecha_a." ".substr($aux_c[1],0,5);
		$smarty->assign('fecha_aprobacion',$aux);
	
	//fecha_aceptacion
	$aux_b= $resultado["fecha_aceptacion"];
	$aux_c= explode(" ",$aux_b);
    $fecha_a= dateDMESY(dateDMY($aux_c[0]));
	$aux= $fecha_a." ".substr($aux_c[1],0,5);
		$smarty->assign('fecha_aceptacion',$aux);

	
	//$fechas_des[0]= $aux;
	
	/*
	//fecha habilitacion, es la ultima siempre
	$aux_b= $resultado["fecha_habilitacion"];
	$aux_c= explode(" ",$aux_b);
	$fecha_a= dateDMESY(dateDMY($aux_c[0]));
	//$fecha_a= $bd_fechas->formar_fecha($aux_c[0], "-", "dd/MMM/yyyy", "yyyy-mm-dd");
	$aux= "&nbsp;".$fecha_a." ".$aux_c[1]." ";
	$fechas_hab[0]= $aux;
	*/
	
	//recuperamos las fechas de la otra tabla
	$sql= "SELECT ilf.*, us.nombres FROM informes_legales_fechas ilf 
	INNER JOIN usuarios us ON us.id_usuario = ilf.usr_acep
	WHERE id_informe_legal='$id' ORDER BY fecha_quitar ";
	//echo $sql;
	$query = consulta($sql);
	$fechas_hab=array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$aux_b= $row["fecha_quitar"];
		if($aux_b!=''){
		$aux_c= explode(" ",$aux_b);
		$fecha_a= dateDMESY(dateDMY($aux_c[0]));
		$aux1= $fecha_a." ".substr($aux_c[1],0,5);
		}else $aux1= '';
		
		$aux_b= $row["fecha_publicacion"];
		if($aux_b!=''){
			$aux_c= explode(" ",$aux_b);
			$fecha_a= dateDMESY(dateDMY($aux_c[0]));
			$aux2= $fecha_a." ".substr($aux_c[1],0,5);
		}else $aux2= '';
		$fechas_hab[$i]=array('hab' => $aux1, 'usr' => $row["nombres"], 'qui' => $aux2);
		
		$i++;
	}
	$smarty->assign('fechas_hab',$fechas_hab);
	
	$smarty->display('informe_legal/ver_detalle.html');
	die();

?>
