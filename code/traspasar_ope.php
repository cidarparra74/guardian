<?php
//elaborar_informe
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="../code/_main.php?action=traspasar_ope.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//$enable_ws = $row["enable_ws"];
	$smarty->assign('enable_ws',$row["enable_ws"]);
	
	// leemos los valores de cada combo
	
	if(!isset($_REQUEST["filtro_almacen"])){
		//$id_oficina = $_SESSION["id_oficina"];
		$id_almacen = $_SESSION["id_almacen"];
	}else{
		$id_almacen = $_REQUEST["filtro_almacen"];
		//$id_oficina = $_REQUEST["filtro_oficina"];
	}
	if(!isset($_REQUEST["filtro_oficina"])){
		$id_oficina = $_SESSION["id_oficina"];
		//$id_oficina = 0; //$_SESSION["id_almacen"];
	}else{
		//$id_almacen = $_REQUEST["filtro_almacen"];
		$id_oficina = $_REQUEST["filtro_oficina"];
	}
	//si se presiona el boton de buscar
	if(isset($_REQUEST['filtro_usuario'])){
		$f_id_usuario= $_REQUEST['filtro_usuario'];
		//$_SESSION["inf_id_usuario"]=$f_id_usuario;
	}else{
		$f_id_usuario='ninguno';
	}

	//echo $f_id_usuario;
	//actualizamos las listas de cada combo
	
	
//----------------almacenes
	
	//recuperando la lista de almacenes
	$sql= "SELECT id_almacen, nombre FROM almacen ORDER BY nombre ";
	$query = consulta($sql);
	$i=0;
	$f_ids_almacen= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_ids_almacen[$i]  = array('id'=>$row["id_almacen"],
									'nombre'=> $row["nombre"]);
		$i++;
	}
	$smarty->assign('f_ids_almacen',$f_ids_almacen);
	
//-----------------oficinas
	$f_ids_oficina= array();
	if($id_almacen != 0){
		//recuperando la lista de oficinas de el primer almacen
		$sql= "SELECT id_oficina, nombre FROM oficinas WHERE id_almacen = $id_almacen ORDER BY nombre ";
		$query = consulta($sql);
		$i=0;
		$existe=0;
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$f_ids_oficina[$i]  = array('id'=>$row["id_oficina"],
										'nombre'=> $row["nombre"]);
			$i++;
			if($id_oficina==$row["id_oficina"]) $existe=1;
		}
		if($existe==0) $id_oficina = $f_ids_oficina[0]["id"];
	}
	$smarty->assign('f_ids_oficina',$f_ids_oficina);
	
//--------------------usuarios origen
	$f_ids_usuario= array();
	if($id_oficina != 0){
		//recuperando la lista de usuarios corrientes 
		$sql= "SELECT id_usuario, nombres FROM usuarios WHERE id_oficina = $id_oficina AND activo='S' ORDER BY nombres ";
		$query = consulta($sql);
		$i=0;
		$existe=0;
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$f_ids_usuario[$i]  = array('id'=>$row["id_usuario"],
										'nombres'=> $row["nombres"]);
			$i++;
			if($f_id_usuario==$row["id_usuario"]) $existe=1;
		}
		if($existe==0) $f_id_usuario = 0;
	}
	$smarty->assign('f_ids_usuario',$f_ids_usuario);
	
//--------------------usuarios destino
	$f_ids_usdest= array();
	if($id_oficina != 0){
		//recuperando la lista de usuarios  
		$sql= "SELECT us.id_usuario, us.nombres, al.nombre as almacen, ofi.nombre as oficina 
		FROM usuarios us 
		INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
		INNER JOIN almacen al ON al.id_almacen = ofi.id_almacen
		WHERE us.activo='S' ORDER BY al.nombre, ofi.nombre, us.nombres ";
		$query = consulta($sql);
		$i=0;
		$existe=0;
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$f_ids_usdest[$i]  = array('id'=>$row["id_usuario"],
										'nombres'=> $row["almacen"].' / '.$row["oficina"].' / '.$row["nombres"]);
			$i++;
			if($f_id_usuario==$row["id_usuario"]) $existe=1;
		}
		if($existe==0) $f_id_usuario = 0;
	}
	$smarty->assign('f_ids_usdest',$f_ids_usdest);
	
	$smarty->assign('f_id_oficina',$id_oficina);
	$smarty->assign('f_id_almacen',$id_almacen);
	$smarty->assign('f_id_usuario',$f_id_usuario);

	//armando la consulta
	$armar_consulta="";
	if($f_id_usuario != "ninguno"){
		$armar_consulta.= "AND il.id_us_comun='$f_id_usuario' ";
	}
	

	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	
	//trasladando
	if(isset($_REQUEST['traspasar_boton_x'])){
		include("./traspasando_ope.php");
	}
	
	
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/

//recuperando los datos para la ventana


// solo se muestran los datos de la oficina correspondiente y al responsable


$solicitudes= array();
if($armar_consulta != ""){

	$sql= "SELECT il.id_informe_legal, il.id_us_comun, il.fecha_solicitud, il.motivo, il.fecha_recepcion, 
	il.nrocaso, il.cliente, tb.tipo_bien 
	FROM informes_legales il
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien
	WHERE estado<>'pub' AND estado<> 'npu' $armar_consulta ORDER BY id_informe_legal DESC ";

	//echo "$sql";

	$query = consulta($sql);

	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		$aux_1= explode(" ",$row["fecha_solicitud"]);
		$sol_fecha= dateDMESY(dateDMY($aux_1[0]));
		$aux= $row["fecha_recepcion"];
		$aux_1= explode(" ",$aux);
		$rec_fecha=dateDMESY(dateDMY($aux_1[0]));
		
		$solicitudes[$i]= array('id'=>$row["id_informe_legal"],
								'motivo'=>$row["motivo"],
								'sol_tipo_bien'=>$row["tipo_bien"],
								'sol_cliente'=>$row["cliente"],
								'nrocaso'=>trim($row["nrocaso"]),
								'sol_fecha'=>$sol_fecha,
								'rec_fecha'=>$rec_fecha);
		$i++;
	}
}	
	$smarty->assign('solicitudes',$solicitudes);
	

	$smarty->display('traspasar_ope.html');
	die();

?>
