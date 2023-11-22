<?php

$id= $_REQUEST['id'];

$sql= "UPDATE contrato_final SET eliminado = 1 WHERE idfinal='$id' ";
ejecutar($sql);
//este contrato podria tener nro de caso. lo eliminamos tambien
// le ponemos entre asteriscos para que no sea encontrado
unset($link);
		require('../lib/conexionMNU.php');

// asi estaba siempre
$sql= "UPDATE ncaso_cfinal SET nrocaso = '*'+rtrim(nrocaso)+'*' 
WHERE idfinal='$id' AND substring(nrocaso,1,1)<>'*'";

//modificado para que salga en automaticos  ---- revisar que otros casos requiere que se elimine el nro de caso
$sql= "UPDATE ncaso_cfinal SET idfinal = 0
WHERE idfinal='$id' AND substring(nrocaso,1,1)<>'*'";
ejecutar($sql);

unset($link);
		require('../lib/conexionSEC.php');
?>