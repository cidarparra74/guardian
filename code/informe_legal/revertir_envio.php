<?php


$id= $_REQUEST["id"];
$task = $_REQUEST['revertir_informe'];
//echo $task;
if($task == 'cat'){
	// movemos a Enviar a catastro
	$sql= "UPDATE informes_legales SET estado='arc' WHERE id_informe_legal='$id' ";
}else{
	// movemos a autorizar 
	$sql= "UPDATE informes_legales SET estado='sol' WHERE id_informe_legal='$id' ";
}
//echo $sql;
ejecutar($sql);

?>