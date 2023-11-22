<?php

$id= $_REQUEST['id'];
$sql= "DELETE FROM expedido WHERE codigo='$id' ";
ejecutar($sql);

?>