<?php

$nrocaso= $_REQUEST["del"];

	$sql= "UPDATE ncaso_cfinal SET idfinal=-1 WHERE nrocaso='$nrocaso' ";
	ejecutar($sql);
	
?>