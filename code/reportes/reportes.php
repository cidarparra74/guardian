<?php

	require_once("../lib/setup.php");
	$smarty = new bd;	
	 //echo getcwd();
	 
	require_once('../lib/conexionMNU.php');
	require_once('../lib/verificar.php');

	//href
	$carpeta_entrar="_main.php?carpeta_entrar=reportes";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "reportes";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	//impresion de reportes de solicitudes aceptadas con firma autorizada
	if(isset($_REQUEST['listado_ace_con_fa'])){
		include("./reportes/listado_ace_con_fa.php");
	}
	//imprimiendo el reporte de solicitud de carpetas aceptadas con firma autorizada
	if(isset($_REQUEST['boton_listado_ace_con_fa'])){
		include("./reportes/listado_ace_con_fa_imp.php");
	}
	
	//impresion del inventario detallado de las carpetas
	if(isset($_REQUEST['inv_detallado_carpetas'])){
		include("./reportes/inv_detallado_carpetas.php");
	}
	//imprimiendo inventario detallado de las carpetas
	if(isset($_REQUEST['boton_inv_detallado_carpetas'])){
		include("./reportes/inv_detallado_carpetas_imp.php");
	}
		
	//impresion del inventario detallado de las carpeta en html
	if(isset($_REQUEST['inv_detallado_carpetas_html'])){
		include("./reportes/inv_detallado_carpetas_html.php");
	}
	//imprimiendo inventario detallado de las carpetas en html
	if(isset($_REQUEST['boton_inv_detallado_carpetas_html'])){
		include("./reportes/inv_detallado_carpetas_imp_html.php");
	}
	
	
	//impresion del inventario detallado de las carpetas por código mis
	if(isset($_REQUEST['inv_detallado_mis'])){
		include("./reportes/inv_detallado_mis.php");
	}
	//imprimiendo inventario detallado de las carpetas por código mis
	if(isset($_REQUEST['boton_inv_detallado_mis'])){
		include("./reportes/inv_detallado_mis_imp.php");
	}
	
	
	
	
	
	//impresion del inventario detallado de las carpetas por código mis
	if(isset($_REQUEST['listado_excepciones'])){
		include("./reportes/listado_excepciones.php");
	}
	//imprimiendo inventario detallado de las carpetas por código mis
	if(isset($_REQUEST['boton_listado_excepciones'])){
		include("./reportes/listado_excepciones_imp.php");
	}
	
	
	
	//impresion del inventario sobres en boveda
	if(isset($_REQUEST['inv_sobres_boveda'])){
		include("./reportes/inv_sobres_boveda.php");
	}
	//imprimiendo inventario sobres in boveda
	if(isset($_REQUEST['boton_inv_sobres_boveda'])){
		include("./reportes/inv_sobres_boveda_imp.php");
	}
	
	//impresion de las fechas de creacion de propietarios y carpetas
	if(isset($_REQUEST['fechas_creacion_pc'])){
		include("./reportes/fechas_creacion_pc.php");
	}
	//imprimiendo inventario sobres in boveda
	if(isset($_REQUEST['boton_fechas_creacion_pc'])){
		include("./reportes/fechas_creacion_pc_imp.php");
	}
	
	//impresion del historial de las carpetas
	if(isset($_REQUEST['historial_carpetas'])){
		include("./reportes/historial_carpetas.php");
	}
	//imprimiendo inventario sobres in boveda
	if(isset($_REQUEST['boton_historial_carpetas'])){
		include("./reportes/historial_carpetas_imp.php");
	}
	
	//impresion del historial de las carpetas
	if(isset($_REQUEST['docsobs'])){
		include("./reportes/docus_obs.php");
	}
	//imprimiendo inventario sobres in boveda
	if(isset($_REQUEST['boton_docsobs'])){
		include("./reportes/docus_obs_imp.php");
	}
	

/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

				
	$smarty->display('../reportes/reportes.html');
	die();

?>