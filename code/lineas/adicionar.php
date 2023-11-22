<?php

	$id = $_REQUEST['id'];
	$smarty->assign('id',$id);
	
	$smarty->display('lineas/adicionar.html');
	die();
?>