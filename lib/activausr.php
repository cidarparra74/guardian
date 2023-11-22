<?php
//ojo ver si reconoce sesion
	require_once('../lib/conexionMNU.php');
	if(isset($_GET['idus']) and isset($_GET['random']) and isset($_GET['idr'])){
		$idus= $_GET['idus'];
		$random= $_GET['random'];
		$mcheck= $_GET['idr'];
		if($mcheck == ($random+29054)){
			$idus= substr(str_replace("'","",$idus),0,10);
			$sql= "UPDATE usuarios SET activo = 'S' WHERE id_usuario='$idus' ";
			ejecutar($sql);
			echo "s";
			die();
		} 
	}
	echo "n";
?>
