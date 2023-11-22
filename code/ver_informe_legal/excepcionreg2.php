<?php
chdir('..');
require_once('../lib/fechas.php');
//$accion = $_REQUEST['excepcion_boton'];
require_once('../lib/setup.php');
	
	$smarty = new bd;
	
$id = $_REQUEST["id"];
//$smarty->assign('id',$id);


//excepcion a informe legal
	if(isset($_REQUEST['excepcion_boton'])){
		//alter table informes_legales add exe_aprobar text default ''
		if($_REQUEST['aprobar']){
			$aprobar = $_REQUEST['aprobar'];
			$sql = "UPDATE informes_legales SET exe_aprobar='$aprobar' WHERE id_informe_legal = $id " ;
			ejecutar($sql);
			//echo $sql;
		}
		
		die("Se ha guardado existosamente la aprobaci&oacute;n. Puede cerrar esta ventana.");
	}	
	
	//imprimir observaciones de la excepcion 
	if(isset($_REQUEST['imprimir_exce'])){
		include("./ver_informe_legal/excepcion_imprime.php");
	}


?>