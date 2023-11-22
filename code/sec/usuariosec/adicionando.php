<?php

$depto= $_REQUEST['depto'];
$loca= $_REQUEST['loca'];
$sql= "INSERT INTO localizacion (departamento, localizacion ) 
VALUES( '$depto', '$loca') ";

ejecutar($sql);

?>