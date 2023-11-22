<?php

//*****require('setup.php');
require_once("../lib/setup.php");
$smarty = new bd;	
//require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	//href
	$carpeta_entrar="_main.php?action=propietarioseli.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "propietarios";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//filtro de la ventana
	if(isset($_REQUEST['v'])){
		$smarty->assign('v',$_REQUEST['v']);
	}else{
		$smarty->assign('v','n');
	}
	if(!isset($_REQUEST['filtro'])){
		$del_filtro= "nada";
		$_SESSION["arch_prop_filtro_nombres"]= "";
		$_SESSION["arch_prop_filtro_mis"]= "";
		$_SESSION["arch_prop_filtro_ci"]= "";
		
		$_SESSION["arch_filtro"]= "";
	}
	else{
		//$del_filtro=$filtro;
		$del_filtro= $_SESSION["arch_filtro"];
	}
	
	if(isset($_REQUEST['buscar_boton'])){
		$del_filtro="";
		$band=0;
		
		//nombres
		$aux=$_REQUEST['filtro_nombres'];
		if($aux != ""){
			if($band == 0){
				$del_filtro= "AND p.nombres LIKE '%$aux%' ";
				$band=1;
			}
		}//fin de nombres
		
		//mis
		$aux=$_REQUEST['filtro_mis'];
		if($aux != ""){
			if($band == 0){
				$del_filtro= "AND p.mis LIKE '%$aux%' ";
				$band=1;
			}
			else{
				$del_filtro= $del_filtro."AND p.mis LIKE '%$aux%' ";
			}
		}//fin de mis
		
		//ci
		$aux=$_REQUEST['filtro_ci'];
		if($aux != ""){
			if($band == 0){
				$del_filtro= "AND p.ci LIKE '%$aux%' ";
				$band=1;
			}
			else{
				$del_filtro= $del_filtro."AND p.ci LIKE '%$aux%' ";
			}
		}//fin de ci
		

		$filtro_nombres= $_REQUEST['filtro_nombres'];
		$filtro_mis= $_REQUEST['filtro_mis'];
		$filtro_ci= $_REQUEST['filtro_ci'];
		
		//variables de sesion
		$_SESSION["arch_prop_filtro_nombres"]= $filtro_nombres;
		$_SESSION["arch_prop_filtro_mis"]= $filtro_mis;
		$_SESSION["arch_prop_filtro_ci"]= $filtro_ci;
	}//fin del if de buscar_boton
	else{
		$filtro_nombres= $_SESSION["arch_prop_filtro_nombres"];
		$filtro_mis= $_SESSION["arch_prop_filtro_mis"];
		$filtro_ci= $_SESSION["arch_prop_filtro_ci"];
	}
	
	//filtro de la ventana
	$_SESSION["arch_filtro"]= $del_filtro;
	$smarty->assign('filtro',$del_filtro);
	//valores del filtro
	
	$smarty->assign('filtro_nombres',$filtro_nombres);
	$smarty->assign('filtro_mis',$filtro_mis);
	$smarty->assign('filtro_ci',$filtro_ci);
	

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	//eliminar
	if(isset($_REQUEST['elimina_x'])){
		include("./propietarios/elimina_todo.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_xboton_x'])){
		include("./propietarios/eliminando.php");
	}
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana
$ids_propietario= array();
//$id_almacen = $_SESSION['id_almacen'];

if($del_filtro != "nada"){

	$sql= "SELECT TOP 25 p.id_propietario, p.nombres, p.mis, p.ci, 
	p.direccion, p.telefonos, p.motivoeli 
	FROM propietarios p  
	WHERE datalength(p.motivoeli)>0   $del_filtro  
	ORDER BY p.nombres ";
}else{

	$sql= "SELECT TOP 25 p.id_propietario, p.nombres, p.mis, p.ci, 
	p.direccion, p.telefonos, motivoeli
	FROM propietarios p 
	WHERE datalength(p.motivoeli)>0 
	ORDER BY p.nombres ";
}
	$query= consulta($sql);

	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_propietario[] = array( 'ids' => $row["id_propietario"],
									'nombres' => $row["nombres"],
									'mis' => $row["mis"],
									'ci' => $row["ci"],
									'direccion' => $row["direccion"],
									'telefonos' => $row["telefonos"],
									'motivoeli' => $row["motivoeli"]);
	}

	$smarty->assign('ids_propietario',$ids_propietario);

	$smarty->display('propietarioseli.html');
	die();

?>