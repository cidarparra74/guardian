<?php

	//href
	$carpeta_entrar="_main.php?action=carpetas.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "documentos";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//filtro de busqueda para la ventana
	if(isset($_REQUEST["documentos_carpeta"])){
		$_SESSION["documentos_id"]= $_REQUEST["id_cpd"];
		$filtro_id_documento= $_REQUEST["id_cpd"];
	}else{
		$filtro_id_documento= $_SESSION["documentos_id"];
	}
	//$id_carpeta = $filtro_id_documento;
	//recuperando el nombre de la carpeta y el nombre del propietario
	$sql= "SELECT c.carpeta, p.nombres, p.mis FROM carpetas c, propietarios p WHERE c.id_carpeta= '$filtro_id_documento' AND c.id_propietario=p.id_propietario ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo_carpeta= $resultado["carpeta"];
	$titulo_propietario= $resultado["nombres"];
	
	$smarty->assign('titulo_carpeta',$titulo_carpeta);
	$smarty->assign('titulo_propietario',$titulo_propietario);

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	//guardando los documentos
//	if(isset($_REQUEST['guardar_documentos'])){
//		include("./documentos/guardando.php");
//	}
	
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/
/**********************valores por defecto*************************/
 
//recuperando los datos para la ventana, los documentos que tiene el propietario
$sql= "SELECT dp.id_documento_propietario, dp.id_documento, dp.id_tipo_documento,  ".
	"dp.numero_hojas, dp.observacion, dp.noobs FROM documentos_propietarios dp ";
$sql.= "WHERE dp.id_carpeta='$filtro_id_documento' ORDER BY dp.id_documento_propietario ";
//echo "$sql<br>";
$query = consulta($sql);

$mis_documentos= array();

$i=0;
//primera vez que se cargan los documentos

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

	$mis_documentos[$i]= array('id_documento' => $row["id_documento"],
								'id_tipo_documento' => $row["id_tipo_documento"],
								'numero_hojas' => $row["numero_hojas"],
								'observacion' => $row["observacion"],
								'noobs' => $row["noobs"],
								'id_dp' => $row["id_documento_propietario"]);
	$i++;
}
$cant_documentos=$i;
$smarty->assign('cant_documentos',$cant_documentos);
$smarty->assign('mis_documentos',$mis_documentos);


//recuperando todos los documentos de este tipo de carpeta
$sql= "SELECT d.id_documento, d.documento, d.post_desembolso, d.requerido, d.seguro  
FROM documentos d 
INNER JOIN tipos_bien_documentos tbd ON tbd.id_documento = d.id_documento 
inner join tipos_bien tb ON tb.id_tipo_bien=tbd.id_tipo_bien
WHERE tb.id_tipo_bien = ".$_REQUEST['id_tcarp'] ." ORDER BY d.documento";
$query = consulta($sql);

$los_documentos= array();

$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

	//verificando si el propietario tiene el documento
	$aux= $row["id_documento"];
	$falta=1;
	$id_tipo_documento = '';
	$p_numero_hojas = '';
	$p_observacion = '';
	$noobs = '0';
	$id_doc_pro = 0;
	for($a=0; $a<$cant_documentos; $a++){
		//echo $id_documento[$a];
		if($aux == $mis_documentos[$a]['id_documento']){
			$falta=0; //tiene el documento
			$id_tipo_documento = $mis_documentos[$a]['id_tipo_documento'];
			$p_numero_hojas = $mis_documentos[$a]['numero_hojas'];
			$p_observacion = $mis_documentos[$a]['observacion'];
			$noobs = $mis_documentos[$a]['noobs'];
			$id_doc_pro = $mis_documentos[$a]['id_dp'];
		}
	}
	$los_documentos[$i]= array('id_documento' => $row["id_documento"],
								'documento' =>  $row["documento"],
								'grupo' =>  $row["grupo"],
								'requerido' =>  $row["requerido"],
								'seguro' =>  $row["seguro"],
								'id_tipo' =>  $id_tipo_documento,
								'fojas' =>  $p_numero_hojas,
								'observacion' =>  $p_observacion,
								'falta' =>  $falta,
								'noobs' =>  $noobs,
								'id_doc_pro' =>  $id_doc_pro);
	$i++;
}
$cantidad_total=$i;
$smarty->assign('los_documentos',$los_documentos);
$smarty->assign('cantidad_total',$cantidad_total);

//recuperando lso tipos de documentos
$sql= "SELECT * FROM tipos_documentos ORDER BY tipo ";
$query = consulta($sql);
$tipos_doc= array();
$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$tipos_doc[$i]  = array('id_tipo' => $row["id_tipo_documento"],
							'tipo' => $row["tipo"]);
	$i++;
}
$smarty->assign('tipos_doc',$tipos_doc);
$fecha_actual= date("d/m/Y");
	
	$smarty->assign('fecha_actual',$fecha_actual);
	
	$smarty->display('documentos/documentos.html');
	die();

?>
