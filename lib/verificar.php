<?php
if(!isset($_SESSION["idusuario"]) or !isset($_SESSION["nombreusr"])){
		$_SESSION = array();
		if(session_id()!='') session_destroy();
		?> 
		<div align='center'>Se ha cerrado esta sesi&oacute;n</div><br>
		<div align='center'><a href="../index.html">Iniciar Sesi&oacute;n</a></div>
		<?php
		die();
	}
?>
