<?php

$id = $_REQUEST['id'];

//sacamos nro de caso
$sql = "SELECT il.nrocaso, tb.cuenta as cuentabien, ca.operacion, ca.id_propietario, il.instancia 
FROM carpetas ca 
LEFT JOIN informes_legales il ON ca.id_informe_legal = il.id_informe_legal
LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = ca.id_tipo_carpeta
WHERE id_carpeta = '$id'";

$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
$nrocaso= trim($row["instancia"]);  
$cuentaBien= $row["cuentabien"];
$cuenta= $row["nrocaso"];  //cuenta bsol
$id_propietario= $row["id_propietario"];
$operacion= $row["operacion"];
$estadoLit='';
if($nrocaso==''){
	//recuperamos el nro de caso desde datosguardianII, usando nro de operacion y cta
	$sql = "SELECT op.instancia, op.descripcion_estado as estado 
	FROM datosGuardianII op WHERE op.operacion_bt = '$operacion' ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$nrocaso= $row["instancia"];
	$estadoLit= $row["estado"];
}

 if(!isset($_REQUEST['decontabilizando'])){
 
	//para el stock el estadoLit no cambia, hay q consultar al WS
	if($nrocaso!=''){
		//no sabemos el estado, vemos mediante el WS
		//ws: guardian_consulta
		require_once("ws_nrocaso_bsol.php");
	}else{
		//recuperamos nombre del cliente
		$sql = "SELECT nombres, ci 
		FROM propietarios WHERE id_propietario = '$id_propietario' ";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$nombreCli= $row["nombres"];
		$documento= $row["ci"];
	}
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
	
	if($nrocaso==''){
	//tampoco existe, no actualizaron aun, 
	//PONEMOS EN CERO PARA Q NO DE ERROR EN "SELECT instancia FROM OPERACIONESCAN WHERE instancia = '$nrocaso'"
	$nrocaso='0';
	}
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