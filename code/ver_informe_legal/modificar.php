<?php
///esto ya no se usa???? la  modificacion se la realiza tambien en adicionar.php
	$id= $_REQUEST["id"]; 
	
	$sql= "SELECT cliente, ci_cliente, id_tipo_identificacion, id_tipo_bien,
		motivo, montoprestamo, nrocaso
	FROM informes_legales WHERE id_informe_legal='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('id',$id);
	$smarty->assign('cliente',$resultado["cliente"]);
	$smarty->assign('ci_cliente',$resultado["ci_cliente"]);
	$smarty->assign('id_tipo_identificacion',$resultado["id_tipo_identificacion"]);
	$smarty->assign('id_tipo_bien',$resultado["id_tipo_bien"]);
	$smarty->assign('motivo',$resultado["motivo"]);
	$smarty->assign('montopre',$resultado["montopre"]);
//	$smarty->assign('nrocaso',$resultado["nrocaso"]);
	
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
	
	//recuperando los tipos de bien
	$sql= "SELECT * FROM tipos_bien ORDER BY id_tipo_bien WHERE con_recepcion = 'S'";
	$query = consulta($sql);
	$i=0;
	$tiposbien=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tiposbien[$i]= array('id' => $row["id_tipo_bien"],
							'descri' => $row["tipo_bien"]);
		$i++;
	}
	
	$smarty->assign('tiposbien',$tiposbien);
	$smarty->assign('identificacion',$identificacion);
	
	$smarty->display('ver_informe_legal/modificar.html');
	die();

?>
