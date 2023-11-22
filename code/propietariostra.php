<?php

//*****require('setup.php');
require_once("../lib/setup.php");
$smarty = new bd;	
require_once("../lib/fechas.php");
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	//href
	$carpeta_entrar="_main.php?action=propietariostra.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "propietariostra";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//filtro de la ventana

	if(!isset($_REQUEST['filtro'])){
		$del_filtro= "nada";
		$_SESSION["arch_prop_filtro_nombres"]= "";
		$_SESSION["arch_prop_filtro_mis"]= "";
		$_SESSION["arch_prop_filtro_ci"]= "";
		$_SESSION["arch_prop_filtro_telefonos"]= "";
		$_SESSION["arch_prop_filtro_direccion"]= "";
		
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
		
		//telefonos
		$aux=$_REQUEST['filtro_telefonos'];
		if($aux != ""){
			if($band == 0){
				$del_filtro= "AND p.telefonos LIKE '%$aux%' ";
				$band=1;
			}
			else{
				$del_filtro= $del_filtro."AND p.telefonos LIKE '%$aux%' ";
			}
		}//fin de telefonos
		
		//direccion
		if(isset($_REQUEST['filtro_direccion'])){
			$aux=$_REQUEST['filtro_direccion'];
			if($aux != ""){
				if($band == 0){
					$del_filtro= "AND p.direccion LIKE '%$aux%' ";
					$band=1;
				}else{
					$del_filtro= $del_filtro."AND p.direccion LIKE '%$aux%' ";
				}
			}//fin de direccion
			$filtro_direccion= $_REQUEST['filtro_direccion'];
		}
		$filtro_nombres= $_REQUEST['filtro_nombres'];
		$filtro_mis= $_REQUEST['filtro_mis'];
		$filtro_ci= $_REQUEST['filtro_ci'];
		$filtro_telefonos= $_REQUEST['filtro_telefonos'];
		
		//variables de sesion
		$_SESSION["arch_prop_filtro_nombres"]= $filtro_nombres;
		$_SESSION["arch_prop_filtro_mis"]= $filtro_mis;
		$_SESSION["arch_prop_filtro_ci"]= $filtro_ci;
		$_SESSION["arch_prop_filtro_telefonos"]= $filtro_telefonos;
		$_SESSION["arch_prop_filtro_direccion"]= $filtro_direccion;
	}//fin del if de buscar_boton
	else{
		$filtro_nombres= $_SESSION["arch_prop_filtro_nombres"];
		$filtro_mis= $_SESSION["arch_prop_filtro_mis"];
		$filtro_ci= $_SESSION["arch_prop_filtro_ci"];
		$filtro_telefonos= $_SESSION["arch_prop_filtro_telefonos"];
		$filtro_direccion= $_SESSION["arch_prop_filtro_direccion"];
	}
	
	//filtro de la ventana
	$_SESSION["arch_filtro"]= $del_filtro;
	$smarty->assign('filtro',$del_filtro);
	//valores del filtro
	
	$smarty->assign('filtro_nombres',$filtro_nombres);
	$smarty->assign('filtro_mis',$filtro_mis);
	$smarty->assign('filtro_ci',$filtro_ci);
	$smarty->assign('filtro_telefonos',$filtro_telefonos);
	$smarty->assign('filtro_direccion',$filtro_direccion);
	

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	//trasladar
	if(isset($_REQUEST['trasladar'])){
		include("./carpetas/trasladar2.php");
	}
	//trasladando
	if(isset($_REQUEST['trasladar_boton_x'])){
		include("./carpetas/trasladando.php");
	}
	/*
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		$adicionar = $_REQUEST['adicionar'];
		include("./propietarios/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton_x'])){
		include("./propietarios/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("./propietarios/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton_x'])){
		include("./propietarios/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("./propietarios/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton_x'])){
		include("./propietarios/eliminando.php");
	}
	*/
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana
$ids_propietario= array();
$id_almacen = $_SESSION['id_almacen'];

if($del_filtro != "nada"){

	$sql= "SELECT TOP 15 p.id_propietario, p.nombres, p.mis, p.ci, 
	p.direccion, p.telefonos, COUNT(c.id_carpeta) AS cantidad 
	FROM propietarios p LEFT JOIN carpetas c 
	ON c.id_propietario=p.id_propietario 
	WHERE  p.fechaeli is null $del_filtro 
	GROUP BY p.id_propietario, p.nombres, p.mis, p.ci, p.direccion, p.telefonos 
	ORDER BY p.nombres ";

	$query= consulta($sql);

	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_propietario[] = array( 'ids' => $row["id_propietario"],
									'nombres' => $row["nombres"],
									'mis' => $row["mis"],
									'ci' => $row["ci"],
									'direccion' => $row["direccion"],
									'telefonos' => $row["telefonos"],
									'tiene_carpeta' => $row["cantidad"]);
	}
}
	$smarty->assign('ids_propietario',$ids_propietario);
	$smarty->display('propietariostra.html');
	die();

?>