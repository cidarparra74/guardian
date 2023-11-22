<?php

//require_once('../lib/lib/nusoap.php');
require_once("../lib/setup.php");
$smarty = new bd;	
//require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//recuperamos los datos del usuario

//vemos cual categoria vamos a procesar
if(isset($_REQUEST['cat'])){
	$cat = $_REQUEST['cat'] ;
	$_SESSION['cat'] = $cat ;
}else{
	$cat = '0';
	if(isset($_SESSION['cat']))
		$cat = $_SESSION['cat'];
	else
		$_SESSION['cat'] = $cat ;
}

$id_oficina = $_SESSION["id_oficina"];

	//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, autosolicita FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	$autosolicita = $row["autosolicita"];
	$smarty->assign('enable_ws',$enable_ws);
	$smarty->assign('autosolicita',$autosolicita);
	
	$id_us_actual = $_SESSION["idusuario"];
	$nombre_us_actual= $_SESSION["nombreusr"];
	$smarty->assign('id_us_actual',$id_us_actual);
	$smarty->assign('nombre_us_actual',$nombre_us_actual);
	
	//href
	$carpeta_entrar="./_main.php?action=recepcion2.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "recepcion2";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//filtro de la ventana
	if(isset($_REQUEST["filtro"])){
			$f_filtro= $_REQUEST["filtro"];
	}else{	$f_filtro = "ninguno";}
	if($f_filtro == "ninguno"){
		$f_cliente= "";
		$f_ci_cliente="";
		$_SESSION["inf_cliente"]="";
		$_SESSION["inf_ci_cliente"]="";
	}

	//si se presiona el boton de buscar
	if(isset($_REQUEST['buscar_boton'])){
		$f_cliente= $_REQUEST['filtro_cliente'];
		$f_ci_cliente= $_REQUEST['filtro_ci_cliente'];
		$_SESSION["inf_cliente"]=$f_cliente;
		$_SESSION["inf_ci_cliente"]=$f_ci_cliente;
	}
	else{
		$f_cliente=$_SESSION["inf_cliente"];
		$f_ci_cliente= $_SESSION["inf_ci_cliente"];
	}

	$smarty->assign('f_cliente',$f_cliente);
	$smarty->assign('f_ci_cliente',$f_ci_cliente);
	
	//armando la consulta
	$armar_consulta = "";
	if($f_cliente != ""){
		$armar_consulta.= "AND cliente LIKE '%$f_cliente%' ";
	}
	if($f_ci_cliente != ""){
		$armar_consulta.= "AND ci_cliente LIKE '%$f_ci_cliente%' ";
	}
	if(isset($_REQUEST["adicionar"])){
		//// verificar si ya existe CI, o numero
		// en ci_cliente capturamos el numero de doc, de cuenta o de caso segun este configurado
		$ci_cliente = $_REQUEST["ci_cliente"];
		$emision = $_REQUEST["emision"];
		if($ci_cliente!=''){
			// la siguiente consulta se usa en adicionar.php en caso de existir
			if($enable_ws == 'N'){ //si es por CI... BISA
				//busqueda por CI
				if(trim($emision)==''){
					//caso bisa 
					$sql = "SELECT id_propietario, nombres FROM propietarios WHERE ci = '$ci_cliente'";
					$query = consulta($sql);
					$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
					$id_propietario = $row["id_propietario"];
					$nombres = $row["nombres"];
				}else{
					$nombres = '';
				}
				if(trim($nombres) != ''){
						//existe, 
						$smarty->assign('alerta','OK');
						$smarty->assign('vertodo','S');
						include("./ver_informe_legal/adicionar.php");
				}else{
					// vemos si hay disponible el WS para caso CIDRE 
					//$documento = $ci_cliente;
					/*
					$sql = "SELECT id_propietario FROM propietarios WHERE ci+emision = UPPER('$ci_cliente')";
					$query = consulta($sql);
					$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
					$id_propietario = $row["id_propietario"];
					//$nombresg = $row["nombres"];
					if($id_propietario==''){
						//$nrocaso = $ci_cliente; //para nro de credito
						//require_once('ws_datoscliente.php');
						require_once('ws_cliente_cidre.php');
						//si ci encontrado entonces $nombre <> ''
						if($nombres!=''){
							//existe en el WS, pero no en guardian
							//lo insertamos directamente en tabla propietarios
							//	$emision se arma en ws_datoscliente.php
								$ci_cliente = substr($documento,0,strlen($documento)-2);
								$estadocivil = substr($estadocivil,0,1);
								$fecha_actual= date("Y-m-d H:i:s");
								$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
								$sql= "INSERT INTO propietarios (nombres, ci, direccion, 
									telefonos, creacion_propietario, estado_civil, nit, emision, mis) 
									VALUES('$nombres', '$ci_cliente', '$direccion', 
									'$telefonos', $fecha_actual, '$estadocivil', '', '$emision', '$ci_cliente') ";
								ejecutar($sql);
								//pero necesitamos el idpropietario!!
								$sql = "SELECT MAX(id_propietario) as idp FROM propietarios WHERE ci='$ci_cliente'";
								$query = consulta($sql);
								$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
								$id_propietario = $row["idp"];
							//----
							$smarty->assign('alerta','OK');
							$smarty->assign('vertodo','S');
							include("./ver_informe_legal/adicionar.php");
						}else{	*/
							$smarty->assign('vertodo','N');
							$smarty->assign('alerta',' No existe el documento indicado!');
					/*	}
					}else{	
						$smarty->assign('alerta','OK');
						$smarty->assign('vertodo','S');
						include("./ver_informe_legal/adicionar.php");
					}
					*/
				}
			}elseif($enable_ws == 'A'){
				// caso baneco
				//usamos ci_cliente como nro de caso, lo pasamos a otra variable  para no confundir
				$nrocaso = $ci_cliente;
				$documento = '';
				require_once('ws_nrocaso.php');
				//si cuenta encontrada entonces $documento <> ''
				if($documento!=''){
					//existe nro de caso, armamos ci y emision
					$emi='';
					$ci_cliente = trim($documento);  //$documento ya incluye emision, 
					$emision = '';
					$smarty->assign('motivo', $motivo);
			//		$smarty->assign('montoprestamo',$montoprestamo);
			//		$smarty->assign('mone',$mone);
					//buscamos el propietario en guardian
					// la siguiente consulta se usa en adicionar.php en caso de existir
					$sql = "SELECT id_propietario FROM propietarios WHERE ci+emision = '$ci_cliente'";
					$query = consulta($sql);
					$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
					$id_propietario = $row["id_propietario"];
					//$nombresg = $row["nombres"];
					if($id_propietario==''){
						//no existe en guardian, buscamos con el WS los datos personales
						//require_once('ws_cliente_baneco.php');
						//   se desactiva esta parte ya que se modifico ws_nrocaso.php
						//   para jalar los datos personales del cliente
						//-----------------------------------
						//if($nombres != ''){
							//existe, lo insertamos directamente en tabla propietarios
							$emision = substr($ci_cliente,-2,2);
							$ci_cliente = substr($ci_cliente,0,strlen($ci_cliente)-2);
							$ecivil = substr($ecivil,0,1);
							$fecha_actual= date("Y-m-d H:i:s");
							$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
							$sql= "INSERT INTO propietarios (nombres, ci, direccion, 
								telefonos, creacion_propietario, estado_civil, nit, emision, mis) 
								VALUES('$nombres', '$ci_cliente', '$direccion', 
								'$telefonos', $fecha_actual, '$ecivil', '', '$emision', '$ci_cliente') ";
							//echo $sql;
							ejecutar($sql);
							//pero necesitamos el idpropietario!!
							$sql = "SELECT MAX(id_propietario) as idp FROM propietarios WHERE ci = '$ci_cliente'";
							$query = consulta($sql);
							$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
							$id_propietario = $row["idp"];
							//----
							$smarty->assign('alerta','OK');
							$smarty->assign('vertodo','S');
							include("./ver_informe_legal/adicionar.php");
						//}else{
						//	//no existe CI indicado x la cuenta
						//	$smarty->assign('vertodo','N');
						//	$smarty->assign('alerta','ERROR: No existe el n&uacute;mero ingresado.');
						//}
					}else{
						// existe en guardian, pasamos a adicionar
						$smarty->assign('alerta','OK');
						$smarty->assign('vertodo','S');
						include("./ver_informe_legal/adicionar.php");
					}
					
				}else{
					//no existe el nro de caso
					$smarty->assign('vertodo','N');
					$smarty->assign('alerta','No existe el n&uacute;mero indicado.');
				}
			}elseif($enable_ws == 'S'){//---------------------------------BSOL
				// caso banco SOL
				//usamos ci_cliente como nro de OPORTUNIDAD, lo pasamos a otra variable  para no confundir
				$nrocaso = $ci_cliente;  //es el nro de OPORTUNIDAD en realidad, se tomara este en el WS
				//// 
				$estado = '0';
				$mensaje = '';
				require_once('ws_oportunidad_bsol.php');
				//$ci_cliente sale del ws como documento
				
				//si cuenta es encontrada entonces $estado <> '0'
				if($estado=='1'){
					// existe, OK
					$smarty->assign('alerta','OK');
					$smarty->assign('vertodo','S');
					$noportunidad = $nrocaso;
					$nrocaso = 0;
					//VERIFICAR SI ESTE nro de oportunidad no esta ya en guardian
					$sql = "SELECT noportunidad FROM informes_legales WHERE noportunidad = $noportunidad";
					$query = consulta($sql);
					$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
					$existe = $row["noportunidad"];
					if($existe==''){
						include("./ver_informe_legal/adicionar.php");
					}else{
						$smarty->assign('vertodo','N');
						$smarty->assign('alerta','Ya se registr&oacute; ese n&uacute;mero de oportunidad!');
					}
				}elseif($estado=='2'){
					//devolvio mal el ws
					$smarty->assign('vertodo','N');
					$smarty->assign('alerta','No se encontro titular para este n&uacute;mero.');
				}elseif($estado=='3'){
					//devolvio mal el ws
					$smarty->assign('vertodo','N');
					$smarty->assign('alerta','Datos erroneos en el WS.');
				}else{
					//no existe el nro de oportunidad 
					$smarty->assign('vertodo','N');
					$smarty->assign('alerta','WS: '.$mensaje);
				}

			}elseif($enable_ws == 'C'){
				/// caso CIDRE
				/// caso CIDRE
				$sql = "SELECT id_propietario FROM propietarios WHERE ci+emision = UPPER('$ci_cliente')";
					$query = consulta($sql);
					$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
					$id_propietario = $row["id_propietario"];
					//$id_propietario='';
					if($id_propietario==''){
						//$nrocaso = $ci_cliente; //para nro de credito
						require_once('ws_cliente_cidre.php');
						//si ci encontrado entonces $nombre <> ''
						if($nombres!=''){
							//existe en el WS, pero no en guardian
							//lo insertamos directamente en tabla propietarios
							//	$emision se arma en ws_cliente_cidre.php
								//$ci_cliente = substr($documento,0,strlen($documento)-2);
								$estadocivil = substr($estadocivil,0,1);
								$fecha_actual= date("Y-m-d H:i:s");
								$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
								if($personanatural == 1)
								$sql= "INSERT INTO propietarios (nombres, ci, direccion,
									telefonos, creacion_propietario, estado_civil, nit, emision, mis, personanatural)
									VALUES('$nombres', '$ci_cliente', '$direccion',
									'$telefonos', $fecha_actual, '$estadocivil', '', '$emision', '$ci_cliente', '1') ";
								else
									//persona juridica
									$sql= "INSERT INTO propietarios (nombres, ci, direccion,
									telefonos, creacion_propietario, estado_civil, nit, emision, mis, razonsocial, personanatural)
									VALUES('$nombres', '$ci_cliente', '$direccion',
									'$telefonos', $fecha_actual, '$estadocivil', '$ci_cliente', '$emision', '$ci_cliente','$nombres','2') ";
								ejecutar($sql);
								//pero necesitamos el idpropietario!!
								$sql = "SELECT MAX(id_propietario) as idp FROM propietarios WHERE ci='$ci_cliente'";
								$query = consulta($sql);
								$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
								$id_propietario = $row["idp"];
							//----
							$smarty->assign('alerta','OK');
							$smarty->assign('vertodo','S');
							include("./ver_informe_legal/adicionar.php");
						}else{	
							$smarty->assign('vertodo','N');
							$smarty->assign('alerta',' No existe el documento indicado!');
						}

					}else{
						$smarty->assign('alerta','OK');
							$smarty->assign('vertodo','S');
							include("./ver_informe_legal/adicionar.php");
					}
			}
		}else{
			//no existe variable ci!
			$smarty->assign('alerta','');
		}
	}else{	//es primer ingreso
		$smarty->assign('vertodo','N');
		$smarty->assign('alerta','');
	}
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
//---------------------
	// asignar numero de instancia BSOL
	if(isset($_REQUEST['asignarnro'])){
		
		include("./ver_informe_legal/asignarnro.php");
	}
	// asignando numero
	if(isset($_REQUEST["asignar_boton_x"])){
		$acc = $_REQUEST["asignar_boton_x"];
		include("./ver_informe_legal/asignandonro.php");
	}
	
	
	//esto mismo usamos para la aceptacion de la elab de i.l.
	
	//aceptar la solicitud del informe legal
	if(isset($_REQUEST['aceptar_informe'])){
		
		include("./informe_legal/aceptar_solicitud.php");
	}
	//aceptando
	if(isset($_REQUEST['aceptar_boton_x'])){
		$aprobando = 'vrb';
		include("./informe_legal/aceptando_solicitud.php");
	}
	//------------------
	
	//impresion
	if(isset($_REQUEST['imprimir_recepcion'])){

		include("ver_informe_legal/imprimir_recepcion.php");
		
	}
	//adicion de informe legal imprimir
/*
	if(isset($_REQUEST['adicionar'])){
		include("./ver_informe_legal/adicionar.php");	
	}
*/
	//adicionando
	if(isset($_REQUEST["adicionar_boton_x"])){
		$esRecepcion = 1; //para saber si adicionando.php es llamado desde recepcion.php
		// los sigtes php tb son llamados desde informe_legal_aprob.php y catastro_aprob.php
		if($_REQUEST["estado_formulario"] == "Adicionar"){
			include("./ver_informe_legal/adicionando.php");
			include("./ver_informe_legal/documentos1.php");
		}else{
			include("./ver_informe_legal/modificando.php");
			include("./ver_informe_legal/documentos1.php");  
		}
	}
	
	//quitando un documento ya recepcionado
	if(isset($_REQUEST["quitar_doc"])){
		$esRecepcion = 1; //para saber si adicionando.php es llamado desde recepcion.php
		// los sigtes php tb son llamados desde informe_legal_aprob.php y catastro_aprob.php
		include("./ver_informe_legal/documentos1.php");  
		
	}
	
	//modificacion de informe legal ... Ahora modificar usa codigo de Adicionar. Victor
	//print_r($_REQUEST);
	if(isset($_REQUEST['modificar'])){
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
		}
		//adicionar.php tambien permite modificar. Victor
		include("./ver_informe_legal/adicionar.php");	
	}
	
	//solicitar inf legal
	if(isset($_REQUEST["solinfleg"])){
		$id = $_REQUEST['id'];
		include("./ver_informe_legal/solicitar.php");
	}else{
		//archivar docs
		if(isset($_REQUEST["archivar"])){
			include("./ver_informe_legal/archivar.php");
		}
	}
	
	//des-archivar docs
	if(isset($_REQUEST["desarchivar"])){
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$sql= "UPDATE informes_legales SET estado='rec'  WHERE id_informe_legal=$id";
			$query = consulta($sql);
			
		}
	}
	
	//eliminacion de informe legal
	//print_r($_REQUEST);
	if(isset($_REQUEST['eliminar'])){
		include("./ver_informe_legal/eliminar.php");	
	}
	//eliminando
	//print_r($_REQUEST);
	if(isset($_REQUEST["eliminar_boton_x"])){
		$acc = $_REQUEST["eliminar_boton_x"];
		include("./ver_informe_legal/eliminando.php");
	}
	
	//viendo el detalle de fechas de este informe legal
	if(isset($_REQUEST["ver_detalle"])){
		include("./ver_informe_legal/ver_detalle.php");
	}
	
	//Guardar Informe con sus documentos
	if(isset($_REQUEST["guardar_doc_infor"])){
 	include("./ver_informe_legal/guardar_infordocu.php");
	}

	//imprimir informe  y sus documentos	
	if(isset($_REQUEST["impri_doc_infor"])){
 	include("ver_informe_legal/imprimir_recepcion.php");
	}

	//opcion refinanciar desde bandeja de ya recepcionados
	if(isset($_REQUEST["refinanciar"])){
 	include("ver_informe_legal/refinanciar.php");
	}
	//opcion refinanciar desde bandeja de catastro
	if(isset($_REQUEST["refinanciar_cat"])){
 	include("ver_informe_legal/refinanciar_cat.php");
	}
	
	//ver en catastro para refinanciar	
	if(isset($_REQUEST["vcatastro"])){
 	include("ver_informe_legal/catastro_ver.php");
	}
	
	//ver el flujo del credito	
	if(isset($_REQUEST["verflujo"])){
		$id = $_REQUEST['id'];
		include("ver_informe_legal/flujo.php");
	}
	
	
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los lugares de emision
	$sql= "SELECT * FROM emisiones ";
	$query = consulta($sql);
	$i=0;
	$emisiones=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$emisiones[$i]= $row["emision"];
		$i++;
	}

	$smarty->assign('emisiones',$emisiones);

//-------------------------------------------------------------------------
//para la lista de recepcionados sin informe legal con nro de caso
$id_oficina = $_SESSION["id_oficina"];
if($armar_consulta == ""){

$sql = "SELECT ile.id_informe_legal, ile.cliente, tb.tipo_bien, tb.con_inf_legal, ile.fecha_recepcion, 
	ile.estado, ile.nrocaso, ofi.nombre as oficina, us.nombres as usuario, ile.noportunidad
	FROM informes_legales ile 
	INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
	INNER JOIN oficinas ofi ON ofi.id_oficina = ile.id_oficina 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	WHERE ile.estado='rec'  AND tb.categoria = '$cat'  
	AND (ile.id_oficina = '$id_oficina' OR ile.id_us_comun = '$id_us_actual')
	ORDER BY ile.id_informe_legal DESC";
//echo $sql;
}else{
$sql = "SELECT TOP 20 ile.id_informe_legal, ile.cliente, tb.tipo_bien, tb.con_inf_legal, ile.fecha_recepcion, 
	ile.estado, ile.nrocaso, ofi.nombre as oficina, us.nombres as usuario, ile.noportunidad
	FROM informes_legales ile 
	INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
	INNER JOIN oficinas ofi ON ofi.id_oficina = ile.id_oficina 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
	WHERE (ile.id_oficina = '$id_oficina' OR ile.id_us_comun = '$id_us_actual')  AND tb.categoria = '$cat' 
	 $armar_consulta 
	ORDER BY ile.id_informe_legal DESC";
	//echo $sql;
}

$query = consulta($sql);
$rec_lista=array();
$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$aux= $row["fecha_recepcion"];
	$aux_1= explode(" ",$aux);
	$aux=dateDMESY(dateDMY($aux_1[0]));
	$estado = $row["estado"];
	if($estado=='sol') $estadolit = 'Con solicitud de I.L.';
	elseif($estado=='apr') $estadolit = 'Aprobado para I.L.';
	elseif($estado=='ace') $estadolit = 'Aceptado para elaboracion de I.L.';
	elseif($estado=='arc') $estadolit = 'Pendiente de archivo';
	elseif($estado=='pub') $estadolit = 'Habilitado/Publicado';
	elseif($estado=='npu') $estadolit = 'No Habilitado';
	elseif($estado=='cat') $estadolit = 'En catastro';
	elseif($estado=='aut') $estadolit = 'Por autorizar revisión';
	elseif($estado=='ref') $estadolit = 'Refinanciado'; //este no se llega a mostrar
	else $estadolit = '???';
	$rec_lista[] = array('id_inf' => $row["id_informe_legal"],
						'clien' => $row["cliente"],
						'tbien' => $row["tipo_bien"],
						'con_il' => $row["con_inf_legal"],
						'estado' => $estado,
						'estadolit' => $estadolit,
						'nrocaso' => trim($row["nrocaso"]),
						'noportunidad' => trim($row["noportunidad"]),
						'oficina' => trim($row["oficina"]),
						'usuario' => trim($row["usuario"]),
						'fecha' => $aux);
	$i++;
}
	$smarty->assign('rec_lista',$rec_lista);
	

//
//para la lista de solicitudes pendientes de autorizacion
//recuperando los datos para la ventana

$sql = "SELECT il.id_informe_legal, il.cliente, tb.tipo_bien, us.nombres, 
	il.fecha, il.fecha_solicitud, il.estado
	FROM informes_legales il
	INNER JOIN usuarios us ON us.id_usuario = il.id_us_comun
	INNER JOIN oficinas ofi ON ofi.id_oficina = il.id_oficina
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien
	WHERE (il.estado='sol' OR il.estado='arc')  AND tb.categoria = '$cat' 
	AND il.id_oficina = '$id_oficina' $armar_consulta ORDER BY il.id_informe_legal DESC";
//echo $sql;
$query = consulta($sql);
$sol_informe_legal= array();
$i=0;

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	
	$aux= $row["fecha"];
	$aux_1= explode(" ",$aux);
	$aux1=dateDMESY(dateDMY($aux_1[0]));
	$aux= $row["fecha_solicitud"];
	$aux_1= explode(" ",$aux);
	$aux2=dateDMESY(dateDMY($aux_1[0]));

	$sol_informe_legal[]= array('id_informe_legal' => $row["id_informe_legal"],
								'nombreus' => $row["nombres"],
								'tipo_bien' => $row["tipo_bien"],
								'cliente' => $row["cliente"],
								'fecha1' => $aux1 ,
								'fecha2' => $aux2 ,
								'estado' => $row["estado"]);
	$i++;
}
	$smarty->assign('sol_informe_legal',$sol_informe_legal);
	
	$smarty->display('recepcion2.html');
	die();

?>
	
