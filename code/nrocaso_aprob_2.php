<?php
//elaborar_informe
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
//require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="../code/_main.php?action=nrocaso_aprob_2.php";
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
	if($enable_ws == 'A'){
		// caso baneco 1!! esto no se debiera dar aqui, esta opcion no se debe habilitar en baneco
		$title = "N&uacute;meros de Caso Pendientes de Autorizaci&oacute;n";
		$title2 = "Autorizar N&uacute;mero de Caso Sin Informe Legal";
	}else{
		$title = "Solicitar elaboraci&oacute;n de contrato";
		$title2 = "Autorizar Contrato Sin Informe Legal";
	}
	//print_r($_REQUEST); die();
	if(isset($_REQUEST["aprobar_ncaso"]) || isset($_REQUEST["rechazar_ncaso"])){
		$opciones = $_REQUEST['opcion'];
		$casos = $_REQUEST['casos'];
		$informes = $_REQUEST['informes'];
		//print_r($casos); die($informes);
		//Hay al menos una opcion marcada 
			if(isset($_REQUEST["rechazar_ncaso"])){
				if(count($opciones)>0){
					foreach($opciones as $indice => $valor) {
						//$valor tiene el nrocaso
						$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal)  select nrocaso, -1 as idfinal from informes_legales where id_informe_legal = '$valor'"; 
						ejecutar($sql);
					} 
					$alert="El o los n&uacute;meros han sido rechazados";
				}else{ 
					$alert="Debe marcar alguno de los n&uacute;meros."; 
				}
			}else{
				//es para aprobar
				$mensaje = '';
				foreach($casos as $indice => $valor) {
					//$valor tiene el nrocaso
					//buscamos valores adicionales del web SERVICE 
					$nrocaso = $valor;
					if($nrocaso != ''){
						if($enable_ws == 'A'){
							require("../code/ws_flujocre.php");
						}elseif($enable_ws == 'C'){
							require("../code/ws_desembolso_cidre.php");
						}
						if($operacion != ''){
							//
							if($uplazo == 'MES(ES)'){
								$plazomeses = $plazo;
								$plazodias = '0';
							}elseif($uplazo == 'DIA(S)'){
								$plazomeses = '0';
								$plazodias = $plazo;
							}else{ //AÑO(S)
								$plazomeses = $plazo*12;
								$plazodias = '0';
							}
							//solo insertamos si no existe previamente, caso contrario actualizamos
							$sql = "SELECT nrocaso FROM ncaso_cfinal WHERE nrocaso = '$nrocaso'";
							$query = consulta($sql);
							$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
							if($nrocaso != $row["nrocaso"])
								$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal, importeprestamo,
								monedaprestamo, destinocredito, numerocuotas,
								tasa1, plazomeses, plazodias, tasa2, frecuenciapagok, periodogracia) VALUES 
								('$nrocaso', '0', '$monto',
								'$moneda', '$destinocre', '$cuotas',
								'$tasabase',  '$plazomeses', '$plazodias', '$pagointeres', '$upagokapital', convert(int, convert(decimal,'$gracia')))";
							else
								$sql="UPDATE ncaso_cfinal SET importeprestamo='$monto',
								monedaprestamo='$moneda', destinocredito='$destinocre', numerocuotas='$cuotas',
								tasa1='$tasabase', plazomeses='$plazomeses', plazodias='$plazodias', tasa2='$pagointeres', frecuenciapagok='$upagokapital', periodogracia=convert(int, convert(decimal,'$gracia')) WHERE nrocaso = '$nrocaso' ";
								
							ejecutar($sql);
							//actualizamos en i.l. el nro de credito
							$id_il = $informes[$indice];
							$sql="UPDATE informes_legales SET nrocaso = '$nrocaso' WHERE id_informe_legal = '$id_il'";
							ejecutar($sql);
						}else{
							$mensaje .= $nrocaso.',';
						}
					}
					//}
				}
				if($mensaje!='')
					$alert = 'Los n&uacute;meros siguientes no han sido encontrados: '.$mensaje;
				else
					$alert="El o los n&uacute;meros seleccionados han sido aprobados";
			}
		
	}
	if(isset($_REQUEST['nrocasoSinIL'])){
		$valor = trim($_REQUEST['nrocasoSinIL']);
		if($valor!=''){
			//$valor tiene el nrocaso, revisamos que no se duplique
			$sql="SELECT nrocaso FROM ncaso_cfinal WHERE nrocaso = '$valor' AND idfinal=0";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$valor2 = trim($row["nrocaso"]);
			if($valor2==$valor){
				//ya existe, no podemos volver a aprobar
				$alert="El n&uacute;mero ingresado ya ha sido aprobado anteriormente";
			}else{
				//vemos si el nrocaso tiene recepcion
				$sql="SELECT nrocaso FROM informes_legales WHERE nrocaso = '$valor' ";
				$query = consulta($sql);
				$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
				$valor2 = trim($row["nrocaso"]);
				if($valor2!=$valor){
					//no existe, no podemos registrar
					$alert="AVISO: El n&uacute;mero ingresado no tiene recepci&oacute;n.<br>";
				}
				//else{
			
				//buscamos valores adicionales del web SERVICE 
				if($enable_ws == 'A'){
					// caso baneco
					require("../code/ws_flujocre.php");
					if($sihay==1){
						$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal, tipocartera, importeprestamo,
								monedaprestamo, cuentadesembolso, destinocredito, numerocuotas,
								tasa1, tasa2, cuotastasafija, cuentadebito,
								numerolinea, importelinea,
								monedalinea, plazomeses, plazodias,
								segurodegravamen, atributoobligatorio, frecuenciapagok, objetocredito, id_banca) VALUES 
								($valor, '0', '$tipocartera', '$importeprestamo',
								'$monedaprestamo', '$cuentadesembolso', '$destinocredito', '$numerocuotas',
								'$tasa1', '$tasa2', convert(int, convert(decimal,'$cuotastasafija')), '$cuentadebito',
								'$numerolinea', '$importelinea',
								'$monedalinea', '$plazomeses', '$plazodias',
								'$segurodegravamen', '$atributoobligatorio', '$frecuenciapagok', '$objetocredito',  '$id_banca')";
						ejecutar($sql);
						$alert.="El nuevo n&uacute;mero ha sido aprobado";
					}else{
						$alert .= 'El n&uacute;mero no ha sido encontrado en el WS: '.$valor;
					}
				}elseif($enable_ws == 'S'){
					//caso BSOL
					$nrocaso = $valor;
					require("../code/ws_desembolso_bsol.php");
					if($operacion != ''){
						$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal, tipocartera, importeprestamo,
								monedaprestamo, cuentadesembolso, destinocredito, numerocuotas,
								tasa1, tasa2, cuotastasafija, cuentadebito,
								numerolinea, importelinea,
								monedalinea, plazomeses, plazodias,
								segurodegravamen, atributoobligatorio, frecuenciapagok, objetocredito) VALUES 
								($nrocaso, '0', '', '$monto',
								'$moneda', '$cuenta', '$destinocre', '0',
								'0', '0', '0', '0',
								'', '0', 
								'0', '0', '0',
								'', '', '', '')";
						ejecutar($sql);
						$alert.="<br>El nuevo n&uacute;mero ha sido aprobado";
					}else{
						$alert = 'El n&uacute;mero no ha sido encontrado: '.$valor;
					}
				}elseif($enable_ws == 'C'){
					//caso cidre
					$nrocaso = $valor;
					require("../code/ws_desembolso_cidre.php");
					
						if($operacion != ''){
							//
						if($uplazo == 'MES(ES)'){
							$plazomeses = $plazo;
							$plazodias = '0';
						}elseif($uplazo == 'DIA(S)'){
							$plazomeses = '0';
							$plazodias = $plazo;
						}else{ //AÑO(S)
							$plazomeses = $plazo*12;
							$plazodias = '0';
						}
						$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal, importeprestamo,
							monedaprestamo, destinocredito, numerocuotas,
							tasa1, plazomeses, plazodias, tasa2, frecuenciapagok, periodogracia) VALUES 
							($nrocaso, '0', '$monto',
							'$moneda', '$destinocre', '$cuotas',
							'$tasabase',  '$plazomeses', '$plazodias', '$pagointeres', '$upagokapital', convert(int, convert(decimal,'$gracia')))";
							ejecutar($sql);
							// no se actualiza en I.L. por que no tiene
							$alert.="El nuevo n&uacute;mero ha sido aprobado";
					}else{
						$mensaje .= $alert = 'El n&uacute;mero no ha sido encontrado: '.$nrocaso;
					}
				}
				
				//}
			}
		}
	}

	
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/
$filtro='';
if(isset($_REQUEST['buscar_boton'])){
	$ifecha = $_REQUEST['iYear'].'-'.$_REQUEST['iMonth'].'-'.$_REQUEST['iDay'];
	$ffecha = $_REQUEST['fYear'].'-'.$_REQUEST['fMonth'].'-'.$_REQUEST['fDay'];
	$filtro =  " AND  ile.fecha >= convert(datetime,'$ifecha',102) AND ile.fecha <= convert(datetime,'$ffecha',102) ";
}else{
	$ifecha = date("Y-m-01");
	$ffecha = date("Y-m-d");
}

	$id_us_actual = $_SESSION["idusuario"]; //usuario guardian en sesion
	$id_almacen = $_SESSION["id_almacen"];
	$sql= "SELECT TOP 100 ile.id_informe_legal as nro, ile.cliente, 
		max(CONVERT(VARCHAR(10), ile.fecha, 103)) AS fecha 
		FROM informes_legales ile INNER JOIN usuarios us ON us.id_usuario =ile.id_us_comun 
		INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
		INNER JOIN tipos_bien tbi ON tbi.id_tipo_bien = ile.id_tipo_bien
		WHERE tbi.con_inf_legal='S' AND ile.nrocaso NOT IN (SELECT nrocaso FROM ncaso_cfinal)
		AND ile.nrocaso<>'' AND ofi.id_almacen = '$id_almacen'		$filtro
		GROUP BY ile.id_informe_legal, ile.cliente
		ORDER BY nro DESC";
	
	$query = consulta($sql);

	$nrocasos= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$nrocasos[] = array('id'=>$row["nro"],
							'fecha'=>$row["fecha"],
							'cliente'=>$row["cliente"]);
	}
	
	$smarty->assign('ifecha',$ifecha );
	$smarty->assign('ffecha',$ffecha );
	$smarty->assign('nrocasos',$nrocasos);
	$smarty->assign('alert',$alert);
	$smarty->assign('title',$title);
	$smarty->assign('title2',$title2);
	$smarty->assign('resumir','N');
	$smarty->display('nrocaso_aprob_2.html');
	die();

?>