<?php

/**********  Modificado  Victor Rivas ***********/
//print_r($_SESSION); echo $_SESSION["idperfil"]; die();
require_once('../lib/fechas.php');
if(!isset($id)){
	if (isset($numero_informe))
		$id = $numero_informe;
	else 
		$id = $_REQUEST['id'];
}
if(!isset($cat)){
	$cat = 0;  //solo en catastro_aprob se define esta variable y en carpetas.php con valor 3 para eliminacion de docs de recepcion //13/04/2015
}
//cargando para el overlib estado
	require_once("../lib/cargar_overlib.php");
	//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, enable_deldoc FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	$enable_deldoc = $row["enable_deldoc"];
	$perfilid = $_SESSION["idperfil"];
	$smarty->assign('enable_ws',$enable_ws);
	$smarty->assign('enable_deldoc',$enable_deldoc);
	$smarty->assign('perfilid',$perfilid);

	//vemos si hay instruccion de quitar algun doc
	if(isset($_REQUEST["quitar_doc"])){
		//quitamos el doc
		$id_doc = $_REQUEST["quitar_doc"];
		$sql="DELETE FROM documentos_informe WHERE din_id = $id_doc ";
		ejecutar($sql);
	}
	
	$sql= "SELECT il.id_tipo_bien, il.cliente, tb.tipo_bien, il.estado, il.bandera FROM informes_legales il ".
			" INNER JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien ".
			" WHERE il.id_informe_legal=$id ";
	
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_tipo_bien=$resultado["id_tipo_bien"];
	$cliente=$resultado["cliente"];
	$nombre_bien=$resultado["tipo_bien"];
	$estado=$resultado["estado"];
	$bandera=$resultado["bandera"]; // bandera servira para distinguir los refinanciados de recepciones nuevas
	// ver guardar_infordocu.php y refinanciar.php
	if($bandera=='x')
		$estado = 'ref'; //para q no se edite los docs ya recepcionados
	
	$smarty->assign('id_tipo_bien',$id_tipo_bien);
	$smarty->assign('estado',$estado);
	//$smarty->assign('bandera',$bandera);
	$smarty->assign('nombre_bien',$nombre_bien);
	$smarty->assign('cliente',$cliente);
	
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
	$registrado = array();
	$sql= "SELECT din_id, din_doc_id, din_tip_doc, fojas, obs, comentario, CONVERT(varchar,fechareg,103) AS fechareg, do.documento 
	FROM documentos_informe di LEFT JOIN documentos do ON do.id_documento=di.din_doc_id 
	WHERE din_inf_id='$id' ORDER BY do.documento";
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		$docInforme[$j]= array('idDoc' => $row["din_doc_id"],
							'idTip' => $row["din_tip_doc"],
							'obs' => $row["obs"],
							'coment' => $row["comentario"],
							'foja' => $row["fojas"]);
							
		$registrado[$j]= array(	'id' => $row["din_id"],
							'tipo' => $row["din_tip_doc"],
							'docu' => $row["documento"],
							'obs' => $row["obs"],
							'coment' => $row["comentario"],
							'foja' => $row["fojas"],
							'fechareg' => $row['fechareg']);
		$j++;
	}
	// hasta aqui $j contiene el nro de docs que tiene tipo de doc asignado

	//recpueramos la lista total de documentos
	//if($cat=='0'){
	$sql= "SELECT doc.id_documento, doc.documento, doc.requerido, doc.imagen 
		FROM tipos_bien_documentos tip 
		INNER JOIN documentos doc ON tip.id_documento = doc.id_documento 
		WHERE tip.id_tipo_bien = $id_tipo_bien 
		ORDER BY doc.requerido DESC, tip.orden ASC, doc.documento ASC";

	//echo $sql;
	$query = consulta($sql);

	$documentos = array();
//	echo $estado; echo $esRecepcion;
	$i = 0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		// recuperamos el ID del doc
		$idDoc = $row["id_documento"];
		// buscamos el idDoc en los docs del informe, para determinar su tipo de doc
		
		$tipDoc = 0 ;
		$foja = '' ;
		$obs = '' ;
		$coment = '' ; 
		//|| $esRecepcion==0
		if($estado=='rec'){
			foreach( $docInforme as $ind => $val ){
				if ( $val['idDoc'] == $idDoc ){
					//El doc es parte del informe, vemos su tipo de doc
					$tipDoc = $val['idTip'] ;
					$foja   = $val['foja'] ;
					$obs   = $val['obs'] ;
					$coment   = $val['coment'] ;
					break;
				}
			}
		}
		//almacenamos el doc completado
		$documentos[$i]= array( 'iddoc' => $idDoc,
								'docu' => $row["documento"],
								'tipo' => $tipDoc,
								'foja' => $foja,
								'obs' => $obs,
								'coment' => $coment,
								'idgru' => $row["requerido"],
								'imagen' => $row["imagen"]);
		
		$i++;
	}
	$cantidad_total=$i;

	$smarty->assign('documentos',$documentos);
	$smarty->assign('registrado',$registrado);
	$smarty->assign('id',$id);
	$smarty->assign('cantidad_total',$cantidad_total);
	$smarty->assign('id',$id);
	$smarty->assign('cat',$cat);

	$smarty->display('ver_informe_legal/documentos1.html');
	die();


?>
