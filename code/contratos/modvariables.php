<?php
	
	//guardamos los datos en sesion para recuperarlos despues
	$_SESSION["principal"] = $principal;
	$_SESSION["partes"] = $partes;
	$_SESSION["idfinal"] = $idfinal;
	$_SESSION["contrato"] = $contrato;
	
	$smarty->assign('principal',$principal);
	
	$smarty->assign('contrato',$contrato);

	$smarty->display('contratos/modvariables.html');
	die();
	
?>