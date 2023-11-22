<?php
//session_start();
/************************************************************************************/
/************************************************************************************/


require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');

//vemos cual categoria vamos aprocesar
if(isset($_REQUEST['cat'])){
	$cat = $_REQUEST['cat'] ;
	$_SESSION['cat'] = $cat ;
}else{
	$cat = '0';
	if(isset($_SESSION['cat']))
		$cat = $_SESSION['cat'];
	else
		$_SESSION['cat'] = $cat ;
}
	
	//cargando para el overlib
	include("../lib/cargar_overlib.php");
	/*********************************operaciones de la ventana*************/
	/*********************************operaciones de la ventana*************/
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("tipos_bien/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("tipos_bien/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("tipos_bien/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("tipos_bien/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("tipos_bien/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("tipos_bien/eliminando.php");
	}
	
	//documentos del tipo de bien
	if(isset($_REQUEST['documentos'])){
		include("tipos_bien/documentos.php");
	}
	
	//guardando los documentos del tipo de bien
	if(isset($_REQUEST['guardar_documentos'])){
		include("tipos_bien/guardar_documentos.php");
	}
	
	//documentos a mostrar en la impresion del tipo de bien
	if(isset($_REQUEST['documentos_imp'])){
		include("tipos_bien/documentos_imp.php");
	}
	//guardando los documentos a imprimir del tipo de bien
	if(isset($_REQUEST['guardar_documentos_imp'])){
		include("tipos_bien/guardar_documentos_imp.php");
	}
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	$sql = "SELECT tb.id_tipo_bien, tb.descripcion, tb.tipo_bien, 
		tb.con_inf_legal, tb.bien, ba.codigo, ba.banca
		FROM tipos_bien tb 
		LEFT JOIN bancas ba ON ba.id_banca = tb.id_banca 
		WHERE tb.categoria = $cat ORDER BY ba.codigo, tb.tipo_bien";
		//echo $sql;
	$query= consulta($sql);
	$i=0;
	$tiposbien= array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if($row["bien"]=='1')
			$bien = 'Inmueble';
		elseif($row["bien"]=='3')
			$bien = 'Vehiculo';
		elseif($row["bien"]=='2')
			$bien = 'Maquinaria';
		elseif($row["bien"]=='4')
			$bien = 'Otros';
		elseif($row["bien"]=='5')
			$bien = 'P. Jur&iacute;dica';
		$tiposbien[$i]= array('id_tipo_bien' => $row["id_tipo_bien"],
		 'tipo_bien' => $row["tipo_bien"],
		 'descripcion' => $row["descripcion"],
		 'con_inf_legal' => $row["con_inf_legal"],
		 'codigo' => $row["codigo"],
		 'banca' => $row["banca"],
		 'bien' => $bien);
		
		$i++;
	}
	
	$smarty->assign('cat',$cat);
	$smarty->assign('tiposbien',$tiposbien);
	$smarty->display('adm/tipos_bien/tipos_bien.html');
	die();
	
?>
