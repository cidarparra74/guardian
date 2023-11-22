<?php

$id = $_REQUEST['id'];
$quehacer = $_REQUEST['eliminar_boton_x'];
$motivo = $_REQUEST['motivo'];
if( $quehacer == 'del')
	$sql= "DELETE FROM propietarios WHERE id_propietario='$id' ";
else
	$sql= "UPDATE propietarios SET motivoeli='$motivo' WHERE id_propietario='$id' ";

ejecutar($sql);


?>