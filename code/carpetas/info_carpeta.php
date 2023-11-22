<?php

	$id_carpeta= $_REQUEST["id"];
	
//	$smarty->assign('id',$id);
		
		
		
		// -----------------  esto para el html
	$sql= "SELECT TOP 1 logo01 FROM opciones ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//datos principales
	$logo01   = $resultado['logo01'];
	$smarty->assign('logo01', $logo01);
	
		
		//nombre del propietario y codigo mis
	$sql= "SELECT pr.nombres, pr.ci, 
	ca.carpeta, convert(varchar(10),ca.creacion_carpeta,103) as fecha, ca.operacion,
	ofi.nombre AS oficina,
	td.tipo_bien
	FROM carpetas ca
	INNER JOIN propietarios pr ON pr.id_propietario = ca.id_propietario
	INNER JOIN oficinas ofi ON ofi.id_oficina = ca.id_oficina
	LEFT JOIN tipos_bien td ON ca.id_tipo_carpeta=td.id_tipo_bien
	WHERE id_carpeta = '$id_carpeta' ";

	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$smarty->assign('carpeta',$resultado["carpeta"]);
	$smarty->assign('operacion',$resultado["operacion"]);
	$smarty->assign('oficina',$resultado["oficina"]);
	$smarty->assign('tipo',$resultado["tipo_bien"]);
	$smarty->assign('nombres',$resultado["nombres"]);
	$smarty->assign('ci',$resultado["ci"]);
	$smarty->assign('fecha',$resultado["fecha"]);
	
//recuperando los datos para la ventana, los documentos que tiene el propietario
$sql= "SELECT * FROM movimientos_carpetas WHERE id_carpeta ='$id_carpeta' ORDER BY id_movimiento_carpeta ";

$query = consulta($sql);
$movimientos= array();
//los movimientos de la carpeta
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$estado = $row["id_estado"];	
	$flujo  = $row["flujo"];	
	// prestarse la carpeta
	$aux_a = $row["corr_auto"];	
	if($aux_a != null){
		$aux_c		= explode(" ",$aux_a);
		$fecha1= dateDMESY(dateDMY($aux_c[0]));
	}else{
		$fecha1		="";
	}
	//CUANDO ES DEVOLUCION DE CARPETA DESDE MEU CARPETAS docs adicionales NO EXISTE ID_US_INICIO
	$id_us_inicio = $row["id_us_inicio"];
	$sql="SELECT nombres FROM usuarios WHERE id_usuario = '$id_us_inicio' ";
	$query2 = consulta($sql);
	$result= $query2->fetchRow(DB_FETCHMODE_ASSOC);
	$us_inicio = $result["nombres"];
	
	if($row["id_us_inicio"] != $row["id_us_corriente"]){
		$sql="SELECT nombres FROM usuarios WHERE id_usuario = ".$row["id_us_corriente"];
		$query2 = consulta($sql);
		$result= $query2->fetchRow(DB_FETCHMODE_ASSOC);
		$us_comun = $result["nombres"];
	}else{
		$us_comun = $us_inicio;
	}
	// tampoco hay autoriza para devolucion desde menu carpetas
	$id_us_autoriza = $row["id_us_autoriza"];
	$sql="SELECT nombres FROM usuarios WHERE id_usuario = '$id_us_autoriza'";
	$query2 = consulta($sql);
	$result= $query2->fetchRow(DB_FETCHMODE_ASSOC);
	$us_autoriza = $result["nombres"];
	
	
	// se rechaza la solicitud de carpeta
	$aux_a = $row["neg_auto_corr"];	
	if($aux_a != null){
		$aux_c		= explode(" ",$aux_a);
		$fecha2= dateDMESY(dateDMY($aux_c[0]));
	}else{
		$fecha2		="";
	}
	// se acepta la solicitud y se da un plazo de devol
	$aux_a = $row["auto_arch"];	
	if($aux_a != null){
		$aux_c		= explode(" ",$aux_a);
		$fecha3= dateDMESY(dateDMY($aux_c[0]));
		//no hay usuario archivo cuando se devuelve por menu carpetas
		$id_us_archivo = $row["id_us_archivo"];
		$sql="SELECT nombres FROM usuarios WHERE id_usuario = '$id_us_archivo'";
		$query2 = consulta($sql);
		$result= $query2->fetchRow(DB_FETCHMODE_ASSOC);
		$us_archivo = $result["nombres"];
	}else{
		$fecha3		="";
		$us_archivo = "";
	}
	$aux_a = $row["auto_arch_plazo"];	
	if($aux_a != null){
		$aux_c		= explode(" ",$aux_a);
		$fecha3b= dateDMESY(dateDMY($aux_c[0]));
	}else{
		$fecha3b		="";
	}
	
	// catastro presta la carpeta
	$aux_a = $row["arch_corr_prest"];	
	if($aux_a != null){
		$aux_c		= explode(" ",$aux_a);
		$fecha4= dateDMESY(dateDMY($aux_c[0]));
	}else{
		$fecha4		="";
	}$aux_a = $row["arch_corr_plazo"];	
	if($aux_a != null){
		$aux_c		= explode(" ",$aux_a);
		$fecha4b= dateDMESY(dateDMY($aux_c[0]));
	}else{
		$fecha4b		="";
	}
	// solicitante confirma que tiene la carpeta
	$aux_a = $row["arch_corr_conf"];	
	if($aux_a != null){
		$aux_c		= explode(" ",$aux_a);
		$fecha5= dateDMESY(dateDMY($aux_c[0]));
	}else{
		$fecha5		="";
	}
	// solicitante devuelve la carpeta
	$aux_a = $row["corr_arch_ret"];	
	if($aux_a != null){
		$aux_c		= explode(" ",$aux_a);
		$fecha6= dateDMESY(dateDMY($aux_c[0]));
	}else{
		$fecha6		="";
	}
	// catastro recibe la carpeta
	$aux_a = $row["corr_arch_ret_conf"];	
	if($aux_a != null){
		$aux_c		= explode(" ",$aux_a);
		$fecha7= dateDMESY(dateDMY($aux_c[0]));
	}else{
		$fecha7		="";
	}

	
	// devoldemos carpta al cliente
	$aux_a = $row["corr_dev"];	
	if($aux_a != null){
		$aux_c		= explode(" ",$aux_a);
		$fecha8= dateDMESY(dateDMY($aux_c[0]));
	}else{
		$fecha8		="";
	}
	// adjudicamos al banco
	$aux_a = $row["corr_adj"];	
	if($aux_a != null){
		$aux_c		= explode(" ",$aux_a);
		$fecha9= dateDMESY(dateDMY($aux_c[0]));
	}else{
		$fecha9		="";
	}
	$movimientos[]= array('fecha1' => $fecha1,
						'us_inicio' => $us_inicio,
						'us_comun' => $us_comun,
						'us_autoriza' => $us_autoriza,
						'obs_1' => $row["obs_1"],
						'fecha2' => $fecha2,
						'obs_2' => $row["obs_2"],
						'fecha3' => $fecha3,
						'fecha3b' => $fecha3b,
						'us_archivo' => $us_archivo,
						'obs_3' => $row["obs_3"],
						'fecha4' => $fecha4,
						'fecha4b' => $fecha4b,
						'obs_4' => $row["obs_4"],
						'fecha5' => $fecha5,
						'obs_5' => $row["obs_5"],
						'fecha6' => $fecha6,
						'obs_6' => $row["obs_6"],
						'fecha7' => $fecha7,
						'obs_7' => $row["obs_7"],
						'fecha8' => $fecha8,
						'obs_8' => $row["obs_8"],
						'fecha9' => $fecha9,
						'obs_9' => $row["obs_adj"],
						'estado' => $estado,
						'flujo' => $flujo);

}

$smarty->assign('movimientos',$movimientos);

// ---- hasta aqui para el html
	$smarty->display('carpetas/info_carpeta.html');

	die();
?>