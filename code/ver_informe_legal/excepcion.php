<?php

if(isset($flag)){
	//viene de un correo
	require_once('../lib/setup.php');
	
	$smarty = new bd;
	$id= $flag; //id del informe_legal
	$smarty->assign('flag',$flag);
	//if(isset($_REQUEST["flag"])){
	$carpeta_entrar="./ver_informe_legal/excepcionreg2.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	
	// validar que exista el nro de informe
	$sql="SELECT id_informe_legal FROM informes_legales WHERE id_informe_legal = '$id'";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id2 = $resultado["id_informe_legal"];
	if($id!=$id2) die("El n&uacute;mero asociado no se encuentra. Imposible continuar.");
	
}else{
	$id= $_REQUEST["id"]; //id del informe_legal
	$smarty->assign('flag','0');
}

$smarty->assign('id',$id);

//recuperamos datos basicos del I.L.
$sql =  "SELECT ile.cliente, ile.ci_cliente, tii.identificacion, tbi.tipo_bien, ".
			"ile.otras_observaciones, ile.conclusiones, ile.motivo, ile.id_us_comun, 
			ile.exe_justifica, ile.exe_aprobar, ile.bandera ".
			"FROM informes_legales ile ".
			"LEFT JOIN tipos_identificacion tii ON id_tipo = id_tipo_identificacion ".
			"LEFT JOIN tipos_bien tbi ON tbi.id_tipo_bien = ile.id_tipo_bien ".
			"WHERE id_informe_legal = $id ";
//echo $sql;
	$query = consulta($sql);
	
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$otras_observaciones = $resultado["otras_observaciones"];
	$id_us_comun = $resultado["id_us_comun"];
	
	$smarty->assign('cliente',$resultado["cliente"]);
	$smarty->assign('ci_cliente',$resultado["ci_cliente"]);
	$smarty->assign('identificacion',$resultado["identificacion"]);
	$smarty->assign('tipo_bien',$resultado["tipo_bien"]);
	$smarty->assign('otras_observaciones',$otras_observaciones);
	$smarty->assign('conclusiones',$resultado["conclusiones"]);
	$smarty->assign('motivo',$resultado["motivo"]);
	$smarty->assign('justifi',$resultado["exe_justifica"]);
	$smarty->assign('aprobar',$resultado["exe_aprobar"]);
	//para la badera
	$banderaimg = 'b'.$resultado["bandera"].'.png';
	$smarty->assign('banderaimg',$banderaimg);
	
	//leemos parametros especiales
	$sql= "SELECT TOP 1 il_estado_fin  FROM opciones ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	// para el estado final del I.L (Rojo;amarillo;Verde)
	$il_estado=explode(';',$row["il_estado_fin"]);
	if($resultado["bandera"]=='r')     $smarty->assign('banderatxt',$il_estado[0]);
	elseif($resultado["bandera"]=='a') $smarty->assign('banderatxt',$il_estado[1]);
	elseif($resultado["bandera"]=='v') $smarty->assign('banderatxt',$il_estado[2]);
	else $smarty->assign('banderatxt',$il_estado[3]);



// verificamos si ya tiene registro en excepciones
$existentes = array();
$sql= "SELECT ex.*, CONVERT(VARCHAR,ex.exce_limite,103) as limite, do.documento FROM excepciones ex 
LEFT JOIN documentos do ON do.id_documento = ex.id_documento
WHERE id_informe_legal = '$id' 
ORDER BY clase";
$query = consulta($sql);
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$existentes[] = array('id_documento'=>$row["id_documento"],
							'exce_tipo'=>$row["exce_tipo"],
							'exce_resp'=>$row["exce_resp"],
							'exce_limite'=>$row["limite"]);

}

//hay registros?
//if(empty($excepciones)){
	//esta vacio, llenamos con los valores correspondientes
	
//Recuperamos lista de documentos que tienen observaciones
	$sql= "SELECT
    CONVERT(nvarchar(10),informes_legales_documentos.fecha,103) as fecha, 
	informes_legales_documentos.fojas, informes_legales_documentos.observaciones,
	CONVERT(nvarchar(10),informes_legales_documentos.fecha_vencimiento,103) as fecha_vencimiento,
    documentos.documento, documentos.id_documento,
    tipos_documentos.tipo
FROM
    { oj (informes_legales_documentos INNER JOIN tipos_documentos ON
        informes_legales_documentos.id_tipo_documento = tipos_documentos.id_tipo_documento)
     INNER JOIN documentos documentos ON
        informes_legales_documentos.id_documento = documentos.id_documento}
WHERE
    informes_legales_documentos.tiene_observacion = 1 AND
    informes_legales_documentos.id_informe_legal = $id AND
    informes_legales_documentos.tomar_en_cuenta = 1 AND
    informes_legales_documentos.fojas <> 0
	";
$excepciones=array();
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$exce_tipo='-';
		$exce_resp=$id_us_comun;
		$limite='';
		
		foreach($existentes as $valor){
			if($valor['id_documento'] == $row["id_documento"]){
				$exce_tipo= $valor['exce_tipo'];
				$exce_resp= $valor['exce_resp'];
				$limite= $valor['exce_limite'];
				break;
			}
		}
		
		$excepciones[]=array('fecha' => $row["fecha"] ,
							'fojas' => $row["fojas"] ,
							'fecha_vencimiento' => $row["fecha_vencimiento"] ,
							'tipo' => $row["tipo"],
							'id_documento'=>$row["id_documento"],
							'documento'=>$row["documento"],
							'obs'=>$row["observaciones"],
							'exce_tipo'=>$exce_tipo,
							'exce_resp'=>$exce_resp,
							'exce_limite'=>$limite,
							'clase'=>'A');
							
	}
	
	//Recuperamos lista de documentos que son requeridos pero faltan
	$sql= "SELECT
    documentos.documento, documentos.id_documento
FROM
    { oj informes_legales_documentos INNER JOIN documentos ON
        informes_legales_documentos.id_documento = documentos.id_documento}
WHERE
    informes_legales_documentos.fojas = 0 AND
    informes_legales_documentos.id_informe_legal = $id AND
    informes_legales_documentos.tomar_en_cuenta = 1
	";

	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		$exce_tipo='-';
		$exce_resp=$id_us_comun;
		$limite='';
		
		foreach($existentes as $valor){
			if($valor['id_documento'] == $row["id_documento"]){
				$exce_tipo= $valor['exce_tipo'];
				$exce_resp= $valor['exce_resp'];
				$limite= $valor['exce_limite'];
				break;
			}
		}
		
		$excepciones[]=array('fecha' => '' ,
							'fojas' => '0' ,
							'fecha_vencimiento' => '' ,
							'tipo' => '',
							'id_documento'=>$row["id_documento"],
							'documento'=>$row["documento"],
							'obs'=>'Documento faltante',
							'exce_tipo'=>$exce_tipo,
							'exce_resp'=>$exce_resp,
							'exce_limite'=>$limite,
							'clase'=>'B');
	}
	
	//vemos otras observaciones

	if($resultado["otras_observaciones"]!=''){
		
		$exce_tipo='-';
		$exce_resp=$id_us_comun;
		$limite='';
		
		foreach($existentes as $valor){
			if($valor['id_documento'] == 0){
				$exce_tipo= $valor['exce_tipo'];
				$exce_resp= $valor['exce_resp'];
				$limite= $valor['exce_limite'];
				break;
			}
		}
		
		$excepciones[]=array('fecha' => '' ,
							'fojas' => '0' ,
							'fecha_vencimiento' => '' ,
							'tipo' => '',
							'id_documento'=>0,
							'documento'=>'',
							'obs'=>$otras_observaciones,
							'exce_tipo'=>$exce_tipo,
							'exce_resp'=>$exce_resp,
							'exce_limite'=>$limite,
							'clase'=>'C');
	}
	
//}// empty()

	$smarty->assign('excepciones',$excepciones);
	
	//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	$smarty->assign('enable_ws',$enable_ws);
	
	if($enable_ws=='S'){
		$sql =  "SELECT us.id_usuario, LEFT(us.nombres,19) as nombre FROM usuarios us 
			INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina
		WHERE id_usuario = $id_us_comun";
	}elseif($enable_ws=='A'){
		//$id_us = $_SESSION["id_usuario"];(SELECT id_oficina FROM usuarios WHERE id_usuario = $id_us)
		if(isset($_SESSION["id_oficina"])){
			$id_of = $_SESSION["id_oficina"];
		}else{
			$sql =  "SELECT id_oficina FROM usuarios WHERE id_usuario = $id_us_comun";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$id_of = $row["id_oficina"];
		}
		$sql =  "SELECT us.id_usuario, LEFT(us.nombres,19) as nombre FROM usuarios us 
			INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina
		WHERE us.activo='S' AND ofi.id_oficina = $id_of
		ORDER BY us.nombres";
	}else{
		//$id_oficina = $_SESSION["id_oficina"];  NO HABILITAR ESTO!
		// Recuperamos usuarios de la agencia para responder excepciones
		$sql =  "SELECT us.id_usuario, LEFT(us.nombres,19) as nombre FROM usuarios us 
			INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina
		WHERE us.activo='S' AND ofi.id_oficina = (SELECT id_oficina FROM usuarios WHERE id_usuario = $id_us_comun)
		ORDER BY us.nombres";
	}
	//.$_SESSION["id_oficina"];
	$query = consulta($sql);
	$revisores = array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$revisores[$i] = array('id_usuario' => $row["id_usuario"],
								'nombre' => $row["nombre"] );
		$i++;
	}
	$smarty->assign('revisores',$revisores);
	
	$smarty->display('ver_informe_legal/excepcion.html');
	die();

?>