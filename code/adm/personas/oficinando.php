<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	


	//Guardamos los cambios
	//  MOSTRAR
	if(isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
	}else{die();}
		//Borramos todas las asignaciones previas
		$sql="DELETE FROM oficina_persona WHERE id_responsable = $id ";
		ejecutar($sql);
		//echo $sql;
		$opciones = $_REQUEST['opcion'];
	/*	echo "<pre>";
	print_r($opciones);
	echo "</pre>";
	die();  */
		if(count($opciones)>0){
			//Hay al menos una oficina marcada para este perito
			
			foreach($opciones as $valor) {
				
			   $sql="INSERT INTO oficina_persona (id_oficina, id_responsable) VALUES ($valor, $id)";
			   ejecutar($sql);
			   
			} 
			
		}

	

?>