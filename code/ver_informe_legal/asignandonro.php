<?php
//llamado de instanciar.php
//ahora tambien llamado y usado desde instanciar_bsol.php

$id= $_REQUEST["id"];
$nrocaso= $_REQUEST["nrocaso"];
$cuenta= $_REQUEST["cuenta"];
$operacion= $_REQUEST["operacion"];
$suboperacion= $_REQUEST["suboperacion"];

//buscamos nro en el webservice
$documento = '';

		// movemos
		$sql= "UPDATE informes_legales SET instancia='$nrocaso' WHERE id_informe_legal='$id' ";
		ejecutar($sql);
		//verificamos si tiene carpeta
		
		$sql= "UPDATE carpetas SET nrocaso='$nrocaso' WHERE id_informe_legal='$id' ";
		ejecutar($sql);


?>