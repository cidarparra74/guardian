<?php

$id= $_REQUEST['id'];
$nombres= $_REQUEST['nombres'];
//$mis= $_REQUEST['mis'];
$ci= $_REQUEST['ci'];
$telefonos= $_REQUEST['telefonos'];
$direccion= $_REQUEST['direccion'];
$estado_civil= $_REQUEST['estado_civil'];
$nit= $_REQUEST['nit'];
	$direccion = str_replace("'","''",$direccion);


$sql= "UPDATE propietarios SET ci='$ci', nombres='$nombres', mis='$ci', direccion='$direccion', 
telefonos='$telefonos', estado_civil='$estado_civil', nit='$nit' WHERE id_propietario='$id' ";
ejecutar($sql);

?>