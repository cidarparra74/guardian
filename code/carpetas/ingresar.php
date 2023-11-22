<?php

/**********  Creado  Victor Rivas ***********/

/*
	 necesitamos pasar las variables siguientes: (para reutilizar adicionando.php)

	$id_propietario= $_REQUEST['id_propietarix'];
	$id_oficina= $_REQUEST['oficina']; ----------------------> la oficina del que recepciona il.id_us_comun
	$carpeta= $_REQUEST['carpeta'];  ----------------------------> descripcion
	$operacion= $_REQUEST['operacion']; --> se jala del WS despues
	$tipo_carpeta= $_REQUEST['tipo_carpeta'];   ------> tipo_bien FROM tipos_bien

	$usuario= $_SESSION["idusuario"];

*/

if(isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
}else die('no pasa el id');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	// RECUPERAMOS DATOS DEL INF LEGAL
		$sql = "SELECT cliente, ci_cliente, nrobien, nrocaso, tipo_bien, 
		il.motivo, convert(varchar(10),fecha_recepcion,103) as fechar,  
		convert(varchar(10),fecha_solicitud,103) as fechas,
		convert(varchar(10),fecha_aprob,103) as fechaa,
		us.nombres as usuario, id_propietario, il.id_tipo_bien, us.id_oficina
		FROM informes_legales il 
		LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien 
		LEFT JOIN usuarios us ON us.id_usuario = il.id_us_comun 
		WHERE id_informe_legal = $id " ;
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('vertodo','S');
		//$smarty->assign('id', $id); //ya esta abajo
		$smarty->assign('cliente', $row["cliente"]);
		$smarty->assign('ci_cliente', $row["ci_cliente"]);
		$smarty->assign('tipo_carpeta', $row["id_tipo_bien"]); // para adicionando.php
		$smarty->assign('tipo_bien', $row["tipo_bien"]);
		$smarty->assign('carpeta', $row["motivo"]);  // para adicionando.php
		$smarty->assign('nrobien', $row["nrobien"]);
		$smarty->assign('nrocaso', $row["nrocaso"]);
		$smarty->assign('fechar', $row["fechar"]);
		$smarty->assign('fechas', $row["fechas"]);
		$smarty->assign('fechaa', $row["fechaa"]);
		$smarty->assign('oficina', $row["id_oficina"]);  // para adicionando.php
		$smarty->assign('nombre_us_actual', $row["usuario"]);  
		$smarty->assign('id_propietarix', $row["id_propietario"]); // para adicionando.php
		$smarty->assign('alerta','NO');

	//documentos del informe
	$j=0;
    $docInforme= array();
	$documentos = array();
	$sql= "SELECT din_doc_id, fojas, obs, comentario, CONVERT(VARCHAR(10),fechareg,103) AS fechareg, do.documento, do.requerido, tipo
	FROM documentos_informe di LEFT JOIN documentos do ON do.id_documento=di.din_doc_id 
	LEFT JOIN tipos_documentos td ON di.din_tip_doc=td.id_tipo_documento
	WHERE din_inf_id='$id' ORDER BY do.requerido DESC, do.documento ASC";
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
							
		$documentos[$j]= array(	
							'docu' => $row["documento"],
							'obs' => $row["obs"],
							'tipo' => $row["tipo"],
							'coment' => $row["comentario"],
							'foja' => $row["fojas"],
							'idgru' => $row["requerido"],
							'fechareg' => $row['fechareg']);
		$j++;
	}
	// hasta aqui $j contiene el nro de docs que tiene tipo de doc asignado
	
	$smarty->assign('editar',$_REQUEST['ingresar']);
	$smarty->assign('documentos',$documentos);
	$smarty->assign('id',$id);
	
	$smarty->display('carpetas/ingresar.html');
	die();

?>