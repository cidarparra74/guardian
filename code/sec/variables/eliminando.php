<?php

$id= $_REQUEST['id'];
$sql= "DELETE FROM oficinas WHERE id_oficina='$id' ";
ejecutar($sql);

?>