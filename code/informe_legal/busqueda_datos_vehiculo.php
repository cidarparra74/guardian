<?php

	//print_r($_REQUEST);
	//die();
	$id_tipo_bien="1"; //vehiculo
	$placa= $_REQUEST['placa'];
	
	//buscamos los datos de esta placa si es que existiera
	$sql= "SELECT MAX(id_informe_legal_vehiculo) AS maximo FROM informes_legales_vehiculos WHERE placa='$placa' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_maximo=0;
	$nuevo_id= $resultado["maximo"];
	if($nuevo_id!=null){
		$id_maximo=$nuevo_id;
	}
	
	//datos de la placa
	$sql= "SELECT * FROM informes_legales_vehiculos WHERE id_informe_legal_vehiculo='$id_maximo' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$placa= $resultado["placa"];
	$marca= $resultado["marca"];
	$chasis= $resultado["chasis"];
	$modelo= $resultado["modelo"];
	$clase= $resultado["clase"];
	$tipo= $resultado["tipo"];
	$motor= $resultado["motor"];
	$color= $resultado["color"];
	$alcaldia= $resultado["alcaldia"];
	$crpva= $resultado["crpva"];
	$poliza= $resultado["poliza"];
	
	if($resultado["fecha_vehiculo"] != null || $resultado["fecha_vehiculo"]!= ""){
		$aux_c= explode(" ",$resultado["fecha_vehiculo"]);
		
		/*$aux_d= $aux_c[0];
		//echo "$aux_d";
		$fecha_aux= $bd_fechas->formar_fecha($aux_d, "-", "dd/MMM/yyyy", "yyyy-mm-dd");
		$fecha= $fecha_aux;*/
		$fecha[$i]= dateDMESY($aux_c[0]);
	}
	else{
		$fecha="";
	}
	
	$smarty->assign('crpva',$crpva);
	$smarty->assign('poliza',$poliza);
	$smarty->assign('fecha',$fecha);
	
	$smarty->assign('placa',$placa);
	$smarty->assign('marca',$marca);
	$smarty->assign('chasis',$chasis);
	$smarty->assign('modelo',$modelo);
	$smarty->assign('clase',$clase);
	$smarty->assign('tipo',$tipo);
	$smarty->assign('motor',$motor);
	$smarty->assign('color',$color);
	$smarty->assign('alcaldia',$alcaldia);
	
	$smarty->display('informe_legal/busqueda_datos_vehiculo.html');
	die();
?>
