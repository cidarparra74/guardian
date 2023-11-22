<?php

	$id= $_REQUEST['id'];
	
	//$sql= "SELECT c.id_carpeta, c.carpeta, c.id_propietario, c.id_oficina, c.id_tipo_carpeta, tc.tipo AS tipo_carpeta FROM carpetas c, propietarios p, oficinas o, tipos_carpetas tc ";
	//$sql.= "WHERE c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario AND c.id_tipo_carpeta=tc.id_tipo_carpeta AND c.id_carpeta='$id' ";
	$sql= "SELECT * FROM carpetas WHERE id_carpeta='$id' ";

	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$carpeta= $resultado["carpeta"];
	$operacion= $resultado["operacion"];
//	$p_id= $resultado["id_propietario"];
	$o_id= $resultado["id_oficina"];
	$c_id= $resultado["id_tipo_carpeta"];
	/*
	//propietarios
	$carp_filtro= "id_propietario='".$_SESSION["carpeta_id"]."' ";
	$sql= "SELECT id_propietario, nombres FROM propietarios WHERE $carp_filtro ORDER BY nombres ";
	$query = consulta($sql);
	$ids_propietario= array();
	$p_apellidos= array();
	$p_nombres= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_propietario[$i]= $row["id_propietario"];
		$p_apellidos[$i]= $row["apellidos"];
		$p_nombres[$i]= $row["nombres"];
		
		$i++;
	}
	*/
	//oficinas
	$id_almacen=$_SESSION['id_almacen'];
	$sql= "SELECT id_oficina, nombre FROM oficinas WHERE id_almacen= '$id_almacen' ORDER BY nombre ";
	$query = consulta($sql);
	$ids_oficina= array();
	//$o_nombre= array();

	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_oficina[$i]= array('id' => $row["id_oficina"],
								'nombre' => $row["nombre"]);
		$i++;
	}
	
	//tipos de carpetas
	$sql= "SELECT id_tipo_bien, tipo_bien FROM tipos_bien ORDER BY tipo_bien ";
	$query = consulta($sql);
	$ids_tipo_carpeta= array();
	//$s_tipo_carpeta= array();

	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_tipo_carpeta[$i]= array('id' => $row["id_tipo_bien"],
									'tipo' => $row["tipo_bien"]);
		$i++;
	}
	
	$smarty->assign('ids_tipo_carpeta',$ids_tipo_carpeta);
	//$smarty->assign('s_tipo_carpeta',$s_tipo_carpeta);
	
	$smarty->assign('id',$id);
	$smarty->assign('carpeta',$carpeta);
	$smarty->assign('operacion',$operacion);
	
	//$smarty->assign('p_id',$p_id);
	$smarty->assign('o_id',$o_id);
	$smarty->assign('c_id',$c_id);
	
	$smarty->assign('ids_oficina',$ids_oficina);
	//$smarty->assign('o_nombre',$o_nombre);
	
	//$smarty->assign('ids_propietario',$ids_propietario);
	//$smarty->assign('p_apellidos',$p_apellidos);
	//$smarty->assign('p_nombres',$p_nombres);
		
	$smarty->display('carpetas/modificar.html');
	die();
?>
