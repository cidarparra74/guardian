<?php
	
	$idd = $_REQUEST['eliadjunto'];
	$sql= "UPDATE documentos_propietarios SET archivo = '' WHERE id_documento_propietario = $idd";
	ejecutar($sql);
			
?>