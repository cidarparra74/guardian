<?php
//elaborar_informe
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');
//require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//href
	$carpeta_entrar="../code/_main.php?action=nrocaso_aprob.php";
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
		// caso baneco
		$title = "N&uacute;meros de Caso Pendientes de Autorizaci&oacute;n";
		$title2 = "Autorizar N&uacute;mero de Caso Sin Informe Legal";
	}else{
		$title = "Solicitar elaboraci&oacute;n de contrato";
		$title2 = "Autorizar Contrato Sin Informe Legal";
	}
	if(isset($_REQUEST["aprobar_ncaso"])||isset($_REQUEST["rechazar_ncaso"])){
		$opciones = $_REQUEST['opcion'];
		
		if(count($opciones)>0){
			
			//Hay al menos una opcion marcada 
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
				foreach($opciones as $indice => $valor) {
					//$valor tiene el nrocaso
					//buscamos valores adicionales del web SERVICE 
					if($enable_ws == 'A'){
						// caso baneco
						require("../code/ws_flujocre.php");
						if($sihay==1){
							//cargamos las garantias y seguro
							require("ws_garantias_armar.php");
							if($sihay==1){
								//tenemos las garantias en $listaGarantias
								//recorremos y buscamos correspondientes
								$sql="SELECT tiposec FROM tipo_garantia WHERE asfi IN ($listaGarantias) AND tiposec IS NOT NULL";
								$query = consulta($sql);
								$codigos = '';
								while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
									//vemos si ya existe
									if(strpos($codigos, $row["tiposec"])===false)
										$codigos .= ' '.$row["tiposec"];
									
								}
								$codigogarantia=trim($codigos);
								require("ws_garantes.php");
								if($ngarantes > 0){
									$codigogarantia .= ' IPN';
								}
								// PARA LOS SEGUROS
								$sql="SELECT seguro FROM tipo_garantia WHERE asfi IN ($listaSeguros) AND SEGURO IS NOT NULL";
								$query = consulta($sql);
								$codigos = '';
								while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
									if(strpos($codigos, $row["seguro"])===false)
									$codigos .= ' '.$row["seguro"];
									
								}
								$tiposeguro=trim($codigos);
								//$sql="UPDATE ncaso_cfinal SET tiposeguro = '$codigos' WHERE nrocaso = '$nrocaso' and idfinal = 0 ";
								//echo $sql;
								////ejecutar($sql); 

							}else{
								$codigogarantia='';
								$tiposeguro='';
							}
							$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal, tipocartera, importeprestamo,
								monedaprestamo, cuentadesembolso, destinocredito, numerocuotas,
								tasa1, tasa2, cuotastasafija, cuentadebito,
								numerolinea, importelinea,
								monedalinea, plazomeses, plazodias,
								segurodegravamen, atributoobligatorio, frecuenciapagok, objetocredito,
								linearotativa, tipogarantia, codigogarantia, tiposeguro, id_banca, agencia) VALUES 
								($valor, '0', '$tipocartera', '$importeprestamo',
								'$monedaprestamo', '$cuentadesembolso', '$destinocredito', '$numerocuotas',
								'$tasa1', '$tasa2', '$cuotastasafija', '$cuentadebito',
								'$numerolinea', '$importelinea',
								'$monedalinea', '$plazomeses', '$plazodias',
								'$segurodegravamen', '$atributoobligatorio', '$frecuenciapagok', '$objetocredito',
								'$linearotativa', '$tipogarantia','$codigogarantia', '$tiposeguro', '$id_banca', '$agencia')";
							ejecutar($sql);
							//echo $sql;
						}else{
							$mensaje .= $valor .'   ';
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
						}else{
							$mensaje .= $valor .'   ';
						}
					}elseif($enable_ws=='C'){
						//caso cidre /bisa
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
								tasa1, plazomeses, plazodias, tasa2) VALUES 
								($nrocaso, '0', '$monto',
								'$moneda', '$destinocre', '$cuotas',
								'$tasabase',  '$plazomeses', '$plazodias', '$pagointeres')";
							ejecutar($sql);
						}
					}elseif($enable_ws=='N'){
						$nrocaso = $valor;
						$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal) VALUES ($nrocaso, '0')";
						ejecutar($sql);
					}


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
								segurodegravamen, atributoobligatorio, frecuenciapagok, objetocredito,
								linearotativa, tipogarantia, codigogarantia, tiposeguro, id_banca) VALUES 
								($valor, '0', '$tipocartera', '$importeprestamo',
								'$monedaprestamo', '$cuentadesembolso', '$destinocredito', '$numerocuotas',
								'$tasa1', '$tasa2', '$cuotastasafija', '$cuentadebito',
								'$numerolinea', '$importelinea',
								'$monedalinea', '$plazomeses', '$plazodias',
								'$segurodegravamen', '$atributoobligatorio', '$frecuenciapagok', '$objetocredito',
								'$linearotativa', '$tipogarantia','$codigogarantia', '$tiposeguro', '$id_banca')";
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
				}elseif($enable_ws=='N'){
				//bisa, esto no usa bisa, ver otro php nrocaso_aprob_bis.php
						$nrocaso = $valor;
						$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal) VALUES ($nrocaso, '0')";
						//ejecutar($sql);
					}
				
				//}
			}
		}
	}

	
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/


	$id_us_actual = $_SESSION["idusuario"]; //usuario guardian en sesion
	$id_almacen = $_SESSION["id_almacen"];
	$sql= "SELECT CONVERT(int,ile.nrocaso) nro, ile.cliente, 
		max(CONVERT(VARCHAR(10), ile.fecha, 103)) AS fecha 
		FROM informes_legales ile INNER JOIN usuarios us ON us.id_usuario =ile.id_us_comun 
		INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
		INNER JOIN tipos_bien tbi ON tbi.id_tipo_bien = ile.id_tipo_bien
		WHERE tbi.con_inf_legal='S' AND ile.nrocaso NOT IN (SELECT nrocaso FROM ncaso_cfinal WHERE nrocaso not like '*%')
		AND ile.nrocaso<>'' AND ofi.id_almacen = '$id_almacen'		
		GROUP BY CONVERT(int,ile.nrocaso), ile.cliente
		ORDER BY nro";
	//echo $sql;
	$query = consulta($sql);

	$nrocasos= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$nrocasos[] = array('nrocaso'=>$row["nro"],
							'fecha'=>$row["fecha"],
							'cliente'=>$row["cliente"]);
	}
	$smarty->assign('nrocasos',$nrocasos);
	$smarty->assign('alert',$alert);
	$smarty->assign('title',$title);
	$smarty->assign('title2',$title2);
	$smarty->assign('resumir','N');
	$smarty->display('nrocaso_aprob.html');
	die();

?>