<?php
/*
  para renumerar posicion de rel_cc en SEC
*/
require_once("../lib/setup.php");
$smarty = new bd;
require_once('../lib/verificar.php');
require_once('../lib/conexionSEC.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");

	$carpeta_entrar="_main.php?action=sec/contratos.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	
if(isset($_REQUEST['idc'])){
	//renumerando
	$idc = $_REQUEST['idc'];
	//leemos en el orden que este establecido actualmente:
	$sql = "SELECT * FROM rel_cc WHERE idcontrato = $idc ORDER BY posicion ";
	$query = consulta($sql);
	$xcnt = 0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$xcnt = $xcnt + 1;
		if($row["posicion"]!=$xcnt){
			$sqlEXE = "UPDATE rel_cc SET posicion = ".$xcnt.
			 " WHERE idcontrato = ".$row["idcontrato"].
			 " AND idclausula = ".$row["idclausula"];
			ejecutar($sqlEXE);
		}
	}
	
	$smarty->assign('ok','Ok.');

}else{

	$smarty->assign('ok','');
}

//************************************************************************************
		
		//vinculos
		if(isset($_REQUEST['idcv'])){
				require_once("sec/contratos/vincular.php");
		}
		//vinculos
		if(isset($_REQUEST['idci'])){
				require_once("sec/contratos/incisos.php");
		}
		//vinculos
		if(isset($_REQUEST['adicclau'])){
				require_once("sec/contratos/adicioncl.php");
		}
		
		//modificar
		if(isset($_REQUEST['modificar'])){
			include("sec/contratos/modificar.php");
		}
		
		//modificando
		if(isset($_REQUEST['modificar_boton'])){
			include("sec/contratos/modificando.php");
		}
		
		//modificar relacion de clausula
		if(isset($_REQUEST['modificacla'])){
			include("sec/contratos/modificacla.php");
		}
		
		//modificando
		if(isset($_REQUEST['modificacla_boton'])){
			include("sec/contratos/modificandocla.php");
		}
		
		//clausulas
		if(isset($_REQUEST['idcc'])){
				require_once("sec/contratos/clausulas.php");
		}
		
		
//************************************************************************************
	//mostramos todos los contratos
	$sql = "SELECT idcontrato, titulo FROM contrato ORDER BY titulo";
	$miscontratos= array();
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$miscontratos[]= array('id' => $row["idcontrato"],
							'titulo' => $row["titulo"]);
	}
	$smarty->assign('miscontratos',$miscontratos);
	$smarty->display('sec/contratos/contratos.html');
	die();

?>