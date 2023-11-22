<?php
//elaborar_informe
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');

	require_once("../lib/cargar_overlib.php");
	
	$carpeta_entrar="../code/_main.php?action=nrocaso_recarga.php";
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
		$title2 = "Actualizar N&uacute;mero de Caso ";
	}else{
		$title2 = "Actualizar Instancia";
	}
	
	if(isset($_REQUEST['nrocasoSinIL'])){
		$valor = trim($_REQUEST['nrocasoSinIL']);
		if($valor!=''){
			
				//buscamos valores adicionales del web SERVICE 
				if($enable_ws == 'A'){
					// caso baneco
					require("../code/ws_flujocre.php");
					if($sihay==1){
					
						$sql="DELETE FROM ncaso_cfinal WHERE nrocaso = '$valor' ";
						ejecutar($sql);

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
						$alert.="El nuevo n&uacute;mero ha sido actualizado";
					}else{
						$alert .= 'El n&uacute;mero no ha sido encontrado en el WS: '.$valor;
					}
				}elseif($enable_ws == 'S'){
					//caso BSOL
					$nrocaso = $valor;
					require("../code/ws_desembolso_bsol.php");
					if($operacion != ''){
						$sql="DELETE FROM ncaso_cfinal WHERE nrocaso = '$valor' ";
						ejecutar($sql);
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
						$alert.="<br>El nuevo n&uacute;mero ha sido actualizado";
					}else{
						$alert = 'El n&uacute;mero no ha sido encontrado: '.$valor;
					}
				}elseif($enable_ws=='N'){
				//bisa, esto no usa bisa, ver otro php nrocaso_aprob_bis.php
						$nrocaso = $valor;
						$sql="INSERT INTO ncaso_cfinal (nrocaso, idfinal) VALUES ($nrocaso, '0')";
						//ejecutar($sql);
					}
		}else{
		
				$alert="Ingrese un n&uacute;mero!";
		}
	}

	
/****************fin de valores para la ventana*************************/

/**********************valores por defecto*************************/


	$smarty->assign('alert',$alert);
	$smarty->assign('title2',$title2);
	$smarty->display('nrocaso_recarga.html');
	die();

?>