<?php

$ruta = $_SERVER['SCRIPT_FILENAME'];
$ruta  = str_replace('\code\borre.php','',$ruta);
 
$vartemp = "../compilado/contrato.rtf";
$vartemp = $ruta."\compilado\contrato.rtf";
	if(file_exists($vartemp)){
		if(!unlink($vartemp))
			echo "No se pudo borrar archivo temporal. $vartemp ";
		else 
			echo 'archivo borrado';
	}else{
		echo "archivo no encontrado $vartemp";
	}
?>