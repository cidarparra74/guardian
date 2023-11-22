<?php


$id_carpeta= $_REQUEST["id_carpeta"];

//eliminando los valores anteriores
//$sql= "DELETE FROM documentos_propietarios WHERE id_carpeta='$id_carpeta' ";
//ejecutar($sql);
//echo "$sql<br>";

$sql = "SELECT TOP 1 rutadoc,extension FROM opciones";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
$rutadoc = $row["rutadoc"];
$extension = $row["extension"];

//if(!file_exists($rutadoc)){
if($rutadoc==''){
	echo $rutadoc;
	echo "<br />";
	die("No se definió el directorio de almacenamiento.");	
}
if($extension=='')
	$extension = 'pdf';
//
//recuperando todos los documentos 
$cantidad= $_REQUEST["cantidad_total"];
$id_doc_pro= $_REQUEST["id_doc_pro"];
$id_files= $_REQUEST["id_file"];
$id_doc= $_REQUEST["id_documento"];
$id_foj= $_REQUEST["fojas"];
$id_obs= $_REQUEST["obs"];
// $miarchivo= $_REQUEST["miarchivo"];  //esto no es necesario
$id_tip= $_REQUEST["tipo"];
$numero = $_REQUEST['numero'];
$formatos_permitidos =  explode(',',$extension); //array('doc','docx' ,'xls', 'pdf','jpg');

$indice = 0;
for($i=0; $i<$cantidad; $i++){
	
	$aux3 = "fecha_".$i;
	//$aux4 = "vencim_".$i;
	$aux5 = "fecha_venc".$i;
	$num = $numero[$i];
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

	
	
	$aux = "tiene_".$i;
	$aux2 = "noobs_".$i;
	if(isset($_REQUEST["$aux"])) //no tomamos en cuenta el doc!
		$falta = 0;
	else
		$falta = 1;
	
	if(isset($_REQUEST["$aux2"])) //no tomamos en reporte de obs
		$noobs = 1;
	else
		$noobs = 0;

	if(	$falta == 1){
		// guardamos datos del dos seleccionado
		$archivonombre = '';
		// verificamos que si el documento tiene algun posible adjunto
		if(count($id_files)>0){
			// verificamos si el documento ue se va a guardar tiene adjunto
			if(count($id_files)>$indice && $id_doc[$i]==$id_files[$indice]){
				if($_FILES["miarchivo"]["name"][$indice]!='') { 
					// el nombre será el id de carpeta con el id de documento
					$extension = pathinfo($_FILES["miarchivo"]["name"][$indice], PATHINFO_EXTENSION);
					$archivonombre = str_pad($id_carpeta, 6, '0', STR_PAD_LEFT).
									 str_pad($id_doc[$i], 6, '0', STR_PAD_LEFT).'.'.$extension;  
					$fuente = $_FILES["miarchivo"]["tmp_name"][$indice]; 
					$carpeta = $rutadoc; //Declaramos el nombre de la carpeta que guardara los archivos
					if(in_array($extension, $formatos_permitidos) ) {
						$dir=opendir($carpeta);
						$target_path = $carpeta.'/'.$archivonombre; //indicamos la ruta de destino de los archivos				
						if(move_uploaded_file($fuente, $target_path)) {	
							//echo "Los archivos $archivonombre se han cargado de forma correcta.<br>";
						} else {	
							// echo "Se ha producido un error, por favor revise los archivos e intentelo de nuevo.<br>";
							$archivonombre = '';
						}
						closedir($dir); //Cerramos la conexion con la carpeta destino
					}else {	
						// echo 'Error formato no permitido !!';
						$archivonombre = '';
					}
				}	
				$indice++; //para siguiente comparacion
			}
		}			
		//guardamos en la bd
		if($id_doc_pro[$i]>0){
			// actualizamos
			$sql= "UPDATE documentos_propietarios SET id_tipo_documento = '". $id_tip[$i]."', ".
			"numero_hojas = '". $id_foj[$i]."', ".
			"observacion = '". $id_obs[$i]."', ".
			"noobs = '$noobs', ".
			"nro_documento = '$num', ".
			"fecha_documento = $fecha_aux, ".
			"fecha_vencimiento = $fecha_aux_vencimiento WHERE id_documento_propietario = ".$id_doc_pro[$i];
			ejecutar($sql);
			if($archivonombre != ''){
				$sql= "UPDATE documentos_propietarios SET archivo = '$archivonombre' WHERE id_documento_propietario = ".$id_doc_pro[$i];
				ejecutar($sql);
			}
		}else{
			// insertamos
			$sql= "INSERT INTO documentos_propietarios (id_carpeta, id_documento, id_tipo_documento, numero_hojas,
			observacion, noobs, nro_documento, fecha_documento, fecha_vencimiento, archivo)"; 
			$sql.= " VALUES ('$id_carpeta', $id_doc[$i], $id_tip[$i], '$id_foj[$i]', 
			'$id_obs[$i]', '$noobs', '$num', $fecha_aux, $fecha_aux_vencimiento, '$archivonombre') ";
			ejecutar($sql);		
		}
		
		
	}else{ //no tomar en cuenta el documento
		// se borra si existe		
		if($id_doc_pro[$i]>0){
			$sql= "DELETE FROM documentos_propietarios WHERE id_documento_propietario = ".$id_doc_pro[$i];
			ejecutar($sql);
		}
		if(count($id_files)>$indice && $id_doc[$i]==$id_files[$indice])
			$indice++; //para siguiente comparacion
	}
}
//die();
?>