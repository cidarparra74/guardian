<?php
//session_start();

require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
require_once("../lib/fechas.php");
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
//echo $_REQUEST['filtro_nombres'];
	//href
	$carpeta_entrar="_main.php?action=solicitud_carpeta.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "solicitud_carpeta";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	$del_filtro = "nada";
	//filtro de la ventana
	if(isset($_REQUEST['buscar_boton'])){
		$filtro_nombres= $_REQUEST['filtro_nombres'];
		$filtro_ci= $_REQUEST['filtro_ci'];
		//variables de sesion
		$_SESSION["comun_prop_filtro_nombres"]= $filtro_nombres;
		$_SESSION["comun_prop_filtro_ci"]= $filtro_ci;
	}elseif(isset($_SESSION["comun_prop_filtro_nombres"])){
		$filtro_nombres= $_SESSION["comun_prop_filtro_nombres"];
		$filtro_ci= $_SESSION["comun_prop_filtro_ci"];
	}else{
		$filtro_nombres= "";
		$filtro_ci="";
	}
	
	
	
		$band=0;
		
		//nombres
		if($filtro_nombres != ""){
			$del_filtro = "WHERE p.nombres LIKE '%$filtro_nombres%' ";
			$band=1;
		}//fin de nombres

		//ci
		if($filtro_ci != ""){
			if($band == 0){
				$del_filtro = "WHERE p.ci LIKE '%$filtro_ci%' ";
			}else{
				$del_filtro = $del_filtro." AND p.ci LIKE '%$filtro_ci%' ";
			}
		}//fin de ci
	
	//valores del filtro
	
	$smarty->assign('filtro_nombres',$filtro_nombres);
	$smarty->assign('filtro_ci',$filtro_ci);
	

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	// ver documentos
	if(isset($_REQUEST['documentos'])){
		include("./solicitud_carpeta/documentos.php");
	}
	
	//lista de todas las carpetas
	if(isset($_REQUEST['carpeta_propietario'])){
		include("./solicitud_carpeta/lista_carpetas.php");
	}
	
	//solicitud de prestamo de la carpeta
	if(isset($_REQUEST["prestar_carpeta"])){
		include("./solicitud_carpeta/lista_carpetas.php");
	}
	
	//prestamo de la carpeta
	if(isset($_REQUEST["prestar_boton_x"])){
		include("./solicitud_carpeta/lista_carpetas.php");
	}
	
	//volviendo a la lista de carpetas del propietario
	if(isset($_REQUEST["volver_lista"])){
		include("./solicitud_carpeta/lista_carpetas.php");
	}
	
//Aumentado por Percy Rivas 2017/12/16 para ver reporte de contenido carpeta
	//impresion del reporte de la carpeta
	if(isset($_REQUEST['reporte_docs'])){
		include("./carpetas/reporte_docs.php");
	}
//
	//modificar prestamo carpeta
	if(isset($_REQUEST["modificar_prestamo"])){
		include("./solicitud_carpeta/lista_carpetas.php");
	}
	
	//modificando prestamo carpeta
	if(isset($_REQUEST['modificar_prestamo_boton_x'])){
		include("./solicitud_carpeta/lista_carpetas.php");
	}
	
	//eliminar prestamo carpeta
	if(isset($_REQUEST["eliminar_prestamo"])){
		include("./solicitud_carpeta/lista_carpetas.php");
	}
	
	//eliminando prestamo carpeta
	if(isset($_REQUEST['eliminar_prestamo_boton_x'])){
		include("./solicitud_carpeta/lista_carpetas.php");
	}
	//include("./solicitud_carpeta/lista_carpetas.php");
	
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana

$ids_propietario= array();

if($del_filtro != "nada"){


	$sql= "SELECT TOP 50 p.id_propietario, p.nombres, p.mis, p.ci, p.direccion, 
	p.telefonos, COUNT(c.id_carpeta) AS cantidad 
	FROM propietarios p 
	INNER JOIN carpetas c 
	ON c.id_propietario=p.id_propietario $del_filtro 
	GROUP BY p.id_propietario, p.nombres, p.mis, p.ci, p.direccion, p.telefonos 
	ORDER BY p.nombres";

	$query = consulta($sql);

	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_propietario[]= array('id' => $row["id_propietario"],
								'nombres' => $row["nombres"],
								'mis' => $row["mis"],
								'ci' => $row["ci"],
								'direccion' => $row["direccion"],
								'telefonos' => $row["telefonos"],
								'tiene_carpeta' => $row["cantidad"]);
	}

}
	$smarty->assign('ids_propietario',$ids_propietario);
		
	$smarty->display('solicitud_carpeta.html');
	die();

?>
