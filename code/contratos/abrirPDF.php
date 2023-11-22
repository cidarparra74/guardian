<?php
session_start();
//se habilita lectura > 1mb via ODBC
ini_set('odbc.defaultlrl','1048576');
chdir('..');
require_once('../lib/conexionMNU.php');
//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, rutatmp FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	$rutatmp = $row["rutatmp"];
	//$smarty->assign('enable_ws',$row["enable_ws"]);
	
require_once('../lib/conexionSEC.php');
//leemos el RTF del al base de datos
//es campo binario pero almacena texto
$idfinal=$_REQUEST["id"];
$idfinal= substr(str_replace("'","",$idfinal),0,10);

	$sql = "SELECT contenido_rtf FROM contrato_final WHERE idfinal=$idfinal";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	// La imagen, que de hecho es codigo RTF
	$imagen = $row["contenido_rtf"];
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
//quitamos el pie
/*
resultado = "{\\footer \\pard\\plain \\s16\\qc \\li0\\ri0\\widctlpar\\tqc\\tx4252\\tqr\\tx8504\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid8010168 \\fs12\\lang3082\\langfe3082\\cgrid\\langnp3082\\langfenp3082 {\\lang1034\\langfe3082\\langnp1034\\insrsid8010168 " + idfinal.ToString() + usuario.ToUpper() + Convert.ToString(idcontrato) + fecha + "}{\\lang1034\\langfe3082\\langnp1034\\insrsid8010168\\charrsid8010168 \\par }} \\paperw12242\\paperh18722\\margl1985\\margr1134\\margt3686\\margb1701 ";
*/
$codigo=$idfinal.date("YmdHms");
$imagen = str_replace("{\\footer \\pard \\qj\\plain \\s16\\qc \\li0\\ri0\\widctlpar\\tqc\\tx4252\\tqr\\tx8504\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid8010168","",$imagen);
$imagen = str_replace("\\fs12\\lang3082\\langfe3082\\cgrid\\langnp3082\\langfenp3082 {\\lang1034\\langfe3082\\langnp1034\\insrsid8010168","#*",$imagen);
$imagen = str_replace("}{\\lang1034\\langfe3082\\langnp1034\\insrsid8010168\\charrsid8010168 \\par }} \\paperw12242\\paperh18722\\margl1985\\margr1134\\margt3686\\margb1701","*#",$imagen);
$desde = strpos($imagen,"#*");
$hasta = strpos($imagen,"*#");
if($desde === false){}else{
	$codigo = substr($imagen, $desde, $hasta-$desde+2);
	$imagen = str_replace($codigo, "", $imagen);
	$codigo = str_replace("#*","",$codigo);
	$codigo = str_replace("*#","",$codigo);
	//echo $codigo;
}
//unimos todo
$imagen=$cabecera.$imagen.$pie;

$usr = $_SESSION["idusuario"];	
//obtenemos la ruta de la carpeta COMPILADO
$path = str_replace("/","\\\\",$rutatmp)."\\\\";

if($rutatmp==''){
	if($enable_ws == 'A'){
		$path = "E:\\WebApp\\guardianpro\\compilado\\";
	}else{
		$path =	"c:\\inetpub\\wwwroot\\guardianpro\\compilado\\";
	}
}
$archivo = fopen($path."datos$usr.rtf","w");
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
//usemos RTF2HTML.dll
//hay que registrar: regsvr32 RTF2HTML.dll
//si no funciona en php.ini aumentar : (luego reinicias iis)
//     [COM_DOT_NET]
//     extension=php_com_dotnet.dll
//creamos instancia
	$rtf2htmlCom = new \COM("RTF2HTML.Converter"); 
         $rtf2htmlCom->PreserveImages=true;
         $rtf2htmlCom->OutputFormat = 1;
         $rtfFile = $path."datos$usr.rtf";
         $htmlFile = $path."datos$usr.html";
		 
		 $rtfFile = file_get_contents($path."datos$usr.rtf");
		 $el_html = $rtf2htmlCom->ConvertString($rtfFile,".",".");
		 
		// echo $el_html;
		// die();
/* 
// usamos conversion de RTF a HTML via archivos       
		$result =$rtf2htmlCom->ConvertFile($rtfFile,$htmlFile);
         unset($rtf2htmlCom);
	//	verificamos que exista el HTML
		if(!file_exists($path."compilado\\datos$usr.html")){
			echo "No se pudo crear el archivo HTML temporal";
			die();
		}
		//volcamos el archivo HTML a una variable
         $el_html = file_get_contents($path."compilado\\datos$usr.html");
*/

//Generacionde del PDF a partir del HTML

//geenramos a partir de variables mediante mPDF
	if($el_html!=''){ 
		//Generamos PDF a partir del HTML
		include("../mpdf/mpdf.php");
		$mpdf=new mPDF(); 
	//	$el_html = utf8_encode($el_html);
		$mpdf->WriteHTML($el_html);
		$pie = array (
			  'odd' => array (
				'L' => array (),
				'C' => array (
				  'content' => $codigo,
				  'font-size' => 8,
				  'font-style' => 'N',
				  'font-family' => 'serif',
				  'color'=>'#666666'
				),
				'R' => array (),
				'line' => 0,
			  ),
			  'even' => array ()
			);
		$mpdf->SetFooter($pie,'');
		//$pie = '<center>'.$codigo.'</center>'; //no funciona
		//$mpdf->SetHTMLFooter($pie,'');
		$mpdf->Output(); 
	}else{
		echo "Error: No se puede generar el archivo PDF! (html vacio)";
	}

/*

//generamos a partir de archivos

//validamos q el HTML no este vacio
	if($el_html!=''){ 
		//Generamos PDF a partir del HTML con mPDF
		include("../mpdf/mpdf.php");
		$mpdf=new mPDF(); 
		$mpdf->WriteHTML($el_html);
		$mpdf->Output(); 
	}else{
		echo "Error: No se puede generar el archivo PDF! (html vacio)";
	}
*/
?>