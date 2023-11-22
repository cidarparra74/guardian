<?php
	session_start();
	require_once("../lib/setup.php");
	$smarty = new bd;
	//lee las opciones de la barra demus
	require_once('../lib/conexionMNU.php');
	//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	if(!isset($_SESSION["idperfil"])){
		$idperfil = 0 ;
	}else{
		//capturamos el perfil
		$idperfil = $_SESSION["idperfil"];
	}
	
	if(isset($_SESSION["id_banca"]) and $_SESSION["id_banca"] > 0){
		$sql = "SELECT banca FROM bancas WHERE id_banca = ".$_SESSION["id_banca"];
		$query = consulta($sql);
		$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
		$banca = $row['banca'];
		if($banca ==''){
			$banca = '(Error en BANCA)';
		}
	}else{
		$banca = '';
	}
	
	$sql = "SELECT perfil FROM perfiles WHERE id_perfil = $idperfil";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$perfil = $row['perfil'];
	//recuperamos oficina
	$id_usuario=$_SESSION["idusuario"];
	$query = consulta("SELECT ofi.nombre, al.nombre as almacen FROM usuarios us LEFT JOIN oficinas ofi ON us.id_oficina = ofi.id_oficina 
	INNER JOIN almacen al ON al.id_almacen = ofi.id_almacen WHERE us.id_usuario= '$id_usuario'");
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$oficina = $row['almacen'].' / '.$row['nombre'];
	//Usuario Para la barra de informacion
	$usuario = $_SESSION["nombreusr"];
	//si no tiene perfil asignado estara en cero
	//
	//if($idperfil!='0'){
		//leemos la tabla para la barra de menu
		$sql = "SELECT bm.descripcion, bm.imagen, left(bm.codigo,1) as nivsup 
				FROM permiso pe 
				INNER JOIN barramenu bm ON pe.codigo = bm.codigo 
				WHERE nivel = 1 AND pe.idperfil = $idperfil 
				ORDER BY bm.codigo ";
		//echo $sql;
		$query = consulta($sql);
		$toolbar = array();
		while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
				$toolbar[] = array( 'descri'  => $row['descripcion'],
									'imagen'  => $row['imagen'],
									'codigo' => $row['nivsup']);
		
		}
	//}else{
	//	$toolbar = array();
	//	$toolbar[] = array( 'descri'  => 'SIN ACCESO',
	//						'imagen'  => '',
	//						'codigo' => '0');
	//}
	if(isset($_SESSION["log_directo"] )){
		$smarty->assign('salir','n');
	}else{
		$smarty->assign('salir','s');
	}
	
	$smarty->assign('idusuario',$id_usuario);
	$smarty->assign('toolbar',$toolbar);
	$smarty->assign('usuario',$usuario);
	$smarty->assign('perfil',$perfil);
	$smarty->assign('banca',$banca);
	$smarty->assign('oficina',$oficina);
	$smarty->display('../templates/_topframe.html');

?>
