<?php

$idl= $_REQUEST['idl'];

$sql= "DELETE FROM lineas WHERE id_linea ='$idl' ";
ejecutar($sql);

?>