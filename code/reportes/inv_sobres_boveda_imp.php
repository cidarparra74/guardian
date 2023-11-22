<?php

$gaveta= $_REQUEST['gaveta'];
$tipo= $_REQUEST['tipo'];
$oficina= $_REQUEST['oficina'];
$estado= $_REQUEST['estado'];
$posicion_gaveta= $_REQUEST['posicion_gaveta'];
$idalm = $_SESSION["id_almacen"];

$consulta="WHERE o.id_almacen = $idalm ";
if($tipo != "todos"){
	if($consulta == ""){
		$consulta="WHERE o.id_almacen = AND t.id_tipo_bien='$tipo' ";
	}else{
		$consulta .=" AND t.id_tipo_bien='$tipo' ";
	}
}
if($oficina != "todos"){
	if($consulta == ""){
		$consulta="WHERE o.id_oficina='$oficina' ";
	}
	else{
		$consulta=$consulta."AND o.id_oficina='$oficina' ";
	}
}
if($gaveta != "todos"){
	if($consulta == ""){
		if($posicion_gaveta == "todos"){
			$consulta="WHERE p.mis LIKE '%$gaveta' ";
		}
		else{
			$consulta="WHERE p.mis LIKE '%$posicion_gaveta$gaveta' ";
		}
	}
	else{
		if($posicion_gaveta == "todos"){
			$consulta=$consulta."AND p.mis LIKE '%$gaveta' ";
		}
		else{
			$consulta=$consulta."AND p.mis LIKE '%$posicion_gaveta$gaveta' ";
		}
	}
}

if($estado=="boveda"){
	if($consulta == ""){
		$consulta= "WHERE ((m.id_estado is null) OR (m.id_estado<=3)) ";
	}
	else{
		$consulta= $consulta."AND  ((m.id_estado is null) OR (m.id_estado<=3))  ";
	}
}else{
	if($estado=="prestados"){
		if($consulta == ""){
			$consulta= "WHERE ((m.id_estado > 3) AND (m.id_estado<=7)) ";
		}
		else{
			$consulta= $consulta."AND  ((m.id_estado > 3) AND (m.id_estado<=7))  ";
		}
	}else{
		if($estado=="cliente"){
			if($consulta == ""){
				$consulta= "WHERE (m.id_estado = 8) ";
			}
			else{
				$consulta= $consulta."AND  (m.id_estado = 8)  ";
			}
		}
	}
}
$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('logo',$resultado['logo01']);

$sql= "SELECT DISTINCT right(mis,1), right(mis,2), p.mis, p.nombres
FROM (propietarios p 
INNER JOIN carpetas c ON c.id_propietario=p.id_propietario 
INNER JOIN oficinas o ON c.id_oficina=o.id_oficina 
INNER JOIN tipos_bien t ON c.id_tipo_carpeta=t.id_tipo_bien) 
LEFT JOIN movimientos_carpetas m ON m.id_carpeta=c.id_carpeta AND (m.flujo!='1' OR m.id_estado='8') 
LEFT JOIN usuarios u ON u.id_usuario=m.id_us_corriente
LEFT JOIN usuarios us ON us.id_usuario=m.id_us_inicio
$consulta ORDER BY right(mis,1), right(mis,2), mis ";

$result= consulta($sql);
$i=0;
$carpetas = array();
while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		//cabecera
		$mis = trim($row["mis"]);
		$gaveta= substr($mis,strlen($mis)-1,strlen($mis));
		$posicion= substr($mis,strlen($mis)-2,1);
		$carpetas[] = array('gaveta'=>$gaveta,
							'posicion'=>$posicion,
							'mis'=>$mis,
							'nombres'=>$row["nombres"]);

}//fin del while
$smarty->assign('carpetas',$carpetas);
$smarty->display('reportes/inv_sobres_boveda_imp.html');
die();
?>