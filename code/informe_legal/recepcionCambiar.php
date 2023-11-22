<?php

$id= $_REQUEST["id"];
	
	$sql= "SELECT ile.*, tb.tipo_bien, tb.id_tipo_bien, o.nombre agencia
	FROM informes_legales ile 
	INNER JOIN oficinas o ON o.id_oficina=ile.id_oficina
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
	$agencia=$resultado["agencia"];
	
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
	$sql = "SELECT id_oficina, nombre
	FROM oficinas o
		WHERE o.id_almacen = '$id_almacen'";
	
	$query = consulta($sql);
	
	$peritos=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$peritos[]=array('id' => $row["id_oficina"],
							'nombres' => $row["nombre"]);
	}
	
	$smarty->assign('id',$id);
	$smarty->assign('cliente',$cliente);
	$smarty->assign('ci_cliente',$ci_cliente);
	$smarty->assign('agencia',$agencia);
	$smarty->assign('tipo_bien',$tipo_bien);
	$smarty->assign('nombre_usuario',$nombre_usuario);
        $smarty->assign('peritos',$peritos);			
	$smarty->display('informe_legal/recepcionCambiar.html');
	die();

?>