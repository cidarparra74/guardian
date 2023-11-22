<?php
///////////////////////  verificar que esta opcion solo sea para BSOL
	require_once("../lib/setup.php");
require_once('../lib/verificar.php');
	$smarty = new bd;

//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	$carpeta_entrar="_main.php?action=elaborar_contrato.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	
if(isset($_REQUEST['id'])){
	require_once("ver_informe_legal/excepcion_imprime.php");
	die();
}
if(isset($_REQUEST['del'])){
	require_once("ver_informe_legal/eliminar_contrato.php");
	//die();
}

if(isset($_REQUEST["chkver"])){
		$opciones = $_REQUEST['opcion'];
		if(count($opciones)>0){
		//Hay al menos una opcion marcada 
			foreach($opciones as $indice => $valor) {
				$sql="UPDATE ncaso_cfinal SET idfinal = '-1' WHERE nrocaso = '$valor'";
				ejecutar($sql);
			} 
			//$alert="El o los n&uacute;meros han sido rechazados";
		}
}
//si se presiona el boton de buscar
	if(isset($_REQUEST['buscar_boton'])){
		$id_oficina= $_REQUEST['id_oficina'];
	}else{
		$id_oficina= '*';
	}
	$smarty->assign('id_oficina',$id_oficina);
	
//vemos si hay nrocaso para BANECO
if(isset($_REQUEST['nrocaso'])){


	//aprobar
		include("./ver_informe_legal/contratoelab.php");
	


}
	
//buscar todos los nrocaso existentes en informes_legales y 
	//que no esten en tabla NCASO_CFINAL(NROCASO, IDFINAL)
	$id_us_actual = $_SESSION["idusuario"];
	$id_almacen = $_SESSION["id_almacen"];
	
	//oficinas del almacen
	$sql= "SELECT *	FROM oficinas ofi 
			WHERE ofi.id_almacen = '$id_almacen' ORDER BY nombre";

	$oficinas= array();
	$query = consulta($sql);

	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$oficinas[]= array('id'=>$row["id_oficina"], 'nombre'=>$row["nombre"]);
	}
	$smarty->assign('oficinas',$oficinas);
	
	if($id_oficina != "*"){	
		$armar_consulta =" AND ofi.id_oficina='$id_oficina' ";
	}else{
		$armar_consulta ="";
	}
	//usuario guardian en sesion   ---, ile.ci_cliente
	//$tipoope = '0';
	//bsol: el nrocaso es cuenta en ncaso_cfinal
	$sql= "SELECT DISTINCT convert(int,ile.nrocaso) nro, ile.cliente, 
	nc.numerolinea, nc.importeprestamo, ile.id_informe_legal
	FROM informes_legales ile 
	INNER JOIN usuarios us ON us.id_usuario = ile.id_us_comun 
	INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
	INNER JOIN ncaso_cfinal nc ON ile.id_informe_legal = nc.id_informe
	WHERE nc.idfinal='0'
		AND ofi.id_almacen = '$id_almacen' $armar_consulta";
//AND ile.instancia<>''
	$query = consulta($sql);
	$nrocasos= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$id = $row["id_informe_legal"];
		$sql= "SELECT count(*) as cant FROM excepciones WHERE id_informe_legal = '$id'";
		$query2 = consulta($sql);
		$row2= $query2->fetchRow(DB_FETCHMODE_ASSOC);
		if($row2["cant"]>0)
			$tipoope = '1';
		else
			$tipoope = '0';
		$nrocasos[] = array('nrocaso'=>$row["nro"],
							'cliente'=>$row["cliente"],
							'id'=>$id,
							'tipoope'=>$tipoope);
	}
	//numeros sin informe legal
	/*
	$sql= "SELECT DISTINCT convert(int,nrocaso) nro,  
	numerolinea, importeprestamo
	FROM ncaso_cfinal  
	WHERE idfinal='0' AND nrocaso NOT IN (
		SELECT instancia 
		FROM informes_legales) ORDER BY nro";
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$nrocasos[] = array('nrocaso'=>$row["nro"],
							'cliente'=>"(Sin informe legal)",
							'tipoope'=>$tipoope);
	}
	*/
	$smarty->assign('nrocasos',$nrocasos);
	
	$smarty->display('elaborar_contrato.html');
	die();
	
?>