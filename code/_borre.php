<?php
	$idfinal = "1008";
require_once('../lib/conexionSEC.php');
	$sql = "SELECT contenido_final FROM contrato_final WHERE idfinal=$idfinal";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	// el contenido
	$contenido = $row["contenido_final"];
	
    //header("Content-type: application/octet-stream");
    //header("Content-Disposition: attachment; filename=\"$f\"\n");  
    //$fp=fopen("$f", "r");
   // fpassthru($contenido);
//print $contenido;

$nombre_archivo='../compilado/doc.rtf';
if (!$gestor = fopen($nombre_archivo, 'w')) {
         echo "No se puede crear el archivo ($nombre_archivo)";
         exit;
    }

    // Escribir $contenido a nuestro archivo abierto.
    if (fwrite($gestor, $contenido) === FALSE) {
        echo "No se puede escribir al archivo ($nombre_archivo)";
        exit;
    }

    echo "Éxito, se escribió archivo ($idfinal)";

    fclose($gestor);


?>