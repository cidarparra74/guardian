<?php
//vemos que exista la sesion del contrato seleccionado
if(!isset($_SESSION['idcontrato'])){	die("No se definio contrato");}
	//recuperamos valores
	$idcontrato = $_SESSION['idcontrato'];
	$contrato = $_SESSION['contrato'];
	$opcional = $_SESSION['opcional'];  // las clausulas opcionales marcadas
	$incisos = $_SESSION['incisos'];  // los incisos marcados
	
	
	//seleccionamos todas las clausulas del contrato, mas las opcionales seleccionadas, mas los incisos seleccionados
	$sql="SELECT r.idclausula, cl.titulo, nu.idnumeral, nu.titulo as inciso 
FROM rel_cc r INNER JOIN clausula cl ON r.idclausula=cl.idclausula 
LEFT JOIN (SELECT IDCLAUSULA, nro_correlativo, idnumeral, titulo FROM numeral WHERE idnumeral IN ($incisos)) nu ON nu.idclausula=cl.idclausula 
WHERE r.idcontrato= 62 AND (r.opcional=0 OR cl.idclausula IN ($opcional))  ORDER BY r.posicion, nu.nro_correlativo";
	$query = consulta($sql);
	//echo $sql;
	$clausulas=array();
	//determinamos que incisos se marcaron
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

	
	//vemos las variables
	$sql="SELECT r.posicion,r.idclausula, c.idnumeral, c.nro_correlativo, var_texto.* 
FROM rel_cc r,numeral c , var_texto
WHERE r.idcontrato= $idcontrato AND r.idclausula=c.idclausula 
AND c.contenido like '%<<'+idtexto+',%'
UNION
SELECT r.posicion,r.idclausula,0,0, var_texto.*  
FROM rel_cc r,clausula c , var_texto
WHERE r.idcontrato= $idcontrato AND r.idclausula=c.idclausula 
AND c.contenido like '%<<'+idtexto+',%'
order by r.posicion";
//echo $sql;
	$query = consulta($sql);
	$variables=array();
	//determinamos que incisos se marcaron
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		//todas las variables por clausula e inciso
		$variables[]= array('id' => $row["idclausula"],
							'idnumeral' => $row["idnumeral"],
							'nro_correlativo' => $row["nro_correlativo"],
							'idtexto' => $row["idtexto"],
							'contenido' => $row["contenido"],
							'esglobal' => $row["esglobal"],
							'lineas' => $row["lineas"],
							'eslista' => $row["eslista"],
							'tipo' => $row["tipo"],
							'descripcion' => $row["descripcion"]);
	}
//	echo "<pre>";
//	print_r($clausulas);
//	echo "</pre>";
//	die();
	//creamos arreglo principal con todas las clausulas, numerales, y variables que deben estar
	$principal = array();
	//recorremos clausulas elegidas
	foreach($clausulas as $valor){
		$id1 = $valor['id'];
		$id2 = $valor['idnumeral'];

		$existe = 0;
		//para esta clausula recorremos q variables tiene en la misma clausula, es decir idnumeral==null
		
		//para esta clausula recorremos q variables tiene en sus incisos, si hay
		foreach($variables as $key => $valor2){
			if($id1 == $valor2['id'] && ($id2 == $valor2['idnumeral'] || $valor2['idnumeral'] == '0')){
				//vemos que clase de variable es
				$valores=array();
				$myvalor = $valor2["contenido"];
				if($valor2["esglobal"]=='1'){
					//estos son valores para la lista
					$sql="SELECT idtexto, valor FROM  var_texto_valores WHERE idtexto = '".$valor2["idtexto"]."' ORDER BY valor";
					$query = consulta($sql);
					while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
						$valores[]= array('id' => $row["idtexto"],
											'valor' => $row["valor"]);
					}
				}else{
					if($valor2["esglobal"]=='2'){
						//estos son valores para recordar
						$sql="SELECT valor FROM  var_texto_recuerda WHERE idtexto = '".$valor2["idtexto"]."' AND usuario = 'admin' and habilitado = 1";
						$query = consulta($sql);
						while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
							$myvalor = $row["valor"];
						}
					}
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
							'descripcion' => $valor2["descripcion"],
							'valores' => $valores);
				$existe = 1;
				$variables[$key]c = -1; //para que no lo tome en cuenta en el sigte ciclo
			}
		}
		if($existe==0)
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
							'descripcion' => '');
		
	}
	unset($valor);
	unset($valor2);
	
	//determinar si existen Partes
	$sql="SELECT COUNT(c.idclausula) AS partes
                FROM clausula c, rel_cc rcc
                WHERE rcc.idcontrato= $idcontrato AND c.idclausula=rcc.idclausula AND (c.contenido like '%<<partes,2>>%' or c.contenido like '%<<partes3,2>>%') 
                UNION
                SELECT COUNT(n.idclausula) as partes
                FROM numeral n, rel_cc rcc
                WHERE rcc.idcontrato = $idcontrato AND n.idclausula = rcc.idclausula AND (n.contenido like '%<<partes,2>>%' or n.contenido like '%<<partes3,2>>%')";
	

	$smarty->assign('principal',$principal);
	$smarty->assign('clausulas',$clausulas);
	//$smarty->assign('idcontrato',$idcontrato);
	$smarty->assign('contrato',$contrato);
	//$smarty->assign('opcional',$opcional);
	$smarty->display('contratos/adicionar5.html');
	die();
	
?>