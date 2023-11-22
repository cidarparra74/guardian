<?php

require_once('../lib/conexionMNU.php');

//nume&nota&fech&otor&regi&foja&tipo&idi&idp

$nomb= $_POST['nomb'];
$ci= $_POST['ci'];
$tipo= $_POST['tipo'];
//$porc= $_POST['porc'];
$facu= $_POST['facu'];
$rest= $_POST['rest'];
$esta= $_POST['esta'];

$idp= $_POST['idp'];
$ida='0';
//if($ida=='0'){
	////es nuevo apoderado

	$sql= "INSERT INTO apoderados (id_poder, apoderado, ci, tipo, 
			porcentaje, facultades, restricciones, vigente) 
		VALUES('$idp', '$nomb', '$ci', '$tipo', 
		'', '$facu', '$rest', '$esta') ";
		//$porc
	ejecutar_con_filter($sql);
	//vemos el id generado para este poder
	$sql="SELECT MAX(id_apoderado) as ida FROM apoderados WHERE id_poder = '$idp'";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$ida= $resultado["ida"];
	
echo "ok|$ida";

?>