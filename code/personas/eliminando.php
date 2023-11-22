<?php

$id= $_REQUEST['id'];
// no podemos eliminar si tiene registros relacionados!
$sql= "DELETE FROM propietarios WHERE id_propietario='$id' ";
ejecutar($sql);

?>