<?php
//esto para la primera vez que ingresa a crear un contrato
		unset($_SESSION['idcontrato']);
		unset($_SESSION['contrato']);
		unset($_SESSION['cantidad']);
		unset($_SESSION['idfinal']);
		unset($_SESSION['opcional']);
		unset($_SESSION['incisos']);
		unset($_SESSION['principal']);
		unset($_SESSION['partes']);
		unset($_SESSION['edita']);
	
	unset($_SESSION['nrocaso']);
	
	if(isset($quien)){
		if($quien!='4'){
			// contratos normales
			include("./contratos/adicionar1.php");
		}else
			// contratos automaticos
			//vemos si se puede editar
			if(isset($_REQUEST["edita"])){
				$_SESSION['edita'] = $_REQUEST["edita"];
			}else{
				$_SESSION['edita'] = 'n';
			}
			if(isset($_REQUEST["word"]))
				$_SESSION['word'] = $_REQUEST["word"];
			else
				$_SESSION['word'] = 'n';
			//$smarty->assign('edita',$edita);
			include("./contratos/adicionar1_esp.php");
	}else{
		include("./contratos/adicionar1.php");
	}
	
	
?>
