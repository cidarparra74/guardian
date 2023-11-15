<?php
require_once('lib/conexionMNU.php');

$nuevo_password = crypt("123456","vic");
		$sql= "UPDATE usuarios SET password='$nuevo_password' WHERE login='CYANEZ' ";
		ejecutar($sql);
echo "Se ha colocado el password de CYANEZ en el valor 123456";
?>