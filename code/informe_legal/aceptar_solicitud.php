<?php
//print_r($_REQUEST);
//die();

	$id= $_REQUEST["id"];
	//esto para saber que estado poner desde catastro_aprob.php
	$acep = $_REQUEST['aceptar_informe'];
	
	
	$sql= "SELECT il.id_propietario, il.id_us_comun, il.id_tipo_bien, pr.nombres, pr.ci, pr.emision, il.nrocaso
	FROM informes_legales il LEFT JOIN propietarios pr ON pr.id_propietario = il.id_propietario
	WHERE il.id_informe_legal='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$cliente= $resultado["nombres"];
	$ci_cliente= $resultado["ci"];
	$emision= $resultado["emision"];
	$nrocaso= $resultado["nrocaso"];
	$id_tipo_bien= $resultado["id_tipo_bien"];
	$id_us_comun= $resultado["id_us_comun"];
	$id_propietario= $resultado["id_propietario"];
	
	//recuperamos el nombe del solicitante
	$sql="SELECT * FROM usuarios WHERE id_usuario='$id_us_comun' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$nombre_usuario= $resultado["nombres"];

	//recuperando tipo de indentificacion
/*
	$sql= "SELECT ti.identificacion FROM propietarios pr
			LEFT JOIN tipos_identificacion ti ON pr.id_tipo_identificacion = ti.id_tipo 
			WHERE pr.id_propietario = $id_propietario";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$identificacion= $row["identificacion"];
*/
	
	//para el tipo de bien
	$sql = "SELECT tipo_bien FROM tipos_bien WHERE id_tipo_bien = '$id_tipo_bien'";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	if($row["tipo_bien"]!='') $smarty->assign('tipo_bien',$row["tipo_bien"]);


	//$smarty->assign('identificacion',$identificacion);
	
	$smarty->assign('id',$id);
	//$smarty->assign('cat',$cat);
	$smarty->assign('acep',$acep);
	//$smarty->assign('Tipo',$Tipo);
	$smarty->assign('cliente',$cliente);
	$smarty->assign('emision',$emision);
	$smarty->assign('nrocaso',$nrocaso);
	$smarty->assign('ci_cliente',$ci_cliente);
	$smarty->assign('id_tipo_bien',$id_tipo_bien);
	$smarty->assign('nombre_usuario',$nombre_usuario);
	
	$smarty->display('informe_legal/aceptar_solicitud.html');
	die();

?>
