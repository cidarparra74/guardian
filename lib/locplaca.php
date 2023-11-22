<?php 


	require_once('../lib/conexionMNU.php');
	require_once('../lib/fechas.php');
	//ver si la llamada es via ajax
	
	$placa= $_GET['placa'];
	$placa= substr(str_replace("'","",$placa),0,10);
	$placa = strtoupper($placa);
	//buscamos los datos de esta placa si es que existiera
	$sql= "SELECT MAX(id_informe_legal_vehiculo) AS maximo FROM informes_legales_vehiculos WHERE placa='$placa' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$nuevo_id= $resultado["maximo"];
	if($nuevo_id!=null)
		$id_maximo=$nuevo_id;
	else 
		$id_maximo=0;
	if($id_maximo!=0){
		//datos de la placa
		$sql= "SELECT * FROM informes_legales_vehiculos WHERE id_informe_legal_vehiculo='$id_maximo' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
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
		//$fecha_poliza= $resultado["fecha_poliza"];
		//$fecha= $resultado["fecha_vehiculo"];
		//if($resultado["fecha_poliza"] != null || $resultado["fecha_poliza"]!= ""){
			$aux_c= explode(" ",$resultado["fecha_poliza"]);
			$fecha_poliza= dateDMY($aux_c[0]);
			$fecha_poliza= dateDMESY($fecha_poliza);
			$aux_c= explode(" ",$resultado["fecha_vehiculo"]);
			$fecha= dateDMY($aux_c[0]);
			$fecha= dateDMESY($fecha);
		//}
		echo '1|'.$marca.'|'.$chasis.'|'.$modelo.'|'.$clase.'|'.$tipo.'|'.$motor.'|'.$color.'|'.$alcaldia.'|'.$crpva.'|'.$poliza.'|'.$fecha_poliza.'|'.$fecha;
	}else{
		echo "0|x";
	}
?>
