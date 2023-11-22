<?php

	$idc = $_REQUEST['idc'];
	
	$smarty->assign('idcaso',$idc);

	$sql = "SELECT * FROM ncaso_cfinal WHERE nrocaso = '$idc' AND idfinal = 0 ";
	$query= consulta($sql);
	if($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){

	$smarty->assign('importePrestamo',$row["importeprestamo"]);
	$smarty->assign('monedaPrestamo',$row["monedaprestamo"]);
	$smarty->assign('CuentaDesembolso',$row["cuentadesembolso"]);
	$smarty->assign('destinoCredito',$row["destinocredito"]);
	$smarty->assign('numeroCuotas',$row["numerocuotas"]);
	$smarty->assign('Tasa1',$row["tasa1"]);
	$smarty->assign('Tasa2',$row["tasa2"]);
	$smarty->assign('Teac',$row["teac"]);
	$smarty->assign('Cuota',$row["cuota"]);
	$smarty->assign('cuotasTasaFija',$row["cuotastasafija"]);
	$smarty->assign('numeroLinea',$row["numerolinea"]);
	$smarty->assign('importeLinea',$row["importelinea"]);
	$smarty->assign('monedaLinea',$row["monedalinea"]);
	$smarty->assign('plazoMeses',$row["plazomeses"]);
	/* $smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]);
	$smarty->assign('xx',$row["xx"]); */
	}
	

          /*  ([nrocaso]
           ,[idfinal]
           ,[tipoCartera]
           ,[importePrestamo]
           ,[monedaPrestamo]
           ,[CuentaDesembolso]
           ,[destinoCredito]
           ,[numeroCuotas]
           ,[Tasa1]
           ,[Tasa2]
           ,[cuotasTasaFija]
           ,[cuentaDebito]
           ,[numeroLinea]
           ,[importeLinea]
           ,[monedaLinea]
           ,[plazoMeses]
           ,[plazoDias]
           ,[seguroDegravamen]
           ,[atributoObligatorio]
           ,[frecuenciaPagoK]
           ,[objetoCredito]
           ,[linearotativa]
           ,[tipogarantia]
           ,[tiposeguro]
           ,[codigogarantia]
           ,[id_banca]
           ,[escrituraLinea]
           ,[fechaescLinea]
           ,[notarioLinea]) */
     
	
	$smarty->display('./informe_legal/llena_variables.html');
	die();
?>
