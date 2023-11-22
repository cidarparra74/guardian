<?php

if(!isset($_REQUEST["gorep"])){

	$smarty->assign('id',$_REQUEST['id']);

	$smarty->display('ver_informe_legal/imprimir_recepcion.html');
	die();
}
chdir( '..' );
require_once('../lib/conexionMNU.php');
//entra al reporte
$id = $_REQUEST['query1'];
$sql="SELECT ile.nrocaso, ile.cliente, ile.montoprestamo,
usr.nombres, ile.motivo, convert(varchar,fecha_recepcion,102) as fecha,
tbi.tipo_bien, ile.ci_cliente
FROM informes_legales ile 
INNER JOIN usuarios usr ON ile.id_us_comun = usr.id_usuario
INNER JOIN tipos_bien tbi ON ile.id_tipo_bien = tbi.id_tipo_bien
WHERE ile.id_informe_legal='$id'";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$nrocaso = $row["nrocaso"];
	$cliente = $row["cliente"];
	$ci = $row["ci_cliente"];
	$montoprestamo = $row["montoprestamo"];
	$motivo = $row["motivo"];
	$nombres = $row["nombres"];
	$fecha = $row["fecha"];
	$tipo_bien = $row["tipo_bien"];

	
$sql="SELECT doc.documento, tip.tipo,
din.fojas, din.obs, din.comentario
FROM documentos doc
INNER JOIN documentos_informe din ON din.din_doc_id = doc.id_documento
INNER JOIN tipos_documentos tip ON tip.id_tipo_documento = din.din_tip_doc
WHERE din.din_inf_id = '$id'";
$query = consulta($sql);
$docus=array();
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$docus[] = $row;
}


require_once('../lib/class.ezpdf.php');
	
$pdf =& new Cezpdf('letter');

$pdf->selectFont('../lib/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);

$titles = array('documento' => '<b>Documento</b>',
					'tipo' => '<b>Tipo</b>',
					'fojas' => '<b>Fojas</b>',
					'obs' => '<b>Observaciones</b>',
					'comentario' => '<b>Comentarios</b>');
unset($optionst);
$optionst = array('fontSize' => 9,
				'shadeCol'=>array(0.9,0.9,0.9),
				'lineCol' => array(0,0,0),
				'xOrientation'=>'center',
				'width'=>500
			);
unset($optionsc);
$optionsc = array( 'showlines' => 0,
				'shaded'=>0,
				'lineCol' => array(255,255,255),
				'showHeadings' => 0,
				'fontSize' => 9,
				'xOrientation'=>'center',
				'width'=>350,
				'cols'=> array('titulo'=>array('justification'=>'right','width'=>100,'link'=>''),
								'valor'=>array('justification'=>'left','width'=>250,'link'=>''))
			);
unset($optionsf);
$optionsf = array('showlines' =>1,
				'shaded'=>0,
				'showHeadings' => 0,
				'lineCol' => array(0,0,0),
				'xOrientation'=>'right',
				'xPos'=>'left',
				'width'=>150
			);
$opc = array('justification'=>'center');
$txttit1 = "<b>RECEPCION DE DOCUMENTOS</b>\n\n";
$cabecera=array();
$cabecera[0] = array('titulo'=> 'Nro:', 				'valor' => $id);
$cabecera[1] = array('titulo'=> 'Cliente:', 			'valor' => $cliente);
$cabecera[2] = array('titulo'=> 'Tipo de Garantía:', 	'valor' => $tipo_bien);
$cabecera[3] = array('titulo'=> 'Descripción:', 		'valor' => $motivo);
$cabecera[4] = array('titulo'=> 'Monto del Préstamo:', 	'valor' => $montoprestamo);
$cabecera[5] = array('titulo'=> ' Recepcionado por:', 	'valor' => $nombres);
$cabecera[6] = array('titulo'=> 'Fecha de Recepción:', 	'valor' => $fecha);
if($nrocaso!=''){
$cabecera[7] = array('titulo'=> 'Nro. Caso:', 			'valor' => $nrocaso);
}
$firmas=array();
$firmas[0] = array('valor' =>'ENTREGUE CONFORME');
$firmas[1] = array('valor' =>'');
$firmas[2] = array('valor' =>'FIRMA');


//$imagen="../images/logo.jpg";
//$pdf->ezImage($imagen,5,70,'normal',left);
$pdf->ezText($txttit1, 12, $opc);
$pdf->ezTable($cabecera, '', '', $optionsc);
unset($optionsc);


$pdf->ezText("\n", 10);
$pdf->ezTable($docus, $titles, '', $optionst);
$pdf->ezText("\n", 10);

$pdf->ezTable($firmas, '', '', $optionsf);
$pdf->ezTable($firmas, '', '', $optionsf);
$pdf->ezText("\n", 10);

$pdf->ezStream();
?>