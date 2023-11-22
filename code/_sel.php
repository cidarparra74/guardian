<?php
$sql = "select alm.id_almacen, alm.nombre as almacen, ofi.id_oficina, ofi.nombre as oficina, usr.nombres 
from usuarios usr  
inner join oficinas ofi on ofi.id_oficina = usr.id_oficina 
inner join almacen alm on alm.id_almacen = ofi.id_almacen 
where usr.activo='S' 
order by alm.nombre, ofi.nombre, usr.nombres";
$query = consulta($sql);
$almacenes = array();
$oficinas = array();
$usuarios = array();
$alm=0;
$ofi=0;
while($res = $query->fetchRow(DB_FETCHMODE_ASSOC)){ 
	if($alm != $res["id_almacen"]){
		$almacenes[] = array('ida'=>$res["id_almacen"],
					'almacen'=>$res["almacen"]);
		$alm=$res["id_almacen"];
	}
	if($ofi != $res["id_oficina"]){
		$oficinas[] = array('ida'=>$res["id_almacen"],
					'ido'=>$res["id_oficina"],
					'oficina'=>$res["oficina"]);
		$ofi=$res["id_oficina"];
	}
	$usuarios[] = array('ida'=>$res["id_almacen"],
					'ido'=>$res["id_oficina"],
					'idu'=>$res["id_usuario"],
					'usuario'=>$res["nombres"]);
}

?>