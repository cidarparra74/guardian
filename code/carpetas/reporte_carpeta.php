<?php

	$id= $_REQUEST['id'];
	
	$smarty->assign('id',$id);
		
	$smarty->display('carpetas/reporte_carpeta_imp.html');
	die();
?>