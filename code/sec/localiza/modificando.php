<?php

$id= $_REQUEST['id'];
$depto= $_REQUEST['depto'];
$loca= $_REQUEST['loca'];

$sql= "UPDATE localizacion SET departamento='$depto', localizacion='$loca'
 WHERE localizacion='$id' ";
ejecutar($sql);

?>