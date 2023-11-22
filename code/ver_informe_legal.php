<?php
//inf_leg
require_once("../lib/setup.php");
$smarty = new bd;	
//require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//recuperamos los datos del usuario

	$id_us_actual = $_SESSION["idusuario"];
	$nombre_us_actual= $_SESSION["nombreusr"];
	$smarty->assign('id_us_actual',$id_us_actual);
	$smarty->assign('nombre_us_actual',$nombre_us_actual);
	
	//href
	$carpeta_entrar="./_main.php?action=ver_informe_legal.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "ver_informe_legal";
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
	
	//armando la consulta ; modificado por Percy 20/12/2018 utilizando p.* en vez de ci_cliente y cliente
	$armar_consulta = "";
	if($f_cliente != ""){
		$armar_consulta.= "AND p.nombres LIKE '%$f_cliente%' ";
	}
	if($f_ci_cliente != ""){
		$armar_consulta.= "AND p.ci LIKE '%$f_ci_cliente%' ";
	}
	
	
/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	//impresion
	if(isset($_REQUEST['imprimir'])){
		//verificamos si es vehiculo o inmueble
		$id=$_REQUEST['id'];
		$tipo_bien = $_REQUEST['imprimir'];
		//echo $bien;
		include("./informe_legal/imprimir_bien2.php");
		
	}
	
	if(isset($_REQUEST['imprimirIL'])){
		$id=$_REQUEST['id'];
		include("./informe_legal/imprimir_final2.php");
	}
	
	//adicion de informe legal

	if(isset($_REQUEST['adicionar'])){
		include("./ver_informe_legal/adicionar.php");	
	}
	//adicionando
	if(isset($_REQUEST["adicionar_boton_x"])){
		$esRecepcion = 0; //para saber si adicionando.php es llamado desde recepcion.php 0=No 1=Si
		if($_REQUEST["estado_formulario"] == "Adicionar"){
			include("./ver_informe_legal/adicionando.php");
			include("./ver_informe_legal/documentos1.php");
		}else{
			include("./ver_informe_legal/modificando.php");
			include("./ver_informe_legal/documentos1.php");  
		}
	}
	
	//modificacion de informe legal ... Ahora modificar usa codigo de Adicionar. Victor
	//print_r($_REQUEST);
	if(isset($_REQUEST['modificar'])){
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
		}
		//echo 'x'; echo $esRecepcion;
		//adicionar.php tambien permite modificar. Victor
		include("./ver_informe_legal/adicionar.php");	
	}
	
	//impresion
	if(isset($_REQUEST['imprimir_recepcion'])){

		include("ver_informe_legal/imprimir_recepcion.php");
		
	}
	
	//solicitar inf legal
	if(isset($_REQUEST["solinfleg"])){
		$id = $_REQUEST['id'];
		include("./ver_informe_legal/solicitar.php");
	}   
	
	//archivar docs
	if(isset($_REQUEST["archivar"])){
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$sql= "UPDATE informes_legales SET estado='arc'  WHERE id_informe_legal=$id";
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
 	include("./reportes/infor_docus_imp.php");
	}
	
	//excepcion a informe legal
	if(isset($_REQUEST['excepcion'])){
		include("./ver_informe_legal/excepcion.php");
	}
	
	//excepcion a informe legal
	if(isset($_REQUEST['excepcion_boton'])){
		include("./ver_informe_legal/excepcionreg.php");
		include("./ver_informe_legal/excepcion.php");
	}	
	
	//imprimir observaciones de la excepcion 
	if(isset($_REQUEST['imprimir_exce'])){
		$id = $_REQUEST['id'];
		//$volvera = "./_main.php?action=ver_informe_legal.php&carpeta_entrar=ver_informe_legal";
		include("./ver_informe_legal/excepcion_imprime.php");
	}
	//
	//solicitar aprobacion excepcion a informe legal
	if(isset($_REQUEST['solicitar_boton_aprobar'])){
		include("./ver_informe_legal/excepcion_solicita.php");
	}
	//enviando solicitud
	if(isset($_REQUEST['solicitando_boton'])){
		include("./ver_informe_legal/excepcion_solicitando.php");
	}
	
	
	//enviando solicitud
	if(isset($_REQUEST['solcontra'])){
		include("./ver_informe_legal/solcontra.php");
	}
	
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//para la lista de sol de il y archivo
	$id_oficina = $_SESSION["id_oficina"];
$sql = "SELECT il.id_informe_legal, il.habilitar_informe, il.nrobien, p.nombres cliente, tb.tipo_bien, tb.bien, us.nombres, 
	il.fecha_recepcion, il.fecha, il.fecha_solicitud, il.puede_operar, il.motivo, il.inf_agencia 
	FROM informes_legales il
	INNER JOIN usuarios us   ON us.id_usuario  =il.id_us_comun 
	inner join oficinas ofi ON ofi.id_oficina = us.id_oficina 
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien=il.id_tipo_bien 
	INNER JOIN propietarios p ON p.id_propietario=il.id_propietario
	WHERE il.estado='sol' AND ofi.id_oficina = '$id_oficina' 
	AND tb.con_inf_legal = 'S' 
	$armar_consulta ORDER BY il.id_informe_legal DESC";
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
								'habilitar_informe' => $row["habilitar_informe"],
								'nombreus' => $row["nombres"],
								'tipo_bien' => $row["tipo_bien"],
								'bien' => $row["bien"],
								'nrobien' => $row["nrobien"],
								'cliente' => $row["cliente"],
								'fecha1' => $aux1 ,
								'fecha2' => $aux2 ,
								'puede_operar' => $row["puede_operar"],
								'motivo' => $row["motivo"],
								'inf_agencia' => $row["inf_agencia"]);
	$i++;
}
	$smarty->assign('sol_informe_legal',$sol_informe_legal);

	//que banco es
	$sql="SELECT TOP 1 enable_ws FROM opciones ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row['enable_ws'];
	$smarty->assign('enable_ws',$enable_ws);
	
	
//para los publicados
//recuperando los datos para la ventana

$pub_informe_legal = array();
$conexcepcion=array();

if($armar_consulta != ""){
	$sql= "SELECT TOP 30 ile.id_informe_legal, ile.id_us_comun, 
	ile.habilitar_informe, p.nombres cliente,
	ile.bandera, ile.nrobien, ile.puede_operar, cast(ile.exe_aprobar AS VARCHAR) exe_aprobar,
	us.nombres, tb.tipo_bien, tb.bien, susobs.nobs, nc.nrocaso
FROM informes_legales ile 
INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien
INNER JOIN propietarios p ON p.id_propietario=ile.id_propietario
LEFT JOIN ( 
	SELECT max(id_informe_legal) as id, count(*) as nobs 
	FROM informes_legales_documentos 
	WHERE tiene_observacion =1 
	GROUP BY  id_informe_legal 
) susobs ON susobs.id = id_informe_legal 
LEFT JOIN ncaso_cfinal  nc ON ile.id_informe_legal = nc.id_informe 
WHERE estado='pub' $armar_consulta ORDER BY id_informe_legal DESC";
//echo $sql;
/*
	$sql= "SELECT TOP 30 ile.id_informe_legal, ile.id_us_comun, 
	ile.habilitar_informe, p.nombres cliente,
	ile.bandera, ile.nrobien, ile.puede_operar, ile.exe_aprobar,
	us.nombres, tb.tipo_bien, tb.bien, susobs.nobs, ile.instancia
FROM informes_legales ile 
INNER JOIN usuarios us   ON us.id_usuario  =ile.id_us_comun 
INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
INNER JOIN propietarios p ON p.id_propietario=ile.id_propietario
LEFT JOIN ( 
	SELECT max(id_informe_legal) as id, count(*) as nobs 
	FROM informes_legales_documentos 
	WHERE tiene_observacion =1 
	GROUP BY  id_informe_legal 
) susobs ON susobs.id = id_informe_legal 
WHERE estado='pub' $armar_consulta ORDER BY id_informe_legal DESC";
*/
	$query = consulta($sql);
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

		$idus = $row["id_us_comun"];
		//echo $row["idfinal"];
		if(($row["nrocaso"]=='0' or $row["nrocaso"]=='') and $enable_ws=='S' )//bsol
		//if($row["idfinal"]=='999' and $enable_ws=='S' )//bsol
			$contrato = 's';
		else
			$contrato = 'n';
		
		$pub_informe_legal[]= array('id' => $row["id_informe_legal"],
						'habilitar_informe' => $row["habilitar_informe"],
						'bandera' => $row["bandera"],
						'icono' => 'b'.$row["bandera"].'.png',
						'usuario' => $row["nombres"],
						'idus' => $row["id_us_comun"],
						'tipo_bien' => $row["tipo_bien"],
						'bien' => $row["bien"],
						'nrobien' => $row["nrobien"],
						'cliente' => $row["cliente"],
						'puede_operar' => $row["puede_operar"],
						'contrato' => $contrato
						);

		//vemos si tiene excepcion, si es asi en que estado esta
		$sql_u="SELECT estado FROM informes_legales_excepciones WHERE id_informe_legal='".$row["id_informe_legal"].
		"' AND (id_us_revisa = $idus or id_us_solicita = $idus)";
		$result_u= consulta($sql_u);
		$resultado_u= $result_u->fetchRow(DB_FETCHMODE_ASSOC);
		if($resultado_u["estado"] != ""){
			$conexcepcion[$i]= $resultado_u["estado"];
		}else{
			$obss=$row["nobs"];
			//20/09/2012 sc bco, se aumenta para que salga tambien si esta con bandera amarilla o roja
			if($obss>0 || $row["bandera"]=='a' || $row["bandera"]=='r'){
				if($enable_ws=='S') //bsol
					$conexcepcion[$i]="OBS";
				else
				if($row["exe_aprobar"]!='')
					$conexcepcion[$i]="OBS";
				else
					$conexcepcion[$i]="xxx";
			}else{
				$conexcepcion[$i]="";
			}
		}

		$i++;
	}	
}
	$smarty->assign('pub_informe_legal',$pub_informe_legal);

	$smarty->assign('conexcepcion',$conexcepcion);
				
	$smarty->display('ver_informe_legal.html');
	die();

?>