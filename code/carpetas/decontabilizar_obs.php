<?php

$id = $_REQUEST['id'];

//sacamos nro de caso
$sql = "SELECT tb.cuenta, ca.operacion, ca.cuenta as cacta, ca.nrocaso 
FROM carpetas ca 
LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = ca.id_tipo_carpeta
WHERE id_carpeta = '$id'";
//echo $sql; il
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
$nrocaso= $row["nrocaso"];
$cuentaBien= $row["cuenta"];
if($nrocaso==''){
	echo '!'; //die();
	//recuperamos el nro de caso desde datosguardianII, usando nro de operacion y cta
	$operacion= $row["operacion"];
	$cacta= $row["cacta"];
	$sql = "SELECT op.instancia FROM datosGuardianII op 
	WHERE operacion_bt = '$operacion' AND cuenta = '$cacta'";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$nrocaso= $row["instancia"];
}

 if(!isset($_REQUEST['decontabilizando'])){
 
	//ws guardian_consulta
	require_once("ws_nrocaso_bsol.php");
/* <Cuenta>612594</Cuenta> 
  <Nombre>GUTIERREZ TALAVERA MODESTO</Nombre> 
  <PaisDoc>1</PaisDoc> 
  <TipoDoc>1</TipoDoc> 
  <NumDoc>6652116PO</NumDoc> 
  <Producto>SOL DPF</Producto> 
  <DestinoCre>CAPITAL DE INVERSION ACT. PRINCIPAL</DestinoCre> 
  <Estado>Operación Cancelada</Estado> */
	$smarty->assign('paisDoc',$paisDoc);
	$smarty->assign('tipoDoc',$tipoDoc);
	$smarty->assign('documento',$documento);
	$smarty->assign('nombre',$nombreCli);
	$smarty->assign('estado',$estadoLit);	
	$smarty->assign('id',$id);
	$smarty->assign('nrocaso',$nrocaso);
	$smarty->assign('fechacan',date("d/m/Y"));
	/// revisar que no este en la tabla para recien insertar
	$sql = "SELECT instancia FROM OPERACIONESCAN WHERE instancia = '$nrocaso'";

	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	if($row["instancia"]==''){
		if(strpos($estadoLit,'Cancelada')>0)
			$smarty->assign('can','s');
		else
			$smarty->assign('can','n');
	}else{
		$smarty->assign('can','n');
		$estadoLit .= ' (ya se encuentra en operaciones canceladas)';
	}
	$smarty->assign('estado',$estadoLit);
	$smarty->display('carpetas/decontabilizar.html');
	
	die();
 }
//ws guardian_desembolso
require_once("ws_desembolso_bsol.php");
if($cuenta!='' && $operacion!='0'){
/*  <Cuenta>612594</Cuenta> 
  <Agencia>103</Agencia> 
  <Operacion>457238</Operacion> 
  <Moneda>101</Moneda> 
  <Modulo>SOL DPF</Modulo> 
  <Papel>0</Papel> 
  <FechaAlta>20100825</FechaAlta> 
  <Monto>22000</Monto> 
  <Producto>SOL DPF</Producto> 
  <DestinoCre>CAPITAL DE INVERSION ACT. PRINCIPAL</DestinoCre> 
  <FechaVenc>20150901</FechaVenc> 
  <Asesor>VILLCA ZAMBRANA ALBERT REYNALD</Asesor> */
  //TipoOperacion
	$paisDoc=$_REQUEST['paisDoc'];
	$tipoDoc=$_REQUEST['tipoDoc'];
	$documento=$_REQUEST['documento'];
	$fechacan=$_REQUEST['fechacan'];
	$fecha_actual = "CONVERT(DATETIME,'$fechacan',103)";
	
		//no existe
		$sql="INSERT INTO OPERACIONESCAN (
		Instancia, Cuenta_Inst, PaisDoc, TipoDoc, NumeroDoc, Sucursal, Moneda,
		Papel, Cuenta, Operacion, SubOperacion,  Importe, Estado, FechaCan, codemp, modulo,TipoOperacion)
		VALUES ('$nrocaso', '',$paisDoc, $tipoDoc, '$documento', '$agencia', '$moneda', 
		'$papel', '$cuenta', '$operacion', 0, '$monto', '99', $fecha_actual, '1','0','0') ";

		ejecutar($sql);
	
}

?>