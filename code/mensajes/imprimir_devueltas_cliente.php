<?php

ejecutar(" TRUNCATE TABLE tmp_listado_devueltas_cliente");

$consulta= " WHERE m.flujo='0' AND m.id_us_corriente= '$id_us_actual' AND m.id_estado='8' AND m.id_us_archivo=u.id_usuario ";
$sql= "INSERT INTO tmp_listado_devueltas_cliente SELECT p.mis, p.nombres, t.tipo_bien, o.nombre, m.id_estado, m.flujo FROM (propietarios p INNER JOIN carpetas c ON c.id_propietario=p.id_propietario INNER JOIN oficinas o ON c.id_oficina=o.id_oficina INNER JOIN tipos_bien t ON c.id_tipo_carpeta=t.id_tipo_bien) ";
$sql.= "LEFT JOIN movimientos_carpetas m ON m.id_carpeta=c.id_carpeta LEFT JOIN usuarios u on m.id_us_archivo=u.id_usuario ";
$sql.= "$consulta ORDER BY c.id_carpeta ";
//echo "$sql";
ejecutar($sql);

	$smarty->display('mensajes/imprimir_devueltas_cliente.html');
	die();

?>