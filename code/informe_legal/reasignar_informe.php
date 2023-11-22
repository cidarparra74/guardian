<?php

$id= $_REQUEST["id"];
$usr_nuevo= $_REQUEST["nuevo_usr"];

$sql= "UPDATE informes_legales SET usr_acep='$usr_nuevo' WHERE id_informe_legal='$id' ";
//echo "$sql";
ejecutar($sql);


?>