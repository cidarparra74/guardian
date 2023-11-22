<?php

$id= $_REQUEST['id'];
if(isset($_REQUEST['optsel'])){
	$optsel = explode('_',$_REQUEST['optsel']);
	$operacion = $optsel[0];
	$nrocaso = $optsel[1];
	$suboperacion = $optsel[2];
}else{
	$operacion = $_REQUEST['operacionws'];
	$nrocaso = $_REQUEST['nrocasows'];
	$suboperacion = $_REQUEST['suboperacionws'];
}
$sql= "UPDATE carpetas SET operacion='$operacion', nrocaso = '$nrocaso' WHERE id_carpeta='$id' ";
ejecutar($sql);
//para bsol:
$sql= "UPDATE carpetas SET suboperacion='$suboperacion' WHERE id_carpeta='$id' ";
ejecutar($sql);

$sql= "SELECT ca.id_informe_legal 
	FROM carpetas ca 
	WHERE id_carpeta='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$idil=$resultado["id_informe_legal"];
	if($idil != '' and $idil != '0'){
		$sql= "UPDATE informes_legales SET instancia = '$nrocaso' WHERE id_informe_legal='$idil' ";
		ejecutar($sql);
	}
	

?>
