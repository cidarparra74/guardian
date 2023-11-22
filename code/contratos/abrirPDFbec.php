<?php
session_start();
ini_set('odbc.defaultlrl','1048576');
chdir('..');
require_once('../lib/conexionSEC.php');

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
$pie="\n}";
//unimos todo
$imagen=$cabecera.$imagen.$pie;
//

$usr = $_SESSION["idusuario"];	
//$path =	$_SERVER["APPL_PHYSICAL_PATH"];
$path = "E:\\WebApp\\guardianpro\\";
//$path2 = $path."compilado\\datos$usr.rtf";

//die();

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

//generamos l html a partir del RTF

	$rtf2htmlCom = new COM("RTF2HTML.Converter"); 
         $rtf2htmlCom->PreserveImages=true;
         $rtf2htmlCom->OutputFormat = 1;
         $rtfFile = $path."compilado\\datos$usr.rtf";
         $htmlFile = $path."compilado\\datos$usr.html";
         $result =$rtf2htmlCom->ConvertFile($rtfFile,$htmlFile);
         unset($rtf2htmlCom);
	//	verificamos que exista l RTF
		if(!file_exists($path."compilado\\datos$usr.html")){
			echo "No se pudo crear el archivo HTML temporal";
			die();
		}
         $el_html = file_get_contents($path."compilado\\datos$usr.html");

	if($el_html!=''){ 
		//quitamos la lineas en blanco
		include("../mpdf/mpdf.php");
		$mpdf=new mPDF(); 
		$mpdf->WriteHTML($el_html);
		$mpdf->Output(); 
	}else{
		echo "Error: No se puede generar el archivo PDF! (html vacio)";
	}

//$r = new COM("SautinSoft.RtfToHtml");

/* Convert RTF file to HTML string and show it in browser */
/*
$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$file = dirname($url);
echo $file;*/

//$file.

/*
$laruta = getcwd();
$laruta.='\\datos.rtf';
$html = $r->ConvertFileToString($laruta);
 */
 
//$html = $r->ConvertString($imagen);
/*
if ($html==""){
	echo "error";
	//print($html);
}else
	print($html);
*/
?>