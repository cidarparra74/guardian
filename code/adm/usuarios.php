<?php
//session_start();
/************************************************************************************/
/************************************************************************************/

require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');


	//cargando para el overlib
	include("../lib/cargar_overlib.php");
	/*********************************operaciones de la ventana*************/
	/*********************************operaciones de la ventana*************/
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("usuarios/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		include("usuarios/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("usuarios/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton'])){
		include("usuarios/modificando.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("usuarios/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton'])){
		include("usuarios/eliminando.php");
	}
	
	/*********************************fin de operaciones de la ventana*************/
	/*********************************fin de operaciones de la ventana*************/
	
	/***************************************************************/
	//valores por defecto
	/***************************************************************/
	
	//para oficinas
	$sql = "SELECT ofi.id_oficina , al.nombre as almacen, ofi.nombre FROM oficinas ofi 
			LEFT JOIN almacen al ON ofi.id_almacen = al.id_almacen ORDER BY al.nombre, ofi.nombre";
	$query= consulta($sql);
	$i=0;
	$oficinas= array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$oficinas[$i]= array('id_oficina' => $row["id_oficina"],
		'oficina' => $row["almacen"].'/'.$row["nombre"]);
		$i++;
	}
	$smarty->assign('oficinas',$oficinas);
	
	//para los perfiles
	$sql = "SELECT id_perfil , perfil FROM perfiles 
			WHERE activo = 'S' ORDER BY perfil";
	$query= consulta($sql);
	$i=0;
	$perfiles= array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$perfiles[$i]= array('id_perfil' => $row["id_perfil"],
		'perfil' => $row["perfil"]);
		$i++;
	}
	$smarty->assign('perfiles',$perfiles);
	
	
	//verificamos opciones de busqueda
	$estado = '*';
	$filtro = " WHERE u.activo <> 'E' ";
	if(isset($_REQUEST['estado']) && $_REQUEST['estado']!='*'){
		$estado = $_REQUEST['estado'];
		$filtro = " WHERE u.activo = '$estado' ";
	}else	$id_oficina = '*';
	if(isset($_REQUEST['id_oficina']) && $_REQUEST['id_oficina']!='*'){
		$id_oficina = $_REQUEST['id_oficina'];
		$filtro .= " AND u.id_oficina = $id_oficina ";
	}else	$id_oficina = '*';
	if(isset($_REQUEST['id_perfil']) && $_REQUEST['id_perfil']!='*'){
		$id_perfil = $_REQUEST['id_perfil'];
		$filtro .= " AND u.id_perfil = $id_perfil ";
	}else	$id_perfil = '*';
	if(isset($_REQUEST['usuario']) && $_REQUEST['usuario']!=''){
		$usuario = $_REQUEST['usuario'];
		$filtro .= " AND u.nombres LIKE '%$usuario%' ";
	}else	$usuario = '';
	
	$smarty->assign('id_oficina',$id_oficina);
	$smarty->assign('id_perfil',$id_perfil);
	$smarty->assign('usuario',$usuario);
	$smarty->assign('estado',$estado);
	
	
	//listado principal
	
	$sql = "SELECT u.id_usuario, u.id_perfil, u.nombres, u.login, u.activo, p.perfil, 
al.nombre as recinto, ofi.nombre, convert(varchar,u.fecha_eli,103) as fecha, u.user_eli
FROM usuarios u 
LEFT JOIN perfiles p ON u.id_perfil=p.id_perfil 
LEFT JOIN oficinas ofi ON u.id_oficina = ofi.id_oficina 
LEFT JOIN almacen al ON ofi.id_almacen = al.id_almacen 
$filtro
ORDER BY u.id_perfil, al.nombre, ofi.nombre, u.nombres";
			

	$query= consulta($sql);
	$i=0;
	$usuarios= array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$usuarios[$i]= array('id_usuario' => $row["id_usuario"],
		'oficina' => $row["recinto"].'/'.$row["nombre"],
		'nombres' => $row["nombres"],
		'id_perfil' => $row["id_perfil"],
		 'perfil' => $row["perfil"],
		'login' => $row["login"],
		'activo' => $row["activo"],
		'fecha_eli' => $row["fecha"],
		'user_eli' => $row["user_eli"]);
		$i++;
	}
	$smarty->assign('usuarios',$usuarios);
	$smarty->display('adm/usuarios/usuarios.html');
	die();
	
?>
