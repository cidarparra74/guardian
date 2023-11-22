<?php

$id= $_REQUEST['id'];
$depto= $_REQUEST['depto'];

$sql= "UPDATE expedido SET descripcion='$depto'
 WHERE codigo='$id' ";
ejecutar($sql);

?>