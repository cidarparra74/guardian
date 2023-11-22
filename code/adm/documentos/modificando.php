<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id					= $_REQUEST['id'];
$documento			= $_REQUEST['documento'];
$grupo				= '0'; //$_REQUEST['grupo'];
$descripcion		= $_REQUEST['descripcion'];
$meses_vencimiento	= $_REQUEST['meses_vencimiento'];
$post_desembolso	= $_REQUEST['post_desembolso'];
$requerido			= $_REQUEST['requerido'];
$seguro				= $_REQUEST['seguro'];
$vencimiento 		= $_REQUEST['vencimiento'];
$tiene_fecha 		= $_REQUEST['tiene_fecha'] ;
$con_numero 		= $_REQUEST['con_numero'] ;
$tiene_coment 		= $_REQUEST['tiene_coment'] ;

$max=1500000; //(1.5Mb)
/*
Ahora ordenamos donde se almacenará la imagen, hemos decidido que se cree un nuevo directorio dentro de la carpeta que hemos creado en el root de nuestro hosting para contener todas las subidas. Con la función mkdir creamos el directorio el cual lo nombramos con la fecha de subida del archivo y el nombre de la imagen. 
*/
//$nombreclean=htmlspecialchars($documento); 
//(htmlspecialchars, esteriliza el texto del campo "nombre" eliminando los caracteres que pudieran ejecutar algún script malicioso en nuestro servidor).
/*
$hh=date("H")+8;
$hora = date("d-m-Y $hh:i:s"); 
$nuevodirectorio="$DOCUMENT_ROOT/../imagenes/$hora.$nombreclean";
mkdir ($nuevodirectorio);
*/
$uploaddir = "../imagenes/";

//A continuación tratamos el archivo de imagen, aplicando unas funciones en particular como medida de seguridad.

$filesize = $_FILES['imagen']['size'];
$filename = trim($_FILES['imagen']['name']); //(trim elimina los posibles espacios al final y al principio del nombre del archivo)
//$filename = substr($filename, 20); //(con substr le decimos que coja solamente los últimos 20 caracteres por si el nombre fuera muy largo) 
$filename = preg_replace('/ /', '', $filename); //(con esta función eliminamos posibles espacios entre los caracteres del nombre) 

/*
Ahora creamos las condiciones que debe cumplir el archivo antes de ser almacenado en el servidor. Restringimos a .jpg ó .gif (tanto en mayusculas como en minúsculas) y finalmente cambiamos el archivo de la carpeta temporal a la final elegida. 
*/
$imagen = '';
if($filesize < $max){
	if($filesize > 0){
		if((preg_match("/.jpg/", $filename)) || (preg_match("/png/", $filename)) || (preg_match("/.JPG/", $filename))|| (preg_match("/PNG/", $filename))){
			$uploadfile = $uploaddir . $filename;
			//$uploadfile = $uploaddir . $nombreclean;
			if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadfile)) {
				$msg = "Archivo subido correctamente";
				$imagen = $filename;
			} else {
				$msg = "Error de conexi&oacute;n con el servidor.";
			}
		} else {
			$msg = "Sólo se permiten imágenes en formato JPG. y PNG., no se ha podido adjuntar.";
		}
	}else{
		$msg = ""; //Campo vac&iacute;o, no ha seleccionado ninguna imagen
	}
}else{
	$msg = "La imagen que ha intentado adjuntar es mayor de 1.5 Mb, si desea cambie el tamaño del archivo y vuelva a intentarlo.";
}

echo $msg;

$accion				= $_REQUEST['accion'] ;
if($accion=='M'){
	// actualizando
	if($imagen==''){
	$sql= "UPDATE documentos SET tiene_coment='$tiene_coment', documento='$documento', 
	descripcion='$descripcion', vencimiento=$vencimiento, meses_vencimiento='$meses_vencimiento', 
	tiene_fecha=$tiene_fecha, post_desembolso=$post_desembolso, requerido=$requerido, 
	seguro=$seguro, con_numero=$con_numero, imagen=''
	WHERE id_documento='$id' ";
	}else{
	$sql= "UPDATE documentos SET tiene_coment='$tiene_coment', documento='$documento', 
	descripcion='$descripcion', vencimiento=$vencimiento, meses_vencimiento='$meses_vencimiento', 
	tiene_fecha=$tiene_fecha, post_desembolso=$post_desembolso, requerido=$requerido, 
	seguro=$seguro, con_numero=$con_numero, imagen='$imagen'
	WHERE id_documento='$id' ";
	}
}else{
	if($accion=='A'){
		// adicionando

		$sql= "INSERT INTO documentos(id_grupo_documento, tiene_coment, documento, descripcion, 
		vencimiento, meses_vencimiento, tiene_fecha, post_desembolso, requerido, seguro, con_numero, imagen) 
		VALUES(0, '$tiene_coment', '$documento', '$descripcion', $vencimiento, '$meses_vencimiento', 
		$tiene_fecha, $post_desembolso, $requerido, $seguro, $con_numero, '$imagen') ";
	}else{
		if($accion=='E'){
			// eliminando
			$sql= "DELETE FROM documentos WHERE id_documento='$id' ";
		}
	}
}
ejecutar($sql);
?>