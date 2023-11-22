<?php
	//ADICION DE PARTES (PERSONAS)

//recuperamos valores de las variables
$firmas=array();
if(isset($_SESSION["principal"])){
	$principal = $_SESSION["principal"];
	
	//echo "<pre>";
	//	print_r($principal); die;
	$sw=0;
	$sinduples = array(); 
	$glogin=$_SESSION['glogin'];
	$nrotabla = 0; //para el caso de q existan tablas
	//recorrer array principal e ir volcando en las variables su contenido
	foreach($principal as $key => $campo){
		//vemos si noes parte firmante
		if($campo["id"]!='0'){
			//ver si existe variable para este campo
			$variable = "data_".$campo["ind"];
			//si variable <> data_x entonces tiene contenido
			if($variable!='data_x'){
				//verificar que existe la variable
				if(isset($_REQUEST["$variable"])){
					//existe, guardamos el contenido de la variable en el mismo arreglo principal
					$principal[$key]["contenido"] = $_REQUEST["$variable"];
					//para caso de variables a recordar hacemos:
					//buscar variable en var_texto_recuerda, si no existe insertamos, si existe actualizamos
					$sql="SELECT valor FROM  var_texto_recuerda WHERE idtexto = '".$principal[$key]["idtexto"]."' AND usuario = '$glogin' AND habilitado = 1";
					$query = consulta($sql);
					$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
					//$myvalor = $row["valor"];
					if($row["valor"]==''){
						//insertamos primera vez
						$sql="INSERT INTO var_texto_recuerda (idtexto, valor, usuario, habilitado) 
						VALUES ('".$principal[$key]["idtexto"]."','".$principal[$key]["contenido"]."','$glogin',1)";
						ejecutar($sql);
					}else{
						//actualizamos solo si son diferentes
						if($row["valor"]!=$principal[$key]["contenido"]){
							$sql="UPDATE var_texto_recuerda SET valor='".$principal[$key]["contenido"]."' WHERE idtexto = '".$principal[$key]["idtexto"]."' AND usuario = '$glogin' and habilitado = 1";
							ejecutar($sql);
						}
					}
						$sinduples[] = array('idtexto'=>$principal[$key]["idtexto"], 
										'contenido'=>$principal[$key]["contenido"]);
				}else{//aqui se trataria de ua variable tipo tabla
					if($campo["titulo"]=='[tabla]'){
						//es tabla
						/*echo "<pre>";
						print_r($campo); //die;
						echo "</pre>";*/
						$nrotabla++;
						//armamos nombra de variables
						$cfil = "cantfilas".$nrotabla;
						$ccol = "cantcols".$nrotabla;
						//vemos cuantas filas y columnas tiene
						//validamos q exista la variable filas, en teoria siempre existe
						if(!isset($_REQUEST["$cfil"])){ echo "Variable tabla incorrecta!"; die();}
						$nfilas = $_REQUEST["$cfil"];
						$ncols  = $_REQUEST["$ccol"];
						//recorremos todas sus celdas
						//primero filas
						$matriz = array(); // contendra todas las celdas de la tabla
						for($i=1; $i<=$nfilas; $i++){
							//columnas
							$columnas = array();
							for($j=1; $j<=$ncols; $j++){
								$celda = 'campo'.$nrotabla.'_'.$i.$j;
								//veos el contenido de la celda
								if(isset($_REQUEST["$celda"]))
									$contenido = $_REQUEST["$celda"];
								else
									$contenido = $celda; //'undefined!';
								//si la celda esta vacia? por el momento no hacer nada
								$columnas[$j-1] = $contenido;
							}
							$matriz[$i-1]=$columnas;
							unset($columnas);
						}
						//guardamos el arreglo de la tabla en principal TODO
						$principal[$key]["contenido"] = $matriz;
						$principal[$key]["titulo"] = '[tabla]';
					}else{
						//aqui seguramente habia una variable duplicada, 
						//la dejamos tal cual y al final hacemos la validacion
						$sw=1;
					}
				}
			}
		}else{
			$firmas[]=$key;
		}
	}
	
}else{
	die("MOD: No pasa el arreglo principal");
}

//ver si existe alguna var duplicada 5dic2013
if($sw==1){
	//validamos variables vacias que son duplicadas
	foreach($sinduples as $campo1){
		foreach($principal as $key => $campo2){
			if($campo2["idtexto"]==$campo1["idtexto"]){
				if($campo2["contenido"]=='__duple__'){
					$principal[$key]["contenido"]=$campo1["contenido"];
				}
			}
		}
	}
}

unset($sinduples);
//actualizamos variables para las firmas
//recorremos los q son firma, y buscaos en principal para ver su nuevo contenido
foreach($firmas as $indice){
	foreach($principal as $key => $campo){
		if($principal[$key]["idtexto"] == $principal[$indice]["idtexto"] && $principal[$key]["id"] !='0'){
			//es la var buscada
			$principal[$indice]["contenido"] = $principal[$key]["contenido"];
			break;
		}
	}
}

//actualizamos la variable de sesion
$_SESSION["principal"] = $principal;
$idcontrato = $_SESSION['idcontrato'];
//determinar si existen Partes
	$sql="SELECT COUNT(c.idclausula) AS partes
                FROM clausula c, rel_cc rcc
                WHERE rcc.idcontrato= $idcontrato AND c.idclausula=rcc.idclausula AND (c.contenido like '%<<partes,2>>%' or c.contenido like '%<<partes3,2>>%') 
                UNION
                SELECT COUNT(n.idclausula) as partes
                FROM numeral n, rel_cc rcc
                WHERE rcc.idcontrato = $idcontrato AND n.idclausula = rcc.idclausula AND (n.contenido like '%<<partes,2>>%' or n.contenido like '%<<partes3,2>>%') ";
	$query = consulta($sql);
	$expedido=array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)) $i += $row["partes"];
	
	if($i==0){
		//el contrato no tiene partes, pasamos directamente a elaborar contrato
		include("./contratos/modarmar.php");
		die();
	}

	//vemos si ya se seleccionaron partes
	$cantidad=0;
	if(isset($_SESSION["partes"])){
		$partes = $_SESSION["partes"];
		$cantidad = count($partes);

	}else $partes=array();
	
	$smarty->assign('partes',$partes);
	$smarty->assign('cantidad',$cantidad);

	$idcontrato = $_SESSION['idcontrato'];
	//recuperando los lugares de emision
	$sql= "SELECT * FROM expedido ORDER BY descripcion ";
	$query = consulta($sql);
	$i=0;
	$expedido=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$expedido[$i]= array('id' => $row["codigo"],
							 'descri' => $row["descripcion"]);
		$i++;
	}
	$smarty->assign('expedido',$expedido);
	
	//recuperando los tipos de indentificacion
	$sql= "SELECT * FROM tipo_documento ORDER BY descripcion ";
	$query = consulta($sql);
	$i=0;
	$tipodocs=array();
	$elNit = '0';
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipodocs[$i]= array('id' => $row["codigo"],
									'descri' => $row["descripcion"]);
		if(trim($row["descripcion"])=='NIT') $elNit = $row["codigo"];
		$i++;
	}
	$smarty->assign('tipodocs',$tipodocs);
	$smarty->assign('elNit',$elNit);
	
	//recuperando los paises
	$sql= "SELECT * FROM pais ORDER BY descripcion ";
	$query = consulta($sql);
	$i=0;
	$paises=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$paises[$i]= array('id' => $row["codigo"],
							'descri' => $row["descripcion"]);
		$i++;
	}
	$smarty->assign('paises',$paises);
	
	//recuperando los tipos rol
	$sql= "SELECT ca.* FROM calidad ca INNER JOIN contratocalidad cc ON cc.idcalidad=ca.codigo WHERE cc.idcontrato='$idcontrato' ORDER BY ca.calidad ";
	$query = consulta($sql);
	$i=0;
	$calidad=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$calidad[$i]= array('id' => $row["codigo"],
							'descri' => $row["calidad"],
							'obliga' => '' );
		$i++;
	}
	
	//cuales de estas calidades estan como variable calidad
	$idfinal = $_SESSION['idfinal'];
	$sql= "SELECT ca.calidad, PATINDEX('%&lt;&lt;'+ca.calidad+'%', cf.contenido_sec) as nro FROM contrato_final cf, calidad ca 
WHERE cf.idfinal= $idfinal   
AND cf.contenido_sec like '%,9&gt;&gt;%'  
AND  PATINDEX('%&lt;&lt;'+ca.calidad+'%', cf.contenido_sec) > 0";
	
	$query = consulta($sql);
	//echo $sql;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		foreach($calidad as $key => $valor){
			if(trim($valor["descri"])==trim($row["calidad"])){
				$calidad[$key]["obliga"]='*';
			}
		}
	}
	//echo "<pre>";
	//print_r($calidad);
	$smarty->assign('calidad',$calidad);
	$smarty->display('contratos/modpartes2.html');
	die();
	
?>