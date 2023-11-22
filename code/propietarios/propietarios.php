<?php

//*****require('setup.php');
require_once("../lib/setup.php");
$smarty = new bd;	
 //echo getcwd();
 
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');

	//href
	$carpeta_entrar="_main.php?carpeta_entrar=propietarios";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "propietarios";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//filtro de la ventana
	$filtro= $_REQUEST['filtro'];
	
	if($filtro == "ninguno"){
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
				$del_filtro= "WHERE p.nombres LIKE '%$aux%' ";
				$band=1;
			}
		}//fin de nombres
		
		//mis
		$aux=$_REQUEST['filtro_mis'];
		if($aux != ""){
			if($band == 0){
				$del_filtro= "WHERE p.mis LIKE '%$aux%' ";
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
				$del_filtro= "WHERE p.ci LIKE '%$aux%' ";
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
				$del_filtro= "WHERE p.telefonos LIKE '%$aux%' ";
				$band=1;
			}
			else{
				$del_filtro= $del_filtro."AND p.telefonos LIKE '%$aux%' ";
			}
		}//fin de telefonos
		
		//direccion
		$aux=$_REQUEST['filtro_direccion'];
		if($aux != ""){
			if($band == 0){
				$del_filtro= "WHERE p.direccion LIKE '%$aux%' ";
				$band=1;
			}
			else{
				$del_filtro= $del_filtro."AND p.direccion LIKE '%$aux%' ";
			}
		}//fin de direccion
		
		$filtro_nombres= $_REQUEST['filtro_nombres'];
		$filtro_mis= $_REQUEST['filtro_mis'];
		$filtro_ci= $_REQUEST['filtro_ci'];
		$filtro_telefonos= $_REQUEST['filtro_telefonos'];
		$filtro_direccion= $_REQUEST['filtro_direccion'];
		
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
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("./propietarios/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton_x'])){
		include("archivo/propietarios/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("archivo/propietarios/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton_x'])){
		include("archivo/propietarios/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("archivo/propietarios/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton_x'])){
		include("archivo/propietarios/eliminando.php");
	}
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana
if($del_filtro == "nada"){

	$sql="insert into propietarios(mis, ci, nombres, direccion, telefonos) (select distinct cg_cod_cliente, cg_doc_identidad, cg_nombre, '-', '-' from CB_GARANTIAS_GUARDIAN left join propietarios on mis=cg_cod_cliente where mis is null)";
	//$link->query($sql);

	$sql= "SELECT p.id_propietario, p.nombres, p.mis, p.ci, p.direccion, p.telefonos, COUNT(c.id_carpeta) AS cantidad, (SELECT COUNT(*) FROM informes_legales WHERE id_propietario=p.id_propietario) as tiene_il ";
	$sql.= "FROM propietarios p LEFT JOIN carpetas c ON c.id_propietario=p.id_propietario WHERE p.id_propietario=0 GROUP BY p.id_propietario, p.nombres, p.mis, p.ci, p.direccion, p.telefonos ";
}
else{
	$sql= "SELECT p.id_propietario, p.nombres, p.mis, p.ci, p.direccion, p.telefonos, COUNT(c.id_carpeta) AS cantidad, (SELECT COUNT(*) FROM informes_legales WHERE id_propietario=p.id_propietario) as tiene_il ";
	$sql.= "FROM propietarios p LEFT JOIN carpetas c ON c.id_propietario=p.id_propietario $del_filtro GROUP BY p.id_propietario, p.nombres, p.mis, p.ci, p.direccion, p.telefonos ORDER BY p.nombres  ";
}
//echo "$sql"; die();
$query= consulta($sql);

$ids_propietario= array();
$nombres= array();
$mis= array();
$ci= array();
$direccion= array();
$telefonos= array();
$tiene_carpeta= array();
$tiene_il= array();

$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$ids_propietario[$i]= $row["id_propietario"];
	$nombres[$i]= $row["nombres"];
	$mis[$i]= $row["mis"];
	$ci[$i]= $row["ci"];
	$direccion[$i]= $row["direccion"];
	$telefonos[$i]= $row["telefonos"];
	$tiene_carpeta[$i]= $row["cantidad"];
	$tiene_il[$i]= $row["tiene_il"];
	
	$i++;
}

	$smarty->assign('ids_propietario',$ids_propietario);
	$smarty->assign('nombres',$nombres);
	$smarty->assign('mis',$mis);
	$smarty->assign('ci',$ci);
	$smarty->assign('direccion',$direccion);
	$smarty->assign('telefonos',$telefonos);
	$smarty->assign('tiene_carpeta',$tiene_carpeta);
	$smarty->assign('tiene_il',$tiene_il);
		
	$smarty->display('../templates/propietarios.html');
	die();

?>
