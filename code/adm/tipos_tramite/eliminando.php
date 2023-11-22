<?php
//print_r($_REQUEST);
//die();
$id= $_REQUEST['id'];

$sql= "DELETE FROM tipos_tramite WHERE id_tipo_tramite='$id' ";
$link->query($sql);

?>