<?php

require_once('../lib/lib/nusoap.php');

$sql = "SELECT TOP 1 ws_url1 FROM opciones";
$queryws = consulta($sql);
$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
if($rowws['ws_url1']==''){
	echo 'No se pudo completar la operación, URL no definida ';
	echo '<br>';
	echo 'Revise al configuraci&oacute;n del Servicio WEB.';
    die();
}
if($rowws['ws_url1']=='php:'){
	$lista[] = array( 'operacion' =>'10011', 'instancia' =>'10010', 'suboperacion' =>'100102' );
	//$lista[] = array( 'operacion' =>'10021', 'instancia' =>'10020', 'suboperacion' =>'100202' );
}else{

	$WS_url=$rowws['ws_url1'];
	$oSoapClient = new nusoap_client($WS_url, true);
	$parametros = array( 'Cuenta' => $nrocaso);

	$resulta = array();
	$oSoapClient->loadWSDL();
	$result = $oSoapClient->call("OperacionPorCliente", $parametros);
	if ($oSoapClient->fault) { // Si
			echo 'No se pudo completar la operación '.$oSoapClient->getError();
			die();
	} else { // No
			$sError = $oSoapClient->getError();
			// Hay algun error ?
			if ($sError) { // Si
				   echo 'Error!:'.$sError;
				   die();
			}else{
			$resulta = $result["OperacionPorClienteResult"];
			}
	}
/*
$resulta = array('diffgram' => 
	array('NewDataSet' => 
		array('Operacion'=> array(
			array('Operacion'=>'123','Instancia'=>'456'),
			array('Operacion'=>'234','Instancia'=>'567'),
			array('Operacion'=>'345','Instancia'=>'678')
			)
			)
		)
		);

echo "<pre>";
print_r($resulta['diffgram']);
echo "</pre>";
die();

con uno diffgram
Array
(
    [NewDataSet] => Array
        (
            [OPERACION] => Array
                (
                    [cuenta] => 10231
                    [operacion] => 1663506
                    [suboperacion] => 0
                    [Instancia] => 5557577
                    [Estado] => ACTIVO
                    [!diffgr:id] => OPERACION1
                    [!msdata:rowOrder] => 0
                    [!diffgr:hasChanges] => inserted
                )

        )

)


con uno valores

Array
(
    [cuenta] => 10231
    [operacion] => 1663506
    [suboperacion] => 0
    [Instancia] => 5557577
    [Estado] => ACTIVO
    [!diffgr:id] => OPERACION1
    [!msdata:rowOrder] => 0
    [!diffgr:hasChanges] => inserted
)

con varios
Array
(
    [0] => Array
        (
            [cuenta] => 10205
            [operacion] => 1663648
            [suboperacion] => 0
            [Instancia] => 5557704
            [Estado] => ACTIVO
            [!diffgr:id] => OPERACION1
            [!msdata:rowOrder] => 0
            [!diffgr:hasChanges] => inserted
        )

    [1] => Array
        (
            [cuenta] => 10205
            [operacion] => 1663505
            [suboperacion] => 0
            [Instancia] => 5557576
            [Estado] => CANCELADO
            [!diffgr:id] => OPERACION2
            [!msdata:rowOrder] => 1
            [!diffgr:hasChanges] => inserted
        )
)

Array
(
    [0] => Array
        (
            [operacion] => 1663648
            [nrocaso] => 5557704
        )

    [1] => Array
        (
            [operacion] => 1663505
            [nrocaso] => 5557576
        )
)
<NewDataSet xmlns="">
<OPERACION diffgr:id="OPERACION1" diffgr:hasChanges="inserted" msdata:rowOrder="0">
<cuenta>535591</cuenta>
<operacion>1943972</operacion>
<suboperacion>0</suboperacion>
<Instancia>5808690</Instancia>
<Estado>ACTIVO</Estado>
</OPERACION>
<OPERACION diffgr:id="OPERACION2" diffgr:hasChanges="inserted" msdata:rowOrder="1">
<cuenta>535591</cuenta>
<operacion>1247465</operacion>
<suboperacion>0</suboperacion>
<Instancia>5225534</Instancia>
<Estado>ACTIVO</Estado>
</OPERACION>
<OPERACION diffgr:id="OPERACION3" diffgr:hasChanges="inserted" msdata:rowOrder="2">
<cuenta>535591</cuenta>
<operacion>1077993</operacion>
<suboperacion>0</suboperacion>
<Instancia>5109864</Instancia>
<Estado>CANCELADO</Estado>
</OPERACION>
<OPERACION diffgr:id="OPERACION4" diffgr:hasChanges="inserted" msdata:rowOrder="3">
<cuenta>535591</cuenta>
<operacion>316643</operacion>
<suboperacion>0</suboperacion>
<Instancia>4561027</Instancia>
<Estado>CANCELADO</Estado>
</OPERACION>
</NewDataSet>




*/

	$lista = array();

	if(isset($resulta['diffgram']['NewDataSet']['OPERACION'])){
		$valores = $resulta['diffgram']['NewDataSet']['OPERACION'];
		if(isset($valores['cuenta'])){
			$lista[] = array( 'operacion' =>$valores['operacion'], 'instancia' =>$valores['Instancia'], 'suboperacion' =>$valores['suboperacion'] );
		}else{
			//if(!isset($lista)) 
			foreach($valores as $item){
				//$operacion = $item['operacion'];
				//$instancia = $item['Instancia'];
				//$suboperacion  = $item['suboperacion'];
				$lista[] = array( 'operacion' =>$item['operacion'], 'instancia' =>$item['Instancia'], 'suboperacion' =>$item['suboperacion'] );
			}
		}
	}
}
/*
echo "<pre>";
print_r($valores);
echo "</pre>";


echo "<pre>";
print_r($lista);
echo "</pre> ";

*/
?>