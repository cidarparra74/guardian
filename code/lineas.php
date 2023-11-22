<?php

//*****require('setup.php');
require_once("../lib/setup.php");
$smarty = new bd;	
//require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="_main.php?action=lineas.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "lineas";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//filtro de la ventana
	$filtro_nombres='';
	$del_filtro="nada";
	if(isset($_REQUEST['buscar_boton'])){
		$filtro_nombres= $_REQUEST['filtro_nombres'];
		if($filtro_nombres!=''){
			$del_filtro= "WHERE nombres LIKE '%$filtro_nombres%' ";
			$_SESSION["filtro_nombres"]= $filtro_nombres;
		}
	}elseif(isset($_SESSION["filtro_nombres"])){
		$filtro_nombres= $_SESSION["filtro_nombres"];
		$del_filtro= "WHERE nombres LIKE '%$filtro_nombres%' ";
	}
	$smarty->assign('filtro_nombres',$filtro_nombres);
	

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("./lineas/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("./lineas/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("./lineas/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("./lineas/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("./lineas/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("./lineas/eliminando.php");
	}
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

if(isset($_REQUEST['id'])){
	$id = $_REQUEST['id'];
	unset($_SESSION["filtro_nombres"]);
	//leemos el propietario
	$sql= "SELECT ci, nombres FROM propietarios where id_propietario = '$id'";
	$query= consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('ci',$row["ci"]);
	$smarty->assign('nombres',$row["nombres"]);
	$smarty->assign('id',$id);
	//leemos sus lineas
	$lineas= array();
	$sql= "SELECT *, CONVERT(VARCHAR(10), fechaesc, 103) as fecha
	FROM lineas where id_propietario = '$id' ORDER BY numero";
	$query= consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if($row["tipo"]=='1') $tipo = 'Rotativa';
		else $tipo = 'Simple';
		$lineas[]= array('idl'=>$row["id_linea"],
		'escritura'=>$row["escritura"],
		'fecha'=> $row["fecha"],
		'notario'=> $row["notario"],
		'numero'=> $row["numero"],
		'importe'=> $row["importe"],
		'moneda'=> $row["moneda"],
		'tipo'=> $tipo);
	}
	$smarty->assign('vacio','');
	$smarty->assign('lineas',$lineas);

}else{
//viendo si es listado de clientes
	if($del_filtro != "nada"){
		//listamos clientes
		$listado= array();
		$sql= "SELECT TOP 50 id_propietario, ci, nombres FROM propietarios $del_filtro ORDER BY nombres";
		//echo $sql;
		$query= consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$listado[]= array('id'=>$row["id_propietario"],
					'ci'=>$row["ci"],
				'nombres'=> $row["nombres"]	);
		}
		if(!isset($listado[0]['id']))
			$smarty->assign('vacio','No se encontraron resultados.');
		else
			$smarty->assign('vacio','');
		$smarty->assign('listado',$listado);


	}
}		
	$smarty->display('lineas.html');
	die();

?>
