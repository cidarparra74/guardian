<?php
//print_r($_REQUEST);
//die();
$id= $_REQUEST['id'];
$descripcion= $_REQUEST['descripcion'];

$sql= "UPDATE tipos_tramite SET  descripcion='$descripcion' WHERE id_tipo_tramite='$id' ";
//echo "$sql";
$link->query($sql);

?>