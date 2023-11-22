<?php
/*
   contrato para ver a nivel usuario
*/
require_once("../lib/setup.php");
$smarty = new bd;
//require_once('../lib/conexionSEC.php');
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//echo $glogin;
	//preparamos datos del usuario


	//href
	$carpeta_entrar="_main.php?action=reppromocion.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "reppromocion";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	/*
	//filtro de la ventana
	if(!isset($_SESSION['filtro_usuario'])){
		//ponemos por defecto contratos de hoy
		$aux1 = date("d/m/Y");
		$_SESSION["filtro_fecha"]= $aux1;
		$_SESSION["filtro_fech2"]= $aux1;
		$_SESSION["filtro_usuario"]= '*';
		$_SESSION["filtro_bien"]= '*';
	}
	*/
	$filtro_usuario= '*';
		$filtro_bien= '*';
		$filtro_fecha= $aux1;
		$filtro_fech2= $aux1;
	
	$del_filtro='';	
	if(isset($_REQUEST['buscar_boton'])){
		
		$filtro_bien= $_REQUEST['filtro_bien'];
		$filtro_usuario= $_REQUEST['filtro_usuario'];
		$filtro_fecha= $_REQUEST['filtro_fecha'];
		$filtro_fech2= $_REQUEST['filtro_fech2'];
		
			//fecha
		if($filtro_fecha != '' && $filtro_fech2 == ''){
			$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), ps.ultima_fecha, 103), 103) = '$filtro_fecha' ";
		}
		if($filtro_fecha != '' && $filtro_fech2 != ''){
			$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), ps.ultima_fecha, 103), 103) >= CONVERT(DATETIME, '$filtro_fecha', 103) AND CONVERT(DATETIME, CONVERT(VARCHAR(10), ps.ultima_fecha, 103), 103) <= CONVERT(DATETIME, '$filtro_fech2', 103) ";
		}
	}//fin del if de buscar_boton
	/*
	else{
		$filtro_usuario= $_SESSION["filtro_usuario"];
		$filtro_bien= $_SESSION["filtro_bien"];
		$filtro_fecha= $_SESSION["filtro_fecha"];
		$filtro_fech2= $_SESSION["filtro_fech2"];
	}	
	*/
	//firma
	if($filtro_bien != "*"){
		$del_filtro= "AND tb.id_tipo_bien = '$filtro_bien' ";
	}
	
	//texto
	if($filtro_usuario != '*'){
		$del_filtro= $del_filtro."AND us.id_usuario = '$filtro_usuario' ";
	}
	

		/*
		//variables de sesion
		$_SESSION["filtro_usuario"]= $filtro_usuario;
		$_SESSION["filtro_bien"]= $filtro_bien;
		$_SESSION["filtro_fecha"]= $filtro_fecha;
		$_SESSION["filtro_fech2"]= $filtro_fech2;
		*/
	//filtro de la ventana

	$smarty->assign('filtro_usuario',$filtro_usuario);
	$smarty->assign('filtro_bien',$filtro_bien);
	$smarty->assign('filtro_fecha',$filtro_fecha);
	$smarty->assign('filtro_fech2',$filtro_fech2);

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	
	
/****************fin de valores para la ventana*************************/
/***********************************************************************/


/**********************valores por defecto*************************/
/******************************************************************/

//recuperando los tipos de bien
	$sql= "SELECT * FROM tipos_bien WHERE con_recepcion = 'S' ORDER BY id_tipo_bien ";
	$query = consulta($sql);
	$i=0;
	$tiposbien=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tiposbien[$i]= array('id' => $row["id_tipo_bien"],
							'descri' => $row["tipo_bien"]);
		$i++;
	}
	$smarty->assign('tiposbien',$tiposbien);
	
	
	$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('logo',$resultado['logo01']);
	
	$id_almacen = $_SESSION["id_almacen"];
	$sql= "SELECT us.id_usuario, us.nombres 
			FROM usuarios us, oficinas ofi 
			WHERE  us.id_oficina = ofi.id_oficina AND ofi.id_almacen = $id_almacen AND us.activo='S'
			ORDER BY nombres ";
	$query = consulta($sql);
	$i=0;
	$f_ids_usuario= array();
	//$f_usuario= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$f_ids_usuario[$i]= array('id_usuario' =>$row["id_usuario"],
								'nombres' =>$row["nombres"]);
		
		$i++;
	}
	$smarty->assign('f_ids_usuario',$f_ids_usuario);
	//
//recuperando los datos para la ventana
$listado= array();

if($del_filtro != ''){

$sql= "SELECT CONVERT(VARCHAR(10), ps.fecha, 103) AS fecha , 
CONVERT(VARCHAR(5), ps.fecha, 108) AS hora , ps.ci,
ps.emision, ps.nombre, tb.tipo_bien, us.nombres, ps.telefono,
 CONVERT(VARCHAR(10), ps.ultima_fecha, 103) AS ufecha , 
CONVERT(VARCHAR(5), ps.ultima_fecha, 108) AS uhora
FROM presolicitud ps
inner join tipos_bien tb on tb.id_tipo_bien = ps.id_tipo_bien
inner join usuarios us on us.id_usuario = ps.id_usuario ".$del_filtro.
" ORDER BY ps.ultima_fecha"; 
//echo $sql;
						
	$query= consulta($sql);
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

		$listado[$i]= array('fecha' => $row["fecha"].' '.$row["hora"],
							'ci' => $row["ci"].' '.$row["emision"],
							'telefono' => $row["telefono"],
							'ufecha' => $row["ufecha"].' '.$row["uhora"], 
							'tipo_bien' => $row["tipo_bien"], 
							'usuario' => $row["nombres"],
							'cliente' => $row["nombre"]);
		$i++;
	}
	$smarty->assign('listado',$listado);
	$smarty->display('reportes\reppromocion2.html');
	die();
}

	
	$smarty->display('reppromocion.html');
	die();

?>