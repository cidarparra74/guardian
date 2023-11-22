<?php
//
// esto solo para BISA
//
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
//require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="../code/_main.php?action=nrocaso_aprob_bis.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	$alert = '';
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
//verificar si esta habilitado el WS
		$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$enable_ws = $row["enable_ws"];
		$smarty->assign('enable_ws',$row["enable_ws"]);
	if($enable_ws == 'N'){
		// caso baneco 1!! esto no se debiera dar aqui, esta opcion no se debe habilitar en baneco
		$title = "[I.L. Publicados] Pendientes de elaboraci&oacute;n de contrato";
		$title2 = "Autorizar elaboraci&oacute;n sin Informe Legal";
	}else{
		$title = "Solicitar elaboraci&oacute;n de contrato";
		$title2 = "Autorizar Contrato Sin Informe Legal";
	}
	if(isset($_REQUEST["aprobar_ncaso"])||isset($_REQUEST["rechazar_ncaso"])){
		$opciones = $_REQUEST['opcion'];
		//print_r($opciones); die('x');
		//$casos = $_REQUEST['casos'];
		//$informes = $_REQUEST['informes'];
		//Hay al menos una opcion marcada 
		if(count($opciones)>0){	
			if(isset($_REQUEST["rechazar_ncaso"])){
				
					foreach($opciones as $indice => $valor) {
						//$valor tiene el nrocaso
						$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal) VALUES ($valor, '-1')";
						ejecutar($sql);
					} 
					$alert="El o los n&uacute;meros han sido rechazados";
				
			}else{
				//es para aprobar
				$mensaje = '';
				//generamos un nro unico para estos informes 
				$nrocaso = $opciones[0];  //el primer nro de informe legal
				foreach($opciones as $indice => $valor) {
					//$valor tiene el nrocaso
					if($nrocaso != ''){
						if($enable_ws == 'N'){
							//require("../code/ws_desembolso_cidre.php");
						}
						//if($operacion != ''){
							//
		
							//solo insertamos si no existe previamente, caso contrario actualizamos
							$sql = "SELECT nrocaso FROM ncaso_cfinal WHERE nrocaso = '$nrocaso'";
							$query = consulta($sql);
							$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
							if($nrocaso != $row["nrocaso"])
								$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal) VALUES 
								('$nrocaso', '0')";
	
							ejecutar($sql);
							//actualizamos en i.l. el nro de credito
							$sql="UPDATE informes_legales SET nrocaso = '$nrocaso' WHERE id_informe_legal = '$valor'";
							ejecutar($sql);
						//}else{
						//	$mensaje .= $nrocaso.',';
						//}
					}
					//}
				}
				if($mensaje!='')
					$alert = 'Los n&uacute;meros siguientes no han sido encontrados: '.$mensaje;
				else
					$alert="El o los n&uacute;meros seleccionados han sido aprobados";
			}
		}else{ 
			$alert="Debe marcar alguno de los n&uacute;meros."; 
		}
	}


	
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/

if(!isset($_REQUEST['idc'])){
	//seleccionamos i.l. sin nro de caso
	$id_us_actual = $_SESSION["idusuario"]; //usuario guardian en sesion
	$id_almacen = $_SESSION["id_almacen"];
	$sql= "SELECT ile.id_informe_legal as nro, pr.nombres as cliente, ile.id_propietario, 
		CONVERT(VARCHAR(10), ile.fecha, 103) as fecha 
		FROM informes_legales ile INNER JOIN usuarios us ON us.id_usuario =ile.id_us_comun 
		INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
		INNER JOIN propietarios pr ON pr.id_propietario =ile.id_propietario
		WHERE ile.estado='pub' AND ile.nrocaso NOT IN (SELECT nrocaso FROM ncaso_cfinal)
		AND ile.nrocaso<>'' AND ofi.id_almacen = '$id_almacen'		
		ORDER BY pr.nombres, nro ";
	$smarty->assign('resumir','S');
}else{
	$idcliente = $_REQUEST["idc"]; // id cliente
	//vemos si tiene linea de cred
	$sql="SELECT * FROM lineas where id_propietario='$idcliente'";
	$query = consulta($sql);
	$lineas= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if($row["tipo"]=='1') $tipo = 'Rotativa';
		else $tipo = 'Simple';
		$lineas[] = array('id'=>$row["id_linea"],
							'numero'=>$row["numero"],
							'importe'=>$row["importe"],
							'moneda'=>$row["moneda"],
							'tipo'=>$tipo);
	}
	$smarty->assign('lineas',$lineas);
	//seleccionamos i.l. del cliente
	$sql= "SELECT ile.id_informe_legal as nro, pr.nombres as cliente,
		CONVERT(VARCHAR(10), ile.fecha, 103) AS fecha, ile.estado as id_propietario
		FROM informes_legales ile 
		INNER JOIN propietarios pr ON pr.id_propietario =ile.id_propietario 
		WHERE ile.id_propietario = '$idcliente'		
		ORDER BY ile.fecha";
	$smarty->assign('resumir','N');
	$smarty->assign('idcliente',$idcliente);
}
	
	$query = consulta($sql);
	$nrocasos= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$nrocasos[] = array('id'=>$row["nro"],
							'fecha'=>$row["fecha"],
							'cliente'=>$row["cliente"],
							'idc'=>$row["id_propietario"]);
	}
	$smarty->assign('nrocasos',$nrocasos);

	$smarty->assign('alert',$alert);
	$smarty->assign('title',$title);
	$smarty->assign('title2',$title2);
	$smarty->display('nrocaso_aprob_bis.html');
	die();

?>