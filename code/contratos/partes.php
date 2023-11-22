<?php
	//ADICION DE PARTES (PERSONAS)

//recuperamos valores de las variables
if(isset($_SESSION["principal"])){
	$principal = $_SESSION["principal"];
	
	$glogin=$_SESSION['glogin'];
	$duplicados = array(); //para casos en q se duplica la var
	$nrotabla = 0; //para el caso de q existan tablas
	//recorrer array principal e ir volcando en contenido las variables del FORM
	foreach($principal as $key => $campo){
		//ver si existe variable para este campo
		$variable = "data_".$campo["ind"];
		//si variable <> data_x entonces tiene contenido
		if($variable!='data_x'){
			//verificar que existe la variable
			if(isset($_REQUEST["$variable"])){
				//existe, guardamos el contenido de la variable en el mismo arreglo principal
				$principal[$key]["contenido"] = $_REQUEST["$variable"];
				//para caso de variables a recordar hacemos:
			  if($campo["esglobal"]=='2' || $campo["esglobal"]=='4'){
				//buscar variable en var_texto_recuerda, si no existe insertamos, si existe actualizamos
				$sql="SELECT valor FROM  var_texto_recuerda WHERE idtexto = '".$principal[$key]["idtexto"].
						"' AND usuario = '$glogin' and habilitado = 1";
				$query = consulta($sql);
				$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
				//echo $sql;
				if($row["valor"]==''){
					//insertamos primera vez
					$sql="INSERT INTO var_texto_recuerda (idtexto, valor, usuario, habilitado) 
					VALUES ('".$principal[$key]["idtexto"]."','".$principal[$key]["contenido"]."','$glogin',1)";
					ejecutar($sql);
				}else{
					//actualizamos solo si son diferentes
					if($row["valor"]!=$principal[$key]["contenido"]){
						$sql="UPDATE var_texto_recuerda SET valor='".$principal[$key]["contenido"]."' WHERE idtexto = '".
								$principal[$key]["idtexto"]."' AND usuario = '$glogin' and habilitado = 1";
						ejecutar($sql);
					}
				}
			  }
			}else{//aqui se trataria de ua variable duplicada o tipo tabla
				if($campo["contenido"]=='[tabla]'){
					//es tabla
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
					//armamos un arreglo temporal de duplicaos
					$duplicados[] = $principal[$key];
				}
			}
		}
	}
	// ver que variables se repiten
	// 
	// las variables repetidas son las que tienen descripcion vacio e idtexto no vacio
	foreach($duplicados as $duple){
		//lo buscamos en principal
		foreach($principal as $campo){
			if($duple["idtexto"] == $campo["idtexto"] && $campo["descripcion"] != ''){
				//copiamos el contenido
				$i1 = $duple["ind"];
				$principal[$i1]["contenido"]= $campo["contenido"];
				break;
			}
		}
	}
}else{
	die("No pasa el arreglo principal");
}

//----------------------------------------------
$idcontrato = $_SESSION['idcontrato'];
	//buscamos si hay variables en partes firmantes
	$sql="SELECT var_texto.* FROM contrato c , var_texto
WHERE c.idcontrato= $idcontrato AND c.partesfirmantes like '%<<'+idtexto+',%'";
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	/// hay que buscar estas variables en principal, 
	// si existen clonar y no pedir, si no existen pedir por pantalla
				$existe = 0;
				foreach($principal as $key => $valorp){
					if($row["idtexto"]==$principal[$key]['idtexto'] ){
						//existe, clonamos
						$existe = $key+1;
					}
				}
				$valores=array();
				if($existe == 0){
					//no existe, habra que pedir.... ver condiciones
					/*
					if($row["esglobal"]=='1' || $row["esglobal"]=='4'){
						//estos son valores para la lista
						$sql="SELECT idtexto, valor FROM var_texto_valores WHERE idtexto = '".$row["idtexto"]."' ORDER BY valor";
						$query2 = consulta($sql);
						while($row2= $query2->fetchRow(DB_FETCHMODE_ASSOC)){
							$valores[]= array('id' => $row2["idtexto"],
												'valor' => $row2["valor"]);
						}
					}
					$myvalor = $row["contenido"];
					if($row["esglobal"]=='2' || $row["esglobal"]=='4'){
						//estos son valores para recordar segun el usuario
						$sql="SELECT valor FROM  var_texto_recuerda WHERE idtexto = '".$row["idtexto"]."' AND usuario = '$glogin' and habilitado = 1";
						$query2 = consulta($sql);
						while($row2= $query2->fetchRow(DB_FETCHMODE_ASSOC)){
							$myvalor = $row2["valor"];
						}
					}
					$principal[] = array('id' => '0',
						'titulo' => '',
						'idnumeral' => '0',
						'inciso' => '0',
						'nro_correlativo' => '0',
						'idtexto' => $row["idtexto"],
						'contenido' => $myvalor ,
						'esglobal' => '1',
						'lineas' => $row["lineas"],
						'eslista' => '0',
						'tipo' => $row["tipo"],
						'nocambia' => '0',
						'descripcion' => $row["descripcion"],
						'valores' => $valores,
						'ind' => $i);
					*/
				}else{				
					$temporal = $principal[$existe-1];
					$temporal['id'] = '0';
				//	$temporal['esglobal'] = '1';
				//	$temporal['descripcion'] = '';
					$temporal['esglobal'] = '4'; 
					$principal[] = $temporal;

				}
			//	$i++;
	}
	
//actualizamos la variable de sesion
$_SESSION["principal"] = $principal;
$tipopersona = $_SESSION["tipopersona"];  //si es natural o juridica
$idcontrato = $_SESSION['idcontrato'];
	//---------------------------
	//recuperando tipo persona mayor o menor de edad o juridica (este ultimo no aplica aun)
	$sql= "SELECT c.tipopersona FROM contrato c WHERE c.idcontrato=$idcontrato ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$edad = $row["tipopersona"];
	
//para controlar personas unipersonal o colectiva
$tipo = $_SESSION["tipo"] ;
//recuperando los datos del contrato
	
// cuantas personas pedir??
$permin = 1;
$permax = 0;
// si es mayor de edad y  unipersonal solo uno
if ($edad == 'N' and $tipo == 'U') {
	$permin = 1;
	$permax = 1;
}
// si es mayor de edad y colectivo, al menos dos
if ($edad == 'N' and $tipo != 'U'){ 
	$permin = 2;
	$permax = 0;
}

// si es menor de edad y unipersonal, dos
if ($edad == 'M' and $tipo == 'U') {
	$permin = 2;
	$permax = 2;
}

// si es menor de edad y colectivo, al menos tres
if ($edad == 'M' and $tipo != 'U') {
	$permin = 3;
	$permax = 0;
}

$smarty->assign('permin',$permin);
$smarty->assign('permax',$permax);
				
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
		include("./contratos/armar.php");
		die();
	}
	// HAY NRO DE CASO baneco 
	if(isset($_SESSION["nrocaso"]))
		$nrocaso = $_SESSION["nrocaso"];
	else 
		$nrocaso='0';
	$partes=array();
		//------------------------------ revisar estoooo desactivamos por el momento con  && 1==2
	if($nrocaso!='0' && !isset($_SESSION["partes"]) ){
		//JALAMOS EL DEUDOR PRINCIPAL POR DEFECTO
		$listaGar = ''; //no mover
		unset($link);
		require('../lib/conexionMNU.php');
		//para ver si es baneco o bisa
		$sql = "SELECT TOP 1 enable_ws FROM opciones";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$enable_ws = $row["enable_ws"];
		//
		//no encontrara en sec por q no guarda antes
			//buscamos en guardian
		$sql = "SELECT il.ci_cliente, pr.emision, pr.nombres, pr.direccion, pr.emision, 
		pr.estado_civil, pr.id_tipo_identificacion, pr.nacionalidad, pr.pais, pr.profesion
		FROM informes_legales il
		LEFT JOIN propietarios pr ON il.id_propietario = pr.id_propietario
		WHERE il.nrocaso = '$nrocaso'";
		$query = consulta($sql);
		$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
		$ci_cliente = $row['ci_cliente'];
		if($ci_cliente!=''){
				//existe, leemos datos
				$nombres = $row['nombres'];
				$direccion = $row['direccion'];
				$emision = $row['emision'];
				$estado_civil = $row['estado_civil'];
			//	unset($link);
			//	require('../lib/conexionSEC.php');
			//	$sql2= "SELECT * FROM expedido WHERE descripcion = '$emision'";
			//	$query2 = consulta($sql2);
			//	$row2= $query2->fetchRow(DB_FETCHMODE_ASSOC);
			//	$emi = $row2["codigo"];   
				$smarty->assign('nombres',$nombres);
				$smarty->assign('ci_cliente',$ci_cliente);
				$smarty->assign('direccion',$direccion);
				//$smarty->assign('ci',$ci_cliente);
				$smarty->assign('emi',$emision);
				$smarty->assign('ecivil',$estado_civil);
				$redaccion = '';
			/*	$partes[] = array( 'ci' => trim($ci_cliente),
								'emi' => $emision,   
								'tipo' => $row['id_tipo_identificacion'],
								'procede' => $row['nacionalidad'],
								'pais' => $row['pais'],
								'ocupa' => $row['profesion'],
								'direc' => $row['direccion'],
								'eciv' => $row['estado_civil'], 
								'rol' => '0',  
								'nombre' => $row['nombres'],  
								'redaccion' => $redaccion,  
								'parrafo' => '');
								*/
	//		}
		}else{
			if($enable_ws=='A'){
				//debe ser un numero sin i.l. jalamos del ws.
				//con el $nrocaso jalamos nro de ci getNumeroIdentificacion
				// con el nro de ci jalamos nombre, direccion  getNombreCliente
				require_once("ws_nrocaso_ci.php");
				if($documento!=''){
					$listaGar = array();
					$listaGar[0] = trim($documento);
				}
			}	//falta cidre
			
		}
		//jalamos los garantes
		if($enable_ws=='A'){
			//$valor=$nrocaso;
			require_once("ws_garantes.php");
		}elseif($enable_ws=='C'){
			require_once("ws_garantes_cidre.php");
		}
		$smarty->assign('listaGar',$listaGar);
		unset($link);
		require('../lib/conexionSEC.php');
	}else{
				$smarty->assign('nombres','');
				$smarty->assign('direccion','');
				$smarty->assign('ci','');
				$smarty->assign('emi','0');
				$smarty->assign('ecivil','-');
	}
	
	//vemos si ya se seleccionaron partes
	$cantidad=0;
	if(isset($_SESSION["partes"])){
		$partes = $_SESSION["partes"];
		$cantidad = count($partes);
	} //else $partes=array();
	
	$smarty->assign('partes',$partes);
	$smarty->assign('cantidad',$cantidad);

	$idcontrato = $_SESSION['idcontrato'];
	//recuperando los lugares de emision
	$sql= "SELECT * FROM expedido ORDER BY descripcion ";
	$query = consulta($sql);
	$i=0;
	$expedido=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if(trim($row["descripcion"])!='--'){ //esto por si el SEC pone su propio '--'
			$expedido[$i]= array('id' => $row["codigo"],
								'descri' => $row["descripcion"]);
			$i++;
		}
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
								'descri' => htmlentities($row["descripcion"]));
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
	$sql= "SELECT ca.* FROM calidad ca INNER JOIN contratocalidad cc ON cc.idcalidad=ca.codigo 
	WHERE cc.idcontrato='$idcontrato' ORDER BY ca.calidad ";
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
	$incisos = $_SESSION['incisos'];
	$opcional = $_SESSION['opcional'];
	$sql= "SELECT ca.calidad, PATINDEX('%<<'+ca.calidad+'%', c.contenido) as nro
	FROM rel_cc r, numeral c, calidad ca 
	WHERE r.idcontrato= $idcontrato AND r.idclausula=c.idclausula AND PATINDEX('%<<'+ca.calidad+'%', c.contenido) > 0
	AND c.contenido like '%,9>>%' AND  c.idnumeral in ($incisos)
	UNION
	SELECT ca.calidad, PATINDEX('%<<'+ca.calidad+'%', c.contenido) as nro FROM rel_cc r, clausula c, calidad ca 
	WHERE r.idcontrato= $idcontrato AND r.idclausula=c.idclausula AND PATINDEX('%<<'+ca.calidad+'%', c.contenido) > 0 
	AND c.contenido like '%,9>>%' AND (r.opcional = 0 or c.idclausula in ($opcional))";
	//echo $sql;
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		foreach($calidad as $key => $valor){
			if(trim($valor["descri"])==trim($row["calidad"])){
				$calidad[$key]["obliga"]='*';
			}
		}
	}
	
	$smarty->assign('calidad',$calidad);
	
	$contrato = $_SESSION['contrato'];
	$smarty->assign('contrato',$contrato);
	
	if($tipopersona != ''){
		$smarty->assign('tipopersona',$tipopersona);
		$smarty->assign('tipo',$tipo);
		$smarty->display('contratos/partes3.html');
	}else
		$smarty->display('contratos/partes2.html');
	die();
	
?>