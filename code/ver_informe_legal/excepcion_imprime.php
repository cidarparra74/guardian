<?php

if(!isset($_REQUEST["gorep"])){

	$smarty->assign('id',$_REQUEST['id']);
	//$smarty->assign('carpeta_entrar',$carpeta_entrar);
	$smarty->display('ver_informe_legal/excepcion_imprime.html');
	die();
}
//

require_once("../lib/conexionMNU.php");
$id = $_REQUEST['query1'];

//recuperamos datos basicos del I.L.
$sql =  "SELECT ile.cliente, ile.ci_cliente, tii.identificacion, tbi.tipo_bien, ile.exe_aprobar,".
			"ile.otras_observaciones, ile.conclusiones, ile.motivo, ile.id_us_comun, ile.exe_justifica  ".
			"FROM informes_legales ile ".
			"LEFT JOIN tipos_identificacion tii ON id_tipo = id_tipo_identificacion ".
			"LEFT JOIN tipos_bien tbi ON tbi.id_tipo_bien = ile.id_tipo_bien ".
			"WHERE id_informe_legal = $id ";

	$query = consulta($sql);

	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$otras_observaciones = $resultado["otras_observaciones"];
	$id_us_comun = $resultado["id_us_comun"];
	
	$smarty->assign('cliente',$resultado["cliente"]);
	$smarty->assign('ci_cliente',$resultado["ci_cliente"]);
	$smarty->assign('identificacion',$resultado["identificacion"]);
	$smarty->assign('tipo_bien',$resultado["tipo_bien"]);
	$smarty->assign('otras_observaciones',$otras_observaciones);
	$smarty->assign('conclusiones',$resultado["conclusiones"]);
	$smarty->assign('motivo',$resultado["motivo"]);
	$smarty->assign('exe_justifica',$resultado["exe_justifica"]);
	$smarty->assign('exe_aprobar',$resultado["exe_aprobar"]);

// verificamos si ya tiene registro en excepciones
$excepciones = array();
$sql= "SELECT ex.*, CONVERT(VARCHAR,ex.exce_limite,103) as limite, do.documento, us.nombres 
FROM excepciones ex 
LEFT JOIN documentos do ON do.id_documento = ex.id_documento
LEFT JOIN usuarios us ON us.id_usuario = ex.exce_resp
WHERE id_informe_legal = '$id' 
ORDER BY clase";

$query = consulta($sql);
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$excepciones[] = array('id_documento'=>$row["id_documento"],
							'documento'=>$row["documento"],
							'obs'=>$row["obs"],
							'exce_tipo'=>$row["exce_tipo"],
							'exce_resp'=>$row["nombres"],
							'exce_limite'=>$row["limite"],
							'clase'=>$row["clase"]);

}

	
	//vemos otras observaciones
/*
	if($resultado["otras_observaciones"]!=''){
		$excepciones[]=array('fecha' => '' ,
							'fojas' => '0' ,
							'fecha_vencimiento' => '' ,
							'tipo' => '',
							'id_documento'=>0,
							'documento'=>'',
							'obs'=>$otras_observaciones,
							'exce_tipo'=>'-',
							'exce_resp'=>$id_us_comun,
							'exce_limite'=>'',
							'clase'=>'C');
	}
*/
$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('logo',$resultado['logo01']);

	$smarty->assign('id',$id);
	$smarty->assign('excepciones',$excepciones);
	
	$smarty->display('ver_informe_legal/excepcion_imprimiendo.html');
	die();

?>