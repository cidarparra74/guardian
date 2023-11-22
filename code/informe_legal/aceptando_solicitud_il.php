<?php

//esto acepta la solicitud de i.l.  a partir de la copia de otro i.l. guardado

$id= $_REQUEST["id"]; //el i.l. guardado
$idnew= $_REQUEST["idnew"]; //el i.l. nuevo

$tipo = $_REQUEST["tipo"];  //tipo de garantia/bien

$idus = $_SESSION["idusuario"];
//fecha de acptacion

$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";


	//aceptando la elaboracion de i.l.
	$sql= "INSERT INTO informes_legales_fechas(id_informe_legal, fecha_quitar, usr_acep) 
			VALUES( '$idnew', $fecha_actual, '$idus') ";
	ejecutar($sql);
	
	$sql= "UPDATE informes_legales SET estado='ace', fecha_aceptacion=$fecha_actual, usr_acep=$idus 
			WHERE id_informe_legal='$idnew' ";
	ejecutar($sql);
//aqui no se si se envia correo

//copiamos del i.l. guardado al nuevo
$sql="SELECT otras_observaciones, garantia_contrato, nota,
	conclusiones, tradicion 
	FROM informes_legales_bk WHERE id_informe_legal='$id'";
$query = consulta($sql);
$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
$otras_observaciones= $resultado["otras_observaciones"];
$garantia_contrato= $resultado["garantia_contrato"];
$nota= $resultado["nota"];
$conclusiones= $resultado["conclusiones"];

$tradicion= $resultado["tradicion"];
//numero_informe, $numero_informe= $resultado["numero_informe"];numero_informe='$numero_informe',
$sql="UPDATE informes_legales 
SET otras_observaciones='$otras_observaciones',
garantia_contrato='$garantia_contrato',
nota='$nota',
conclusiones='$conclusiones',
tradicion='$tradicion'
WHERE id_informe_legal='$idnew'";
ejecutar($sql);


//otros documentos
//aqui se supone que como recien se esta aceptando el i.l. no existe ningun registro en tablas
// secundarias, por lo que hay que insertar los registros del BK
if($tipo=='1'){
	//inmueble
	//No existe, recuperamos el maximo id de informe legal inmueble
	$sql= "SELECT MAX(id_informe_legal_inmueble) AS maximo FROM informes_legales_inmuebles ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
 
	$idi= $resultado["maximo"] + 1;
	$sql= "INSERT INTO informes_legales_inmuebles ( [id_informe_legal_inmueble]
		, [id_informe_legal]
		, [descripcion_bien]
		,[extension]
		,[ubicacion]
		,[registro_dr]
		,[superficie_titulo]
		,[superficie_plano]
		,[limite_este]
		,[limite_oeste]
		,[limite_norte]
		,[limite_sud]
		,[datos_documento]) 
	SELECT '$idi' as id_informe_legal_inmueble,
		'$idnew' as id_informe_legal
		,[descripcion_bien]
		,[extension]
		,[ubicacion]
		,[registro_dr]
		,[superficie_titulo]
		,[superficie_plano]
		,[limite_este]
		,[limite_oeste]
		,[limite_norte]
		,[limite_sud]
		,[datos_documento]
	FROM [informes_legales_inmuebles]
	WHERE id_informe_legal = '$id' ";

	ejecutar($sql);
}elseif($tipo=='2' or $tipo=='3' or $tipo=='6'){
	//maquinaria  //vehiculo  //semovientes
	$sql= "INSERT INTO informes_legales_vehiculos (
	[id_informe_legal]
      ,[numero_escritura]
      ,[fecha_escritura]
      ,[fecha_registro]
      ,[matricula]
      ,[asiento]
      ,[fecha_gravamen]
      ,[numero_boleta]
      ,[fecha_boleta]
      ,[placa]
      ,[marca]
      ,[chasis]
      ,[modelo]
      ,[motor]
      ,[clase]
      ,[tipo]
      ,[color]
      ,[alcaldia]
      ,[crpva]
      ,[fecha_vehiculo]
      ,[poliza]
      ,[fecha_poliza]
      ,[sidunea]
      ,[fecha_sidunea])
	SELECT '$idnew' as id_informe_legal
      ,[numero_escritura]
      ,[fecha_escritura]
      ,[fecha_registro]
      ,[matricula]
      ,[asiento]
      ,[fecha_gravamen]
      ,[numero_boleta]
      ,[fecha_boleta]
      ,[placa]
      ,[marca]
      ,[chasis]
      ,[modelo]
      ,[motor]
      ,[clase]
      ,[tipo]
      ,[color]
      ,[alcaldia]
      ,[crpva]
      ,[fecha_vehiculo]
      ,[poliza]
      ,[fecha_poliza]
      ,[sidunea]
      ,[fecha_sidunea]
  FROM [informes_legales_vehiculos]
  WHERE id_informe_legal = '$id' ";
  ejecutar($sql);
}elseif($tipo=='4'){
	//otros
}elseif($tipo=='5'){
	//personeria
	$sql= "INSERT INTO informes_legales_pj (
	[id_informe_legal]
	,[tipo_sociedad]
      ,[actividad]
      ,[objeto]
      ,[duracion]
      ,[nro_afiliados]
      ,[licencia])
	SELECT '$idnew' as id_informe_legal
      ,[tipo_sociedad]
      ,[actividad]
      ,[objeto]
      ,[duracion]
      ,[nro_afiliados]
      ,[licencia]
  FROM [informes_legales_pj]
  WHERE id_informe_legal = '$id' ";
  ejecutar($sql);
}

// propietarios

$sql= "INSERT INTO informes_legales_propietarios (
id_propietario, id_informe_legal, estitular)
 SELECT id_propietario, '$idnew' as id_informe_legal, estitular 
FROM [informes_legales_propietarios]
  WHERE id_informe_legal = '$id' ";
  
  ejecutar($sql);
 // documentos
// asumimos los mismos docs
$sql= "INSERT INTO informes_legales_documentos 
(id_informe_legal, id_tipo_bien, id_documento, id_tipo_documento, numero, fecha, 
fojas, observaciones, fecha_vencimiento, tiene_observacion, tomar_en_cuenta) 
 SELECT '$idnew' as id_informe_legal, id_tipo_bien, id_documento, id_tipo_documento, numero, fecha, 
fojas, observaciones, fecha_vencimiento, tiene_observacion, tomar_en_cuenta 
FROM informes_legales_documentos 
WHERE id_informe_legal = '$id' ";
 
ejecutar($sql);
 
?>