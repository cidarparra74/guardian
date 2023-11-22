<?php
session_start();
ini_set('odbc.defaultlrl','1048576');
chdir( $_SESSION['MainDir'] );
	
require_once('../lib/conexionSEC.php');
$idfinal=$_REQUEST["id"];
$tipodoc=$_REQUEST["td"];
	$sql = "SELECT contenido_final FROM contrato_final WHERE idfinal=$idfinal";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	// La imagen, que de hecho es codigo RTF
	$imagen = $row["contenido_final"];
	// VER DE CORREGIR ESPACIO DEMAS ANTES DEL TITULO
	//$error = "\par   \b";
	//$fixed = "\par  \b";
	//$imagen = str_replace($error,$fixed,$imagen);
	//definimos la cabecera para corregir los margenes y dar el mismo formato del SEC
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
$imagen=$cabecera.$imagen.$pie;
//
	$tam=strlen($imagen);
	
	header("Pragma: no-cache");
    header("Content-type: application/rtf; charset=UTF-8");
	if($tipodoc =='O')
    header("Content-Disposition: attachment; filename=\"documento.odt\"\n");
	else
    header("Content-Disposition: attachment; filename=\"documento.doc\"\n");
	
	header("Cache-Control: no-cache, must-revalidate");
	header("Content-Description: Contrato" );
	header("Content-Length: " . (string)$tam);
	header("Connection: close");
	// descarga el doc
	print $imagen;
	
	

	
	/*
$archivo = fopen("../compilado/datos.rtf","w");
if(fputs($archivo,$imagen) == TRUE){
echo "Se a creado con exito el archivo";
}else {
echo "No se pudo crear el archivo";
}
#se cierra el fichero
fclose($archivo);
chdir("../rtf2htm");
 exec("../lib/rtf2html.exe $archivo ../compilado/datos.html");
 //$salida="../compilado/datos.html";
//require("rtf2htm.php");
// exec("php.exe rtf2htm.php $archivo ../compilado/datos.html");
*/
?>