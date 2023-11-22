<?php

	//recuperando las gavetas del sistema
	$sql= "SELECT * FROM gavetas ORDER BY id_gaveta ";
	$result= consulta($sql);
	$ids_gaveta= array();
	$gaveta= array();
	$i=0;
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_gaveta[$i]= $row["id_gaveta"]-1;
		$gaveta[$i]= $row["gaveta"];
		$i++;
	}
	

	//recuperando los tipos de carpetas
	$sql= "SELECT id_tipo_carpeta, tipo FROM tipos_carpetas ORDER BY id_tipo_carpeta ";
	$result= consulta($sql);
	$ids_tipo= array();
	$tipo= array();
	$i=0;
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_tipo[$i]= $row["id_tipo_carpeta"];
		$tipo[$i]= $row["tipo"];
		
		$i++;
	}
	
	//recuperando las agencias del sistema
	$id_almacen = $_SESSION['id_almacen'];
	//recuperando las agencias del sistema
	$sql= "SELECT id_oficina, nombre FROM oficinas WHERE id_almacen='$id_almacen' ORDER BY nombre";
	$result= consulta($sql);
	$ids_oficina= array();
	$oficina= array();
	$i=0;
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_oficina[$i]= $row["id_oficina"];
		$oficina[$i]= $row["nombre"];
		
		$i++;
	}
	
	//para los estados
	$f_ids_estado=array();
	$f_estado= array();
	$f_ids_estado[0]=1;
	$f_ids_estado[1]=2;
	$f_ids_estado[2]=3;
	$f_ids_estado[3]=4;
	$f_ids_estado[4]=5;
	$f_ids_estado[5]=6;
	$f_ids_estado[6]=7;
	$f_ids_estado[7]=8;
	
	$f_estado[0]="Carpetas Solicitadas";
	$f_estado[1]="Carpetas Rechazadas";
	$f_estado[2]="Aceptados con Firma Autorizada";
	$f_estado[3]="Prestados a Solicitante sin Confirmar";
	$f_estado[4]="Prestados a Solicitante Confirmados";
	$f_estado[5]="Devueltos a Boveda por Solicitante sin Confirmar";
	$f_estado[6]="Devueltos a Boveda Confirmados";
	$f_estado[7]="Devueltos al Cliente";
	
	$smarty->assign('f_ids_estado',$f_ids_estado);
	$smarty->assign('f_estado',$f_estado);
	
	$smarty->assign('ids_gaveta',$ids_gaveta);
	$smarty->assign('gaveta',$gaveta);
	
	$smarty->assign('ids_tipo',$ids_tipo);
	$smarty->assign('tipo',$tipo);
	
	$smarty->assign('ids_oficina',$ids_oficina);
	$smarty->assign('oficina',$oficina);
	
	
	$smarty->display('reportes/historial_carpetas.html');
	die();
?>