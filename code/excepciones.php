<?php
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
require_once("../lib/fechas.php");
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
//datos del usuario

	$id_us_actual = $_SESSION["idusuario"];
	$nombre_us_actual= $_SESSION["nombreusr"];
	$smarty->assign('id_us_actual',$id_us_actual);
	$smarty->assign('nombre_us_actual',$nombre_us_actual);
	$smarty->assign('id_us_actual',$id_us_actual);
	$smarty->assign('nombre_us_actual',$nombre_us_actual);

	//href
	$carpeta_entrar="_main.php?action=excepciones.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "excepciones";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	
	//SE ESPERA QUE DE ERROR EN ALGUN MOMENTO POR LA TABLA DONDE SE ALMACENA
	//recuperando la lista de usuarios corrientes
	$sql= "SELECT id_usuario, nombres FROM usuarios WHERE activo='S' ORDER BY nombres ";
	$query= consulta($sql);
	$i=0;
	$f_ids_responsable= array();
	$f_responsable= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_ids_responsable[$i]= $row["id_usuario"];
		$f_responsable[$i]= $row["nombres"];
		$i++;
	}
		
	//filtro de la ventana
	//$f_filtro= $_REQUEST["filtro"];
	if(!isset($_REQUEST['buscar_boton'])){
		$f_id_responsable= "ninguno";
		$f_tipo= "ninguno";
		$_SESSION["arch_id_responsable"]="ninguno";
		$_SESSION["arch_tipo"]="ninguno";
	}
	
	//si se presiona el boton de buscar
	if(isset($_REQUEST['buscar_boton'])){
		$f_id_responsable= $_REQUEST['filtro_responsable'];
		$f_tipo= $_REQUEST['filtro_tipo'];
		
		$_SESSION["arch_id_responsable"]= $f_id_responsable;
		$_SESSION["arch_tipo"]= $f_tipo;
	}
	else{
		$f_id_responsable= $_SESSION["arch_id_responsable"];
		$f_tipo= $_SESSION["arch_tipo"];
	}
	
	
	
	$smarty->assign('f_ids_responsable',$f_ids_responsable);
	$smarty->assign('f_responsable',$f_responsable);
		
	$smarty->assign('f_tipo',$f_tipo);
	
	$smarty->assign('f_id_responsable',$f_id_responsable);
	$smarty->assign('f_tipo',$f_tipo);

	//armando la consulta
	$armar_consulta="";
	if($f_id_responsable != "ninguno"){
		$armar_consulta.="AND de.id_responsable='$f_id_responsable' ";
	}
	if($f_tipo == "pendientes"){
		$armar_consulta.="AND de.vigente='si' ";
	}
	elseif($f_tipo == "regularizados"){
		$armar_consulta.="AND de.vigente='no' ";
	}
	//echo "consulta: $armar_consulta <br>";
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	//ventana para aceptar la solicitud con firma autorizada
	if(isset($_REQUEST['regularizar'])){
		include("archivo/excepciones/regularizar.php");
	}
	
	//aceptando la solicitud con firma autorizada
	if(isset($_REQUEST['boton_regularizar_x'])){
		include("archivo/excepciones/regularizando.php");
	}
		
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana

if( ($f_tipo=="ninguno" && $f_id_responsable=="ninguno") || ($f_tipo==null && $f_id_responsable==null) ){
	$sql= "SELECT datediff(day,fecha_regularizacion,fecha_regula) AS dias, de.id_documento_excepcion, p.nombres AS nombre_cli, 
	p.mis, d.documento, de.observacion, de.fecha_regularizacion, de.vigente, u.nombres 
	FROM propietarios p, carpetas c, documentos_excepciones de, documentos d, usuarios u ";
	$sql.= "WHERE de.id_carpeta=c.id_carpeta AND c.id_propietario=p.id_propietario AND de.id_documento=d.id_documento 
	AND de.id_responsable=u.id_usuario ORDER BY u.nombres ";
}
else{
	$sql= "SELECT datediff(day,fecha_regularizacion,fecha_regula) AS dias, de.id_documento_excepcion, p.nombres AS nombre_cli, 
	p.mis, d.documento, de.observacion, de.fecha_regularizacion, de.vigente, u.nombres 
	FROM propietarios p, carpetas c, documentos_excepciones de, documentos d, usuarios u ";
	$sql.= "WHERE de.id_carpeta=c.id_carpeta AND c.id_propietario=p.id_propietario AND de.id_documento=d.id_documento 
	AND de.id_responsable=u.id_usuario $armar_consulta ORDER BY u.nombres ";
}

$query= consulta($sql);
$i=0;
$ids_de= array();
$cliente=array();
$documento=array();
$nota=array();
$plazo=array();
$dias=array();
$estado=array();
$responsable=array();

//lista de carpetas en solicitud con firma autorizada.....
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	
	if($i<100){

		$ids_de[$i]= $row["id_documento_excepcion"];	
		$cliente[$i]=$row["nombre_cli"];
		$documento[$i]=$row["documento"];
		$nota[$i]=$row["observacion"];
		
		$aux_c= explode(" ",$row["fecha_regularizacion"]);
		$aux_d= $aux_c[0];
		//echo "$aux_d";
		$fecha_aux = dateDMY($aux_d);
		//$fecha_aux= $bd_fechas->formar_fecha($aux_d, "-", "dd/MMM/yyyy", "yyyy-mm-dd");
		$plazo[$i]=$fecha_aux;
		
		$dias[$i]=$row["dias"];
		if($row["vigente"]=="si"){
			$estado[$i]="vigente";
		}
		else{
			$estado[$i]="regularizado";
		}
		
		$responsable[$i]= $row["nombres"];
	}
	$i++;

}
	

	$smarty->assign('ids_de',$ids_de);
	$smarty->assign('cliente',$cliente);
	$smarty->assign('documento',$documento);
	$smarty->assign('nota',$nota);
	$smarty->assign('plazo',$plazo);
	$smarty->assign('dias',$dias);	
	$smarty->assign('estado',$estado);	
	$smarty->assign('responsable',$responsable);	
				
	$smarty->display('../templates/excepciones.html');
	die();

?>
