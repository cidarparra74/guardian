<?php
//elaborar_informe
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
//require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="../code/_main.php?action=canceladas.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
//verificar si esta habilitado el WS
		$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$enable_ws = $row["enable_ws"];
		$smarty->assign('enable_ws',$row["enable_ws"]);
	
		$title = "Lista Cancelados";
	//	$title2 = "Adicionar Instancia";

if(isset($_REQUEST["adjudicar_bien"])||isset($_REQUEST["devolver_bien"])){
	$opciones = $_REQUEST['opcion'];
	if(count($opciones)>0){
		$fecha_actual= date("Y-m-d H:i:s");
		$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
		//Hay al menos una opcion marcada 
		if(isset($_REQUEST["devolver_bien"])){
			//"Devueltos al Cliente"
			$id_estado = 8;
			// esta consulta esta a medias ya q se completa mas abajo
			$sqlMov= "UPDATE movimientos_carpetas SET corr_dev=$fecha_actual, id_estado='8' WHERE id_carpeta=";
		}else{
			//"Adjudicadas para el Banco"
			$id_estado = 9;
			// esta consulta esta a medias ya q se completa mas abajo
			$sqlMov= "UPDATE movimientos_carpetas SET corr_adj=$fecha_actual, id_estado='9' WHERE id_carpeta=";
		}
		$alerta = '';
		foreach($opciones as $indice => $valor) {
			//$valor tiene el nrocaso
			//buscamos valores adicionales del web SERVICE 
			//caso BSOL
			$nrocaso = $valor;
			//contabilizamos
			
			$sql = "SELECT tb.cuenta cuentabien, ca.id_carpeta, ca.cuenta, ca.operacion
			FROM OPERACIONESCAN il
			LEFT JOIN carpetas ca ON ca.operacion = convert(varchar,il.operacion) AND ca.cuenta = il.cuenta
			LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = ca.id_tipo_carpeta
			WHERE il.instancia = '$nrocaso' AND tb.cuenta <>'' ";
			$query = consulta($sql);
			//echo $sql;
			while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
				$id_carpeta= $row["id_carpeta"];
				$cuentaBien= $row["cuentabien"];
				$cuenta= $row["cuenta"];
				$operacion= $row["operacion"];
				//llamar al WS execute
				$idAsiento = 'BAJA_VALOR_GUARDIAN';
				$xmlEntrada = '<registro asiento="BAJA_VALOR_GUARDIAN">
					<dato cta="'.$cuenta.'" oper="'.$operacion.'" importe="100" moneda="0" idTipo="'.$cuentaBien.'" />
				</registro>';
				// ws: Execute
				require_once("ws_execute_bsol.php");
				if($mensaje=='OK'){
					$sql= "INSERT INTO comprobantes (id_carpeta, cuenta, debe, haber)
							VALUES ('$id_carpeta', '$cuentaBien', '0', '1')";
					ejecutar($sql);
					
					//esta parte actuliza el movimiento de la carpeta
					//q pasa si no existe mov alguno?
					$sql="SELECT count(*) as casos FROM movimientos_carpetas 
					WHERE flujo = 0 AND id_estado < 8 AND id_carpeta='$id_carpeta'";
					$query = consulta($sql);
					$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
					if($row["casos"] > 0){
						$sqlMov2 = $sqlMov."'$id_carpeta'";
						ejecutar($sqlMov2);
					}else{
						//insertamos para q salga en 
						//id_carpeta, id_us_inicio=id_us_corriente, id_us_autoriza, id_estado = 8, flujo=0, obs_8
						$idusuario = $_SESSION["idusuario"];
						$sql="INSERT INTO movimientos_carpetas (id_carpeta, id_us_inicio, id_us_corriente, id_us_autoriza, id_estado, flujo, obs_8, corr_dev)
						VALUES ('$id_carpeta', $idusuario, $idusuario, 0, $id_estado, 0, 'Contabilizacion de BAJA', $fecha_actual) ";
						ejecutar($sql);
					}
					
					if(isset($_REQUEST["devolver_bien"])){
						$sql= "UPDATE carpetas SET fecha_devolucion=$fecha_actual WHERE id_carpeta ='$id_carpeta'";
						ejecutar($sql);
					}
				}else{
					$alerta .= $nrocaso.'/';
				}
			}
			$sql= "UPDATE OPERACIONESCAN SET fechabaja=$fecha_actual WHERE instancia = '$nrocaso'";
					ejecutar($sql);
		}
		//die();
		if($alerta!='')
			$alert = 'Los n&uacute;meros siguientes no han sido procesados por el WS: '.$alerta;
		else
			$alert="El o los n&uacute;meros seleccionados han sido procesados correctamente";
		
	}else{ 
		$alert="Debe marcar alguno de los n&uacute;meros."; 
	}
}
	
//***************************//
// aarmamos filtros  //
if(isset($_REQUEST["fil_nrocaso"]) && $_REQUEST["fil_nrocaso"]!=''){
	$fil_nrocaso = $_REQUEST["fil_nrocaso"];
	$filtronro = " AND convert(varchar,oc.instancia) = '$fil_nrocaso' ";
}else{
	$filtronro = '';
}
if(isset($_REQUEST["fil_cliente"]) && $_REQUEST["fil_cliente"]!=''){
	$fil_cliente = $_REQUEST["fil_cliente"];
	$filtrocli = " AND pr.nombres LIKE '%$fil_cliente%' ";
}else{
	$filtrocli = '';
}
$smarty->assign('fil_nrocaso',$fil_nrocaso);
$smarty->assign('fil_cliente',$fil_cliente);
/****************fin de valores para la ventana*************************/
/**********************valores por defecto*************************/

//
$id_almacen = $_SESSION['id_almacen'];

$nrocasos= array();
//
if( $filtronro != '' || $filtrocli != ''){
$sql= "SELECT oc.instancia as nro, pr.nombres  
FROM carpetas ca 
INNER JOIN propietarios pr ON pr.id_propietario = ca.id_propietario
INNER JOIN oficinas ofi ON ofi.id_oficina = ca.id_oficina  
INNER JOIN tipos_bien tb ON tb.id_tipo_bien = ca.id_tipo_carpeta AND tb.cuenta<>'' 
INNER JOIN OPERACIONESCAN oc ON ca.operacion = convert(varchar,oc.operacion) 
	AND (oc.fechabaja is null OR oc.fechabaja='')
	WHERE ofi.id_almacen = $id_almacen  $filtronro  $filtrocli 
ORDER BY nro";
//echo $sql;
	$query = consulta($sql);
	
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$nrocasos[] = array('nrocaso'=>$row["nro"],
							'cliente'=>$row["nombres"]);
	}
}
	$smarty->assign('nrocasos',$nrocasos);
	$smarty->assign('alert',$alert);
	$smarty->assign('title',$title);
	//$smarty->assign('title2',$title2);
	$smarty->assign('resumir','S');
	$smarty->display('canceladas.html');
	die();

?>