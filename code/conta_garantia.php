<?php
//elaborar_informe
require_once("../lib/setup.php");
$smarty = new bd;
require_once('../lib/verificar.php');
//require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="../code/_main.php?action=conta_garantia.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	$alert="";
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	
	$smarty->assign('enable_ws',$enable_ws);
	if($enable_ws == 'S'){
		// caso bsol
		$title = "Contabilizar Garant&iacute;as";
		$title2 = "Adicionar Instancia";
	}else{
		die("No usar esta opcion - bsol");
		
	}
	if(isset($_REQUEST["aprobar_ncaso"])||isset($_REQUEST["rechazar_ncaso"])){
		$opciones = $_REQUEST['opcion'];
		
		if(count($opciones)>0){
			
			//Hay al menos una opcion marcada 
			if(isset($_REQUEST["rechazar_ncaso"])){
				foreach($opciones as $indice => $valor) {
					//$valor tiene el id carpeta
					//$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal) VALUES ($valor, '-1')";
					$sql="UPDATE carpetas SET cuenta = '(rechazado)' WHERE id_carpeta = $valor";
					ejecutar($sql);
				} 
				$alert="El o los n&uacute;meros han sido rechazados";
			}else{
				//es para aprobar
				$mensajeG = '';
				foreach($opciones as $indice => $valor) {
					//$valor tiene el id carpeta
					//buscamos valores adicionales del web SERVICE -- ya no por que ya tiene operacion 
					//caso BSOL
					$id_carpeta = $valor;
					//require_once("ws_desembolso_bsol.php");
					//	echo $cuenta.'/'.$operacion.'/'.$destinocre;
				//	if($operacion != '' && $operacion != '0'){
						//contabilizamos
						$sql = "SELECT il.nrocaso, tb.cuenta, ca.operacion
						FROM carpetas ca
						LEFT JOIN informes_legales il ON ca.id_informe_legal = il.id_informe_legal
						LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien
						WHERE ca.id_carpeta = $id_carpeta";
						$query = consulta($sql);
						while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
							$operacion= $row["operacion"];
							$cuentaBien= $row["cuenta"];
							$cuenta= $row["nrocaso"];
							if($operacion == '0' or $operacion == ''){
								$mensajeG .= "</br>Cuenta: $cuenta - No desembolsado. ";
							}else{
								//llamar al WS execute
								$idAsiento = 'ALTA_VALOR_GUARDIAN';
								$xmlEntrada = '<registro asiento="ALTA_VALOR_GUARDIAN">
									<dato cta="'.$cuenta.'" oper="'.$operacion.'" importe="100" moneda="0" idTipo="'.$cuentaBien.'" />
								</registro>';
								/*
								<registro asiento="ALTA_VALOR_GUARDIAN">
									<dato cta="1234" oper="1234" importe="1" moneda="0" idTipo="100" />
								</registro>
								<registro asiento="BAJA_VALOR_GUARDIAN">
									<dato cta="1234" oper="1234" importe="1" moneda="0" idTipo="100" />
								</registro>
								*/
								require_once("ws_execute_bsol.php"); 
								if($mensaje=='OK'){
									$sql= "INSERT INTO comprobantes (id_carpeta, cuenta, debe, haber)
											VALUES ('$id_carpeta', '$cuentaBien', '1', '0')";
									ejecutar($sql);
									//actualizamos cuenta cliente en carpeta, no es ctactble
									$sql="UPDATE carpetas SET cuenta = '$cuenta' WHERE id_carpeta = '$id_carpeta'";
									ejecutar($sql);
								}else{
									$mensajeG .= "</br>".$operacion.": ERROR, no registrado por el WS.";
									echo "<!--  ".$xmlEntrada."  -->";
								}
							}
						}
				/*	}else{
						if($operacion == '0')
						$mensajeG .= "</br>".$nrocaso.": No desembolsado. ".trim($Descripcion);
						else
						$mensajeG .= "</br>".$nrocaso.": No encontrado en WS. ".trim($Descripcion);
					}	*/			
				}
				if($mensajeG!='')
					$alert = 'Los n&uacute;meros siguientes reportan fallas: '.$mensajeG;
				else
					$alert="El o los n&uacute;meros seleccionados han sido aprobados";
			}
		}else{ 
			$alert="Debe marcar alguno de los n&uacute;meros."; 
		}
	}
	
//filtros
if(isset($_REQUEST["fil_nrocaso"]) && $_REQUEST["fil_nrocaso"]!=''){
	$fil_nrocaso = $_REQUEST["fil_nrocaso"];
	$filtronro = " AND ile.instancia = '$fil_nrocaso' ";
	$smarty->assign('fil_nrocaso',$fil_nrocaso);
}else{
	$filtronro = '';
}
if(isset($_REQUEST["fil_cliente"]) && $_REQUEST["fil_cliente"]!=''){
	$fil_cliente = $_REQUEST["fil_cliente"];
	$filtrocli = " AND ile.cliente LIKE '%$fil_cliente%' ";
	$smarty->assign('fil_cliente',$fil_cliente);
}else{
	$filtrocli = '';
}

	
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/
$nrocasos= array();
if( $filtronro != '' || $filtrocli != ''){		
	//$id_us_actual = $_SESSION["idusuario"]; //usuario guardian en sesion
	$id_almacen = $_SESSION["id_almacen"];
	$sql= "SELECT DISTINCT TOP 50 ile.instancia as nro, ile.cliente, ile.nrocaso, 
	ca.operacion, ca.id_carpeta, ca.carpeta, tb.tipo_bien
	FROM informes_legales ile INNER JOIN usuarios us ON us.id_usuario = ile.id_us_comun 
	INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
	INNER JOIN carpetas ca ON ca.id_informe_legal = ile.id_informe_legal
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien = ca.id_tipo_carpeta
	WHERE tb.bien <> '4' and (ca.cuenta ='' OR ca.cuenta is null) AND tb.cuenta <>'' AND ile.instancia<>'0' 
	AND ofi.id_almacen = '$id_almacen' $filtronro  $filtrocli  ORDER BY ile.cliente, nro";

//tb.bien <> '4' que no sea de contratos
	$query = consulta($sql);

	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if($row["operacion"] == '' or $row["operacion"] == '0'){
			$operacion = '0';
			//require_once("ws_desembolso_bsol.php");
		}else{
			$operacion = $row["operacion"];
		}
		$nrocasos[] = array('id'=>$row["id_carpeta"],
							'instancia'=>$row["nro"],
							'nrocaso'=>$row["nrocaso"],
							'cliente'=>$row["cliente"],
							'carpeta'=>$row["carpeta"],
							'tipo_bien'=>$row["tipo_bien"],
							'operacion'=>$operacion);
	}
}
	$smarty->assign('nrocasos',$nrocasos);
	$smarty->assign('alert',$alert);
	$smarty->assign('title',$title);
	$smarty->assign('title2',$title2);
	$smarty->display('conta_garantia.html');
	die();

?>