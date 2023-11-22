<?php

	require_once("../lib/setup.php");
	$smarty = new bd;	
	 //echo getcwd();
	 
	require_once('../lib/conexionMNU.php');
	require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	if(isset($_REQUEST['rt'])){
		$carpeta_entrar="_main.php?action=reportesar.php&rt=l";
		$smarty->assign('lst','0'); //mostrar solo el inventario
	}else{
		$carpeta_entrar="_main.php?action=reportesar.php";
		$smarty->assign('lst','1'); // mostrar todos los reportes
	}
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
	if(isset($_REQUEST['boton_listado_ace_con_fa'])){
		include("./reportes/listado_ace_con_fa_imp.php");
	}
	
	//impresion del inventario detallado de las carpetas
	if(isset($_REQUEST['inv_detallado_carpetas'])){
		include("./reportes/inv_detallado_carpetas.php");
	}
	if(isset($_REQUEST['boton_inv_detallado_carpetas'])){
		include("./reportes/inv_detallado_carpetas_imp.php");
	}
		
	//impresion del inventario detallado de las carpeta en html...
	if(isset($_REQUEST['inv_detallado_carpetas_html'])){
		include("./reportes/inv_detallado_carpetas_html.php");
	}
	if(isset($_REQUEST['boton_inv_detallado_carpetas_html'])){
		include("./reportes/inv_detallado_carpetas_imp_html.php");
	}
	if(isset($_REQUEST['boton_detallado_carpetas_dev_html'])){
		include("./reportes/detallado_carpetas_imp_dev_html.php");
	}
	if(isset($_REQUEST['detallado_carpetas_dev_html'])){
		include("./reportes/detallado_carpetas_dev_html.php");
	}
	//inv_auditoria...
	if(isset($_REQUEST['inv_auditoria'])){
		include("./reportes/inv_auditoria.php");
	}
	if(isset($_REQUEST['inv_auditoria_imp'])){
		include("./reportes/inv_auditoria_imp.php");
	}
	//impresion del inventario detallado de las carpetas por código mis
	if(isset($_REQUEST['inv_detallado_mis'])){
		include("./reportes/inv_detallado_mis.php");
	}
	if(isset($_REQUEST['boton_inv_detallado_mis'])){
		include("./reportes/inv_detallado_mis_imp.php");
	}
	
	
	//impresion del inventario detallado de las carpetas por código mis
	if(isset($_REQUEST['listado_excepciones'])){
		include("./reportes/listado_excepciones.php");
	}
	if(isset($_REQUEST['boton_listado_excepciones'])){
		include("./reportes/listado_excepciones_imp.php");
	}
	
	
	//impresion del inventario sobres en boveda
	if(isset($_REQUEST['inv_sobres_boveda'])){
		include("./reportes/inv_sobres_boveda.php");
	}
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
	if(isset($_REQUEST['boton_historial_carpetas'])){
		include("./reportes/historial_carpetas_imp.php");
	}
	
	//impresion del observados...
	if(isset($_REQUEST['docsobs'])){
	//if($radRepo == 'docsobs'){
		include("./reportes/docus_obs.php");
	}
	
	if(isset($_REQUEST['boton_docsobs'])){
		include("./reportes/docus_obs_imp.php");
	}
	
	//impresion resumen digitacion...
	if(isset($_REQUEST['avanceres'])){
	//if($radRepo == 'avanceres'){
		include("./reportes/avanceres.php");
	}
	
	//impresion detalle digitacion...
	if(isset($_REQUEST['avancedet'])){
	//if($radRepo == 'avancedet'){
		include("./reportes/avancedet.php");
	}
	if(isset($_REQUEST['inv_detallado_dcsdigs_imp'])){
		include("./reportes/inv_detallado_dcsdigs_imp.php");
	}
	if(isset($_REQUEST['boton_inv_detallado_dcsdigs_imp_html'])){
		include("./reportes/inv_detallado_dcsdigs_imp_html.php");
	}

/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

	$smarty->display('reportes.html');
	die();			
	

?>