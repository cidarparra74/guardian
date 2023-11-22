<?php

	//print_r($_POST);
	//die();
/// leemos los campos del SEC
require_once('../lib/conexionSEC.php');
$sql = "SELECT idtexto, descripcion FROM var_texto ORDER BY idtexto";
$query = consulta($sql);
$variables = array();
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$variables[]=array('id'=>$row["idtexto"], 'desc'=>$row["descripcion"]);
}
	
require_once('../lib/conexionMNU.php');
/// cargamos los campos del guardian
$tablag = array();

$tablag[]= "informes_legales.cliente";
$tablag[]= "informes_legales.ci_cliente";
$tablag[]= "informes_legales.otras_observaciones";
$tablag[]= "informes_legales.garantia_contrato";
$tablag[]= "informes_legales.nota";
$tablag[]= "informes_legales.conclusiones";
$tablag[]= "informes_legales.tradicion";
$tablag[]= "------------------------";
$tablag[]= "ncaso_cfinal.tipoCartera";
$tablag[]= "ncaso_cfinal.importePrestamo.monedaPrestamo";
$tablag[]= "ncaso_cfinal.CuentaDesembolso";
$tablag[]= "ncaso_cfinal.destinoCredito";
$tablag[]= "ncaso_cfinal.numeroCuotas";
$tablag[]= "ncaso_cfinal.Tasa1";
$tablag[]= "ncaso_cfinal.Tasa2";
$tablag[]= "ncaso_cfinal.cuotasTasaFija";
$tablag[]= "ncaso_cfinal.cuentaDebito";
$tablag[]= "ncaso_cfinal.numeroLinea";
$tablag[]= "ncaso_cfinal.importeLinea.monedaLinea";
$tablag[]= "ncaso_cfinal.plazoMeses";
$tablag[]= "ncaso_cfinal.plazoDias";
$tablag[]= "ncaso_cfinal.seguroDegravamen";
$tablag[]= "ncaso_cfinal.atributoObligatorio";
$tablag[]= "ncaso_cfinal.frecuenciaPagoK";
$tablag[]= "ncaso_cfinal.objetoCredito";
$tablag[]= "ncaso_cfinal.linearotativa";
$tablag[]= "ncaso_cfinal.tipogarantia";
$tablag[]= "ncaso_cfinal.tiposeguro";
$tablag[]= "ncaso_cfinal.codigogarantia";
$tablag[]= "ncaso_cfinal.periodogracia";
$tablag[]= "ncaso_cfinal.teac";
$tablag[]= "ncaso_cfinal.cuota";
$tablag[]= "ncaso_cfinal.fechaFijaPlazo";
$tablag[]= "ncaso_cfinal.producto";
$tablag[]= "ncaso_cfinal.ubicaciongarantia";

	$smarty->assign('variables',$variables);
	$smarty->assign('tablag',$tablag);
	$smarty->display('adm/variablesec/adicionar.html');
	die();
?>