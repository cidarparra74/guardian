<?php
//session_start();

	
		$filtro_id_carpeta= $_REQUEST["idprop"];
		$filtro_carpeta= "AND c.id_propietario='$filtro_id_carpeta' ";	
	
	

/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

// lista de oficinas
$sql= "SELECT o.nombre AS oficina, o.id_oficina, a.nombre as almacen
FROM oficinas o INNER JOIN almacen a ON a.id_almacen = o.id_almacen ORDER BY a.nombre, o.nombre ";
//echo $sql;
$query = consulta($sql);

$oficinas= array();
$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$oficinas[$i]= array('id' => $row["id_oficina"],
						'oficina' => $row["oficina"],
						'almacen' => $row["almacen"]);
	$i++;
}
$smarty->assign('oficinas',$oficinas);

//recuperando los datos para la ventana
//filtro de propietarios
$sql= "SELECT c.id_carpeta, c.creacion_carpeta, c.carpeta, o.nombre AS o_nombre, tc.tipo_bien AS tipo_carpeta,  
 c.operacion, fecha_devolucion as fdev
FROM carpetas c, propietarios p, oficinas o, tipos_bien tc 
WHERE c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario 
AND c.id_tipo_carpeta=tc.id_tipo_bien $filtro_carpeta ORDER BY c.operacion ";
//echo $sql;
$query = consulta($sql);

$ids_carpeta= array();
$puede_mover=array();
$id_us_actual = $_SESSION['idusuario'];

$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$aux_c= explode(" ",$row["creacion_carpeta"]);
	//CAMBIAR LAS FECHA
	$meslit = dateDMESY(dateDMY($aux_c[0]));
	//$creacion_carpeta[$i] = $meslit;
	$ids_carpeta[$i]= array('id' => $row["id_carpeta"],
							'fecha' => $meslit,
							'carpeta' => $row["carpeta"],
							'operacion' => $row["operacion"],
							'o_nombre' => $row["o_nombre"],
							'tipo_carpeta' => $row["tipo_carpeta"],
							'fdev' => $row["fdev"]);

	//para ver si se puede mover la carpeta
	//(m.id_estado='8' AND m.flujo='1') --> CARPETA DEVUELTA AL CLIENTE
	// m.flujo=0 --> CARPETA EN MOV
	//m.flujo=1 -->FLUJO CERRADO
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT m.id_carpeta, m.auto_arch, m.id_estado, m.flujo, m.id_us_archivo, u.nombres 
	FROM movimientos_carpetas m LEFT JOIN usuarios u ON m.id_us_corriente=u.id_usuario
	WHERE ((m.id_estado='8' AND m.flujo='1') OR m.flujo=0) AND m.id_carpeta='$aux'  ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	if($row_a == null){
		$puede_mover[$i]= "si";
	}else{
		$puede_mover[$i]= "no";
	}
	
	$i++;
}

	$smarty->assign('ids_carpeta',$ids_carpeta);
	$smarty->assign('puede_mover',$puede_mover);
	$smarty->assign('idprop',$filtro_id_carpeta);
	
	
	$smarty->display('carpetas/trasladar.html');
	die();

?>
