<?php
require_once('../lib/fechas.php');
//$accion = $_REQUEST['excepcion_boton'];

$id = $_REQUEST["id"];
$smarty->assign('id',$id);

	// hace la solicitud
	$fecha_actual= date("Y-m-d H:i:s");
	$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

	$id_documento = $_REQUEST['id_documento'];
	$obs = $_REQUEST['obs'];
	$clase = $_REQUEST['clase'];
	$tipo = $_REQUEST['tipo'];
	$resp = $_REQUEST['resp'];
	$limite = $_REQUEST['limite'];
	
	$justifi = $_REQUEST['justifi'];

	
//alter table informes_legales add exe_justifica text default ''
	$sql = "UPDATE informes_legales SET exe_justifica='$justifi' WHERE id_informe_legal = $id " ;
	ejecutar($sql);
	
	//alter table informes_legales add exe_aprobar text default ''
	if(isset($_REQUEST['aprobar'])){
		$aprobar = $_REQUEST['aprobar'];
		$sql = "UPDATE informes_legales SET exe_aprobar='$aprobar' WHERE id_informe_legal = $id " ;
		ejecutar($sql);
	}
		
	//borramos el q haya
	$sql = "DELETE FROM excepciones WHERE id_informe_legal = $id " ;
	ejecutar($sql);
	//insertamos la solicitud

	foreach($id_documento as $key=>$valor){
		$laobs = $obs[$key] ;
		$laclase= $clase[$key] ;
		$latipo= $tipo[$key] ;
		$laresp= $resp[$key] ;
		$lalimite= $limite[$key] ;
		
		$fecha_aux = dateYMD($lalimite);
		if($fecha_aux!='--' and $lalimite!='')
			$fecha_aux = "CONVERT(DATETIME,'$fecha_aux',102)";
		else 
			$fecha_aux = "null";
		
		if($latipo == 'P')
			$laresp = 0;
		
		$sql = "INSERT INTO excepciones 
		(id_informe_legal, id_documento, obs, exce_tipo, 
		exce_resp, exce_revisa, exce_limite, clase) VALUES 
		($id, $valor, '$laobs', '$latipo', 
		'$laresp', $fecha_actual, $fecha_aux, '$laclase')";
		ejecutar($sql);
	}

?>