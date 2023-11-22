<?php
//elaborar_informe
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="../code/_main.php?action=peritos_asignados2.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "peritos_asignados2";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	$id_oficina = $_SESSION["id_oficina"];
	
	//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//$enable_ws = $row["enable_ws"];
	$smarty->assign('enable_ws',$row["enable_ws"]);
	/*
	//recuperando la lista de usuarios corrientes ///WHERE id_perfil='2'
	$sql= "SELECT id_usuario, nombres FROM usuarios WHERE id_oficina = $id_oficina ORDER BY nombres ";
	$query = consulta($sql);
	$i=0;
	$f_ids_usuario= array();
	$f_usuario= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_ids_usuario[$i]= $row["id_usuario"];
		$f_usuario[$i]= $row["nombres"];
		$i++;
	}
	*/
	//recuperando los tipos de bien
	$sql= "SELECT * FROM tipos_bien ORDER BY id_tipo_bien ";
	$query = consulta($sql);
	$tiposbien=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tiposbien[]= array('id' => $row["id_tipo_bien"],
							'descri' => $row["tipo_bien"]);
	}
	$smarty->assign('tiposbien',$tiposbien);
	
	//buscamos peritos que sepan de la garantia y sean de la oficina
	$sql = "SELECT pe.id_persona, pe.apellidos, pe.nombres
	FROM personas pe WHERE tipo_rol='P' ORDER BY pe.apellidos";
	$query = consulta($sql);
	$peritos=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$peritos[]=array('id' => $row["id_persona"],
							'nombres' => $row["apellidos"].' '.$row["nombres"]);
	}
	$smarty->assign('peritos',$peritos);
	
/*
	if($f_filtro == "ninguno"){
	//	$f_id_usuario= "ninguno";
		$f_id_tipo_bien= "ninguno";
		$f_cliente= "";
		$f_ci_cliente="";
		$_SESSION["inf_id_usuario"]="ninguno";
		$_SESSION["inf_id_tipo_bien"]="ninguno";
		$_SESSION["inf_cliente"]="";
		$_SESSION["inf_ci_cliente"]="";
		//$_SESSION["inf_puede_operar"]="ninguno";
	}
	*/
	//si se presiona el boton de buscar
	if(isset($_REQUEST['buscar_boton']) || isset($_REQUEST['imprimir_boton'])){
		$f_nini= $_REQUEST['filtro_nini'];
		$f_nfin= $_REQUEST['filtro_nfin'];
		$f_cliente= $_REQUEST['filtro_cliente'];
		$f_ci_cliente= $_REQUEST['filtro_ci_cliente'];
		$id_perito= $_REQUEST['id_perito'];
		$id_tipo_bien= $_REQUEST['id_tipo_bien'];
		
		$_SESSION["f_nini"]=$f_nini;
		$_SESSION["f_nfin"]=$f_nfin;
		$_SESSION["id_perito"]=$id_perito;
		$_SESSION["id_tipo_bien"]=$id_tipo_bien;
		$_SESSION["inf_cliente"]=$f_cliente;
		$_SESSION["inf_ci_cliente"]=$f_ci_cliente;
	}
	else{
		$f_nini=$_SESSION["f_nini"];
		$f_nfin=$_SESSION["f_nfin"];
		$id_perito=$_SESSION["id_perito"];
		$id_tipo_bien=$_SESSION["id_tipo_bien"];
		$f_cliente=$_SESSION["inf_cliente"];
		$f_ci_cliente=$_SESSION["inf_ci_cliente"];
	}

	$smarty->assign('f_nini',$f_nini);
	$smarty->assign('f_nfin',$f_nfin); 
	$smarty->assign('id_perito',$id_perito); 
	$smarty->assign('id_tipo_bien',$id_tipo_bien); 
	$smarty->assign('f_cliente',$f_cliente);
	$smarty->assign('f_ci_cliente',$f_ci_cliente);
	
	//$smarty->assign('f_ids_usuario',$f_ids_usuario);
	//$smarty->assign('f_usuario',$f_usuario);
	
	//armando la consulta
	$armar_consulta="";
	if($f_nini != "" && $f_nfin != ""){
		$armar_consulta.= "AND il.id_informe_legal >= '$f_nini' AND il.id_informe_legal <= '$f_nfin' ";
	}elseif($f_nini != ""){
		$armar_consulta.= "AND il.id_informe_legal >= '$f_nini' ";
	}
	
	
	if($id_tipo_bien != "*"){
		$armar_consulta.= "AND il.id_tipo_bien='$id_tipo_bien' ";
	}elseif($id_tipo_bien == "*"){
		$armar_consulta.= "AND il.id_tipo_bien > '0' ";
	}
	
	if($id_perito != "*"){
		$armar_consulta.= "AND il.id_perito='$id_perito' ";
	}elseif($id_perito == "*"){
		$armar_consulta.= "AND il.id_perito > '0' ";
	}
	
	if($f_cliente != ""){
		$armar_consulta.= "AND cliente LIKE '%$f_cliente%' ";
	}
	if($f_ci_cliente != ""){
		$armar_consulta.= "AND ci_cliente LIKE '%$f_ci_cliente%' ";
	}

	
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	if(isset($_REQUEST['asignar_perito'])){
		
		include("./informe_legal/asignar_perito.php");
	}
	//asignando perido a un i.l.
	if(isset($_REQUEST['asignar_perito_x'])){
		include("./informe_legal/asignando_perito.php");
	}
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/

//recuperando los datos para la ventana use

// solo se muestran los datos de la oficina correspondiente y al responsable
	
$asignados= array();
if($armar_consulta != ""){
	$sql= "SELECT id_informe_legal, cliente, nrocaso, tb.tipo_bien, pe.nombres, pe.apellidos, il.id_perito 
	FROM informes_legales il, tipos_bien tb, personas pe
	WHERE il.id_tipo_bien = tb.id_tipo_bien AND il.id_perito = pe.id_persona
	AND il.id_perito<>0 AND tb.con_perito='S' $armar_consulta
	ORDER BY id_informe_legal DESC ";
	//echo $sql;
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$asignados[]= array('id_informe_legal'=>$row["id_informe_legal"],
							'sol_tipo_bien'=>$row["tipo_bien"],
							'sol_cliente'=>$row["cliente"],
							'nrocaso'=>trim($row["nrocaso"]),
							'id_perito'=>$row['id_perito'],
							'perito'=>$row['apellidos'].' '.$row['nombres']);
	}
}
	$smarty->assign('asignados',$asignados);

	$smarty->display('peritos_asignados2.html');
	die();

?>
