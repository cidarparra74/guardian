<?php

	$id= $_REQUEST['id'];
	
	//nombre del tipo de bien
	$sql= "SELECT * FROM tipos_carpetas WHERE id_tipo_carpeta='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$nombre_bien=$resultado["tipo"];
	
//recpueramos la lista total de documentos
//recuperando todos los documentos
$sql= "SELECT d.id_documento, d.id_grupo_documento, d.documento, d.vencimiento, g.grupo AS g_grupo ";
$sql.= "FROM documentos d, grupos_documentos g ";
$sql.= "WHERE d.id_grupo_documento=g.id_grupo_documento ORDER BY g.grupo ";
$query= consulta($sql);

$ids_documento= array();
$ids_grupo_documento= array();
$documento= array();
$grupo= array();
$vencimiento= array();
$tiene= array();
$p_ids_grupo_documento= array();
$aux=0;
$i=0;
$cont=0;
$aux_val="";
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$ids_documento[$i]= $row["id_documento"];
	$ids_grupo_documento[$i]= $row["id_grupo_documento"];
	$documento[$i]= $row["documento"];
	$grupo[$i]= $row["g_grupo"];
	$vencimiento[$i]= $row["vencimiento"];
	
	if($i==0){
		$aux_val=$row["id_grupo_documento"];
		$p_ids_grupo_documento[$cont]=$aux_val;
		$cont++;
	}
	else{
		if($aux_val != $row["id_grupo_documento"]){
			$aux_val= $row["id_grupo_documento"];
			$p_ids_grupo_documento[$cont]= $aux_val;
			$cont++;
		}
	}
	
	//verificando si este tipo de carpeta tiene este documento
	$sql_a= "SELECT id_documento FROM tipos_carpeta_documentos WHERE id_tipo_carpeta='$id' AND id_documento='".$row["id_documento"]."' ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	$aux= $row_a["id_documento"];
	
	if($aux == null){ //no tiene
		$tiene[$i]=0;
	}
	else{
		$tiene[$i]=1;
	}
	
	$i++;
}
$cantidad_total=$i;
	
	$lugar_primer_grupo= 0;

	$smarty->assign('cantidad_total',$cantidad_total);
	$smarty->assign('p_ids_grupo_documento',$p_ids_grupo_documento);
	$smarty->assign('lugar_primer_grupo',$lugar_primer_grupo);
	$smarty->assign('ids_documento',$ids_documento);
	$smarty->assign('ids_grupo_documento',$ids_grupo_documento);
	$smarty->assign('documento',$documento);
	$smarty->assign('grupo',$grupo);
	$smarty->assign('vencimiento',$vencimiento);
	$smarty->assign('tiene',$tiene);
	$smarty->assign('nombre_bien',$nombre_bien);
	$smarty->assign('id',$id);
	
	$smarty->display('adm/tipos_carpetas/documentos.html');
	die();
?>
