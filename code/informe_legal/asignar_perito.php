<?php

$id= $_REQUEST["id"];
	
	$sql= "SELECT ile.*, tb.tipo_bien, tb.id_tipo_bien 
	FROM informes_legales ile 
	LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = ile.id_tipo_bien
	WHERE id_informe_legal='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	/*$result= $link->query($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);*/
	$cliente= $resultado["cliente"];
	$ci_cliente= $resultado["ci_cliente"];
	//$id_tipo_identificacion= $resultado["id_tipo_identificacion"];
	$tipo_bien= $resultado["tipo_bien"];
	$id_tipo_bien= $resultado["id_tipo_bien"];
	$id_us_comun= $resultado["id_us_comun"];
	
	$smarty->assign('id_perito',$resultado["id_perito"]);
	
	//recuperamos el nombre del solicitante
	$sql="SELECT * FROM usuarios WHERE id_usuario='$id_us_comun' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	/*$result= $link->query($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);*/
	$nombre_usuario= $resultado["nombres"];

	$id_almacen = $_SESSION['id_almacen'];
	
	//recuperando los peritos
//	$sql= "SELECT * FROM personas WHERE tipo_rol='P' ORDER BY nombres ";
	//buscamos peritos que sepan de la garantia y sean de la oficina
	$sql = "SELECT pe.id_persona, pe.apellidos, pe.nombres
	FROM personas pe 
		WHERE  pe.tipo_rol='P'
		AND pe.id_oficina = '$id_almacen'";
	//	INNER JOIN tipobien_persona tp ON tp.id_persona=pe.id_persona ...tp.id_tipo_bien = $id_tipo_bien AND
	$query = consulta($sql);
	
	$peritos=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$peritos[]=array('id' => $row["id_persona"],
							'nombres' => $row["apellidos"].' '.$row["nombres"]);
	}
	
	$smarty->assign('id',$id);
	$smarty->assign('cliente',$cliente);
	$smarty->assign('ci_cliente',$ci_cliente);
	//$smarty->assign('id_tipo_identificacion',$id_tipo_identificacion);
	$smarty->assign('tipo_bien',$tipo_bien);
	$smarty->assign('nombre_usuario',$nombre_usuario);
    $smarty->assign('peritos',$peritos);			
	$smarty->display('informe_legal/asignar_perito.html');
	die();

?>