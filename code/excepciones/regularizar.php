<?php
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
require_once("../lib/fechas.php");

	$id= $_REQUEST["id"];
	//recuperando los datos de la excepcion
	$sql= "SELECT datediff(fecha_regularizacion,fecha_regula) AS dias, de.id_documento_excepcion, p.nombres AS nombre_cli, p.mis, d.documento, de.observacion, de.fecha_regularizacion, de.vigente, u.nombres 
	FROM propietarios p, carpetas c, documentos_excepciones de, documentos d, usuarios u ";
	$sql.= "WHERE de.id_documento_excepcion='$id' AND de.id_carpeta=c.id_carpeta AND c.id_propietario=p.id_propietario AND de.id_documento=d.id_documento AND de.id_responsable=u.id_usuario ORDER BY u.nombres ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$cliente=$resultado["nombre_cli"];
	$documento=$resultado["documento"];
	$nota=$resultado["observacion"];
	
	$aux_c= explode(" ",$resultado["fecha_regularizacion"]);
	$aux_d= $aux_c[0];
	//echo "$aux_d";
	$fecha_aux = dateDMY($aux_d);
	//$fecha_aux= $bd_fechas->formar_fecha($aux_d, "-", "dd/MMM/yyyy", "yyyy-mm-dd");
	$plazo=$fecha_aux;
	
	$dias=$resultado["dias"];
	if($resultado["vigente"]=="si"){
		$estado="vigente";
	}
	else{
		$estado="regularizado";
	}
	
	$responsable= $resultado["nombres"];

	
	$smarty->assign('id',$id);
	$smarty->assign('cliente',$cliente);
	$smarty->assign('documento',$documento);
	$smarty->assign('nota',$nota);
	$smarty->assign('plazo',$plazo);
	$smarty->assign('dias',$dias);	
	$smarty->assign('estado',$estado);	
	$smarty->assign('responsable',$responsable);
	
			
	$smarty->display('excepciones/regularizar.html');
	die();
?>
