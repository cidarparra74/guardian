<?php
	
	//SELECCION DE CLAUSULAS OPCIONALES
	
	// leemos valores 
	$idcontrato = $_REQUEST['idcontrato'];
	$tipo = $_REQUEST['tipo'];  //Unipersonal, Conjunto, Indistinto
	$servicio = $_REQUEST['servicio'];
	//echo $servicio;
	$combinacion = "";
	
	if ($tipo == 'U') $combinacion = "UNIPERSONAL";
	elseif ($tipo == 'I') $combinacion = "INDISTINTO";
	elseif ($tipo == 'C') $combinacion = "CONJUNTO";
	if ($servicio == 'S') $combinacion .= " CON SERVICIOS ADICIONALES";
	elseif ($servicio == 'N') $combinacion .= " SIN SERVICIOS ADICIONALES";
	$_SESSION["idcontrato"] = $idcontrato;
	$_SESSION["tipopersona"] = 'N';
	$_SESSION["servicio"] = $servicio;
	$_SESSION["tipo"] = $tipo;
	
	//recuperando los datos del contrato
	$sql= "SELECT c.titulo, c.tipopersona
		 FROM contrato c WHERE c.idcontrato=$idcontrato ";
	
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$tipopersona = $row["tipopersona"];
	$titulo = $row["titulo"];
	//recuperando los parametros del contrato
	$sql= "SELECT servicios FROM parametros_c";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//la clausula de servicios adicionales pa ca y cc
	$servicios = $row["servicios"];
	
	
	$opcionales=array();
	$i=0;
	//VERIFICAMOS SI HAY servicios
	if($servicio == 'S'){
		//seleccionamos clausula con cta col. y sin cta col
		$sql= "SELECT servtarindi, servtarjeta FROM parametros_c";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		//verificamos si es indistinto
		if($tipo == 'I')
			$excluir = $row["servtarjeta"];
		else
			$excluir = $row["servtarindi"];
			
		$sql= "SELECT r.posicion, r.idclausula, c.descri as titulo, r.dependiente
				FROM rel_cc r
				INNER JOIN clausula c ON r.idclausula=c.idclausula 
				LEFT JOIN vinculo v on v.vinculo = c.idclausula AND v.idcontrato = $idcontrato
				WHERE r.idcontrato = $idcontrato 
					AND r.opcional = 1 
					AND v.idclausula = $servicios
					AND r.idclausula <> $excluir
				ORDER BY r.posicion";
		//		echo $sql;
		$query = consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$opcionales[]= array('id' => $row["idclausula"],
								'titulo' => htmlentities($row["titulo"],ENT_IGNORE),
								'marcado' => '',
								'esserv' => '1');
			//if($row["dependiente"]!='S')
				$i++;
		}
	}
	//VERIFICAMOS SI HAY mas CLAUSULAS OPCIONALES
	$sql= "SELECT r.posicion, r.idclausula, c.descri as titulo, r.dependiente
			FROM rel_cc r, clausula c 
			LEFT JOIN vinculo v on v.vinculo = c.idclausula
			WHERE r.idcontrato = $idcontrato 
				AND r.idclausula=c.idclausula 
				AND r.opcional = 1 and v.idclausula is null
				AND r.dependiente <> 'S' 
			ORDER BY r.posicion";
	//		echo $sql;
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$opcionales[]= array('id' => $row["idclausula"],
							'titulo' => htmlentities($row["titulo"],ENT_IGNORE),
							'marcado' => '',
							'esserv' => '0');
			$i++;
	}

	$_SESSION['contrato'] = $titulo . ' ('.$combinacion.')' ;
	if($i>=1){
		$smarty->assign('opcionales',$opcionales);
		$smarty->assign('contrato',$_SESSION['contrato']);
		//$smarty->assign('contrato',$contrato);
		//$smarty->assign('verpersona',$verpersona);
		$_SESSION['cantidad'] = $i;
	//	$smarty->display('contratos/adicionar2.html');
	$smarty->display('contratos/adicionar_pla.html');
	}else{
		//no existen clausulas opcionales, pasamos directamente al paso 3
		$_SESSION['cantidad'] = 0;
		include("./contratos/adicionar3.php");
	}
	die();
	
?>
