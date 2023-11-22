<?php
	//LLENADO DE VARIABLES 
	//(LUEGO SIGUE PARTES.php)
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

//vemos que exista la sesion del contrato seleccionado
if(!isset($_SESSION['idcontrato'])){	die("No se definio contrato");}
	//recuperamos valores var_texto
	$idcontrato = $_SESSION['idcontrato'];
	$contrato = $_SESSION['contrato'];
	$opcional = $_SESSION['opcional'];
	$glogin=$_SESSION['glogin'];
	
	//vemos las clausulas q tiene incisos incisos, igual que en php adicionar3
	/*
	$sql= "SELECT cl.idclausula, cl.titulo, nu.idnumeral, nu.nro_correlativo, nu.titulo as inciso, nu.excluyente 
	FROM numeral nu 
	INNER JOIN clausula cl ON cl.idclausula=nu.idclausula 
	INNER JOIN rel_cc rc ON rc.idclausula=cl.idclausula 
	WHERE nu.idclausula IN 
	(SELECT r.idclausula
	FROM rel_cc r INNER JOIN clausula c ON r.idclausula=c.idclausula 
	WHERE r.idcontrato= $idcontrato AND (r.opcional=0 OR c.idclausula IN ($opcional))
	) ORDER BY rc.posicion, nu.nro_correlativo";
	*/
	$sql= "SELECT cl.idclausula, cl.titulo, nu.idnumeral, nu.nro_correlativo, nu.titulo as inciso, nu.excluyente 
	FROM numeral nu 
	INNER JOIN clausula cl ON cl.idclausula=nu.idclausula 
	INNER JOIN rel_cc rc ON rc.idclausula=cl.idclausula 
	WHERE rc.idcontrato= $idcontrato AND (rc.opcional=0 OR cl.idclausula IN ($opcional))
	ORDER BY rc.posicion, nu.nro_correlativo";
	$query = consulta($sql);
	$i=0;
	//$incisos=array();
	$incisos = '0';
	//determinamos que incisos se marcaron
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$checkbox = 'opc_'.$i;
		$radio = 'rad_'.$i;
		if(isset($_REQUEST["$checkbox"])){
			//$checkbox = 'opc_'.$i;
			//verificamos si se trata de un check box o de un radio  button
			//if($row["excluyente"]=='0'){
				//check
				$incisos .= ','.$row["idnumeral"];
			//}else{
				//radio
				//$checkbox = 'rad_'.$i;
			//	$incisos .= ','.$_REQUEST["$radio"];
			//}
		}
		if(isset($_REQUEST["$radio"])){
			//$checkbox = 'opc_'.$i;
			//verificamos si se trata de un check box o de un radio  button
			//if($row["excluyente"]=='0'){
				//check
			//	$incisos .= ','.$row["idnumeral"];
			//}else{
				//radio
			//	$checkbox = 'rad_'.$i;
				$incisos .= ','.$_REQUEST["$radio"];
			//}
		}
		$i++;
	}
	//echo  $incisos;
	//echo $_REQUEST["rad_1"];
	$_SESSION['incisos'] = $incisos;
	//, cl.contenido as cont_cla, nu.contenido as cont_inc
	
//seleccionamos todas las clausulas del contrato, 
//mas las opcionales seleccionadas, mas los incisos seleccionados
	$sql="SELECT r.idclausula, cl.titulo, nu.idnumeral, nu.titulo as inciso
FROM rel_cc r INNER JOIN clausula cl ON r.idclausula=cl.idclausula 
LEFT JOIN (SELECT IDCLAUSULA, nro_correlativo, idnumeral, titulo FROM numeral WHERE idnumeral IN ($incisos)) nu ON nu.idclausula=cl.idclausula 
WHERE r.idcontrato= $idcontrato AND (r.opcional=0 OR cl.idclausula IN ($opcional)) ORDER BY r.posicion, nu.nro_correlativo";
	$query = consulta($sql);
	//echo $sql; die();
	$clausulas=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if($row["idnumeral"]==''){
			//estas son las clausulas sin incisos
			$clausulas[]= array('id' => $row["idclausula"],
							'titulo' => htmlentities($row["titulo"]),
//'titulo' => $row["titulo"],
							'idnumeral' => '0',
							'inciso' => htmlentities($row["inciso"]));
//'inciso' => $row["inciso"]);
		}else{
			$clausulas[]= array('id' => $row["idclausula"],
							'titulo' => htmlentities($row["titulo"]),
//'titulo' => $row["titulo"],
							'idnumeral' => $row["idnumeral"],
							'inciso' => htmlentities($row["inciso"]));
//'inciso' => $row["inciso"]);
		}
	}
	//-------------------------------------------------------------------------------------
	//leemos del guardian las variables con contenido 
	//q se debe tomar de alguna tabla, solo si aplica el nro de caso
	//-------------------------------------------------------------------------------------
	if(isset($_SESSION["nrocaso"]))
		$nrocaso = $_SESSION["nrocaso"];
	else 
		$nrocaso='0';
		
$representa=array(); //para los representantes si hay
		
if($nrocaso!='0'){
	unset($link);
	require('../lib/conexionMNU.php');
	$sql="SELECT * FROM variable_campo WHERE campo <> ''";
	$query = consulta($sql);
	$guardian_var=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tabla=$row['tabla'];
		$campo=trim($row['campo']);
		$adicional=trim($row['adicional']);
		$contenido='';
		$contenido2 = '';
		$tipogarantia=trim($row['tipogarantia']);
		//echo trim($row['idtexto'])." <br>";
		if($tipogarantia!='' && $tipogarantia!='0'){
			if($tipogarantia=='INM'){
				$tipogarantia=1;
			}elseif($tipogarantia=='VEH'){
				$tipogarantia=3;
			}
			$sqlg="SELECT il.garantia_contrato as dato1 FROM informes_legales il 
					LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien WHERE tb.bien = '$tipogarantia' AND il.nrocaso = '$nrocaso'";
				//echo "x";	
		}else{
			if($campo!='' && $adicional!='' && $tabla!=''){
				$sqlg="SELECT $campo as dato1, $adicional as dato2 FROM $tabla WHERE nrocaso = '$nrocaso'";
			}elseif($campo!='' && $tabla!=''){
				$sqlg="SELECT $campo as dato1 FROM $tabla WHERE nrocaso = '$nrocaso'";
				//echo $sqlg; echo trim($row['idtexto'])." <br>";
			}else{
				$sqlg=''; 
			}
		}
		if($sqlg!=''){
			$query2 = consulta($sqlg);
			$rowg= $query2->fetchRow(DB_FETCHMODE_ASSOC);
			if($rowg["dato1"]!=''){
				$contenido = $rowg["dato1"];
				//ver como hacer para el dato2 (adicional)
				if(isset($rowg["dato2"]))
					$contenido2 = $rowg["dato2"];

			}
			$guardian_var[] = array('idtexto'=>trim($row['idtexto']),
									'contenido'=>htmlentities($contenido),
									'contenido2'=>htmlentities($contenido2));
//'contenido'=>$contenido,
//'contenido2'=>$contenido2);
		}
		
	}
	// vemos a que oficina y banca corresponde el nro de caso
	//para luego buscar los representantes legales de esa agencia y banca
		$sqlg="SELECT id_banca, agencia FROM ncaso_cfinal WHERE nrocaso = '$nrocaso'";
		$query2 = consulta($sqlg);
		$rowg= $query2->fetchRow(DB_FETCHMODE_ASSOC);
		if($rowg["id_banca"]!=''){
			$id_banca = $rowg["id_banca"];
			$id_oficina = $rowg["agencia"]; //en ws_flujocre se transforma el codigo de agencia en id_agencia
			//lo sigte no funcionara bien si no se configuro los codigos de agencias
			$sqlg="SELECT nombre, idtexto FROM representa WHERE id_banca = '$id_banca' AND id_oficina = '$id_oficina'";
			$query2 = consulta($sqlg);
			while($rowg= $query2->fetchRow(DB_FETCHMODE_ASSOC)){
				$representa[] = array('idtexto'=>$rowg["idtexto"], 'nombre'=>$rowg["nombre"]);
			}
		}
	//
	unset($link);
	require('../lib/conexionSEC.php');
}
	//vemos las variables de todas las clausulas e incisos sin restriccion

	$sql="SELECT r.posicion, r.idclausula, c.idnumeral, c.nro_correlativo, 
	vt.idtexto, vt.contenido, vt.esglobal, vt.descripcion, vt.eslista, vt.lineas, vt.tipo,  
	PATINDEX('%<<INCISOS,%', cl.contenido) as nro,
PATINDEX('%'+vt.idtexto+'%', c.contenido) as nrocla
FROM rel_cc r, numeral c , var_texto vt, clausula cl
WHERE r.idcontrato= $idcontrato AND r.idclausula=c.idclausula AND r.idclausula=cl.idclausula
AND c.contenido like '%<<'+idtexto+',%' AND  c.idnumeral in ($incisos) 
UNION
SELECT r.posicion,r.idclausula,0 as idnumeral,0 as nro_correlativo, 
	vt.idtexto, vt.contenido, vt.esglobal, vt.descripcion, vt.eslista, vt.lineas, vt.tipo, 
	PATINDEX('%'+idtexto+'%', c.contenido) as nro , 0 as nrocla
FROM rel_cc r, clausula c, var_texto vt
WHERE r.idcontrato= $idcontrato AND r.idclausula=c.idclausula 
AND c.contenido LIKE '%<<'+idtexto+',%' AND (r.opcional = 0 or c.idclausula in ($opcional))
UNION 
SELECT r.posicion,r.idclausula,0 as idnumeral,0 as nro_correlativo, 
	vt.idtabla as idtexto, '[tabla]' as contenido, 0 as esglobal, vt.descripcion, 
		0 as eslista, 0 as lineas, 0 as tipo, 
	PATINDEX('%'+idtabla+'%', c.contenido) as nro, 0 as nrocla 
FROM rel_cc r, clausula c, var_tabla vt
WHERE r.idcontrato= $idcontrato AND r.idclausula=c.idclausula 
AND c.contenido LIKE '%<<'+idtabla+',%' AND (r.opcional = 0 or c.idclausula in ($opcional))
UNION
SELECT r.posicion,r.idclausula,c.idnumeral, c.nro_correlativo,
vt.idtabla as idtexto, '[tabla]' as contenido, 0 as esglobal, vt.
descripcion,
0 as eslista, 0 as lineas, 0 as tipo,
PATINDEX('%<<INCISOS,%', cl.contenido) as nro,
PATINDEX('%'+idtabla+'%', c.contenido) as nrocla
FROM rel_cc r, numeral c, var_tabla vt, clausula cl
WHERE r.idcontrato= $idcontrato AND r.idclausula=c.idclausula AND r.idclausula=cl.idclausula
AND c.contenido LIKE '%<<'+idtabla+',%' AND  c.idnumeral in ($incisos) 
ORDER BY r.posicion, nro, nrocla";
//echo $sql; die();
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
				//desactivamos lo sigte para que puedan cambiar el contenido de las variables
			//		$nocambia='1';  //si desactivamos esto desactivar tambien el de abajo
			//		$esglobal = 5;  //esto para que sea siempre una caja de texto
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
						$moneda='€';
						 $monedalit='EUROS)';
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
	//ya existe la variable? buscamos $row["idtexto"] en $variables
	//si existe ponemos descripcion a vacio para que no salga en el html
	//dejamos que se repita si son clausulas distintas
		$sw1='0';
		$descripcion = $row["descripcion"]; 
		$idt = $row["idtexto"];
		//$idc = $row["idclausula"];
		foreach($variables as $svar){
			if($idt == $svar["idtexto"]){
				// aqui es una variable duplicada!!!
				$sw1='1';
				//$descripcion = '';
			}
		}
		//if($sw1 == '0')
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
							'descripcion' => htmlentities($descripcion),
							'doble' => $sw1);  
							//$row["descripcion"]
		
	}
	//echo "<pre>"; print_r($variables); die();
	/*
	//para las variables CALIDAD
$sql = "SELECT r.posicion,r.idclausula,0 as idnumeral,0 as nro_correlativo, 
	vt.idtabla as idtexto, '[tabla]' as contenido, 0 as esglobal, vt.descripcion, 
		0 as eslista, 0 as lineas, 0 as tipo, 
	PATINDEX('%'+idtabla+'%', c.contenido) as nro, 0 as nrocla
FROM rel_cc r, clausula c, var_tabla vt
WHERE r.idcontrato= $idcontrato AND r.idclausula=c.idclausula 
AND c.contenido LIKE '%,9>>%'
UNION
SELECT r.posicion,r.idclausula,c.idnumeral, c.nro_correlativo, 
	vt.idtabla as idtexto, '[tabla]' as contenido, 0 as esglobal, vt.descripcion, 
		0 as eslista, 0 as lineas, 0 as tipo, 
PATINDEX('%<<INCISOS,%', cl.contenido) as nro,	
PATINDEX('%'+idtabla+'%', c.contenido) as nrocla
FROM rel_cc r, numeral c, var_tabla vt, clausula cl
WHERE r.idcontrato= $idcontrato AND r.idclausula=c.idclausula AND r.idclausula=cl.idclausula
AND c.contenido LIKE '%<<'+idtabla+',%'
ORDER BY r.posicion, nro";

	$query = consulta($sql);
	//$variables=array();
	
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		//buscamos si la variable tiene contenido del guardian
		$contenido = $row["contenido"];
		$nocambia='0';
		$esglobal = $row["esglobal"];
		if($contenido=='-') $contenido = '';
		
		$sw1='0';
		$descripcion = $row["descripcion"]; 
		foreach($variables as $svar){
			if($row["idtexto"] == $svar["idtexto"])
				$sw1='1';
		}
		if($sw1 == '0')
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
							'descripcion' => htmlentities($descripcion));  
	}
	*/
	//
	//
	unset($guardian_var);
//si no existe ya sesion para array principal, entonces lo creamos
if(!isset($_SESSION["principal"])){
	//creamos arreglo principal con todas las clausulas, numerales, y variables que deben estar
	// principal contiene toda la info para armar el contrato(clausulas sin variables tb)
	$principal = array();
	$i=0;
	//$nrotabla = 0;
	//recorremos clausulas elegidas
	foreach($clausulas as $valor){
		$id1 = $valor['id'];
		$id2 = $valor['idnumeral'];
		$existe = 0;
		//para esta clausula recorremos q variables tiene en la misma clausula, es decir idnumeral==null
		//y q variables tiene en sus incisos, si hay incisos
		foreach($variables as $key => $valor2){
			if($id1 == $valor2['id'] && ($id2 == $valor2['idnumeral'] || $valor2['idnumeral'] == '0') ){
				//vemos que clase de variable es                                                     && $valor2["descripcion"] != ''
				$valores=array();
				$myvalor = $valor2["contenido"];
			//	echo $myvalor ;
				if($myvalor!='[tabla]'){
					$sw=0;
					if($valor2["esglobal"]=='1' || $valor2["esglobal"]=='4'){
						if($valor2["esglobal"]=='4'){
							//aqui vemos si la variables de tipo representante
							foreach($representa as $rep){
								if($rep["idtexto"]==$valor2["idtexto"]){
									//si es representante
									$sw=1;
									$valores[]= array('id' => $rep["idtexto"],
												'valor' => $rep["nombre"]);
								}
							}
						}
						if($sw==0){
							//estos son valores para la lista
							$sql="SELECT idtexto, valor FROM var_texto_valores WHERE idtexto = '".$valor2["idtexto"]."' ORDER BY valor";
							$query = consulta($sql);
							while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
								$valores[]= array('id' => $row["idtexto"],
													'valor' => $row["valor"]);
							}
						}
						
					}
					//controlamos que el valor del contenido no cambie si ya tiene un contenido previo
					if($valor2["nocambia"]=='0' && $sw==0){
						if($valor2["esglobal"]=='2' || $valor2["esglobal"]=='4'){
							//estos son valores para recordar segun el usuario
							$sql="SELECT valor FROM  var_texto_recuerda WHERE idtexto = '".$valor2["idtexto"]."' AND usuario = '$glogin' and habilitado = 1";
							$query = consulta($sql);
							while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
								$myvalor = $row["valor"];
							}
						}
					}
				}else{
					//es tipo tabla, vemos cuantas col. tiene y el titulo de cada una
					$sql="SELECT idtabla, titulo, nrocolumna FROM campo_fila WHERE idtabla = '".$valor2["idtexto"]."' ORDER BY nrocolumna";
					$query = consulta($sql);
					$valores=array();
					while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
						$valores[] = array(	'idtabla' => $row["idtabla"],
											'titulo' => $row["titulo"],
											'nrocolumna' => $row["nrocolumna"]);
					}
					//para el ancho de las columnas
					$valor2["lineas"] = ceil(650 / count($valores));
					//$nrotabla++;
					//$smarty->assign('nrotabla',$nrotabla);
				}
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
							'doble' => $valor2["doble"],
							'ind' => $i);
				
				$i++;
				$existe = 1;
				$variables[$key]["idnumeral"] = -1;
			}
		}
	/*	if($existe==0){
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
		}*/
	}
	unset($valor);
	unset($valor2);
	//	echo "<pre>"; echo print_r($principal);	
		
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