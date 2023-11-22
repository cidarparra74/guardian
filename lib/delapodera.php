<?php
//ojo ver si reconoce sesion
require_once('../lib/conexionMNU.php');
$ida= $_GET['ida'];

	$ida= substr(str_replace("'","",$ida),0,10);
	$sql= "DELETE FROM apoderados WHERE id_apoderado='$ida'";
	ejecutar($sql);
echo "ok";

?>