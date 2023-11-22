<?php
	session_start();
	
	require_once('../lib/conexionMNU.php');
	
	// Recuperamos el grupo de opciones para elmeu
	if(isset($_REQUEST['grupoMnu'])){
		$grupoMnu = $_REQUEST['grupoMnu'];
	}else{
		$grupoMnu = '1';
	}
	if(!isset($_SESSION["idperfil"])){
		//error en variable de sesion! o sesion expiro
		//require_once('../code/restringido.php');
		?> 
		<div align='center'>La sesi&oacute;n expir&oacute;</div><br>
		<div align='center'><a href="../index.html">Iniciar Sesi&oacute;n</a></div>
		<?php
		die();
	}
	//capturamos el perfil
	$idperfil = $_SESSION["idperfil"] ;
	//leemos la tabla para la barra de menu segun los permisos del usuario
	$sql = "SELECT bm.* 
			FROM permiso pe 
			INNER JOIN barramenu bm ON pe.codigo = bm.codigo 
			WHERE left(bm.codigo,1) = $grupoMnu AND bm.activo = 'S' 
			AND pe.idperfil = $idperfil ORDER BY bm.codigo ";

	//$sql = "SELECT * FROM barramenu WHERE left(codigo,1) = $grupoMnu ORDER BY codigo";
	$query = consulta($sql);
	$menuOption = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if($row['nivel']>=2){
			$menuOption[] = array( 'nivel'   => $row['nivel'],
								   'descri'  => $row['descripcion'],
								   'imagen'  => $row['imagen'],
								   'comando' => $row['comando']);
		}
	}
	require_once("../lib/setup.php");
	
	$smarty = new bd;
	
	$smarty->assign('menuOption',$menuOption);
	
	$smarty->display('../templates/_leftframe.html');
	die();

?>