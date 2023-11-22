<?php

	//$smarty->assign('existentes',$existentes);
	//recuperando los tipos de indentificacion
//	$sql= "SELECT * FROM tipos_identificacion ORDER BY identificacion ";
	//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	$smarty->assign('enable_ws',$enable_ws);
	
	$sql= "SELECT * FROM emisiones";
	$query = consulta($sql);
	$i=0;
	$emisiones=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$emisiones[$i]= array('id' => $row["emision"],
									'descri' => $row["emision"]);
		$i++;
	}
	if($adicionar!='Buscar'){
		$smarty->assign('busca','S'); 
	}else{
		$smarty->assign('busca','N');
		$ci_cliente = $_REQUEST["ci_cliente"];
		$emision = $_REQUEST["emision"];
		//echo $ci_cliente;
		if(trim($ci_cliente)!=''){
			$sql = "SELECT id_propietario, nombres FROM propietarios WHERE ci = '$ci_cliente' AND emision = '$emision'";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$id_propietario = $row["id_propietario"];
			$nombres = $row["nombres"];
			//echo $nombres.'1';
			if($row["nombres"]==''){
				//no existe, buscamos con el WS
				if($enable_ws == 'S'){
					$Pais 	 = '1';
					if($emision!='PE')
						$TipoDoc = '1';
					else
						$TipoDoc = '3';
					$documento=$ci_cliente;  //esto para no perder el ci
					$ci_cliente=$ci_cliente.$emision;  
					require_once('ws_cliente.php');
				}
				//-----------------------------------
				//echo $nombres.'2';
				if(trim($nombres) != ''){
					//existe en el WS, lo insertamos directamente en tabla propietarios
					/*
					$fecha_actual= date("Y-m-d H:i:s");
					$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
					$sql= "INSERT INTO propietarios (nombres, ci, direccion, 
						telefonos, creacion_propietario, estado_civil, nit, emision, mis) 
						VALUES('$nombres', '$ci_cliente', '$direccion', 
						'$telefonos', $fecha_actual, '$ecivil', '', '$emision', '') ";
					ejecutar($sql);
					*/
					//----
					$smarty->assign('alerta','Encontrado, complete los datos y guardar');
					$smarty->assign('busca','N');
					$smarty->assign('ci_cliente',$documento);
					$smarty->assign('emision',$emision);
					$smarty->assign('nombres',$nombres);
					$smarty->assign('direccion',$direccion);
					$smarty->assign('telefonos',$telefonos);
					$smarty->assign('xnombres',$nombres);
					$smarty->assign('xdireccion',$direccion);
					$smarty->assign('xtelefonos',$telefonos);
					//include("./ver_informe_legal/adicionar.php");
				}else{
					//no existe
					$smarty->assign('busca','N');
					$smarty->assign('alerta','No existe el documento indicado. Adicione el propietario');
				}
			}else{
				// existe, pasamos a adicionar
				$smarty->assign('alerta','Ya existe '.$nombres.', use la opci&oacute;n de b&uacute;squeda de carpetas');
				$smarty->assign('busca','S');
				//include("./ver_informe_legal/adicionar.php");
				
			}
		}else{
			//vemos d buscar por cuenta
			if(isset($_REQUEST["cuenta"]))
				$cuenta = $_REQUEST["cuenta"];
			else
				$cuenta = '';
			//echo $cuenta.'--2';
			if($cuenta!=''){
				//buscamos cuenta con el WS
				//if($enable_ws == 'S'){
				require_once('ws_cuenta.php');
				//}
				//-----------------------------
				//si cuenta encontrada entonces $documento <> ''
				if($documento!=''){
					//existe, armamos ci y emision
					$ci_cliente = $documento.$emision;  //$documento no incluye emision
					
					if($ci_cliente!=''){
						// la siguiente consulta se usa en adicionar.php en caso de existir
						$sql = "SELECT id_propietario, nombres FROM propietarios WHERE ci+emision = '$ci_cliente'";
						$query = consulta($sql);
						$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
						$id_propietario = $row["id_propietario"];
						$nombres = $row["nombres"];
						if($nombres==''){
							//no existe, buscamos con el WS
							require_once('ws_cliente.php');
							//-----------------------------------
							if($nombres != ' '){
								//existe, insertamos manualmente
								/*
								$fecha_actual= date("Y-m-d H:i:s");
								$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
								$sql= "INSERT INTO propietarios (nombres, ci, direccion, 
									telefonos, creacion_propietario, estado_civil, nit, emision, mis) 
									VALUES('$nombres', '$ci_cliente', '$direccion', 
									'$telefonos', $fecha_actual, '$ecivil', '', '$emision', '') ";
								ejecutar($sql);
								*/
								//----
								$emision = substr($ci_cliente,-2,2);
								$ci_cliente = $ci_cliente = substr($ci_cliente,0,strlen($ci_cliente)-2);
								$smarty->assign('alerta','Encontrado, complete los datos y guardar');
								$smarty->assign('busca','N');
								$smarty->assign('ci_cliente',$ci_cliente);
								$smarty->assign('emision',$emision);
								$smarty->assign('nombres',$nombres);
								$smarty->assign('direccion',$direccion);
								$smarty->assign('telefonos',$telefonos);
								$smarty->assign('xnombres',$nombres);
								$smarty->assign('xdireccion',$direccion);
								$smarty->assign('xtelefonos',$telefonos);
								/*
								//pero necesitamos el idpropietario!!
								$sql = "SELECT MAX(id_propietario) as idp FROM propietarios ";
								$query = consulta($sql);
								$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
								$id_propietario = $row["idp"];
								//----
								*/
								$smarty->assign('alerta','Encontrado, complete los datos y guardar');
								$smarty->assign('busca','N');
								//include("./ver_informe_legal/adicionar.php");
							}else{
								//no existe CI indicado x la cuenta
								//$smarty->assign('vertodo','N');
								//$smarty->assign('alerta','ERROR: No existe el documento indicado por el nro de cuenta.');
								$smarty->assign('busca','N');
								$smarty->assign('alerta','No existe el documento indicado.');
							}
						}else{
							// existe, pasamos a adicionar
							$smarty->assign('alerta','Ya existe '.$nombres.', use la opci&oacute;n de b&uacute;squeda de carpetas');
							$smarty->assign('busca','S');
							//include("./ver_informe_legal/adicionar.php");
						}
					}else{
						//no hay ci en el WS
						$smarty->assign('busca','S');
						$smarty->assign('alerta','No existe un documento de identidad asociado a la cuenta indicada.');
					}
				}else{
					//no existe el nro de cta
					$smarty->assign('busca','S');
					$smarty->assign('alerta','No existe el nro de cuenta indicado. Busque por documento');
				}
			}else{
				//no ingreso ci ni cuenta
				$smarty->assign('alerta','');
				$smarty->assign('busca','S');
			}
		}
		
		
	}
	$smarty->assign('emisiones',$emisiones);
	
	$smarty->display('propietarios/adicionar.html');
	die();
?>
