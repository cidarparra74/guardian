<?php
if(isset($_REQUEST['gaveta'])){
	$gaveta= $_REQUEST['gaveta'];
	$posicion_gaveta= $_REQUEST['posicion_gaveta'];
}else{
	$gaveta = "todos";
}
$tipo= $_REQUEST['tipo'];
$oficina= $_REQUEST['oficina'];
$estado= $_REQUEST['estado'];
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
		}else{
			if($consulta == ""){
				$consulta= "WHERE (m.id_estado = 9) ";
			}
			else{
				$consulta= $consulta."AND  (m.id_estado = 9)  ";
			}
		}
	}
}
$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('logo',$resultado['logo01']);

$sql= "SELECT p.mis, p.emision, p.nombres, t.tipo_bien, o.nombre, m.id_estado, m.flujo, 
convert(varchar,c.creacion_carpeta,103) as fecha, convert(varchar,m.corr_auto,103) as fecmov, 
us.nombres as solicita, c.operacion, c.nrocaso as cuenta
FROM (propietarios p 
INNER JOIN carpetas c ON c.id_propietario=p.id_propietario 
INNER JOIN oficinas o ON c.id_oficina=o.id_oficina 
INNER JOIN tipos_bien t ON c.id_tipo_carpeta=t.id_tipo_bien) 
LEFT JOIN movimientos_carpetas m ON m.id_carpeta=c.id_carpeta 
LEFT JOIN usuarios us ON us.id_usuario = m.id_us_inicio 
$consulta ORDER BY right(mis,1), right(mis,2) ";

$result= consulta($sql);
$i=0;
$carpetas = array();
while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
/*
		$gaveta= substr($row["mis"],strlen($row["mis"])-1,strlen($row["mis"]));
		$posicion= substr($row["mis"],strlen($row["mis"])-2,1);
		$gaveta_aux=$gaveta;
		$posicion_aux=$posicion;
*/
		//cabecera
		$mis = trim($row["mis"]);
		$gaveta= substr($mis,strlen($mis)-1,strlen($mis));
		$posicion= substr($mis,strlen($mis)-2,1);
		if($row["id_estado"]==null){
			$estado = "Boveda";
		}else{
			if($row["id_estado"]==8){
				if($row["flujo"]==0){
					$estado = "x Dev.";
				}else{
					$estado = "Dev.";
				}
			}else{
				if($row["id_estado"]>3){
					$estado = "Mov.";
				}else{
					$estado = "Bov.";
				}
			}
		}
		$carpetas[] = array('gaveta'=>$gaveta,
							'posicion'=>$posicion,
							'mis'=>$mis,
							'emision'=>$row["emision"],
							'nombres'=>$row["nombres"],
							'tipo'=>$row["tipo_bien"],
							'fecha'=>$row["fecha"],
							'fecmov'=>$row["fecmov"],
							'nombre'=>$row["nombre"],
							'solicita'=>$row["solicita"],
							'operacion'=>$row["operacion"],
							'cuenta'=>$row["cuenta"],
							'estado'=>$estado);

}//fin del while
$smarty->assign('carpetas',$carpetas);
$smarty->display('reportes/inv_auditoria_imp.html');
die();
?>