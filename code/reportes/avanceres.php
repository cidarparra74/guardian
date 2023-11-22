<?php
	//recuperar lista de regionales
$sql = "select ofi.nombre, us.nombres, count(*) as cantidad 
from carpetas c 
left join usuarios us
on us.id_usuario = c.id_usuario
left join oficinas ofi
on ofi.id_oficina = c.id_oficina
group by ofi.nombre, us.nombres, c.id_usuario
order by ofi.nombre, us.nombres, c.id_usuario";
	$result = consulta($sql);
	$resumen = array();
	while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$resumen[] = array('oficina' => $row['nombre'], 
							'nombre' => $row['nombres'], 
							'cantidad' => $row['cantidad']);
	}
	$smarty->assign('resumen',$resumen);
		$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('logo',$resultado['logo01']);

	$smarty->display('reportes/avanceres.html');
/*	
$el_html =$smarty->fetch('reportes/avanceres.html');

ini_set('pcre.backtrack_limit','2000000');
ini_set('execution_time',600);
ini_set('memory_limit','1024M');
require_once('../html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','LETTER','es');
$html2pdf->WriteHTML($el_html);
$html2pdf->Output('resumen.pdf');


include("../mpdf/mpdf.php");
$mpdf=new mPDF(); 
$mpdf->WriteHTML($el_html);
$mpdf->Output();
*/
	die();

?>