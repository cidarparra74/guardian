<?php
require_once("../lib/dompdf/dompdf_config.inc.php");
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
	
//	$smarty->display('reportes/avanceres.html');
	
	//$tmpfile = tempnam("../tmp", "dompdf_");
	//$tmpfile = "D:\\tmp";
//file_put_contents($tmpfile, $smarty->fetch('reportes/avanceres.html')); 
/*
//rawurlencode($tmpfile)
$url = "../lib/dompdf/dompdf.php?input_file=" . $tmpfile . 
       "&paper=letter&output_file=" . rawurlencode("Reporte.pdf");

//header("Location: http://" . $_SERVER["HTTP_HOST"] . "/$url");
header("Location: $url");
*/
$dompdf = new DOMPDF();
$dompdf->load_html($smarty->fetch('reportes/avanceres.html'));
$dompdf->render();
//$opc = array('Attachment' => 0 or 1, 'compress' => 1 or 0);
$opc = array('Attachment' => 0, 'compress' => 1);
$dompdf->stream("sample.pdf", $opc);
	die();
?>