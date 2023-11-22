<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	


	//Guardamos los cambios
	//  MOSTRAR
	if(isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
	}else{die();}
	
	//Borramos todas las asignaciones previas
	$sql="DELETE FROM tipobien_persona WHERE id_persona = $id ";
	ejecutar($sql);
	//echo $sql;
	$opciones = $_REQUEST['opcion'];
	//print_r($opciones);
	if(count($opciones)>0){
		//Hay al menos una oficina marcada para este perito
		
		foreach($opciones as $valor) {
			
		   $sql="INSERT INTO tipobien_persona (id_tipo_bien, id_persona) VALUES ($valor, $id)";
		   ejecutar($sql);
		   //echo $sql;
		} 
		
	}

	

?>