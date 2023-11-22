<?php
//  esto era para bsol ya no se usa
echo "No usar";
//die("no usar");
//require_once('../lib/lib/nusoap.php');
require_once("../lib/setup.php");
$smarty = new bd;	
//require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//recuperamos los datos del usuario

//vemos cual categoria vamos a procesar
if(isset($_REQUEST['cat'])){
	$cat = $_REQUEST['cat'] ;
	$_SESSION['cat'] = $cat ;
}else{
	$cat = '0';
	if(isset($_SESSION['cat']))
		$cat = $_SESSION['cat'];
	else
		$_SESSION['cat'] = $cat ;
}
	//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, autosolicita FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	$autosolicita = $row["autosolicita"];
	$smarty->assign('enable_ws',$enable_ws);
	$smarty->assign('autosolicita',$autosolicita);
	
	$id_us_actual = $_SESSION["idusuario"];
	$nombre_us_actual= $_SESSION["nombreusr"];
	$smarty->assign('id_us_actual',$id_us_actual);
	$smarty->assign('nombre_us_actual',$nombre_us_actual);
	
	//href
	$carpeta_entrar="./_main.php?action=instanciar.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "instanciar";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//filtro de la ventana
	if(isset($_REQUEST["filtro"])){
			$f_filtro= $_REQUEST["filtro"];
	}else{	$f_filtro = "ninguno";}
	if($f_filtro == "ninguno"){
		$f_cliente= "";
		$f_ci_cliente="";
		$_SESSION["inf_cliente"]="";
		$_SESSION["inf_ci_cliente"]="";
	}

	//si se presiona el boton de buscar
	if(isset($_REQUEST['buscar_boton'])){
		$f_cliente= $_REQUEST['filtro_cliente'];
		$f_ci_cliente= $_REQUEST['filtro_ci_cliente'];
		$_SESSION["inf_cliente"]=$f_cliente;
		$_SESSION["inf_ci_cliente"]=$f_ci_cliente;
	}
	else{
		$f_cliente=$_SESSION["inf_cliente"];
		$f_ci_cliente= $_SESSION["inf_ci_cliente"];
	}

	$smarty->assign('f_cliente',$f_cliente);
	$smarty->assign('f_ci_cliente',$f_ci_cliente);
	
	//armando la consulta
	$armar_consulta = "";
	if($f_cliente != ""){
		$armar_consulta.= "AND cliente LIKE '%$f_cliente%' ";
	}
	if($f_ci_cliente != ""){
		$armar_consulta.= "AND ci_cliente LIKE '%$f_ci_cliente%' ";
	}
		//es primer ingreso
		$smarty->assign('vertodo','N');
		$smarty->assign('alerta','');


/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
//---------------------
	// asignar numero de instancia BSOL
	if(isset($_REQUEST['asignarnro'])){
		
		include("./ver_informe_legal/asignarnro.php");
	}
	// asignando numero
	if(isset($_REQUEST["asignar_boton_x"])){
		$acc = $_REQUEST["asignar_boton_x"];
		include("./ver_informe_legal/asignandonro.php");
	}
	
	
	//impresion
	if(isset($_REQUEST['imprimir_recepcion'])){

		include("ver_informe_legal/imprimir_recepcion.php");
		
	}
	
	
	//viendo el detalle de fechas de este informe legal
	if(isset($_REQUEST["ver_detalle"])){
		include("./ver_informe_legal/ver_detalle.php");
	}
	
	
	
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los lugares de emision
	$sql= "SELECT * FROM emisiones ";
	$query = consulta($sql);
	$i=0;
	$emisiones=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$emisiones[$i]= $row["emision"];
		$i++;
	}

	$smarty->assign('emisiones',$emisiones);

//-------------------------------------------------------------------------
//para la lista de recepcionados sin informe legal SIN nro de caso/instancia (para BSOL)
$id_oficina = $_SESSION["id_oficina"];
if($armar_consulta == ""){
$sql = "SELECT ile.id_informe_legal, ile.cliente, tb.tipo_bien, tb.con_inf_legal, ile.fecha_recepcion, 
	ile.estado, ile.nrocaso as cuenta, ofi.nombre as oficina, us.nombres as usuario
	FROM informes_legales ile 
	INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
	INNER JOIN oficinas ofi ON ofi.id_oficina = ile.id_oficina 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	WHERE ile.estado='rec' AND ile.nrocaso = '' AND (ile.id_oficina = '$id_oficina' OR ile.id_us_comun = '$id_us_actual')
	ORDER BY ile.id_informe_legal DESC";
	//para ver las recepciones de la oficina y del usuario:
	//(ile.id_oficina = '$id_oficina' OR ile.id_us_comun = '$id_us_actual')
}else{
$sql = "SELECT TOP 20 ile.id_informe_legal, ile.cliente, tb.tipo_bien, tb.con_inf_legal, ile.fecha_recepcion, 
	ile.estado, ile.nrocaso as cuenta, ofi.nombre as oficina, us.nombres as usuario
	FROM informes_legales ile 
	INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
	INNER JOIN oficinas ofi ON ofi.id_oficina = ile.id_oficina  
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	WHERE (ile.id_oficina = '$id_oficina' OR ile.id_us_comun = '$id_us_actual') AND ile.nrocaso = '' $armar_consulta 
	ORDER BY ile.id_informe_legal DESC";
}
//echo $sql;

$query = consulta($sql);
$sin_lista=array();
$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$aux= $row["fecha_recepcion"];
	$aux_1= explode(" ",$aux);
	$aux=dateDMESY(dateDMY($aux_1[0]));
	$estado = $row["estado"];
	if($estado=='sol') $estadolit = 'Con solicitud de I.L.';
	elseif($estado=='apr') $estadolit = 'Aprobado para I.L.';
	elseif($estado=='ace') $estadolit = 'Aceptado para elaboracion de I.L.';
	elseif($estado=='arc') $estadolit = 'Pendiente de archivo';
	elseif($estado=='pub') $estadolit = 'Habilitado/Publicado';
	elseif($estado=='npu') $estadolit = 'No Habilitado';
	elseif($estado=='cat') $estadolit = 'En catastro';
	elseif($estado=='aut') $estadolit = 'Por autorizar revisión';
	elseif($estado=='ref') $estadolit = 'Refinanciado'; //este no se llega a mostrar
	else $estadolit = '???';
	$sin_lista[] = array('id_inf' => $row["id_informe_legal"],
						'clien' => $row["cliente"],
						'tbien' => $row["tipo_bien"],
						'con_il' => $row["con_inf_legal"],
						'estado' => $estado,
						'estadolit' => $estadolit,
						'cuenta' => trim($row["cuenta"]),
						'oficina' => trim($row["oficina"]),
						'usuario' => trim($row["usuario"]),
						'fecha' => $aux);
	$i++;
}
	$smarty->assign('sin_lista',$sin_lista);

//-------------------------------------------------------------------------
//para la lista de recepcionados sin informe legal con nro de caso
$id_oficina = $_SESSION["id_oficina"];
if($armar_consulta == ""){

$sql = "SELECT ile.id_informe_legal, ile.cliente, tb.tipo_bien, tb.con_inf_legal, ile.fecha_recepcion, 
	ile.estado, ile.nrocaso, ofi.nombre as oficina, us.nombres as usuario
	FROM informes_legales ile 
	INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
	INNER JOIN oficinas ofi ON ofi.id_oficina = ile.id_oficina 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	WHERE ile.estado='rec' AND tb.categoria = '$cat' AND ile.nrocaso <> '' 
	AND (ile.id_oficina = '$id_oficina' OR ile.id_us_comun = '$id_us_actual')
	ORDER BY ile.id_informe_legal DESC";
}else{
$sql = "SELECT TOP 20 ile.id_informe_legal, ile.cliente, tb.tipo_bien, tb.con_inf_legal, ile.fecha_recepcion, 
	ile.estado, ile.nrocaso, ofi.nombre as oficina, us.nombres as usuario
	FROM informes_legales ile 
	INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
	INNER JOIN oficinas ofi ON ofi.id_oficina = ile.id_oficina 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	WHERE (ile.id_oficina = '$id_oficina' OR ile.id_us_comun = '$id_us_actual')  AND tb.categoria = '$cat' 
	AND ile.nrocaso <> '' $armar_consulta 
	ORDER BY ile.id_informe_legal DESC";
}
//echo $sql;

$query = consulta($sql);
$rec_lista=array();
$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$aux= $row["fecha_recepcion"];
	$aux_1= explode(" ",$aux);
	$aux=dateDMESY(dateDMY($aux_1[0]));
	$estado = $row["estado"];
	if($estado=='sol') $estadolit = 'Con solicitud de I.L.';
	elseif($estado=='apr') $estadolit = 'Aprobado para I.L.';
	elseif($estado=='ace') $estadolit = 'Aceptado para elaboracion de I.L.';
	elseif($estado=='arc') $estadolit = 'Pendiente de archivo';
	elseif($estado=='pub') $estadolit = 'Habilitado/Publicado';
	elseif($estado=='npu') $estadolit = 'No Habilitado';
	elseif($estado=='cat') $estadolit = 'En catastro';
	elseif($estado=='aut') $estadolit = 'Por autorizar revisión';
	elseif($estado=='ref') $estadolit = 'Refinanciado'; //este no se llega a mostrar
	else $estadolit = '???';
	$rec_lista[] = array('id_inf' => $row["id_informe_legal"],
						'clien' => $row["cliente"],
						'tbien' => $row["tipo_bien"],
						'con_il' => $row["con_inf_legal"],
						'estado' => $estado,
						'estadolit' => $estadolit,
						'nrocaso' => trim($row["nrocaso"]),
						'oficina' => trim($row["oficina"]),
						'usuario' => trim($row["usuario"]),
						'fecha' => $aux);
	$i++;
}
	$smarty->assign('rec_lista',$rec_lista);
	

	$smarty->display('instanciar.html');
	die();

?>
	
