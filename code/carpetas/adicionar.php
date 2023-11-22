<?php
//se usa para adicionar y modificar, tambien para adicionar carpeta de contratos

	$id = $_REQUEST['adicionar']; //el id_carpeta
	$operacion = '';
	if(is_numeric($id)){
		$sql= "SELECT c.operacion, c.id_oficina, il.nrocaso, c.nrocaso
			FROM carpetas c
			LEFT JOIN informes_legales il ON il.id_informe_legal = c.id_informe_legal
			WHERE id_carpeta='$id' ";

		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$operacion = $resultado['operacion'];

		$smarty->assign('id',$id);
		$smarty->assign('id_oficina',$resultado["id_oficina"]);
		$smarty->assign('operacion',$resultado["operacion"]);
		$smarty->assign('cuenta',$resultado["nrocaso"]);
	}
	
	$id_almacen = $_SESSION['id_almacen'];
	//oficinas
	$sql= "SELECT id_oficina, nombre FROM oficinas WHERE id_almacen = $id_almacen	ORDER BY nombre ";
	$query = consulta($sql);
	$ids_oficina= array();

	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_oficina[]= array('id' => $row["id_oficina"],
								'nombre' => $row["nombre"]);
	}
	$smarty->assign('ids_oficina',$ids_oficina);
	//tipos de carpetas
	if($operacion != '')
		//los tipos de carpeta Carpeta Contratos
		$sql= "SELECT id_tipo_bien, tipo_bien FROM tipos_bien WHERE bien='4' and con_recepcion ='N' and cuenta='' ORDER BY tipo_bien ";
	else
		//todas las carpetas
		$sql= "SELECT id_tipo_bien, tipo_bien FROM tipos_bien ORDER BY tipo_bien ";
	$query = consulta($sql);
	$ids_tipo_carpeta= array();

	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_tipo_carpeta[]= array('id' => $row["id_tipo_bien"],
									'tipo' => $row["tipo_bien"]);
	}
	
	$smarty->assign('ids_tipo_carpeta',$ids_tipo_carpeta);
		
	$smarty->assign('id_propietarix',$filtro_id_carpeta);
	
	$smarty->display('carpetas/adicionar.html');
	die();
?>
