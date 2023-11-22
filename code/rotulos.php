<?php

//*****require('setup.php');
require_once("../lib/setup.php");
$smarty = new bd;	
//require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	//href
	$carpeta_entrar="_main.php?action=rotulos.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "rotulos";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//filtro de la ventana
	
	if(!isset($_REQUEST['filtro'])){
		$del_filtro= "nada";
		$_SESSION["arch_prop_filtro_nombres"]= "";
		$_SESSION["arch_prop_filtro_mis"]= "";
		$_SESSION["arch_prop_filtro_ci"]= "";
		$_SESSION["arch_filtro"]= "";
	}else{
		//$del_filtro=$filtro;
		$del_filtro= $_SESSION["arch_filtro"];
	}
	
	if(isset($_REQUEST['buscar_boton'])){
		$del_filtro="";
		$band=0;
		
		//nombres
		$filtro_nombres=$_REQUEST['filtro_nombres'];
		if($filtro_nombres != ""){
			if($band == 0){
				$del_filtro= " p.nombres LIKE '%$filtro_nombres%' ";
				$band=1;
			}
		}//fin de nombres
		
		//ci
		$filtro_ci=$_REQUEST['filtro_ci'];
		if($filtro_ci != ""){
			if($band == 0){
				$del_filtro= " p.ci LIKE '%$filtro_ci%' ";
				$band=1;
			}else{
				$del_filtro= $del_filtro."AND p.ci LIKE '%$filtro_ci%' ";
			}
		}//fin de ci
		
		//nrocaso
		$filtro_ncaso=$_REQUEST['filtro_ncaso'];
		if($filtro_ncaso != ""){
			if($band == 0){
				$del_filtro= " c.nrocaso LIKE '%$filtro_ncaso%' ";
				$band=1;
			}else{
				$del_filtro= $del_filtro."AND c.nrocaso LIKE '%$filtro_ncaso%' ";
			}
		}//fin de nrocaso
		
		$filtro_nombres= $_REQUEST['filtro_nombres'];
		$filtro_ncaso= $_REQUEST['filtro_ncaso'];
		$filtro_ci= $_REQUEST['filtro_ci'];
		
		//variables de sesion
		$_SESSION["arch_prop_filtro_nombres"]= $filtro_nombres;
		$_SESSION["arch_prop_filtro_mis"]= $filtro_ncaso;
		$_SESSION["arch_prop_filtro_ci"]= $filtro_ci;

	}//fin del if de buscar_boton
	else{
		$filtro_nombres= $_SESSION["arch_prop_filtro_nombres"];
		$filtro_ncaso= $_SESSION["arch_prop_filtro_mis"];
		$filtro_ci= $_SESSION["arch_prop_filtro_ci"];
	}
	
	//filtro de la ventana
	$_SESSION["arch_filtro"]= $del_filtro;
	$smarty->assign('filtro',$del_filtro);
	//valores del filtro
	
	$smarty->assign('filtro_nombres',$filtro_nombres);
	$smarty->assign('filtro_ncaso',$filtro_ncaso);
	$smarty->assign('filtro_ci',$filtro_ci);
	
/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana
$ids_propietario= array();
$ids_casos= array();

if($del_filtro != "nada" && $filtro_ncaso==''){

	$sql= "SELECT TOP 20 p.id_propietario, p.nombres, p.ci, COUNT(c.id_carpeta) AS cantidad 
	FROM propietarios p LEFT JOIN carpetas c 
	ON c.id_propietario=p.id_propietario 
	WHERE  $del_filtro 
	GROUP BY p.id_propietario, p.nombres, p.ci, p.direccion, p.telefonos 
			ORDER BY p.nombres ";
	
	$query= consulta($sql);
	
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_propietario[] = array( 'ids' => $row["id_propietario"],
									'nombres' => $row["nombres"],
									'ci' => $row["ci"],
									'tiene_carpeta' => $row["cantidad"]);
	}
}elseif($del_filtro != "nada" && $filtro_ncaso!=''){
	$sql= "SELECT p.id_propietario, p.nombres, p.ci, il.id_informe_legal 
	FROM informes_legales il INNER JOIN propietarios p 
	ON il.id_propietario=p.id_propietario 
	WHERE  il.nrocaso = '$filtro_ncaso' ";
	
	$query= consulta($sql);
	
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_casos[] = array( 'ids' => $row["id_propietario"],
								'nombres' => $row["nombres"],
								'ci' => $row["ci"],
								'id' => $row["id_informe_legal"]);
	}
}

	$smarty->assign('ids_propietario',$ids_propietario);
	$smarty->assign('ids_casos',$ids_casos);
	
	$smarty->display('rotulos.html');
	die();

?>
