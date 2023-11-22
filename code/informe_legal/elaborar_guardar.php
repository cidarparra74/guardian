<?php
require_once('../lib/fechas.php');
	
	$id= $_REQUEST['id'];
	$fecha = $_REQUEST['fecha'];
	// para el tipo de bien  tipo_bien
	$id_tipo_bien = $_REQUEST['id_tipo_bien'];
	
	//convertimos la fecha a formato corecto
		if($fecha != ""){
			$fechaok = dateYMD($fecha);
			$fechaok = "CONVERT(DATETIME,'$fechaok',102)";
		}else{
			$fechaok ="null";
		}
	
	$id_us_comun= $_REQUEST['usuario_comun'];
	if($id=='0'){
		//Es nuevo, viene desde la opcion de Nuevo I.L. (sin docs)  adicionar_informe.php
		//recuperamos el siguiente numero de informe
		$sql= "SELECT MAX(id_informe_legal) AS maximo FROM informes_legales ";
		$idus = $_SESSION["idusuario"];
		$fechaac = date("Y-m-d H:i:s");
		$fechaac = "CONVERT(DATETIME,'$fechaac',102)";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$id= bcadd($resultado["maximo"],1,0);
		$id_propietari1 = $_REQUEST['id_propietari1'];
		//insertando
		$sql= "INSERT INTO informes_legales (id_informe_legal, id_us_comun, id_tipo_bien, cliente, ci_cliente, fecha, puede_operar,
		numero_informe, habilitar_informe, estado, id_titular, fecha_solicitud, usr_acep, id_propietario) VALUES 
		('$id','$idus', $id_tipo_bien, '', $fechaac, '', 0, $id, 0, 'ace',0,'',$id_us_comun, $id_propietari1) ";
		//echo $sql;
		ejecutar($sql);
	}
	
	$cliente= $_REQUEST['cliente'];
	$ci_cliente= $_REQUEST['ci_cliente'];
	$id_tipo_identificacion= $_REQUEST['tipo_identificacion'];
	$montoprestamo =  '0'; //$_REQUEST['montoprestamo'];
	//$moneda = $_REQUEST['moneda'];

	$motivo = $_REQUEST['motivo'];
	
	$otras_observaciones= $_REQUEST['otras_observaciones'];
	if(isset($_REQUEST['tradicion']))
		$tradicion= $_REQUEST['tradicion'];
	else
		$tradicion= ''; // tipos bien <> Inmueble no tiene tradicion
		
	$garantia_contrato= $_REQUEST['garantia_contrato'];
	$nota= $_REQUEST['nota'];
	$conclusiones= $_REQUEST['conclusiones'];
	$bandera= $_REQUEST['bandera'];
	
	//actualizamos en la tabla informe legal
//Modificado por Percy 31-10-2018, quitamos: montoprestamo = '$montoprestamo', 
	//id_us_comun='$id_us_comun',
	$sql= "UPDATE informes_legales SET   "
	." id_us_comun = $id_us_comun, cliente='$cliente', ci_cliente='$ci_cliente', fecha=$fechaok, "
	." motivo = '$motivo', "
	."otras_observaciones='$otras_observaciones', tradicion='$tradicion', "
	."garantia_contrato='$garantia_contrato', nota='$nota', conclusiones='$conclusiones', "
	."bandera='$bandera' "
	."WHERE id_informe_legal='$id' ";
	//echo "$sql";

	ejecutar($sql);
	
	//datos aparentemente innecesarios
	$nombres_apellidos= $_REQUEST['nombres_apellidos'];
	$ci= $_REQUEST['ci'];
	$nit= $_REQUEST['nit'];
	$direccion= $_REQUEST['direccion'];
	
		
	if($tipo_bien == 'I'){
		//datos del inmueble
		$descripcion_bien= $_REQUEST['descripcion_bien'];
		$extension= $_REQUEST['extension'];
		$ubicacion= $_REQUEST['ubicacion'];
		$registro_dr= $_REQUEST['registro_dr'];
		$datos_documento= $_REQUEST['datos_documento'];
		$superficie_titulo= $_REQUEST['superficie_titulo'];
		$superficie_plano= $_REQUEST['superficie_plano'];
		$limite_este= $_REQUEST['limite_este'];
		$limite_oeste= $_REQUEST['limite_oeste'];
		$limite_norte= $_REQUEST['limite_norte'];
		$limite_sud= $_REQUEST['limite_sud'];
	
		//actualizamos los datos del inmueble
		//en caso de que no este creado
		$sql="SELECT id_informe_legal FROM informes_legales_inmuebles WHERE id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$aux_acc= $resultado['id_informe_legal'];
	
		if($aux_acc == $id){
			//Existe, actualizamos los datos del inmueble
			$sql="UPDATE informes_legales_inmuebles SET descripcion_bien='$descripcion_bien', extension='$extension', ubicacion='$ubicacion', registro_dr='$registro_dr', superficie_titulo='$superficie_titulo', superficie_plano='$superficie_plano', limite_este='$limite_este', limite_oeste='$limite_oeste', limite_norte='$limite_norte', limite_sud='$limite_sud', datos_documento='$datos_documento' WHERE id_informe_legal='$id' ";
			//echo "$sql";
			ejecutar($sql);
		}
		else{
			//No existe, recuperamos el maximo id de informe legal inmueble
			$sql= "SELECT MAX(id_informe_legal_inmueble) AS maximo FROM informes_legales_inmuebles ";
			$query = consulta($sql);
		    $resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
			
			$id_informe_legal_inmueble= $resultado["maximo"] + 1; 
			//insertamos los datos del inmueble
			$sql= "INSERT INTO informes_legales_inmuebles(id_informe_legal_inmueble, id_informe_legal, descripcion_bien, extension, ubicacion, registro_dr, superficie_titulo, superficie_plano, limite_este, limite_oeste, limite_norte, limite_sud, datos_documento) VALUES('$id_informe_legal_inmueble', '$id', '$descripcion_bien', '$extension', '$ubicacion', '$registro_dr', '$superficie_titulo', '$superficie_plano', '$limite_este', '$limite_oeste', '$limite_norte', '$limite_sud', '$datos_documento') ";
			//echo "$sql";
			ejecutar($sql);
		}

	} // fin if($tipo_bien == 'I')
	elseif($tipo_bien == 'V'){
		$placa= strtoupper($_REQUEST['placa']);
		$marca= $_REQUEST['marca'];
		$chasis= $_REQUEST['chasis'];
		$modelo= $_REQUEST['modelo'];
		$motor= $_REQUEST['motor'];
		$clase= $_REQUEST['clase'];
		$tipo= $_REQUEST['tipo'];
		$color= $_REQUEST['color'];
		$crpva= $_REQUEST['crpva'];
		
		$fecha_vehiculo= $_REQUEST['fecha_vehiculo'];
		if($fecha_vehiculo != null && trim($fecha_vehiculo) != ''){
			$fechaok = dateYMD($fecha_vehiculo);
			$fechaok = "CONVERT(DATETIME,'$fechaok',102)";
		}else{
			$fechaok="null";
		}

		$fpoliza= $_REQUEST['fpoliza'];
		if($fpoliza != null && trim($fpoliza) != ''){
			$fechapo = dateYMD($fpoliza);
			$fechapo = "CONVERT(DATETIME,'$fechapo',102)";
		}else{
			$fechapo="null";
		}
		
		$poliza= $_REQUEST['poliza'];
		$alcaldia= $_REQUEST['alcaldia'];
		
		//actualizamos los datos del vehiculo
		//en caso de que no este creado
		$sql="SELECT id_informe_legal FROM informes_legales_vehiculos WHERE id_informe_legal='$id' ";

		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$aux_acc= $resultado['id_informe_legal'];
	
		if($aux_acc == $id){
			$sql= "UPDATE informes_legales_vehiculos SET placa='$placa', marca='$marca', chasis='$chasis', modelo='$modelo', motor='$motor', clase='$clase', 
				tipo='$tipo', color='$color', crpva='$crpva', fecha_vehiculo=$fechaok, poliza='$poliza', alcaldia='$alcaldia', fecha_poliza=$fechapo WHERE id_informe_legal='$id' ";
			ejecutar($sql);
		}else{
			//recuperamos el maximo id de informe legal vehiculo
			//$sql= "SELECT MAX(id_informe_legal_vehiculo) AS maximo FROM informes_legales_vehiculos ";
			//$query = consulta($sql);
			//$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
			//$id_informe_legal_vehiculo= $resultado["maximo"] + 1;
			//insertamos los datos del vehiculo
			$sql= "INSERT INTO informes_legales_vehiculos( id_informe_legal, placa, marca, chasis, modelo, motor, clase, tipo, color, alcaldia, crpva, fecha_vehiculo, poliza, fecha_poliza) ";
			$sql.= "VALUES('$id', '$placa', '$marca', '$chasis', '$modelo', '$motor', '$clase', '$tipo', '$color', '$alcaldia', '$crpva', $fechaok, '$poliza', $fechapo) ";
			ejecutar($sql);
		}
	
	} //fin if($tipo_bien == 'V')
	elseif($tipo_bien == 'M'){ // aqui $tipo_bien es M=Maquinaria
			//usamos las mismas variables y tabla para maquinaquia y vehiculos
			$tipo= $_REQUEST['tipo'];
			$placa= $_REQUEST['placa'];
			$marca= $_REQUEST['marca'];
			$motor= $_REQUEST['motor'];
			$modelo= $_REQUEST['modelo'];
			$chasis= $_REQUEST['chasis'];
			$poliza= $_REQUEST['poliza'];
			$crpva= $_REQUEST['crpva'];
			$clase= $_REQUEST['clase'];
			//nuevos desde 10/04/2012
			$fpoliza= $_REQUEST['fpoliza'];
			$sidunea= $_REQUEST['sidunea'];
			$fsidunea= $_REQUEST['fsidunea'];
			
			if($fpoliza != null && trim($fpoliza) != ''){
				$fechapo = dateYMD($fpoliza);
				$fechapo = "CONVERT(DATETIME,'$fechapo',102)";
			}else{
				$fechapo="null";
			}
			
			if($fsidunea != null && trim($fsidunea) != ''){
				$fechasi = dateYMD($fsidunea);
				$fechasi = "CONVERT(DATETIME,'$fechasi',102)";
			}else{
				$fechasi="null";
			}
			
			//actualizamos los datos del vehiculo
			//en caso de que no este creado
			$sql="SELECT id_informe_legal FROM informes_legales_vehiculos WHERE id_informe_legal='$id' ";
			$query = consulta($sql);
		    $resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$aux_acc= $resultado['id_informe_legal'];
		
			if($aux_acc == $id){
				$sql= "UPDATE informes_legales_vehiculos SET placa='$placa', marca='$marca', chasis='$chasis', modelo='$modelo', motor='$motor', clase='$clase', tipo='$tipo', crpva='$crpva', poliza='$poliza', fecha_poliza=$fechapo, sidunea='$sidunea', fecha_sidunea= $fechasi WHERE id_informe_legal='$id' ";
				//echo "$sql";
				ejecutar($sql);

			}else{
				//recuperamos el maximo id de informe legal vehiculo
				//$sql= "SELECT MAX(id_informe_legal_vehiculo) AS maximo FROM informes_legales_vehiculos ";

				//$query = consulta($sql);
		    	//$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
				//$id_informe_legal_vehiculo= $resultado["maximo"] + 1;
				//insertamos los datos del vehiculo
				$sql= "INSERT INTO informes_legales_vehiculos(id_informe_legal, placa, marca, chasis, modelo, motor, clase, tipo, crpva, poliza, fecha_poliza, sidunea, fecha_sidunea) ";
				$sql.= "VALUES( '$id', '$placa', '$marca', '$chasis', '$modelo', '$motor', '$clase', '$tipo', '$crpva', '$poliza', $fechapo, '$sidunea', $fechasi) ";

				ejecutar($sql);

			}
		
	}elseif($tipo_bien == 'S'){ // aqui $tipo_bien es S=Semovientes
		//usamos las mismas variables y tabla para maquinaquia y vehiculos
		$tipo= $_REQUEST['tipo'];
		$asiento= $_REQUEST['asiento'];
		$marca= $_REQUEST['marca'];
		$clase= $_REQUEST['clase'];
		$matricula= $_REQUEST['matricula'];
		$poliza= $_REQUEST['poliza'];
		$fpoliza= $_REQUEST['fpoliza'];
		
		if($fpoliza != null && trim($fpoliza) != ''){
			$fechapo = dateYMD($fpoliza);
			$fechapo = "CONVERT(DATETIME,'$fechapo',102)";
		}else{
			$fechapo="null";
		}
		
		//actualizamos los datos del vehiculo
		//en caso de que no este creado
		$sql="SELECT id_informe_legal FROM informes_legales_vehiculos WHERE id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$aux_acc= $resultado['id_informe_legal'];
	
		if($aux_acc == $id){
			$sql= "UPDATE informes_legales_vehiculos SET marca='$marca', clase='$clase', tipo='$tipo', poliza='$poliza', fecha_poliza=$fechapo, matricula='$matricula', asiento='$asiento' WHERE id_informe_legal='$id' ";
			ejecutar($sql);
		}else{
			$sql= "INSERT INTO informes_legales_vehiculos(id_informe_legal, marca, asiento, clase, tipo, poliza, fecha_poliza, matricula) ";
			$sql.= "VALUES( '$id', '$marca', '$asiento', '$clase', '$tipo', '$poliza', $fechapo, '$matricula') ";
			ejecutar($sql);
		}
			
	}else{
		$matricula= $_REQUEST['matricula'];
		//actualizamos los datos del vehiculo
		//en caso de que no este creado
		$sql="SELECT id_informe_legal FROM informes_legales_vehiculos WHERE id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$aux_acc= $resultado['id_informe_legal'];
		if($aux_acc == $id){
			$sql= "UPDATE informes_legales_vehiculos SET matricula='$matricula' WHERE id_informe_legal='$id' ";
			ejecutar($sql);
		}else{
			$sql= "INSERT INTO informes_legales_vehiculos(id_informe_legal, matricula) ";
			$sql.= "VALUES( '$id', '$matricula') ";
			ejecutar($sql);
		}
	}
	
	
	//***********
	$sql= "DELETE FROM informes_legales_propietarios WHERE id_informe_legal= '$id'";
			ejecutar($sql);
	//verificamos si el contrato tiene propietarios
if(isset($_REQUEST["hdnCi"])){
	//recuperamos todo el conjunto de datos dispuestos en arreglos
	$hdnID=$_REQUEST["hdnIDprop"];
	//$hdnCi=$_REQUEST["hdnCi"];
	//$hdnEmi=$_REQUEST["hdnEmi"];
	//$hdnDirec=$_REQUEST["hdnDirec"];
	$hdnTitu=$_REQUEST["hdnTitu"];
	//$hdnNombre=$_REQUEST["hdnNombre"];

	
	//recorremos el arreglo de los CI y utilizamos el mismo indice para los demas
	//nos armamos un arreglo de propietarios
	
	foreach($hdnID as $key => $idprop){
		$idNuevo = $idprop;
		$titu = $hdnTitu[$key];
		$sql= "INSERT INTO informes_legales_propietarios (id_propietario, id_informe_legal, estitular) VALUES ('$idNuevo', '$id', '$titu')";
		ejecutar($sql);
		
	}
	/*
}else{
	//no existen propietarios en el i.l.
	*/
}
	//******************************

	
	//----------
	
	// Guardamos docs e informacion del I.L. - Nueva forma de hacerlo
	//eliminamos los documentos que tenia anteriormente
	$sql= "DELETE FROM informes_legales_documentos WHERE id_informe_legal='$id' ";
	ejecutar($sql);
	
	//recuperando los documentos de este tipo de bien
	
	//documento_id -> arregloe q contiene los id de los documentos pertenecientes a este tipo de bien
	$documento_id = $_REQUEST['documento_id'];
	//fechas -> areglo multidimensional que contiene el numero del doc, dias, fecha vencimiento y los check boxs
	$numero = $_REQUEST['numero'];
	//tipo_documento -> arreglo de los tipos de doc
	$tipo_documento = $_REQUEST['tipo_documento'];
	//fojas -> arreglo de la cantidad de docs fisico
	//$fojas = $_REQUEST['fojas'];
	//observaciones -> arreglo de observaciones
	//$observaciones = $_REQUEST['observaciones'];

	
	//iniciamos el ciclo de recorrido de los arreglos
	$i=0;
	foreach($documento_id as $ids){
		
		$aux1 = "tomar_".$i;
		$aux2 = "obs_".$i;
		$aux3 = "fecha_".$i;
		$aux4 = "vencim_".$i;
		$aux5 = "fecha_venc".$i;
		$aux6 = "fojas".$i;
		if(!isset($_REQUEST["$aux1"])){
			
			$id_documento = $ids;
			$id_tipo_documento = $tipo_documento[$i];
			$num = $numero[$i];
			$tomar_en_cuenta = 1;
			if(isset($_REQUEST["$aux2"])){
				$obs = $_REQUEST["$aux2"];
			}else{
				$obs = "";
			}
			if(isset($_REQUEST["$aux3"])){
				$fecha_aux = $_REQUEST["$aux3"];
				$fecha_aux = dateYMD($fecha_aux);
				if($_REQUEST["$aux3"]!='')
					$fecha_aux = "CONVERT(DATETIME,'$fecha_aux',102)";
				else 
					$fecha_aux = "null";
			}else{
				$fecha_aux = "null";
			}
			
			if(isset($_REQUEST["$aux5"])){
				$fecha_aux_vencimiento = $_REQUEST["$aux5"];
				$fecha_aux_vencimiento = dateYMD($fecha_aux_vencimiento);
				//echo $fecha_aux_vencimiento;
				if($_REQUEST["$aux5"]!='')
					$fecha_aux_vencimiento = "CONVERT(DATETIME,'$fecha_aux_vencimiento',102)";
				else
					$fecha_aux_vencimiento = "null";
				//echo $fecha_aux_vencimiento;
			}else{
				$fecha_aux_vencimiento = "null";
			}
			if(isset($_REQUEST["$aux6"])){
				$foj = $_REQUEST["$aux6"];
			}else{
				$foj = "0";
			}
			
			if($obs == ""){
				$tiene_observacion=0;
			}else{
				$tiene_observacion=1;
			}
			if($foj == "0"){
				$tiene_observacion=1;
			}
			$sql_in= "INSERT INTO informes_legales_documentos(id_informe_legal, id_tipo_bien, id_documento, id_tipo_documento, numero, fecha, fojas, observaciones, fecha_vencimiento, tiene_observacion, tomar_en_cuenta) ";
			$sql_in.= "VALUES('$id', '$id_tipo_bien', '$id_documento', '$id_tipo_documento', '$num', $fecha_aux, '$foj', '$obs', $fecha_aux_vencimiento, '$tiene_observacion', '$tomar_en_cuenta') ";
			ejecutar($sql_in);
			//echo $sql_in;
		} //fin if(isset($_REQUEST["$aux"])){
		$i++;	
	} //fin foreach

?>
