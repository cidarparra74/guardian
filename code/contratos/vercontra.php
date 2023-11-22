<?php
//session_start();
	//ARMADO DEL CONTRATO FINAL (XML)
//ini_set('odbc.defaultlrl','1048576');
//verificamos si el contrato tiene partes
if(isset($_REQUEST["id"])){

	$idfinal=$_REQUEST["id"];
	
ini_set('odbc.defaultlrl','1048576');
chdir( $_SESSION['MainDir'] );
	
require_once('../lib/conexionSEC.php');
///$idfinal=$_REQUEST["id"];


	//establecemos el tipo de usuario 1=normal, 4=especial, 5=dos_firmas

	$quien = $_SESSION["quien"];
	$smarty->assign('quien',$quien);
	
	//establecemos el tipo docuemnto word o open
	$tipodoc = $_SESSION["tipodoc"];
	$smarty->assign('tipodoc',$tipodoc); 
	
	//vemos si puede abrir en word
	//lo siguiente es para cuando entra a modificar el contrato:
	$word = $_SESSION["word"];
	$smarty->assign('word',$word);



	$sql= "SELECT TOP 50 cf.idfinal, cf.idcontrato, co.titulo, 
(CASE WHEN PATINDEX('%<personas>%', cf.contenido_sec) > 0 THEN substring(cf.contenido_sec, patindex('%<nombre>%', cf.contenido_sec)+ 8, (patindex('%</nombre>%', cf.contenido_sec)-patindex('%<nombre>%', cf.contenido_sec)-8)) ELSE '' END) cliente,
CONVERT(VARCHAR(10), cf.fechahora, 103) AS fecha , 
CONVERT(VARCHAR(10), cf.fechahora, 108) AS hora, 
cf.firmado, co.con_firma_abogado AS confirma, 
CASE WHEN cf.contenido_final is null THEN '1' ELSE '0' END nulo,
cf.ultimo_login AS modifica, us.nombres, us.appaterno
FROM contrato_final cf LEFT JOIN contrato co 
ON cf.idcontrato = co.idcontrato 
LEFT JOIN usuario us ON us.login = cf.ultimo_login 
WHERE cf.idfinal=$idfinal ";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
		$micontrato= array('id' => $row["idfinal"],
							'titulo' => $row["titulo"],
							'parte' => $row["cliente"],
							'fecha' => $row["fecha"],
							'hora' => substr($row["hora"],0,5),
							'confirma' => $row["confirma"], 
							'firma' => $row["firmado"], 
							'firmante' => $row["nombres"]." ".$row["appaterno"],
							'nulo' => $row["nulo"],
							'modifica' => $row["modifica"],
							'nrocaso' => '');
	
	$smarty->assign('micontrato',$micontrato);						
	
	$alert = "Abrir, cambiar tamaño de fuente o eliminar el documento.";
	
	//llamar al Web Service para crear el doc en RTF
	if($_REQUEST['masopc']!='acc'){
		$resulta=0;
		$tfuente = $_REQUEST['masopc'];
		require('ws_sec.php');
		//mostrar el DOC
		if($resulta==0){
			//se ha generado el DOC
			$alert = "Se ha guardado el contrato, puede abrir el documento";
		}else{
			$alert = "Atenci&oacute;n! No se pudo generar el documento, codigo de error: $resulta.";
		}
	}
	
	
	$smarty->assign('alert',$alert);
	$smarty->assign('idfinal',$idfinal);
	$smarty->display('contratos/vercontra.html');
	die();
	
	
}
	
?>