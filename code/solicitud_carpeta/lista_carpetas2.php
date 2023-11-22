<?php
//session_start();
	//href
	$carpeta_entrar="_main.php?action=revisar_carpeta.php";
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
	
	//filtro de la ventana
	if(isset($_REQUEST["carpeta_propietario"])){
		$_SESSION["carpeta_id"]= $_REQUEST["id_cp"];
		$filtro_id_carpeta= $_REQUEST["id_cp"];
		$filtro_carpeta= "AND c.id_propietario='$filtro_id_carpeta' ";	
	}
	else{
		$filtro_id_carpeta= $_SESSION["carpeta_id"];
		$filtro_carpeta= "AND c.id_propietario='$filtro_id_carpeta' ";
	}
	
	//nombre del propietario y codigo mis
	$sql= "SELECT * FROM propietarios WHERE id_propietario='$filtro_id_carpeta' ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo_nombre= $resultado["nombres"];
	$titulo_mis= $resultado["mis"];
	
	$smarty->assign('titulo_nombre',$titulo_nombre);
	$smarty->assign('titulo_mis',$titulo_mis);

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	

	
	//modificar prestamo carpeta
	if(isset($_REQUEST["modificar_prestamo"])){
		include("./solicitud_carpeta/modificar_prestamo.php");
	}
	
	//modificando prestamo carpeta
	if(isset($_REQUEST['modificar_prestamo_boton_x'])){
		include("./solicitud_carpeta/modificando_prestamo.php");
	}
	
	//eliminar prestamo carpeta
	if(isset($_REQUEST["eliminar_prestamo"])){
		include("./solicitud_carpeta/eliminar_prestamo.php");
	}
	
	//eliminando prestamo carpeta
	if(isset($_REQUEST['eliminar_prestamo_boton_x'])){
		include("./solicitud_carpeta/eliminando_prestamo.php");
	}	
	
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana
//filtro de propietarios
$id_almacen = $_SESSION["id_almacen"];
$smarty->assign('id_almacen',$id_almacen);

$sql= "SELECT c.id_carpeta, c.carpeta, 
	p.nombres AS p_nombres, p.mis AS p_mis, 
	o.nombre AS o_nombre, o.id_almacen,  
	tc.tipo_bien AS tipo_carpeta 
FROM carpetas c, 
	propietarios p, 
	oficinas o, 
	tipos_bien tc 
WHERE c.id_oficina=o.id_oficina
	AND c.id_propietario=p.id_propietario 
	AND c.id_tipo_carpeta=tc.id_tipo_bien 
	$filtro_carpeta 
ORDER BY p.nombres ";
//echo $sql;
$query = consulta($sql);

$ids_carpeta= array();
$puede_modificar= 'no';

$id_us_actual=$_SESSION['idusuario'];
$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	
	//para ver si se puede prestar la carpeta
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT m.id_carpeta, m.id_estado, m.flujo, m.id_us_corriente, u.nombres 
	FROM movimientos_carpetas m, usuarios u 
	WHERE ((m.id_estado='8' AND m.flujo='1') OR m.flujo=0) AND m.id_carpeta='$aux' AND m.id_us_corriente=u.id_usuario ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	
		if($row_a == null){
			$puede_prestar="si";		
			$nombres_us="";
		}else{
			//if($row_a["id_us_corriente"] == $id_us_actual){
			//	$puede_modificar= "si";
			//}else{
			//	$puede_modificar= "no";
			//}
			$puede_prestar="no";
			$nombres_us= $row_a["nombres"];
		}
		$los_estados = $row_a["id_estado"];
	//}else{
	//	$puede_prestar="no";
		//$puede_modificar= "si";
	if($id_almacen != $row["id_almacen"]){
		$los_estados = 0;
	}
	$ids_carpeta[]= array(  'id_carpeta' => $row["id_carpeta"],
							'carpeta' => $row["carpeta"],
							'p_nombres' => $row["p_nombres"],
							'p_mis' => $row["p_mis"],
							'o_nombre' => $row["o_nombre"],
							'tipo_carpeta' => $row["tipo_carpeta"],
							'los_estados' => $los_estados,
							'puede_prestar' => $puede_prestar,
							'nombres_us' => $nombres_us,
							'puede_modificar' => $puede_modificar);

}

	$smarty->assign('ids_carpeta',$ids_carpeta);
		
	$smarty->display('solicitud_carpeta/lista_carpetas2.html');
	die();

?>
