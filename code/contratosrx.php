<?php

/*
   contrato para ver a nivel regional
*/

require_once("../lib/setup.php");
$smarty = new bd;
require_once('../lib/conexionSEC.php');
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	//preparamos datos del usuario
	if(isset($_SESSION["USER"])){
		$USER = $_SESSION["USER"];
	}else{
		if(isset($glogin)){
			$_SESSION["USER"]=$glogin;
			$USER = $glogin;
		}else{
			die("No se encontr&oacute; usuario con sesi&oacute;n activa!");
		}
	}
	//establecemos el tipo de usuario 2=normal_regional, 6=regional_dos_firmas
	if(isset($quien)){
		$_SESSION["quien"]=$quien;
	}else{
		$quien = $_SESSION["quien"];
	}
	
	//obtenemos la localizacion de este usuario
	if(!isset($_SESSION["localiza"])){
		$sql = "SELECT localizacion FROM usuario WHERE login = '$glogin' ";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		if($row["localizacion"]!=''){
			$_SESSION["localiza"] = $row["localizacion"];
			$localiza = $_SESSION["localiza"];
		}else{
			die("No se puede obtener la regional de este usuario!.(Revise sistema SEC, falta completar la informacion de este usuario (Herramientas/Usuarios).");
		}
	}else{
		$localiza = $_SESSION["localiza"];
	}
	// href
	$carpeta_entrar="_main.php?action=contratosr.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	// form
	$carpeta_acc= "contratosr";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//filtro de la ventana
	if(!isset($_SESSION['filtro_texto'])){
		//ponemos por defecto contratos de hoy
		$aux1 = date("d/m/Y");
		$_SESSION["filtro_fecha"]= $aux1;
		$_SESSION["filtro_fech2"]= $aux1;
		$_SESSION["filtro_texto"]= '';
		$_SESSION["filtro_firma"]= '*';
	}
	
	if(isset($_REQUEST['buscar_boton'])){
		
		$filtro_firma= $_REQUEST['filtro_firma'];
		$filtro_texto= $_REQUEST['filtro_texto'];
		$filtro_fecha= $_REQUEST['filtro_fecha'];
		$filtro_fech2= $_REQUEST['filtro_fech2'];
	}//fin del if de buscar_boton
	else{
		$filtro_texto= $_SESSION["filtro_texto"];
		$filtro_firma= $_SESSION["filtro_firma"];
		$filtro_fecha= $_SESSION["filtro_fecha"];
		$filtro_fech2= $_SESSION["filtro_fech2"];
	}	
	$del_filtro='';	
	//firma
	if($filtro_firma != "*"){
		$del_filtro= "AND cf.firmado = '$filtro_firma' ";
	}
	
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
		$_SESSION["filtro_firma"]= $filtro_firma;
		$_SESSION["filtro_fecha"]= $filtro_fecha;
		$_SESSION["filtro_fech2"]= $filtro_fech2;
		
	//filtro de la ventana

	$smarty->assign('filtro_texto',$filtro_texto);
	$smarty->assign('filtro_firma',$filtro_firma);
	$smarty->assign('filtro_fecha',$filtro_fecha);
	$smarty->assign('filtro_fech2',$filtro_fech2);
	

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	
	//adicionar Paso 0, elegir contrato nuevo por primera vez
	if(isset($_REQUEST['adicionar'])){
		include("./contratos/adicionar.php");
	}
	//adicionar Paso 1, elegir contrato
	if(isset($_REQUEST['adicionar1'])){
		include("./contratos/adicionar1.php");
	}
	
	//adicionar Paso 2, seleccionar clausulas opcionales
	if(isset($_REQUEST['adicionar2'])){
		include("./contratos/adicionar2.php");
	}
	
	//adicionar Paso 3, seleccionar incisos
	if(isset($_REQUEST['adicionar3'])){
		include("./contratos/adicionar3.php");
	}
	
	//adicionar Paso 4, llenar variables
	if(isset($_REQUEST['adicionar4'])){
		include("./contratos/adicionar4.php");
	}
	
	//adicionar Paso 5, registrar partes
	if(isset($_REQUEST['adicionar5'])){
		include("./contratos/partes.php");
		die();
	}
	
	//adicionar Paso 6, generar contrato
	if(isset($_REQUEST['adicionar6'])){
		include("./contratos/armar.php");
		die();
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton_x'])){
		include("./contratos/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("./contratos/modificar.php");
	}
	
	//modificando modificar_f
	if(isset($_REQUEST['modificar_p'])){
		include("./contratos/modpartes.php");
	}
	
	//modificando modificar_f
	if(isset($_REQUEST['modificar_f'])){
		include("./contratos/modarmar.php");
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
		if($_REQUEST['firma']=='1')
		$etiqueta = '  Firmar  ';
		else
		$etiqueta = 'Quitar Firma';
		include("./contratos/firma.php");
	}
	
/****************fin de valores para la ventana*************************/
/***********************************************************************/


/**********************valores por defecto*************************/
/******************************************************************/

//recuperando los datos para la ventana
$miscontratos= array();
$glogin = $_SESSION['glogin'];
if($quien == "2"){
//regional sin notificacion
$sql= "SELECT cf.idfinal, cf.idcontrato, co.titulo, cf.login, us.nombres, us.appaterno,  
(CASE WHEN PATINDEX('%<personas>%', cf.contenido_sec) > 0 THEN substring(cf.contenido_sec, patindex('%<nombre>%', cf.contenido_sec)+ 8, (patindex('%</nombre>%', cf.contenido_sec)-patindex('%<nombre>%', cf.contenido_sec)-8)) ELSE '' END) cliente,
CONVERT(VARCHAR(10), cf.fechahora, 103) AS fecha , 
CONVERT(VARCHAR(10), cf.fechahora, 108) AS hora, 
cf.firmado ,
CASE WHEN cf.contenido_final is null THEN '1' ELSE '0' END nulo,
cf.ultimo_login AS modifica
FROM contrato_final cf LEFT JOIN contrato co 
ON cf.idcontrato = co.idcontrato 
INNER JOIN usuario us ON us.login = cf.login
WHERE us.localizacion ='$localiza' AND cf.eliminado = '0' ".
$del_filtro." ORDER BY cf.fechahora";  
}elseif($quien == "5"){
//regional con notificacion
$sql= "SELECT cf.idfinal, cf.idcontrato, co.titulo, cf.login, us.nombres, us.appaterno,  
(CASE WHEN PATINDEX('%<personas>%', cf.contenido_sec) > 0 THEN substring(cf.contenido_sec, patindex('%<nombre>%', cf.contenido_sec)+ 8, (patindex('%</nombre>%', cf.contenido_sec)-patindex('%<nombre>%', cf.contenido_sec)-8)) ELSE '' END) cliente,
CONVERT(VARCHAR(10), cf.fechahora, 103) AS fecha , 
CONVERT(VARCHAR(10), cf.fechahora, 108) AS hora, 
cf.firmado ,
CASE WHEN cf.contenido_final is null THEN '1' ELSE '0' END nulo,
cf.ultimo_login AS modifica
FROM contrato_final cf LEFT JOIN contrato co 
ON cf.idcontrato = co.idcontrato 
INNER JOIN usuario us ON us.login = cf.login
WHERE us.localizacion ='$localiza' AND cf.eliminado = '0'
AND cf.firmado <> '0' ".
$del_filtro." ORDER BY cf.fechahora";  
}else{
// a nivel de relacion usuario en SEC
$sql= "SELECT cf.idfinal, cf.idcontrato, co.titulo,  cf.login,  us.localizacion, us.nombres, us.appaterno,
(CASE WHEN PATINDEX('%<personas>%', cf.contenido_sec) > 0 THEN substring(cf.contenido_sec, patindex('%<nombre>%', cf.contenido_sec)+ 8, (patindex('%</nombre>%', cf.contenido_sec)-patindex('%<nombre>%', cf.contenido_sec)-8)) ELSE '' END) cliente,
CONVERT(VARCHAR(10), cf.fechahora, 103) AS fecha , 
CONVERT(VARCHAR(10), cf.fechahora, 108) AS hora, 
cf.firmado,
CASE WHEN cf.contenido_final is null THEN '1' ELSE '0' END nulo,
cf.ultimo_login AS modifica
FROM contrato_final cf 
LEFT JOIN contrato co ON cf.idcontrato = co.idcontrato 
INNER JOIN usuariousuario uu ON uu.usuario = cf.login AND usuarioreg = '$glogin'
INNER JOIN usuario us ON us.login = uu.usuario
WHERE cf.eliminado = '0'".
$del_filtro." ORDER BY us.localizacion, cf.fechahora"; 
}
//, cf.contenido_sec
	$query= consulta($sql);
	$i=0;
	require('../lib/conexionMNU.php');
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		//
		
		$sql = "SELECT us.telefono, ofi.nombre as oficina
			FROM usuarios us INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
			WHERE login = '".$row["login"]."' ";
		$query2 = consulta($sql);
		$row2= $query2->fetchRow(DB_FETCHMODE_ASSOC);
		
		$miscontratos[$i]= array('id' => $row["idfinal"],
							'login' => $row["login"],
							'titulo' => $row["titulo"],
							'parte' => $row["cliente"],
							'fecha' => $row["fecha"],
							'hora' => substr($row["hora"],0,5),
							'firma' => $row["firmado"],
							'nulo' => $row["nulo"],
							'telefono' => $row2["telefono"],
							'oficina' => $row2["oficina"],
							'elabora' => trim($row["nombres"]).' '.trim($row["appaterno"]),
							'modifica' => $row["modifica"]);
							
		$i++;
	}
	require('../lib/conexionSEC.php');
//}
	$smarty->assign('quien',$quien);
	$smarty->assign('miscontratos',$miscontratos);
	$smarty->display('contratosr.html');
	die();

?>