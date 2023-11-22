<?php

	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM propietarios WHERE id_propietario='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$nombres= $resultado["nombres"];
	$mis= $resultado["mis"];
	$ci= $resultado["ci"];
	$telefonos= $resultado["telefonos"];
	$direccion= $resultado["direccion"];
	$estado_civil= $resultado["estado_civil"];
	$nit= $resultado["nit"];
	$id_tipo_id= $resultado["id_tipo_identificacion"];
	/*
	$sql = "SELECT ci FROM propietarios WHERE id_propietario!='$id' ORDER BY ci ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["ci"];

		$i++;
	}
	*/
	//recuperando los tipos de indentificacion
	$sql= "SELECT * FROM tipos_identificacion ORDER BY identificacion ";
	$query = consulta($sql);
	$i=0;
	$identificacion=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$identificacion[$i]= array('id' => $row["id_tipo"],
									'descri' => $row["identificacion"]);
		$i++;
	}

	$smarty->assign('identificacion',$identificacion);
	$smarty->assign('id_tipo_id',$id_tipo_id);
	$smarty->assign('id',$id);
	$smarty->assign('ci',$ci);
	$smarty->assign('nombres',$nombres);
	$smarty->assign('mis',$mis);
	$smarty->assign('telefonos',$telefonos);
	$smarty->assign('direccion',$direccion);
	$smarty->assign('nit',$nit);
	$smarty->assign('estado_civil',$estado_civil);
	
	//$smarty->assign('existentes',$login);
	
	$smarty->display('../templates/propietarios/modificar.html');
	die();
?>
