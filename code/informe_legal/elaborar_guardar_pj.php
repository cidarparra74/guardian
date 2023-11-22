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
	$cliente= $_REQUEST['cliente'];
	$ci_cliente= $_REQUEST['ci_cliente'];

	$motivo = $_REQUEST['motivo'];
	
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
		('$id','$idus', $id_tipo_bien, '$cliente', '$ci_cliente', $fechaac, 0, $id, 0, 'ace',0,'',$id_us_comun, $id_propietari1) ";
		ejecutar($sql);
	}
	
	$otras_observaciones= $_REQUEST['otras_observaciones'];
	$conclusiones= $_REQUEST['conclusiones'];
	$bandera= $_REQUEST['bandera'];
	
	//actualizamos en la tabla informe legal
	//id_us_comun='$id_us_comun',
	$sql= "UPDATE informes_legales SET "
	." id_us_comun = $id_us_comun, fecha=$fechaok, "
	." motivo = '$motivo', "
	."otras_observaciones='$otras_observaciones', conclusiones='$conclusiones', "
	."bandera='$bandera' "
	."WHERE id_informe_legal='$id' ";
	ejecutar($sql);
		
	
		//datos de la personeria
		$tipo_sociedad= $_REQUEST['tipo_sociedad'];
		$actividad= $_REQUEST['actividad'];
		$duracion= $_REQUEST['duracion'];
		$direccion= $_REQUEST['direccion'];
		$matricula= $_REQUEST['matricula'];
		$nro_escritura= $_REQUEST['nro_escritura'];
		$notario= $_REQUEST['notario'];
		//para la nomina
		$nomina_dir='';
		$nombre= $_REQUEST['hdnNom'];
		$cargo = $_REQUEST['hdnCar'];
		$ci    = $_REQUEST['hdnCi'];
		foreach($nombre as $key=>$nom){
			if($nom!='')
			$nomina_dir .= $nom . ';' . $cargo[$key] . ';' . $ci[$key] . '|';
		}
		//para las fechas
		$fecha_aux= $_REQUEST['fecha_vence'];
		$fecha_aux = dateYMD($fecha_aux);
		if($fecha_aux!='--' and $fecha_aux!='')
			$fecha_vence = "CONVERT(DATETIME,'$fecha_aux',102)";
		else 
			$fecha_vence = "null";
			
		$fecha_aux= $_REQUEST['fecha_matri'];
		$fecha_aux = dateYMD($fecha_aux);
		if($fecha_aux!='--' and $fecha_aux!='')
			$fecha_matri = "CONVERT(DATETIME,'$fecha_aux',102)";
		else 
			$fecha_matri = "null";
			
		$fecha_aux= $_REQUEST['fecha_escri'];
		$fecha_aux = dateYMD($fecha_aux);
		if($fecha_aux!='--' and $fecha_aux!='')
			$fecha_escri = "CONVERT(DATETIME,'$fecha_aux',102)";
		else 
			$fecha_escri = "null";
		
		//actualizamos los datos de la pj
		//en caso de que no este creado
		$sql="SELECT id_informe_legal FROM informes_legales_pj WHERE id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$aux_acc= $resultado['id_informe_legal'];
	
		if($aux_acc == $id){
			//Existe, actualizamos los datos del inmueble
			$sql="UPDATE informes_legales_pj SET tipo_sociedad='$tipo_sociedad', actividad='$actividad', duracion='$duracion', 
			fecha_vence=$fecha_vence, matricula='$matricula', fecha_matri=$fecha_matri, nro_escritura='$nro_escritura', 
			fecha_escri=$fecha_escri, notario='$notario', nomina_dir='$nomina_dir', direccion='$direccion' 
			WHERE id_informe_legal='$id' ";
			//echo "$sql";
			ejecutar($sql);
		}
		else{
			//No existe, recuperamos el maximo id de informe legal inmueble
			$sql= "INSERT INTO informes_legales_pj(id_informe_legal, tipo_sociedad, fecha_vence, actividad, duracion, 
			matricula, fecha_matri, nro_escritura, fecha_escri, notario, nomina_dir, direccion) 
			VALUES('$id', '$tipo_sociedad', $fecha_vence, '$actividad', '$duracion', 
			'$matricula', $fecha_matri, '$nro_escritura', $fecha_escri, '$notario', '$nomina_dir', '$direccion') ";
			ejecutar($sql);
		}

	
	
	
	
	
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
				if($fecha_aux!='--' and $fecha_aux!='')
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
				if($fecha_aux_vencimiento!='--' and $fecha_aux_vencimiento!='')
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
