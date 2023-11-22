<?php
/*
   contrato para ASISTENTE PLATAFORMA
*/

require_once("../lib/setup.php");
$smarty = new bd;
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	$guser = $_SESSION["idusuario"];
	//leemos del guardian
	$sql = "SELECT login, nombres FROM usuarios WHERE id_usuario=$guser";
	//echo $sql;
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$glogin = $row["login"];
	$gnombre = $row["nombres"];
	$_SESSION['glogin'] = $glogin;


	unset($link); 

require_once('../lib/conexionSEC.php');

	//preparamos datos del usuario
	if(isset($_SESSION["USER"]) && $_SESSION["USER"]!=0){
		$USER = $_SESSION["USER"];
	}else{
		if(isset($_SESSION['glogin'])){
			$_SESSION["USER"]=$_SESSION['glogin'];
			$USER = $_SESSION['glogin'];
		}else{
			die("No se encontr&oacute; usuario con sesi&oacute;n activa!");
		}
	}
	
	//establecemos el tipo docuemnto word o open
	if(isset($_SESSION["tipodoc"])){
		$tipodoc = $_SESSION["tipodoc"];
	}//else no debiera ocurrir
	
	//definido en contratoslog.php (leido de tabla opciones)
	$smarty->assign('tipodoc',$tipodoc); 
	
	//vemos si son automaticos, si pued editar
	/*
	if(isset($_REQUEST["edit"])){
		$edita = $_REQUEST["edit"];
	}else{
		$edita = 's';
	}
	*/
	$edita = 'n';
	
	$smarty->assign('edita',$edita);
	
	//vemos si puede abrir en word
	//lo siguiente es para cuando entra a modificar el contrato:
	
	//if(isset($_SESSION["word"])){
		//$word = $_SESSION["word"];
	//}else{
		// en teoria nunca entrara aqui
		$word = 'n';
		//$word = 's';
		$_SESSION['word']=$word;
	//}
	$smarty->assign('word',$word);

	
	//href
	$carpeta_entrar="_main.php?action=contratos_bus.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "contratos_bus";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//vemos que grupo de ocntratos es (cja o  cta)
		$sql = "SELECT cta_ahorro, cta_corriente FROM parametros_c ";
	//echo $sql;
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$tipo = $row["cta_ahorro"] .',' . $row["cta_corriente"];
	
	//filtro de la ventana
	if(!isset($_SESSION['filtro_texto'])){
		//ponemos por defecto contratos de hoy
		$aux1 = date("d/m/Y");
		$_SESSION["filtro_fecha"]= $aux1;
		$_SESSION["filtro_fech2"]= $aux1;
		$_SESSION["filtro_texto"]= '';
		//$_SESSION["filtro_firma"]= '*';
	}
	
	if(isset($_REQUEST['buscar_boton'])){
		
		//$filtro_firma= $_REQUEST['filtro_firma'];
		$filtro_texto= $_REQUEST['filtro_texto'];
		$filtro_fecha= $_REQUEST['filtro_fecha'];
		$filtro_fech2= $_REQUEST['filtro_fech2'];
	}//fin del if de buscar_boton
	else{
		$filtro_texto= $_SESSION["filtro_texto"];
		//$filtro_firma= $_SESSION["filtro_firma"];
		$filtro_fecha= $_SESSION["filtro_fecha"];
		$filtro_fech2= $_SESSION["filtro_fech2"];
	}	
	$del_filtro='';	
	//firma
	//if($filtro_firma != "*"){
	//	$del_filtro= "AND cf.firmado = '$filtro_firma' ";
	//}
	
	//texto
	if($filtro_texto != ''){
		$del_filtro= $del_filtro."AND cf.contenido_sec LIKE '%$filtro_texto%' ";
	}
	
	//fecha
	if($filtro_fecha != '' && $filtro_fech2 == ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), cf.fechahora, 103), 103) = '$filtro_fecha' ";
	}
	if($filtro_fecha != '' && $filtro_fech2 != ''){
		$del_filtro= $del_filtro."AND CONVERT(DATETIME, CONVERT(VARCHAR(10), cf.fechahora, 103), 103) >= CONVERT(DATETIME, '$filtro_fecha', 103) AND CONVERT(DATETIME, CONVERT(VARCHAR(10), cf.fechahora, 103), 103) <= CONVERT(DATETIME, '$filtro_fech2', 103) ";
	}
		
		//variables de sesion
		$_SESSION["filtro_texto"]= $filtro_texto;
		//$_SESSION["filtro_firma"]= $filtro_firma;
		$_SESSION["filtro_fecha"]= $filtro_fecha;
		$_SESSION["filtro_fech2"]= $filtro_fech2;
		
	//filtro de la ventana

	$smarty->assign('filtro_texto',$filtro_texto);
	//$smarty->assign('filtro_firma',$filtro_firma);
	$smarty->assign('filtro_fecha',$filtro_fecha);
	$smarty->assign('filtro_fech2',$filtro_fech2);

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		//include("./contratos/modificar.php");
	}
	
	//modificando modificar_f
	if(isset($_REQUEST['modificar_p'])){
		//include("./contratos/modpartes.php");
	}
	
	//modificando modificar_f
	if(isset($_REQUEST['modificar_f'])){
		//include("./contratos/modarmar.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("./contratos/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton_x'])){
		include("./contratos/eliminando.php");
	}
	
	//cancelando
	if(isset($_REQUEST['adicionar_boton_cancelar'])){
		unset($_SESSION['idcontrato']);
		unset($_SESSION['contrato']);
		unset($_SESSION['cantidad']);
		unset($_SESSION['idfinal']);
		unset($_SESSION['opcional']);
		unset($_SESSION['incisos']);
		unset($_SESSION['principal']);
		unset($_SESSION['partes']);
	}
	
	//firmando
	if(isset($_REQUEST['firmando'])){
		include("./contratos/firmando.php");
	}
	//firma
	if(isset($_REQUEST['firma'])){
		if($_REQUEST['firma']=='2')
		$etiqueta = '  Notificar  ';
		elseif($_REQUEST['firma']=='1')
		$etiqueta = 'Quitar Notificaci&oacute;n';
		elseif($_REQUEST['firma']=='3')
		$etiqueta = 'Firmar';
		elseif($_REQUEST['firma']=='4')
		$etiqueta = 'Quitar Firma';
		elseif($_REQUEST['firma']=='0')
		$etiqueta = 'Quitar Notificaci&oacute;n';
		
		include("./contratos/firma.php");
	}
	//firma
	if(isset($_REQUEST['gendoc'])){
		
		include("./contratos/generadoc.php");
	}
	
	//enviado correo
	if(isset($_REQUEST['enviarmail'])){
		
		include("./contratos/enviarmail.php");
	}
	
	
	//firma
	if(isset($_REQUEST['masopc'])){
		
		include("./contratos/vercontra.php");
	}
/****************fin de valores para la ventana*************************/
/***********************************************************************/

/**********************valores por defecto*************************/
/******************************************************************/

//recuperando los datos para la ventana
$miscontratos= array();
$glogin = $_SESSION['glogin'];
//if($del_filtro != ''){

$sql= "SELECT TOP 50 cf.idfinal, cf.idcontrato, co.titulo, 
(CASE WHEN PATINDEX('%<personas>%', cf.contenido_sec) > 0 THEN substring(cf.contenido_sec, patindex('%<nombre>%', cf.contenido_sec)+ 8, (patindex('%</nombre>%', cf.contenido_sec)-patindex('%<nombre>%', cf.contenido_sec)-8)) ELSE '' END) cliente,
CONVERT(VARCHAR(10), cf.fechahora, 103) AS fecha , 
CONVERT(VARCHAR(10), cf.fechahora, 108) AS hora, 
cf.firmado, co.con_firma_abogado AS confirma, 
CASE WHEN cf.contenido_final is null THEN '1' ELSE '0' END nulo,
cf.ultimo_login AS modifica, us.nombres, us.appaterno
FROM contrato_final cf LEFT JOIN contrato co 
ON cf.idcontrato = co.idcontrato 
LEFT JOIN usuario us ON us.login = cf.ultimo_login
WHERE cf.login ='$glogin' AND cf.eliminado = '0' AND co.codtipo IN ($tipo) ".$del_filtro;  //, cf.contenido_sec
//echo $sql; die();
/*
SELECT TOP 50 cf.idfinal, cf.idcontrato, co.titulo, 
(CASE WHEN PATINDEX('%<personas>%', cf.contenido_sec) > 0 THEN substring(cf.contenido_sec, patindex('%<nombre>%', cf.contenido_sec)+ 8, (patindex('%</nombre>%', cf.contenido_sec)-patindex('%<nombre>%', cf.contenido_sec)-8)) ELSE '' END) cliente,
CONVERT(VARCHAR(10), cf.fechahora, 103) AS fecha , 
CONVERT(VARCHAR(10), cf.fechahora, 108) AS hora, 
cf.firmado, co.con_firma_abogado AS confirma, 
CASE WHEN cf.contenido_final is null THEN '1' ELSE '0' END nulo,
cf.ultimo_login AS modifica, us.nombres, us.appaterno
FROM contrato_final cf LEFT JOIN contrato co 
ON cf.idcontrato = co.idcontrato 
LEFT JOIN usuario us ON us.login = cf.ultimo_login
WHERE cf.idfinal=  -->
	*/						
	$query= consulta($sql);
	
	$nrocasos = '-9' ;  //aumentamos para q salga el nro de caso
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$nrocasos .= ','.$row["idfinal"] ;
		$miscontratos[$i]= array('id' => $row["idfinal"],
							'titulo' => $row["titulo"],
							'parte' => $row["cliente"],
							'fecha' => $row["fecha"],
							'hora' => substr($row["hora"],0,5),
							'confirma' => $row["confirma"], 
							'firma' => $row["firmado"], 
							'firmante' => $row["nombres"]." ".$row["appaterno"],
							'nulo' => $row["nulo"],
							'modifica' => $row["modifica"],
							'nrocaso' => '');
		$i++;
	}
	require('../lib/conexionMNU.php');
	
	//buscamo los nros de caso correspondientes al contrato segun idfinal --- 18/09/2012 bec
	$sql = "SELECT nrocaso, idfinal FROM ncaso_cfinal WHERE idfinal IN ($nrocasos)";
	//echo $sql;
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		foreach($miscontratos as $key => $cntrt){
			if($cntrt['id']==$row["idfinal"]){
				$miscontratos[$key]['nrocaso'] = $row["nrocaso"];
				break;
			}
		}
	}

	
	require('../lib/conexionSEC.php');
//}

	$smarty->assign('miscontratos',$miscontratos);
	$smarty->assign('quien',$quien);
	$smarty->display('contratos_bus.html');
	die();

?>