<?php
// cargarmos funciones propias
	require_once('../lib/conexionMNU.php');
	
	if(isset($_GET['ci'])){
		$ci=$_GET["ci"];
		$sql = "SELECT nombres FROM propietarios WHERE ci = '$ci'";
		$query = consulta($sql);
		$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
		if($row['nombres']!=''){
			//existe, leemos datos 
			$nombres = trim($row['nombres']);
			
			//$nombres = utf8_encode($row['nombres']);
			echo "1|".$nombres;
		}else{
			echo "0|$ci";
		}
	}
		
?>