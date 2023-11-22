<?php

$depto= $_REQUEST['depto'];
$sql= "INSERT INTO expedido ( descripcion ) 
VALUES( '$depto') ";

ejecutar($sql);

?>