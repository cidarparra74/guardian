<?php
session_start();
ini_set('odbc.defaultlrl','1048576');
chdir( $_SESSION['MainDir'] );
//chdir("E:\\WebApp\\guardianpro\\code");
//chdir('..');
$idfinal=$_REQUEST["id"];
$tipodoc=$_REQUEST["td"];
//llamamos al servicio q devuelve el rtf con variables armadas
require_once('../code/ws_secvar.php');
//el resutado esta en $variables
if($variables!=''){	
	//$variables = $row["contenido_final"];
	
		$cabecera = "{\\rtf1\\ansi
{\\fonttbl
{\\f0 \\fcharset0 Arial;}
}
{\\colortbl;
\\red0\\green0\\blue0;
\\red255\\green255\\blue255;
}
\\pgnstart0
\\paperw11907\\paperh16840\\margl1134\\margr1134\\margt1417\\margb1134
\\f0 \\fs22 \\b0 \\i0 \\ulnone";
$pie="\n}";
//unimos todo
$imagen=$cabecera.$variables.$pie;
//$imagen=$variables;
	
	$tam=strlen($imagen);
/*	echo $imagen;
	*/
	$filename = 'documentoPrevio.rtf';
	header("Pragma: no-cache");
    header("Content-type: application/octet-stream");
    //header("Content-Disposition: attachment; filename=\"documentoPrevio.rtf\"\n");
	if($tipodoc =='O') //open-office
    header("Content-Disposition: attachment; filename=\"documentoPrevio.odt\"\n");
	else
    header("Content-Disposition: attachment; filename=\"documentoPrevio.doc\"\n");
	
	header("Cache-Control: no-cache, must-revalidate");
	header("Content-Description: " . trim(htmlentities($filename)));
	header("Content-Length: " . (string)$tam);
	header("Connection: close");
 
	// descarga el doc
	print $imagen;

}else{
	echo "No se pudo recuperar la informaci&oacute;n, intente de nuevo. (WS no disponible)";
}
?>