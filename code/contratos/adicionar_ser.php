<?php
	
	//SELECCION DE CLAUSULAS OPCIONALES
	
require_once("../lib/setup.php");
$smarty = new bd;
require_once('../lib/conexionSEC.php');
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//recuperando los parametros del contrato
	$sql= "SELECT servadic FROM parametros_c";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$idcontrato = $row["servadic"];
	
	
	$_SESSION["idcontrato"] = $idcontrato;
	$_SESSION["tipopersona"] = 'N';
	$_SESSION["tipo"] = 'U';
	
	
	//recuperando los datos del contrato
	$sql= "SELECT c.titulo, c.tipopersona
		 FROM contrato c WHERE c.idcontrato=$idcontrato ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$tipopersona = $row["tipopersona"];
	$titulo = $row["titulo"];
	
	
	$opcionales=array();
	$i=0;
	//VERIFICAMOS SI HAY servicios
	if($servicio == 'S'){
		$sql= "SELECT r.posicion, r.idclausula, c.descri as titulo, r.dependiente
				FROM rel_cc r, clausula c 
				LEFT JOIN vinculo v on v.vinculo = c.idclausula
				WHERE r.idcontrato = $idcontrato AND r.idclausula=c.idclausula AND r.opcional = 1 and v.idclausula = $servicios
				ORDER BY r.posicion";
		//		echo $sql;
		$query = consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$opcionales[]= array('id' => $row["idclausula"],
								'titulo' => htmlentities($row["titulo"],ENT_IGNORE),
								'marcado' => '',
								'dependiente' => $row["dependiente"]);
			if($row["dependiente"]!='S')
				$i++;
		}
	}
	//VERIFICAMOS SI HAY CLAUSULAS OPCIONALES
	$sql= "SELECT r.posicion, r.idclausula, c.descri as titulo, r.dependiente
			FROM rel_cc r, clausula c 
			LEFT JOIN vinculo v on v.vinculo = c.idclausula
			WHERE r.idcontrato = $idcontrato AND r.idclausula=c.idclausula AND r.opcional = 1 and v.idclausula is null
			ORDER BY r.posicion";
	//		echo $sql;
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$opcionales[]= array('id' => $row["idclausula"],
							'titulo' => htmlentities($row["titulo"],ENT_IGNORE),
							'marcado' => '',
							'dependiente' => $row["dependiente"]);
		if($row["dependiente"]!='S')
			$i++;
	}
	//$smarty->assign('opcionales',$opcionales);
	//$smarty->assign('tipopersona',$tipopersona);
	$_SESSION['contrato'] = $titulo  ;
	if($i>=1){
		$smarty->assign('opcionales',$opcionales);
		$smarty->assign('contrato',$titulo);
		//$smarty->assign('contrato',$contrato);
		//$smarty->assign('verpersona',$verpersona);
		$_SESSION['cantidad'] = $i;
		$smarty->display('contratos/adicionar2.html');
	}else{
		//no existen clausulas opcionales, pasamos directamente al paso 3
		$_SESSION['cantidad'] = 0;
		include("./contratos/adicionar3.php");
	}
	//$smarty->display('contratos/adicionar_pla.html');
	die();
	
?>
