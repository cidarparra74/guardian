<?php

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
	$sql= "SELECT * FROM tipos_bien ORDER BY id_tipo_bien ";
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
	
	//recuperando los tipos de documentos
	$sql= "SELECT * FROM tipos_documentos ORDER BY tipo ";
	$query = consulta($sql);
	$tiposDocs= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tiposDocs[$i]= array('idTipo' => $row["id_tipo_documento"] ,
							  'descri' => $row["tipo"]);
		$i++;
	}
	$smarty->assign('tiposDocs',$tiposDocs);

	//nombre del tipo de bien especificado al informe leg
	$j=0;
    $docInforme= array();
	$sql= "SELECT din_doc_id, din_tip_doc FROM documentos_informe WHERE din_inf_id='$id' ";
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$docInforme[$j]= array('idDoc' => $row["din_doc_id"],
							'idTip' => $row["din_tip_doc"]);
		$j++;
	}
	// hasta aqui $j contiene el nro de docs que tiene tipo de doc asigndo

//recpueramos la lista total de documentos

$sql= "SELECT tip.id_documento, doc.documento, doc.id_grupo_documento, gru.grupo ".
	"FROM tipos_bien_documentos tip ".
	"INNER JOIN documentos doc ".
	"ON tip.id_documento = doc.id_documento ".
	"INNER JOIN grupos_documentos gru ".
	"ON gru.id_grupo_documento=doc.id_grupo_documento ".
	"WHERE tip.id_tipo_bien = $id_tipo_bien";

$query = consulta($sql);

$documentos = array();
$grupos = array();
$idgrupo = 0;
$i = 0;
$g = 1;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	// recuperamos el ID del doc
	$idDoc = $row["id_documento"];
	// buscamos el idDoc en los docs del informe, para determinar su tipo de doc
	$tipDoc = 0 ;
	foreach( $docInforme as $ind => $val ){
		if ( $val['idDoc'] == $idDoc ){
			//El doc es parte del informe, vemos su tipo de doc
			$tipDoc = $val['idTip'] ;
			break;
		}
	}
	// vemos si se cambia el grupo
	if ( $idgrupo !=  $row["id_grupo_documento"] ){
		$grupos[$g] = $row["grupo"];
		$g++;
		$idgrupo =  $row["id_grupo_documento"];
	}
	//almacenamos el doc completado
	$documentos[$i]= array( 'iddoc' => $idDoc,
							'docu' => $row["documento"],
							'tipo' => $tipDoc,
							'idgru' => $g);
	$i++;
}
$cantidad_total=$i;
$igrupo= 0;

	$smarty->assign('grupos',$grupos);
	$smarty->assign('documentos',$documentos);
	$smarty->assign('id',$id);
	$smarty->assign('cantidad_total',$cantidad_total);
	$smarty->assign('igrupo',$igrupo);
	
	$smarty->display('ver_informe_legal/documentos1.html');
	die();


?>
