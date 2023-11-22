<?php
//session_start();
//movimientos_carpetas
require_once("../lib/setup.php");
$smarty = new bd;	

require_once('../lib/verificar.php');
require_once("../lib/fechas.php");
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	//href
	$carpeta_entrar="_main.php?action=carpetaslst.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "carpetaslst";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	//filtro de la ventana
	if(isset($_REQUEST["id_cp"])){
		//echo "entra desde la seleccion del propietario?";
		$_SESSION["carpeta_id"]= $_REQUEST["id_cp"];
		$filtro_id_carpeta= $_REQUEST["id_cp"];
	}else{
		$filtro_id_carpeta= $_SESSION["carpeta_id"];
		//echo $filtro_id_carpeta;
	}
	$filtro_carpeta= " il.id_propietario='$filtro_id_carpeta' ";
	
	//nombre del propietario y codigo mis
	$sql= "SELECT nombres, ci FROM propietarios WHERE id_propietario='$filtro_id_carpeta' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo_nombre= $resultado["nombres"];
	$titulo_mis= $resultado["ci"];
	
	$smarty->assign('titulo_nombre',$titulo_nombre);
	$smarty->assign('titulo_mis',$titulo_mis);

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	

	if(isset($_REQUEST['id_il'])){
		//recuperamos datos de l informe legal
		
		$sql="SELECT o.nombre as oficina, us.nombres as asesor,p.nombres as cliente, 
				p.ci, p.emision, tc.tipo_bien, tc.id_tipo_bien, il.nrocaso
		FROM informes_legales il
		INNER JOIN tipos_bien tc ON il.id_tipo_bien=tc.id_tipo_bien
		INNER JOIN usuarios us ON il.id_us_comun = us.id_usuario
		INNER JOIN propietarios p ON il.id_propietario = p.id_propietario
		left JOIN oficinas o ON us.id_oficina=o.id_oficina
		WHERE id_informe_legal = ".$_REQUEST['id_il'];
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$smarty->assign('oficina',$resultado["oficina"]);
		$smarty->assign('asesor',$resultado["asesor"]);
		$smarty->assign('cliente',$resultado["cliente"]);
		$smarty->assign('ci',$resultado["ci"].' '.$resultado["emision"]);
		$smarty->assign('tipo_bien',$resultado["tipo_bien"]);
		$smarty->assign('cuenta',$resultado["nrocaso"]);
		
		$id_tipo_bien= $resultado["id_tipo_bien"];
		$cuenta= $resultado["nrocaso"];
		//fecha de creacion de la carpeta --> envio a catastro
		$sql="SELECT convert(varchar,creacion_carpeta,103) as crea FROM carpetas WHERE id_informe_legal = ".$_REQUEST['id_il'];
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$fechaca= $resultado["crea"];
		$smarty->assign('fechaca',$fechaca);
		//
		$iduser = $_SESSION["idusuario"];
		$sql="SELECT nombres FROM usuarios WHERE id_usuario = '$iduser'";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('responsable',$resultado["nombres"]);
		//vemos q tipo de garantia es
		$sql="SELECT con_inf_legal FROM tipos_bien WHERE id_tipo_bien = '$id_tipo_bien'";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		if($resultado["con_inf_legal"] =='S'){
			$smarty->display('rotulo_hip.html');
		}else{
			//el usuario actual
			//jalamos demas datos de WS
			// mejorar esto si se jala antes al contabilizar o crear carpeta
			//ver conta_garantia.php linea 140 adelante
			//$nrocaso = $valor;
	//		require_once("ws_desembolso_bsol.php");
			//	echo $cuenta.'/'.$operacion.'/'.$destinocre;
	/*		if($operacion != '' && $operacion != '0'){
				//credito: (CUENTA CLIENTE, MONEDA Y OPERACIÓN) EJ. 45895-000-201281
				$smarty->assign('credito',$cuenta.'-'.$moneda.'-'.$operacion);
				$smarty->assign('fechade',$fdesembolso);
			}*/
			$smarty->display('rotulo_cus.html');
		}
		die();
	}
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana
$sql= "SELECT il.id_informe_legal, tc.tipo_bien,  
 o.nombre, il.nrocaso
FROM informes_legales il
INNER JOIN tipos_bien tc ON il.id_tipo_bien=tc.id_tipo_bien
INNER JOIN usuarios us ON il.id_us_comun = us.id_usuario
left JOIN oficinas o ON us.id_oficina=o.id_oficina
WHERE $filtro_carpeta   
ORDER BY tc.tipo_bien ";

$query = consulta($sql);

$ids_carpeta= array();

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$ids_carpeta[]= array('id' => $row["id_informe_legal"],
							'cuenta' => $row["nrocaso"],
							'tipo_bien' => $row["tipo_bien"],
							'oficina' => $row["nombre"]);
}

	$smarty->assign('ids_carpeta',$ids_carpeta);
	$smarty->display('carpetaslst.html');
	die();

?>
