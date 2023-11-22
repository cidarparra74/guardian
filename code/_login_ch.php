<?php

//
//     Victor Rivas
//
	$_SESSION = array();
//	ini_set("session.gc_maxlifetime", 18000); 
	require_once("../lib/setup.php");
	
	$smarty = new bd;
	
	if(!isset($txtaviso))
		$txtaviso = "";
	if(isset($_REQUEST['username']))
		$username = $_REQUEST['username'];
	else
		$username = "";
		
	$smarty->assign('txtaviso',$txtaviso);
	$smarty->assign('username',strtoupper($username));
	$smarty->assign('id_usuario',$data['id_usuario']);
	$smarty->display('../templates/_login_ch.html');
	die();
	
?>