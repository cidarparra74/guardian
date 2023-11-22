<?php

$id= $_REQUEST["id"];
	
	$sql= "SELECT ile.*, tb.tipo_bien FROM informes_legales ile LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = ile.id_tipo_bien
	WHERE id_informe_legal='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	/*$result= $link->query($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);*/
	$cliente= $resultado["cliente"];
	$ci_cliente= $resultado["ci_cliente"];
	//$id_tipo_identificacion= $resultado["id_tipo_identificacion"];
	$tipo_bien= $resultado["tipo_bien"];
	$id_us_comun= $resultado["id_us_comun"];
	
	//recuperamos el nombre del solicitante
	$sql="SELECT * FROM usuarios WHERE id_usuario='$id_us_comun' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	/*$result= $link->query($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);*/
	$nombre_usuario= $resultado["nombres"];

	
	//recuperando los usuarios que pueden realizar informes legales
	//$id_usante=$_REQUEST['idus'];
	$sql= "SELECT id_usuario, nombres FROM usuarios 
			WHERE activo='S' AND id_usuario<>(SELECT usr_acep FROM informes_legales WHERE id_informe_legal='$id') 
			ORDER BY nombres ";
	$query = consulta($sql);
	$i=0;
	$ids_usr=array();
	$usuarios=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_usr[$i]=$row["id_usuario"];
		$usuarios[$i]= $row["nombres"];
		$i++;
	}
	
	$smarty->assign('id',$id);
	$smarty->assign('cliente',$cliente);
	$smarty->assign('ci_cliente',$ci_cliente);
	//$smarty->assign('id_tipo_identificacion',$id_tipo_identificacion);
	$smarty->assign('tipo_bien',$tipo_bien);
	$smarty->assign('nombre_usuario',$nombre_usuario);
    $smarty->assign('usuarios',$usuarios);			
	$smarty->assign('ids_usr',$ids_usr);
	$smarty->display('informe_legal/reasignar_usuario.html');
	die();

?>
