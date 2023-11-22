<?php

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
	$resulta = $V->ValorEnLetras($nro1,"","punto ",0);
	return $resulta;
}

//$nrocaso = $_REQUEST['nrocaso']; quien
/* tenemos esto
$_SESSION['idcontrato'] = $idcontrato;
	$_SESSION['nrocaso'] = $nrocaso;
	$tipoope = "P";
*/
$nrocaso = $_REQUEST['nrocaso'];

		require('../lib/conexionMNU.php');
			// recpcion caso baneco por nro de caso
			$sql= "SELECT nc.segurodegravamen, nc.numerocuotas, nc.tasa1, nc.tasa2, nc.importelinea, nc.importeprestamo, nc.tiposeguro, nc.codigogarantia, il.cliente
			FROM ncaso_cfinal nc LEFT JOIN informes_legales il ON il.nrocaso=nc.nrocaso WHERE nc.nrocaso='$nrocaso'";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$tipoope=$nrocaso.' '.$row["cliente"].' <br>';
			//$to='x';
			if($row["importelinea"]>0){
					if($row["importeprestamo"]==0){
						$tipoope .= "(APERTURA LINEA, "; 
						//$to='A';
					}else{ 
						$tipoope .= "(BAJO LINEA, ";
						//$to='B';
					}
			}else{
					$tipoope .= "(PRESTAMO, ";
					//$to='P';
			}
			
			//jalamos el nombre del contrato
			$tipoope .= "SEGURO DESGRAVAMEN: ".$row["segurodegravamen"].", " ;
			if($row["tasa1"]>0 && $row["tasa2"]>0){$tipoope .= "TASA MIXTA, ";}
			elseif($row["tasa1"]>0 && $row["tasa2"]==0){$tipoope .= "TASA FIJA, ";}
			elseif($row["tasa1"]==0 && $row["tasa2"]>0){$tipoope .= "TASA VARIABLE, ";}
			elseif($row["tasa1"]==0 && $row["tasa2"]==0){$tipoope .= "SIN TASA, ";}
			if($row["numerocuotas"]==1){
				$tipoope .= "PAGO UNICO," ;
			}else{
				$tipoope .= "PAGOS SECUENCIALES,";
			}
			$tipoope .= "TipoSeguro:". $row["tiposeguro"] ;
			$tipoope .= ", TipoGar:". $row["codigogarantia"].')' ;
			//$_SESSION['tipoope'] = $tipoope;
			$smarty->assign('tipoope',$tipoope);
			
		


	//unset($link);
	
	$sql= "SELECT DISTINCT convert(int,nrocaso) nro, importelinea, importeprestamo,
		segurodegravamen, tiposeguro, linearotativa, numerocuotas, tasa1, tasa2,
		codigogarantia, tiposeguro, producto
		FROM ncaso_cfinal  
		WHERE idfinal='0' AND nrocaso = '$nrocaso'";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		if($row["importelinea"]>0)  
			if($row["importeprestamo"]==0) 
				$tipoope = "A"; //Apertura de linea (9)
			else 
				$tipoope = "B";  //Bajo Linea (2)
		else
			$tipoope = "P";     //Préstamo (4)
		$segurodesg= $row["segurodegravamen"];
		$tiposeguro= $row["tiposeguro"];
		$linearota = $row["linearotativa"];
		$ncuotas = $row["numerocuotas"];
		$producto = $row["producto"];
		$cgarantia = $row["codigogarantia"];
		$tseguro = $row["tiposeguro"];
		$tasa1 = $row["tasa1"];
		$tasa2 = $row["tasa2"];
		$tipopp = $row["tipopp"];
		//para caso especial de contratos fijos (si viene de adicionar1_esp.php
		$sql2= "SELECT idcontrato FROM contratos_fijos WHERE clase='$tipoope' ";
		$query2 = consulta($sql2);
		$row2= $query2->fetchRow(DB_FETCHMODE_ASSOC);
		$idcontrato = $row2["idcontrato"];
	$_SESSION['idcontrato'] = $idcontrato;
	$_SESSION['nrocaso'] = $nrocaso;
	//echo $contrato;
//para el nrocaso: X
/*
$sql2= "SELECT clase FROM contratos_fijos WHERE idcontrato=$idcontrato ";
		$query2 = consulta($sql2);
		$row2= $query2->fetchRow(DB_FETCHMODE_ASSOC);
		$clase = $row2["clase"];
		*/

//determinamos si tiene seguro
/*
$sql="SELECT segurodegravamen, tiposeguro, linearotativa, numerocuotas, tasa1, tasa2,
codigogarantia, tiposeguro  
FROM ncaso_cfinal WHERE nrocaso = '$nrocaso' AND idfinal = 0";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
$segurodesg= $row["segurodegravamen"];
$tiposeguro= $row["tiposeguro"];
$linearota = $row["linearotativa"];
$ncuotas = $row["numerocuotas"];
$cgarantia = $row["codigogarantia"];
$tseguro = $row["tiposeguro"];
$tasa1 = $row["tasa1"];
$tasa2 = $row["tasa2"];
*/
$codigos = trim($tseguro).' '.trim($cgarantia);
//echo $codigos.'<BR>';
if($segurodesg=='S' || $tiposeguro!='')
	$tc = 'SEG';

	
//Para saber si tiene seguro y elegir la clausula opcional de seguros
$sql="SELECT DISTINCT idclausula FROM sec_opcional WHERE idcontrato=$idcontrato AND idnumeral=0 AND tc='$tc'";
$query = consulta($sql);

$opcional='';
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$opcional .= ' '.$row["idclausula"];
}
//para el tipo de linea
$li = '';
if($tipoope=='A'){
if($linearota=='S')
	$li = 'LIR';
else
	$li = 'LIS';
}
//$sql="SELECT DISTINCT idnumeral FROM sec_opcional WHERE idcontrato=$idcontrato AND tc='$li'";
//para garantias

/// SEGURO DE DESGRAVAMEN

if($segurodesg=='S')  //SEGURO DE VIDA DE DESGRAVAMEN en cláusula
	$codigos = 'SVD '.$codigos;
else
	if($segurodesg=='B') //SEGURO DE VIDA DE DESGRAVAMEN en inciso
		$codigos = 'SVD '.$codigos;
	else
		if($segurodesg=='O')  //SEGURO DE VIDA DE DESGRAVAMEN de terceros u otros bancos
			$codigos = 'SVO '.$codigos;
		else
			$codigos = 'SVN '.$codigos;  //NO cuenta con SEGURO DE VIDA DE DESGRAVAMEN inciso especifico

			
/// PAGO UNICO O EN CUOTAS			
if($ncuotas==1){
	$codigos = 'FPU '.$codigos;
}else{
	$codigos = 'FPS '.$codigos;
}

	
/// PAGO UNICO O EN CUOTAS	
			
if($producto>0){
	$sql = "SELECT sigla FROM producto WHERE codigo=$producto";
	$query = consulta($sql);	
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	if($row["sigla"]!='')
			$codigos = $row["sigla"].' '.$codigos;
	/*
	if($producto==999){
		$codigos = 'FPU '.$codigos;
	}else{
		$codigos = 'FPS '.$codigos;
	}
	*/
}

/// TIPO DE TASA: MIXTA, FIJA O VARIABLE

if($tasa1>0 && $tasa2>0){$codigos = 'TMI '.$codigos;}
elseif($tasa1>0 && $tasa2==0){$codigos = 'TFI '.$codigos;}
elseif($tasa1==0 && $tasa2>0){$codigos = 'TVA '.$codigos;}

/// TIPO DE PLAN DE PAGOS: F=Frances cuota fija, A=Alemán cuota variable, Sin Capital solo interés
if($tipopp=="F"){$codigos = 'FRC '.$codigos;}
elseif($tipopp=="A"){$codigos = 'ALE '.$codigos;}
elseif($tipopp=="?"){$codigos = 'S/K '.$codigos;}

			
$codigos = "'".str_replace(" ","','",trim($codigos))."','$li'";
//echo $codigos;
//echo "<br>";
$sql = "SELECT DISTINCT idnumeral, titulo FROM sec_opcional WHERE idcontrato=$idcontrato AND tc IN ($codigos)";
//echo $sql;
//Obtención de todos los incisos que se usarán
$incisos=''; 
$query = consulta($sql);
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$incisos .= ' '.$row["idnumeral"];
	$label .= ' '.$row["titulo"];
}
//echo $label;
$opcional = "'0','".str_replace(" ","','",trim($opcional))."'";
$incisos = "'0','".str_replace(" ","','",trim($incisos))."'";
$_SESSION['opcional'] = $opcional;
$_SESSION['incisos'] = $incisos;
/*
SVD SEGURO DESGRAVAMEN

FPS   SECUENCIALES idnumeral
FPU   UNICO

TMI	TASA MIXTA
TVA	TASA VARIABLE
TFI	TASA FIJA

			if($row["tasa1"]>0 && $row["tasa2"]>0){$tipoope .= "TASA MIXTA, ";}
			elseif($row["tasa1"]>0 && $row["tasa2"]==0){$tipoope .= "TASA FIJA, ";}
			elseif($row["tasa1"]==0 && $row["tasa2"]>0){$tipoope .= "TASA VARIABLE, ";}
			elseif($row["tasa1"]==0 && $row["tasa2"]==0){$tipoope .= "SIN TASA, ";}
			if($row["numerocuotas"]==1){
				$tipoope .= "PAGO UNICO)" ;
			}else{
				$tipoope .= "PAGOS SECUENCIALES)";
			}

*/

	$glogin=$_SESSION['glogin'];
	unset($link);
	require('../lib/conexionSEC.php');
	//$contrato =='' && 
		if($idcontrato != 0){
			$sql= "SELECT c.titulo FROM contrato c WHERE c.idcontrato=$idcontrato";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$contrato= $row["titulo"];
		}
	$_SESSION['contrato'] = $contrato;
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
		$tipogarantia=trim($row['tipogarantia']);
		
		if($tipogarantia!='' and $tipogarantia!='0'){
			if($tipogarantia=='INM'){
				//$sqlg="SELECT tb.bien, il.garantia_contrato FROM informes_legales il 
				//	LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien 
				//  WHERE tb.bien = 1 AND il.nrocaso = '$nrocaso'";
				$tipogarantia=1;
			}elseif($tipogarantia=='VEH'){
				$tipogarantia=3;
			}
			//cambiamos para relacionar segun la garantia definida en la tabla 18/10/12
			$sqlg="SELECT tb.bien, il.garantia_contrato FROM informes_legales il 
					LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien WHERE tb.bien = '$tipogarantia' AND il.nrocaso = '$nrocaso'";
		//	echo $sqlg;
			$query2 = consulta($sqlg);
			while($rowg= $query2->fetchRow(DB_FETCHMODE_ASSOC)){
				if($rowg["garantia_contrato"]!=''){
					$contenido .= $rowg["garantia_contrato"]."\n\r";
					//$contenido2 = $rowg["dato2"];
				}
			}
				$guardian_var[] = array('idtexto'=>trim($row['idtexto']),
										'contenido'=>$contenido,
										'contenido2'=>'');
			
		}else{
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
	}
	//echo $sqlg;
	//echo '<pre>';
	//echo print_r($guardian_var);
	//echo '</pre>';
	//unset($link);
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
				//echo '('.trim($row["idtexto"]).'='.$contenido.')';
					//si tiene, vemos q valor tomar
					//echo trim($row["idtexto"]).'/'.$gvar["idtexto"]. '/' .$gvar["contenido"].'<br>';
					$contenido = $gvar["contenido"];
					$contenido2 = trim($gvar["contenido2"]);
					//lo sigte para ver desde que opcion ingresan si desde especial con modificacion
					// o especial sin modificacion de variables
					//si $quien=4 estamos en contratos autom no modificables
					if(isset($_SESSION["edita"]) && $_SESSION["edita"] =='n'){
						$nocambia='1';
						$esglobal = 5; //esto para que sea siempre una caja de texto
					}else{
						// autom modificables
						$nocambia='0';
					}
					
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
						$contenido = $contenido.'% ('.strtoupper(decimalx($contenido)).'PORCIENTO)' ;
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
		/*	$principal[] = array('id' => $valor["id"],
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
				*/				
				//	$i++; //lo desactivamos para que la numeracion de variables sea consecutiva
		}
	}
	unset($valor);
	unset($valor2);
				
	//if(isset($_SESSION['tipoope'])){
	//	$smarty->assign('tipoope',$_SESSION['tipoope']);
	//}	
					
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
	$smarty->display('contratos/variables_esp.html');
	die();

?>