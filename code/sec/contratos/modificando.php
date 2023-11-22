<?php

$id= $_REQUEST['id'];
$que= $_REQUEST['que'];
if($que=='dat'){
	$titulo= $_REQUEST['titulo'];
	$habilitado= $_REQUEST['habilitado'];
	$codtipo= $_REQUEST['codtipo'];
	$materia= $_REQUEST['materia'];
	$entidad= $_REQUEST['entidad'];
	$con_firma= $_REQUEST['con_firma'];
	$con_firma_abogado= $_REQUEST['con_firma_abogado'];
	$numerar_clausulas= $_REQUEST['numerar_clausulas'];
	$tipopersona= $_REQUEST['tipopersona'];
	

	$sql= "UPDATE contrato SET titulo='$titulo', habilitado='$habilitado', codtipo='$codtipo', codmateria='$materia', 
				codentidad='$entidad', con_firma='$con_firma', con_firma_abogado='$con_firma_abogado'
				, numerar_clausulas='$numerar_clausulas', tipopersona='$tipopersona'
			WHERE idcontrato='$id' ";
	ejecutar($sql);
}else{
	$contenido= $_REQUEST['contenido'];
	//reemplazamos comilla simple por dos comillas simples para no generar error en sql INSERT
	$contenido = str_replace("'","''",$contenido);
	//reemplazamos caracteres extraños
	$contenido = str_replace("&#13;","",$contenido);
	$sql= "UPDATE contrato SET contenido='$contenido' WHERE idclausula='$id' ";
	ejecutar($sql);
}
?>