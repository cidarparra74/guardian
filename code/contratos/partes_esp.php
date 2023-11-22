<?php
	//ADICION DE PARTES (PERSONAS)
if(isset($_SESSION["principal"])){
	$principal = $_SESSION["principal"];

	//recorrer array principal e ir verificando su contenido
	foreach($principal as $key => $campo){
		//ver si existe variable para este campo
		$variable = "data_".$campo["ind"];
		//si variable <> data_x entonces tiene contenido
		if($variable!='data_x'){
			//verificar que existe la variable
			if(isset($_REQUEST["$variable"])){
				//existe, vemos si el contenido cambio'!= $_REQUEST["$variable"]
			  if($principal[$key]["contenido"]=='' ){
				$principal[$key]["contenido"] = $_REQUEST["$variable"];
				//echo 'entrooo';
			  }
			}
		}
	}

}else{
	die("No pasa el arreglo principal");
}
	//echo $idcontrato;
		unset($link);
		require('../lib/conexionSEC.php');
		if($contrato =='' && $idcontrato != 0){
			$sql= "SELECT c.titulo FROM contrato c WHERE c.idcontrato=$idcontrato";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$contrato= $row["titulo"];
		}
//
//----------------------------------------------
$idcontrato = $_SESSION['idcontrato'];
$nrocaso = $_SESSION['nrocaso']; 	

//$idcontrato = $_SESSION['idcontrato'];
//determinar si existen Partes
	$sql="SELECT COUNT(c.idclausula) AS partes
                FROM clausula c, rel_cc rcc
                WHERE rcc.idcontrato= $idcontrato AND c.idclausula=rcc.idclausula AND (c.contenido like '%<<partes,2>>%' or c.contenido like '%<<partes3,2>>%') 
                UNION
                SELECT COUNT(n.idclausula) as partes
                FROM numeral n, rel_cc rcc
                WHERE rcc.idcontrato = $idcontrato AND n.idclausula = rcc.idclausula AND (n.contenido like '%<<partes,2>>%' or n.contenido like '%<<partes3,2>>%') ";
	//			echo htmlentities($sql);
	$query = consulta($sql);
	$expedido=array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)) $i += $row["partes"];
	
	if($i==0){
		//el contrato no tiene partes, pasamos directamente a elaborar contrato
		
		die('sin partes en contrato');
	}

	$cantidad=0;
	//---------------------echo $nrocaso;
		unset($link);
		require('../lib/conexionMNU.php');
	$sql = "SELECT TOP 1 enable_ws FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
			$smarty->assign('enable_ws',$enable_ws);
	
//------------------------------ revisar estoooo desactivamos por el momento con  && 1==2
	if($nrocaso!='0' && !isset($_SESSION["partes"]) ){
		
		if($enable_ws=='N'){ //es bisa
			$listaGar = array(); //no mover
			$encalidad = array();
			$partes=array();
			
			
			unset($link);
			require('../lib/conexionSEC.php');
		
			$sql = "SELECT re.ci, pr.expedido, re.relacion FROM relacion re
			LEFT JOIN persona pr ON re.ci = pr.ci
			WHERE re.nrocaso = '$nrocaso' ORDER BY re.relacion DESC";
			//cambiamos para solo leer el CI
			
		//	$sql = "SELECT re.ci, re.relacion FROM relacion re
		//	WHERE re.nrocaso = '$nrocaso' ORDER BY re.relacion DESC";
			$query = consulta($sql);
			//guardamos partes en $listagar aunq no solo son los garantes, el primero es deudor, garante 02
			while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
				$ci_cliente = $row['ci'];
				$emision = $row['expedido'];
				$listaGar[] = trim($ci_cliente).$emision;
				$encalidad[] = $row['relacion'];
			}
			
			$smarty->assign('encalidad',$encalidad);
			unset($link);
			require('../lib/conexionMNU.php');
		
		}else{
			
			//JALAMOS EL DEUDOR PRINCIPAL POR DEFECTO
			$listaGar = array(); //no mover
			$partes=array();
			$sql = "SELECT il.ci_cliente, pr.emision FROM informes_legales il
			LEFT JOIN propietarios pr ON il.id_propietario = pr.id_propietario
			WHERE il.nrocaso = '$nrocaso'";
			//echo $sql;
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$ci_cliente = $row['ci_cliente'];
			if($ci_cliente!=''){
					//existe, leemos datos
					$emision = $row['emision'];
					$listaGar[] = $ci_cliente.$emision;
				
			}elseif($enable_ws=='A'){
					//debe ser un numero sin i.l. jalamos del ws.
					//con el $nrocaso jalamos nro de ci getNumeroIdentificacion
					// con el nro de ci jalamos nombre, direccion  getNombreCliente
					require_once("ws_nrocaso_ci.php");
					if($documento!=''){
						$listaGar[] = trim($documento);
						
					}
			}
			
			if($enable_ws=='A'){
				// jalamos los garantes en listagar
				// 
				require_once("ws_garantes.php");
				// 
			}elseif($enable_ws=='C'){
				require_once("ws_garantes_cidre.php");
			}
		}
		
		if($enable_ws=='N'){ //bisa
			unset($link);
			require('../lib/conexionSEC.php');
		}
		
		foreach($listaGar as $valor){
			$ci_cliente = trim($valor);
			//jalamos datos personales $idx => 
			if($enable_ws=='A'){
				require("ws_cliente_baneco2.php");
			}elseif($enable_ws=='C'){
				require("ws_cliente_cidre.php");
				$nombre = $nombres;
			}elseif($enable_ws=='N'){
				/// BISA aqui  leer datos de propietarios segun CI
				
		
				$sql = "SELECT nombre, domicilio , profesion, edocivil, expedido FROM persona 
				WHERE RTRIM(ci)+expedido = '$ci_cliente' ";
				$query = consulta($sql);
				$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
					$nombre = $row['nombre'];
					$direccion = $row['domicilio'];
					$profesion = $row['profesion'];
					$estadocivil = $row['edocivil'];
					$emision = $row['expedido'];
				
			}
			/* $nombre 
			   $direccion 
			   $profesion 
			   $estadocivil */
			$redaccion = "mayor de edad, estado civil $estadocivil de nacionalidad Boliviana profesión u ocupación $profesion, hábil por derecho, con domicilio en $direccion, ";
			$parrafo = '';
			$partes[] = array( 'ci' => trim($valor),
								'emi' => $emision,   
								'tipo' => '1',
								'procede' => 'Boliviana',
								'pais' => '1',
								'ocupa' => $profesion,
								'direc' => $direccion,
								'eciv' => $estadocivil, 
								'rol' => '0',  
								'nombre' => $nombre,  
								'redaccion' => $redaccion,  
								'parrafo' => $parrafo); 
			$cantidad++;
		}
		unset($link);
		require('../lib/conexionSEC.php');
		
	}
//	 $principal = $_SESSION["principal"] ;
	//buscamos si hay variables en partes firmantes
	$sql="SELECT var_texto.* FROM contrato c , var_texto
WHERE c.idcontrato= $idcontrato AND c.partesfirmantes like '%<<'+idtexto+',%'";
	$query = consulta($sql);
	
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	/// hay que buscar estas variables en principal, 
	// si existen clonar con id clausula = 0
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
				}else{				
					$temporal = $principal[$existe-1];
					$temporal['id'] = '0';
					$temporal['esglobal'] = '4'; 
					$principal[] = $temporal;

				}
	}
	//actualizamos la variable de sesion
$_SESSION["principal"] = $principal;
	/*	echo '<pre>';
print_r($listaGar);
echo '</pre>';

echo '<pre>';
print_r($partes);
echo '</pre>';
*/
//	$_SESSION["partes"] = $partes;
	$cantidad = count($partes);
	//echo $cantidad;
	$smarty->assign('partes',$partes);
	$smarty->assign('cantidad',$cantidad);

	//$idcontrato = $_SESSION['idcontrato'];

	//recuperando los tipos rol
	$sql= "SELECT ca.* FROM calidad ca INNER JOIN contratocalidad cc ON cc.idcalidad=ca.codigo WHERE cc.idcontrato='$idcontrato' ORDER BY ca.calidad ";
	$query = consulta($sql);
	$i=0;
	$calidad=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$calidad[$i]= array('id' => $row["codigo"],
									'descri' => $row["calidad"]);
		$i++;
	}
	$smarty->assign('calidad',$calidad);
	
	$smarty->display('contratos/partes_esp.html');
	die();
	
?>