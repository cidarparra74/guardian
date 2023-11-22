<?php
	

		
	require_once("../lib/setup.php");
	
	$smarty = new bd;
	
	//$smarty->assign('toolbar',$toolbar);
	$smarty->display('../templates/_intro.html');

?>