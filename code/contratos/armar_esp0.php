<?php

//$nrocaso = $_REQUEST['nrocaso'];
/* tenemos esto
$_SESSION['idcontrato'] = $idcontrato;
	$_SESSION['nrocaso'] = $nrocaso;
	$tipoope = "P";
*/
//para el nrocaso: X
//determinamos si tiene seguro
$sql="SELECT segurodegravamen, tiposeguro, linearotativa FROM ncaso_cfinal WHERE nrocaso = '$nrocaso' AND idfinal = 0";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
$segurodesg= $row["segurodegravamen"];
$tiposeguro= $row["tiposeguro"];
$linearota = $row["linearotativa"];

if($segurodesg=='S' || $tiposeguro!='')
	$tc = 'SEG';

//Para saber si tiene seguro y elegir la clausula opcional de seguros
$sql="SELECT DISTINCT idclausula FROM sec_opcional WHERE idcontrato=$idcontrato AND idnumeral=0 AND tc='$tc'";
$query = consulta($sql);
$opcionales='';
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$opcionales .= ' '.$row["idclausula"];
}
//para el tipo de linea
if($linearota='S')
	$li = 'LIR';
else
	$li = 'LIS';
	
//$sql="SELECT DISTINCT idnumeral FROM sec_opcional WHERE idcontrato=$idcontrato AND tc='$li'";
//para garantias
$codigos = "('".str_replace(" ","','",trim($codigos))."','$li')";
echo $codigos;
$sql = "SELECT DISTINCT idnumeral FROM sec_opcional WHERE idcontrato=$idcontrato AND tc IN ($codigos)'";
$query = consulta($sql);
$incisos='';
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$incisos .= ' '.$row["idnumeral"];
}
$opcionales = "('".str_replace(" ","','",trim($opcionales))."')";
$incisos = "('".str_replace(" ","','",trim($incisos))."')";

/*
SVD SEGURO DESGRAVAMEN

FPS   SECUENCIALES idnumeral
FPU   UNICO

TMI	TASA MIXTA
TVA	TASA VARIABLE
TFI	TASA FIJA
*/
require_once('../lib/nro2lit.php');

function monedax($nro1){
	$nro1 = $nro1 + 0.0001;
	$V=new EnLetras();
	$resulta = $V->ValorEnLetras($nro1,"","",1);
	return $resulta;
}
function enterox($nro1){
	$V=new EnLetras();
	$resulta = $V->ValorEnLetras($nro1,"","",0);
	return $resulta;
}
function decimalx($nro1){
	//$nro1 = $nro1 + 0.0001;
	$V=new EnLetras();
	$resulta = $V->ValorEnLetras($nro1,"","punto",0);
	return $resulta;
}


	$glogin=$_SESSION['glogin'];
	
	
	$_SESSION['incisos'] = $incisos;
	//seleccionamos todas las clausulas del contrato, mas las opcionales seleccionadas, mas los incisos seleccionados
	$sql="SELECT r.idclausula, cl.titulo, nu.idnumeral, nu.titulo as inciso
FROM rel_cc r INNER JOIN clausula cl ON r.idclausula=cl.idclausula 
LEFT JOIN (SELECT IDCLAUSULA, nro_correlativo, idnumeral, titulo FROM numeral WHERE idnumeral IN ($incisos)) nu ON nu.idclausula=cl.idclausula 
WHERE r.idcontrato= $idcontrato AND (r.opcional=0 OR cl.idclausula IN ($opcional))  ORDER BY r.posicion, nu.nro_correlativo";
	$query = consulta($sql);
	//echo $sql;
	$clausulas=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if($row["idnumeral"]==''){
			//estas son las clausulas sin incisos
			$clausulas[]= array('id' => $row["idclausula"],
							'titulo' => $row["titulo"],
							'idnumeral' => '0',
							'inciso' => $row["inciso"]);
		}else{
			$clausulas[]= array('id' => $row["idclausula"],
							'titulo' => $row["titulo"],
							'idnumeral' => $row["idnumeral"],
							'inciso' => $row["inciso"]);
		}
	}
	//leemos del guardian las variables con contenido 
	//q se debe tomar de alguna tabla, solo si aplica el nro de caso
	if(isset($_SESSION["nrocaso"]))
		$nrocaso = $_SESSION["nrocaso"];
	else 
		$nrocaso='0';
if($nrocaso!='0'){
	unset($link);
	require('../lib/conexionMNU.php');
	$sql="SELECT * FROM variable_campo";
	$query = consulta($sql);
	$guardian_var=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tabla=$row['tabla'];
		$campo=trim($row['campo']);
		$adicional=trim($row['adicional']);
		$contenido='';
		if($campo!='' && $adicional!='' && $tabla!=''){
			$sqlg="SELECT $campo as dato1, $adicional as dato2 FROM $tabla WHERE nrocaso = '$nrocaso'";
		}elseif($campo!='' && $tabla!=''){
			$sqlg="SELECT $campo as dato1 FROM $tabla WHERE nrocaso = '$nrocaso'";
		}else{
			$sqlg='';
		}
		if($sqlg!=''){
			$query2 = consulta($sqlg);
			$rowg= $query2->fetchRow(DB_FETCHMODE_ASSOC);
			if($rowg["dato1"]!=''){
				$contenido = $rowg["dato1"];
			//	echo $contenido;
				//ver como hacer para el dato2 (adicional)
				$contenido2 = $rowg["dato2"];
			}
			$guardian_var[] = array('idtexto'=>trim($row['idtexto']),
									'contenido'=>$contenido,
									'contenido2'=>$contenido2);
		}
	}
	//echo '<pre>';
	//echo print_r($guardian_var);
	//echo '</pre>';
	unset($link);
	require('../lib/conexionSEC.php');
}
	//vemos las variables de todas las clausulas e incisos sin restriccion
	$sql="SELECT r.posicion, r.idclausula, c.idnumeral, c.nro_correlativo, var_texto.*,  
	PATINDEX('%'+idtexto+'%', c.contenido) as nro 
FROM rel_cc r,numeral c , var_texto
WHERE r.idcontrato= $idcontrato AND r.idclausula=c.idclausula 
AND c.contenido like '%<<'+idtexto+',%'
UNION
SELECT r.posicion,r.idclausula,0,0, var_texto.*, PATINDEX('%'+idtexto+'%', c.contenido) as nro 
FROM rel_cc r, clausula c, var_texto
WHERE r.idcontrato= $idcontrato AND r.idclausula=c.idclausula 
AND c.contenido like '%<<'+idtexto+',%'
order by r.posicion, nro";
//echo $sql;
	$query = consulta($sql);
	$variables=array();
	
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		//buscamos si la variable tiene contenido del guardian
		$contenido = $row["contenido"];
		$nocambia='0';
		$esglobal = $row["esglobal"];
		if($contenido=='-') $contenido = '';
		if($nrocaso!='0'){
		//
			foreach($guardian_var as $gvar){
				
				if(trim($row["idtexto"]) == $gvar["idtexto"] && trim($gvar["contenido"])!=''){
					//si tiene, vemos q valor tomar
					//echo trim($row["idtexto"]).'/'.$gvar["idtexto"]. '/' .$gvar["contenido"].'<br>';
					$contenido = $gvar["contenido"];
					$contenido2 = trim($gvar["contenido2"]);
					$nocambia='1';
					$esglobal = 5; //esto para que sea siempre una caja de texto
					//como unimos el contenido 2?
					if($contenido2=='1'||$contenido2=='4'){
						//es bs
						$moneda='Bs';
						 $monedalit='BOLIVIANOS)';
					}elseif($contenido2=='2'||$contenido2=='3'){
						//es $us
						$moneda='$us';
						 $monedalit='DOLARES DE LOS ESTADOS UNIDOS DE AMERICA)';
					}else{
						$moneda='';
						 $monedalit=')';
					}
					//aqui ya tenemos el tipo de variable en $row["tipo"], le damos formato
					//--------------------
					if($row["tipo"]=='1'){
						//Es un valor numérico decimal, y debe ser convertido a literal con moneda. Ej. Bs 100.50 (Son Cien 50/100 Bolivianos)
						$x = number_format($contenido,2,'.',','); //echo $x;
						$contenido = $moneda.' '.$x.' ('.strtoupper(monedax($contenido)).' '. $monedalit;
					}elseif($row["tipo"]=='2'){
						//Es un valor numérico decimal, y debe ser convertido a literal porcentaje. Ej. 10.1% (Diez punto uno por ciento)
						$contenido = $contenido.' ('.strtoupper(decimalx($contenido)).' PORCIENTO)' ;
					}elseif($row["tipo"]=='3'){
						//Es un valor numérico decimal, y debe ser convertido a literal. Ej. 8.33 (Ocho punto Treinta tres)
						$contenido = $contenido.' ('.strtoupper(decimalx($contenido)).')' ;
					}elseif($row["tipo"]=='4'){
						//Es un valor numérico entero, y debe ser convertido a literal. Ej. 12 (doce)
						$contenido = $contenido.' ('.strtoupper(enterox($contenido)).')' ;
					}elseif($row["tipo"]=='5'){
						//Es un valor fecha, y debe ser convertido a literal. Ej “12-09-2010” cambiar por:  “12 de septiembre de 2011”
						//$contenido = $contenido.' fecha' ;
					}elseif($row["tipo"]>=6){
						//tipo >= 6 No esta definido. no cambiamos nada
						//$contenido = $contenido.' ninguno' ;
					}
					//--------------------- salimos ya no buscamos mas
					break;
				}
			}//foreach
		}
		//todas las variables por clausula e inciso
	//	$esglobal = $row["esglobal"];
		$variables[]= array('id' => $row["idclausula"],
							'idnumeral' => $row["idnumeral"],
							'nro_correlativo' => $row["nro_correlativo"],
							'idtexto' => $row["idtexto"],
							'contenido' => $contenido,
							'esglobal' => $esglobal,
							'lineas' => $row["lineas"],
							'eslista' => $row["eslista"],
							'tipo' => $row["tipo"],
							'nocambia' => $nocambia,
							'descripcion' => $row["descripcion"]);
	}
	unset($guardian_var);
	//si no existe ya sesion para array principal, entonces lo creamos
	if(!isset($_SESSION["principal"])){
	//creamos arreglo principal con todas las clausulas, numerales, y variables que deben estar
	// principal contiene toda la info para armar el contrato(clausulas sin variables tb)
	$principal = array();
	$i=0;
	//recorremos clausulas elegidas
	foreach($clausulas as $valor){
		$id1 = $valor['id'];
		$id2 = $valor['idnumeral'];
		$existe = 0;
		//para esta clausula recorremos q variables tiene en la misma clausula, es decir idnumeral==null
		//y q variables tiene en sus incisos, si hay incisos
		foreach($variables as $key => $valor2){
			if($id1 == $valor2['id'] && ($id2 == $valor2['idnumeral'] || $valor2['idnumeral'] == '0')){
				//vemos que clase de variable es
				$valores=array();
				$myvalor = $valor2["contenido"];
				//echo $myvalor ;
				if($valor2["esglobal"]=='1' || $valor2["esglobal"]=='4'){
					//estos son valores para la lista
					$sql="SELECT idtexto, valor FROM  var_texto_valores WHERE idtexto = '".$valor2["idtexto"]."' ORDER BY valor";
					$query = consulta($sql);
					while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
						$valores[]= array('id' => $row["idtexto"],
											'valor' => $row["valor"]);
					}
					//print_r($valores);
				}
				//controlamos que el valor del contenido no cambie si ya tiene un contenido previo
				if($valor2["nocambia"]=='0'){
					if($valor2["esglobal"]=='2' || $valor2["esglobal"]=='4'){
						//estos son valores para recordar segun el usuario
						$sql="SELECT valor FROM  var_texto_recuerda WHERE idtexto = '".$valor2["idtexto"]."' AND usuario = '$glogin' and habilitado = 1";
						$query = consulta($sql);
						while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
							$myvalor = $row["valor"];
						}
					}
				}
				//echo $valor2["idtexto"].' / ';
				$principal[] = array('id' => $valor2["id"],
							'titulo' => $valor["titulo"],
							'idnumeral' => $valor2["idnumeral"],
							'inciso' => $valor["inciso"],
							'nro_correlativo' => $valor2["nro_correlativo"],
							'idtexto' => $valor2["idtexto"],
							'contenido' => $myvalor ,
							'esglobal' => $valor2["esglobal"],
							'lineas' => $valor2["lineas"],
							'eslista' => $valor2["eslista"],
							'tipo' => $valor2["tipo"],
							'nocambia' => $valor2["nocambia"],
							'descripcion' => $valor2["descripcion"],
							'valores' => $valores,
							'ind' => $i);
				$i++;
				$existe = 1;
				$variables[$key]["idnumeral"] = -1;
			}
		}
		if($existe==0){
			//esto era para que salgan los incisos, depurar despues
			$principal[] = array('id' => $valor["id"],
							'titulo' => $valor["titulo"],
							'idnumeral' => $valor["idnumeral"],
							'inciso' => $valor["inciso"],
							'nro_correlativo' => '',
							'idtexto' => '',
							'contenido' => '',
							'esglobal' => '0',
							'lineas' => '',
							'eslista' => '',
							'tipo' => '',
							'nocambia' => '0',
							'descripcion' => '',
							'ind' => 'x'); 
					//	$i++; //lo desactivamos para que la numeracion de variables sea consecutiva
		}
	}
	unset($valor);
	unset($valor2);
				
	if(isset($_SESSION['tipoope'])){
		$smarty->assign('tipoope',$_SESSION['tipoope']);
	}	
					
	//guardamos los datos en sesion para recuperarlos despues
	$_SESSION["principal"] = $principal;
	
	}else{//por else sesion ya existe
		$principal = $_SESSION["principal"];
	}

	$smarty->assign('principal',$principal);
	$smarty->assign('clausulas',$clausulas);
	//$smarty->assign('idcontrato',$idcontrato);
	$smarty->assign('contrato',$contrato);
	//$smarty->assign('opcional',$opcional);
	$smarty->display('contratos/adicionar4.html');
	die();

?>