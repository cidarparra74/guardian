<?php
	$idf= $_REQUEST["idf"];
	ini_set('odbc.defaultlrl','1048576');
	//recuperamos el informe
	$sql= "SELECT ilf.html FROM informes_legales_fechas ilf WHERE id_informe_legal_fecha='$idf'  ";
	//echo $sql;
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$informe= $row["html"];
	$informe = str_replace("../../","../",$informe);
		echo $informe;
	/*
	include("../mpdf/mpdf.php");
	$mpdf=new mPDF(); 
	$mpdf->WriteHTML($informe);
	$mpdf->Output();
	*/
	die();

?>