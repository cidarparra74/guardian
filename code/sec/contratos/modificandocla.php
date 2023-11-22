<?php

$idcl= $_REQUEST['idcla'];
$idco= $_REQUEST['idcon'];

	$posicion= $_REQUEST['posicion'];
	$opcional= $_REQUEST['opcional'];
	$sintitulo= $_REQUEST['sintitulo'];
	$dependiente= $_REQUEST['dependiente'];
	$tipopersona= $_REQUEST['tipopersona'];
	
if($posicion<>'*'){
	$sql= "UPDATE rel_cc SET posicion='$posicion'
			WHERE idclausula='$idcl' AND idcontrato='$idco' ";
	//ejecutar($sql);
}

	$sql= "UPDATE rel_cc SET opcional='$opcional', sintitulo='$sintitulo', 
	dependiente='$dependiente' , tipopersona='$tipopersona' 
	WHERE idclausula='$idcl' AND idcontrato='$idco' ";
	ejecutar($sql);

?>