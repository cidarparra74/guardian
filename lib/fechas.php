<?php
//session_start();
// ********************************************************* //
// EL SEPARADOR DE FECHA 
// ES EL GUION  -
// ********************************************************* // 
////////////////////////////////////////////////////

//Convierte fecha de mysql a normal
////////////////////////////////////////////////////
function dateDMY($fecha){
	if($fecha=='') return '';
	//forzamos a usar guion

		$fecha = str_replace('/','-',$fecha);
		preg_match('/([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})/', $fecha, $mifecha);
		$lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
		return $lafecha;

}



////////////////////////////////////////////////////
//Convierte fecha de normal a mysql
////////////////////////////////////////////////////
function dateYMD($fecha){
	if($fecha=='') return '';
	//forzamos a usar guion
	$fecha = str_replace('/','-',$fecha);
	//verificamos que no este en formato literal
	//01-ENE-2011
	$mes = substr($fecha,3,3);
	if($mes == 'ENE')
		$fecha = str_replace('ENE','01',$fecha);
	elseif($mes == 'FEB')
		$fecha = str_replace('FEB','02',$fecha);
	elseif($mes == 'MAR')
		$fecha = str_replace('MAR','03',$fecha);
	elseif($mes == 'ABR')
		$fecha = str_replace('ABR','04',$fecha);
	elseif($mes == 'MAY')
		$fecha = str_replace('MAY','05',$fecha);
	elseif($mes == 'JUN')
		$fecha = str_replace('JUN','06',$fecha);
	elseif($mes == 'JUL')
		$fecha = str_replace('JUL','07',$fecha);
	elseif($mes == 'AGO')
		$fecha = str_replace('AGO','08',$fecha);
	elseif($mes == 'SEP')
		$fecha = str_replace('SEP','09',$fecha);
	elseif($mes == 'OCT')
		$fecha = str_replace('OCT','10',$fecha);
	elseif($mes == 'NOV')
		$fecha = str_replace('NOV','11',$fecha);
	elseif($mes == 'DIC')
		$fecha = str_replace('DIC','12',$fecha);
	
    preg_match( '/([0-9]{1,2})-([0-9]{1,2})-([0-9]{2,4})/', $fecha, $mifecha);
	$lafecha = $mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
    return $lafecha;
}



////////////////////////////////////////////////////
// convierte de 'cadena' a tipo 'fecha'
///////////////////////////////////////////////////
function tipofecha($fecha){
	if($fecha=='') return '';
	$fecha=mktime(0,0,0,substr($fecha,3,2), substr($fecha,0,2), substr($fecha,6,4));
	return $fecha;
}


////////////////////////////////////////////////////
// convierte de fecha normal a fecha con mes en literal
///////////////////////////////////////////////////
function dateDMESY($fecha){
	if($fecha=='') return '';
	preg_match('/([0-9]{1,2})-([0-9]{1,2})-([0-9]{2,4})/', $fecha, $mifecha);
	$meslit = $mifecha[2];
	if($mifecha[2]=='01')
		$meslit = 'ENE';
	elseif($mifecha[2]=='02')
		$meslit = 'FEB';
	elseif($mifecha[2]=='03')
		$meslit = 'MAR';
	elseif($mifecha[2]=='04')
		$meslit = 'ABR';
	elseif($mifecha[2]=='05')
		$meslit = 'MAY';
	elseif($mifecha[2]=='06')
		$meslit = 'JUN';
	elseif($mifecha[2]=='07')
		$meslit = 'JUL';
	elseif($mifecha[2]=='08')
		$meslit = 'AGO';
	elseif($mifecha[2]=='09')
		$meslit = 'SEP';
	elseif($mifecha[2]=='10')
		$meslit = 'OCT';
	elseif($mifecha[2]=='11')
		$meslit = 'NOV';
	elseif($mifecha[2]=='12')
		$meslit = 'DIC';
	$lafecha = $mifecha[1]."/".$meslit."/".$mifecha[3];
    return $lafecha;
}

function fechaSQL($fsql){
	//$fecha_aux = $fsql;
	if($fsql!='--')
		$fecha_aux = "CONVERT(DATETIME,'$fsql',102)";
	else 
		$fecha_aux = "null";
	return $fecha_aux;
}

function fechaDMYh($fecha_datetime){
//Esta función convierte la fecha del formato DATETIME de SQL
//a formato DD-MM-YYYY HH:mm
$fecha = explode("-",$fecha_datetime);
$hora = explode(":",$fecha[2]);
$fecha_hora=explode(" ",$hora[0]);
$fecha_convertida=$fecha_hora[0].'-'.$fecha[1].'-'.$fecha[0].' '.$fecha_hora[1].':'.$hora[1];
return $fecha_convertida;
//
}

?>