<?php

$bien = substr($tipo_bien,0,1);
if($tipo_bien == "3"){
	//vehiculos
	$smarty->assign('tipo_bien','Vehiculos');
	$reporte = "informe_legal_vehiculos.rpt";
}elseif($tipo_bien == "1"){
	//inmuebles
	$smarty->assign('tipo_bien','Inmuebles');
	$reporte = "informe_legal_inmuebles.rpt";
}elseif($tipo_bien == "2"){
	//maquinaria
	$smarty->assign('tipo_bien','Maquinaria');
	$reporte = "informe_legal_maquinaria.rpt";

}

$smarty->assign('id',$id);

$smarty->assign('reporte',$reporte);

$smarty->display('informe_legal/imprimir_bien.html');
die();
?>