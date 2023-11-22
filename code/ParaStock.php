<?php
// 17/04/2012: Se habilito para incluir canceladas
session_start();
require_once("../lib/setup.php");
	//18/07/2015
	require_once('../lib/verificar.php');
require_once("../lib/class.inputfilter.php");
$ifilter = new InputFilter();
$smarty = new bd;
$errores = '';
if(isset($_REQUEST['cue']) ){
		//xss prevent
	$cue = $ifilter->process($_REQUEST['cue']);
	if(is_numeric($cue)){ 
	
	$suc = $ifilter->process(trim($_REQUEST['suc']));

	$sql="SELECT TOP 102 DatosGuardianII.cuenta, DatosGuardianII.operacion_bt, tipos_bien.cuenta as cbien,
	ca.id_carpeta
	FROM carpetas ca
	INNER JOIN DatosGuardianII 
	ON CONVERT(VARCHAR(16),operacion_bt)=ca.operacion 
	INNER JOIN propietarios 
	ON ca.id_propietario = propietarios.id_propietario 
	AND propietarios.mis= DatosGuardianII.ci 
	INNER JOIN tipos_bien 
	ON tipos_bien.id_tipo_bien = ca.id_tipo_carpeta 
	WHERE tipos_bien.cuenta <>'' AND (ca.cuenta IS NULL or ca.cuenta = '') 
	AND DatosGuardianII.sucursal='$suc'
	AND tipos_bien.cuenta = '$cue' ORDER BY ca.id_carpeta ";
	
	$query = consulta($sql);
	$xmlEntrada = '<registro asiento="ALTA_VALOR_GUARDIAN_MIGRACION">
	<dato cta="0" oper="0" importe="0" moneda="0" idTipo="0" /> 
';
	$cnt =0;
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$cuenta = $row['cuenta'];
		$operacion = $row['operacion_bt'];
		$cuentaBien = $row['cbien'];
		
		$xmlEntrada .= 
		'<dato cta="'.$cuenta.'" oper="'.$operacion.'" importe="100" moneda="0" idTipo="'.$cuentaBien.'" /> 
';
		$cnt++;
		$id_carpeta = $row['id_carpeta'];
		if($cnt>=100){
			break;
		}
	}
	
	
	$xmlEntrada .='</registro>';
	$idAsiento = 'ALTA_VALOR_GUARDIAN_MIGRACION';
	require('ws_execute_bsol.php');
	//
	if($mensaje=='OK'){
			$sql="UPDATE carpetas SET carpetas.cuenta=dg.cuenta, carpetas.nrocaso = dg.instancia
			FROM carpetas ca
			INNER JOIN DatosGuardianII dg
			ON CONVERT(VARCHAR(16),operacion_bt)=ca.operacion
			INNER JOIN propietarios 
			ON ca.id_propietario = propietarios.id_propietario 
			AND propietarios.mis= dg.ci 
			INNER JOIN tipos_bien 
			ON tipos_bien.id_tipo_bien = ca.id_tipo_carpeta 
			WHERE tipos_bien.cuenta <>'' AND (ca.cuenta IS NULL or ca.cuenta = '')
			AND dg.sucursal='$suc'
			AND tipos_bien.cuenta = '$cue' 
			AND id_carpeta <= $id_carpeta";
			//AND RIGHT(dg.cuenta,1)=$i AND dg.estado<>'99'
			ejecutar($sql);
	}
	// esta parte muestra en pantalla el XML q se envia al WS
	
		echo "<pre>";
		echo htmlentities($xmlEntrada);
		echo "</pre>";
	//---------------------
		

	$errores.=$cnt.' casos = '.$mensaje;
	$smarty->assign('errores',$errores);
}

}

//regionales
$sql = "SELECT id_almacen, nombre FROM almacen";
$query = consulta($sql);
$recinto = array();
while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$recinto[] = array('id_almacen' => $row['id_almacen'],
					'nombre' => $row['nombre']);
}
$smarty->assign('recinto',$recinto);
$main = array();
if(isset($_REQUEST['recintoSel']))
	$recintoSel = $_REQUEST['recintoSel'];
else 
	$recintoSel = '-';

if($recintoSel!='-'){
	$sqlMain="SELECT tipos_bien.cuenta , DatosGuardianII.sucursal, COUNT(*) AS casos
	FROM carpetas ca
	INNER JOIN DatosGuardianII 
	ON CONVERT(VARCHAR(16),operacion_bt)=ca.operacion 
	INNER JOIN propietarios 
	ON ca.id_propietario = propietarios.id_propietario AND propietarios.mis= DatosGuardianII.ci 
	INNER JOIN oficinas 
	ON oficinas.id_oficina = ca.id_oficina AND oficinas.id_almacen = $recintoSel 
	INNER JOIN tipos_bien 
	ON tipos_bien.id_tipo_bien = ca.id_tipo_carpeta 
	WHERE tipos_bien.cuenta <>'' AND (ca.cuenta IS NULL or ca.cuenta = '') 
	GROUP BY tipos_bien.cuenta , DatosGuardianII.sucursal";
	$queryMain = consulta($sqlMain);
	while($rowMain = $queryMain->fetchRow(DB_FETCHMODE_ASSOC)){
		$main[] = array('cuenta' => $rowMain['cuenta'],
						'sucursal' => $rowMain['sucursal'],
						'casos' => $rowMain['casos']);
	}
}
$smarty->assign('recintoSel',$recintoSel);
$smarty->assign('main',$main);
$smarty->display('../templates/ParaStock.html');
die();
?>