<?php

$id= $_REQUEST['id'];
$sql= "DELETE FROM localizacion WHERE localizacion='$id' ";
ejecutar($sql);

?>