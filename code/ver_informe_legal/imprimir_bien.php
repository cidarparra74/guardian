<?php

//$id= $_REQUEST['id'];
//$bien = substr($tipo_bien,0,1);
if($bien == "3"){
	//vehiculos
	$reporte = "informe_legal_vehiculos.rpt";
	$tipo_bien = 'Vehiculo';
}elseif($bien == "1"){
	//inmuebles
	$reporte = "informe_legal_inmuebles.rpt";
	$tipo_bien = 'Inmueble';
}elseif($bien == "2"){
	//maquinaria
	$reporte = "informe_legal_maquinaria.rpt";
	$tipo_bien = 'Maquinaria';

}

$smarty->assign('id',$id);
$smarty->assign('tipo_bien',$tipo_bien);
$smarty->assign('reporte',$reporte);

$smarty->display('ver_informe_legal/imprimir_bien.html');
die();
?>