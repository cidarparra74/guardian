<?php
session_start();
//se habilita lectura > 1mb via ODBC
ini_set('odbc.defaultlrl','1048576');
chdir('..');
require_once('../lib/conexionSEC.php');
//leemos el RTF del al base de datos
//es campo binario pero almacena texto
$idfinal=$_REQUEST["id"];
	$sql = "SELECT contenido_final FROM contrato_final WHERE idfinal=$idfinal";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	// La imagen, que de hecho es codigo RTF
	$imagen = $row["contenido_final"];
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
//definimos final de archivo
$pie="\n}";
//unimos todo
$imagen=$cabecera.$imagen.$pie;

$usr = $_SESSION["idusuario"];	
//obtenemos la ruta de  la carpeta COMPILADO
//$path =	$_SERVER["APPL_PHYSICAL_PATH"];
$path =	"c:\\inetpub\\wwwroot\\guardian2\\";

$archivo = fopen($path."compilado\\datos$usr.rtf","w");
if(fputs($archivo,$imagen) == TRUE){
	//echo "Se a creado con exito el archivo";
}else {
	echo "No se pudo crear el archivo RTF temporal";
	die();
}

#se cierra el fichero
fclose($archivo);

//estabelcemos carpeta de trabajo
chdir("../compilado/");

//generamos el html a partir del RTF

//creamos instancia
	$rtf2htmlCom = new COM("RTF2HTML.Converter"); 
         $rtf2htmlCom->PreserveImages=true;
         $rtf2htmlCom->OutputFormat = 1;
         $rtfFile = $path."compilado\\datos$usr.rtf";
         $htmlFile = $path."compilado\\datos$usr.html";
         $result =$rtf2htmlCom->ConvertFile($rtfFile,$htmlFile);
         unset($rtf2htmlCom);
	//	verificamos que exista el RTF
		if(!file_exists($path."compilado\\datos$usr.html")){
			echo "No se pudo crear el archivo HTML temporal";
			die();
		}
		//volcamos el archivo HTML a una variable
         $el_html = file_get_contents($path."compilado\\datos$usr.html");
//validamos q el HTML no este vacio
	if($el_html!=''){ 
		//Generamos RTF a partir del HTML
		include("../mpdf/mpdf.php");
		$mpdf=new mPDF(); 
		$mpdf->WriteHTML($el_html);
		$mpdf->Output(); 
	}else{
		echo "Error: No se puede generar el archivo PDF! (html vacio)";
	}

?>