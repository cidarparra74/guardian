<?php
	$id_propietario = $_REQUEST["vcatastro"];
	$nrocaso = $_REQUEST["ncaso"];
	//temporalmente:
	if($nrocaso=='') $nrocaso = '0';  //para bsol es nro de cuenta ahora. 06/2013
	
	// RECUPERAMOS DATOS DEL propietario
	$sql = "SELECT nombres, ci, emision FROM propietarios WHERE id_propietario = $id_propietario " ;
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('ci_cliente', $row["ci"]);
	$smarty->assign('emision', $row["emision"]);
	$smarty->assign('cliente', $row["nombres"]);
	$smarty->assign('nrocaso', $nrocaso);
//recuperando las carpetas del cliente
$sql= "SELECT c.id_carpeta, CONVERT(VARCHAR,c.creacion_carpeta,103) as fecha, c.carpeta, 
tc.tipo_bien AS tipo_carpeta,  
c.operacion, o.nombre
FROM carpetas c,  oficinas o, tipos_bien tc 
WHERE c.id_oficina=o.id_oficina 
AND c.id_tipo_carpeta=tc.id_tipo_bien 
AND c.id_propietario='$id_propietario' AND tc.cuenta <>''
ORDER BY c.operacion ";
//c.cuenta,'cuenta' => $row["cuenta"],
	$query = consulta($sql);
	$i=0;
	$carpeta=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$carpeta[$i]= array('id' => $row["id_carpeta"],
							'fecha' => $row["fecha"],
							'carpeta' => $row["carpeta"],
							'tipo_bien' => $row["tipo_carpeta"],
							'operacion' => $row["operacion"],
							'nombre' => $row["nombre"]);
		$i++;
	}
	
	$smarty->assign('carpeta',$carpeta);
	$smarty->display('ver_informe_legal/catastro_ver.html');
	die();

?>
