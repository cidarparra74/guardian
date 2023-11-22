<?php

	//recuperar lista de regionales
$sql = "SELECT al.nombre as almacen, ofi.nombre as oficina, us.nombres as usuario, pr.nombres, tc.tipo_bien, 
	CONVERT(varchar,c.creacion_carpeta,103) as fecha, c.carpeta as obs, al.id_almacen, ofi.id_oficina, us.id_usuario
FROM carpetas c 
	LEFT JOIN propietarios pr
		ON pr.id_propietario = c.id_propietario
	LEFT JOIN usuarios us 
		ON us.id_usuario = c.id_usuario
	LEFT JOIN tipos_bien tc
		ON tc.id_tipo_bien = c.id_tipo_carpeta
	LEFT JOIN oficinas ofi
		ON ofi.id_oficina = c.id_oficina
	LEFT JOIN almacen al
		ON al.id_almacen = ofi.id_almacen
ORDER BY al.nombre, ofi.nombre, us.nombres, pr.nombres";
	$result = consulta($sql);
	$detalle = array();
	$al = 0;
	$ofi = 0;
	$us = 0;
	while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
		if($al<>$row['id_almacen'] or $ofi<>$row['id_oficina']){
			//vemos si es del mismo lugar
			$detalle[] = array('nombre' => $row['almacen'].'/'.$row['oficina'], 
							'tipo' => '++', 
							'fecha' => '',
							'obs' => '');
			//asumimos cambio de usuario
			$detalle[] = array('nombre' => $row['usuario'], 
							'tipo' => '--', 
							'fecha' => '',
							'obs' => '');
			$al=$row['id_almacen'];
			$ofi=$row['id_oficina'];
			$us=$row['id_usuario'];
		}else{
			if($us<>$row['id_usuario']){
				//cambio de usuario
				$detalle[] = array('nombre' => $row['usuario'], 
							'tipo' => '--', 
							'fecha' => '',
							'obs' => '');
				$us=$row['id_usuario'];
			}
		}
		$detalle[] = array('nombre' => $row['nombres'], 
							'tipo' => $row['tipo_bien'], 
							'fecha' => $row['fecha'],
							'obs' => $row['obs']);
	}
	$smarty->assign('detalle',$detalle);
		$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('logo',$resultado['logo01']);
	$smarty->display('reportes/avancedet.html');
	die();
?>

