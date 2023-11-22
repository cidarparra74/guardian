<?php
	session_start();
	// cargarmos funciones propias
	require_once('../lib/conexionMNU.php');
	require_once('../lib/codificar.php');
	require_once('../lib/verificar.php');
	//print_r($_REQUEST);
	
	//-----------------------------------------------------
	//     AQUI SE PROCESA EL ABM
	//-----------------------------------------------------
	if(isset($_REQUEST["ejecutaABM"])){
	// vemos que accion realizar luego del mostrado/llenado del formulario
		//Vemos si hay un ID de registro para Edicion/Eliminacion
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
		}else{
			$id = 0;
		}
		if( isset($_REQUEST['inserta2'])){
			//------------------------------
			// realizamos INSERT
			$sql = "INSERT INTO barramenu (codigo, nivel, descripcion, imagen, ".
					"comando, verimagen, vertexto, activo) VALUES ('".
					$_REQUEST["codigo"]."','".
					$_REQUEST["nivel"]."','".
					$_REQUEST["descripcion"]."','".
					$_REQUEST["imagen"]."','".
					$_REQUEST["comando"]."','".
					$_REQUEST["verimagen"]."','".
					$_REQUEST["vertexto"]."','".
					$_REQUEST["activo"]."')";
			ejecutar($sql);
			//echo $sql;
		}elseif(isset($_REQUEST['edita'])){
			//------------------------------
			// realizamos UPDATE
			$sql = "UPDATE barramenu SET descripcion='".$_REQUEST['descripcion'].
					"', imagen='".$_REQUEST['imagen'].
					"', comando='".$_REQUEST['comando'].
					"', verimagen='".$_REQUEST['verimagen'].
					"', vertexto='".$_REQUEST['vertexto'].
					"', activo='".$_REQUEST['activo'].
					"' WHERE codigo = '$id' ";
			ejecutar($sql);
		}elseif(isset($_REQUEST['elimina'])){			
			//------------------------------
			// eliminamos los permisos
			$sql = "DELETE FROM permiso WHERE codigo = '$id'";
			ejecutar($sql);
			
			// eliminamos el menu
			$sql = "DELETE FROM barramenu WHERE codigo = '$id'";
			ejecutar($sql);
		}
	}
	//-----------------------------------------------------
	//-----------------------------------------------------
	//-----------------------------------------------------
	//          DESDE AQUI SE PROCESA LA LISTA
	//-----------------------------------------------------
	//-----------------------------------------------------
	//-----------------------------------------------------
	require_once("../lib/setup.php");
	$smarty = new bd;
	
	if(isset($_REQUEST['moduloSel'])){
		$moduloSel = substr($_REQUEST['moduloSel'],0,4);
	}else{ $moduloSel='1000'; }
	
	//ACA VAN LOS FILTROS
	if (!isset($_REQUEST["btnFiltro"])){
		$filtro = "";
	}else{
		//Recuperamos el dato a buscar y el campo donde buscar
		$fDatoFiltro = $_REQUEST["fDatoFiltro"];
		$fCampoFiltro = $_REQUEST["fCampoFiltro"];
		$filtro = " AND $fCampoFiltro like '%$fDatoFiltro%' ";
	}
	//definimos opciones del Filtro
	$criterio = array(); // llenamos esta matriz con el nombre del campo en la tabla 
							// y su descripcion en la pantalla
	//$criterio[] = array('campo' => 'nombre',   'descri' => 'Perfil');
	//$criterio[] = array('campo' => 'idperfil', 'descri' => 'Id');
	$smarty->assign('Fnrocampos', count($criterio));
	$smarty->assign('Fcriterio',$criterio);
	//-----------fin definicion filtro
	
	// aca poner el nombre de este archivo!!!!
	//$modulopaginador = "admmenu.php";
	//$smarty->assign('modulopaginador',$modulopaginador);

	// Lista demodulos existentes y disponibles
	$sql =  "SELECT descripcion, imagen, codigo FROM barramenu WHERE nivel = 1";
	$query = consulta($sql);
	$Modulos = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$Modulos[] =  array('codigo'	=> $row['codigo'],
							'nombre'	=> $row['descripcion'],
							'imagen'	=> $row['imagen']);
	}
	
	
	//ACA VA LA CONSULTA MAESTRA PARA EL LISTADO DE LA PANTALLA
	//leemos la tabla con todos los datos requeridos, siempre en $sql para paginacion
	//y siempre debe tener la clausula WHERE para los filtros
	$sql = "SELECT *
			FROM barramenu 
			WHERE codigo > $moduloSel AND codigo < ($moduloSel + 1000) ORDER BY codigo ";
	$query = consulta($sql);  //siempre asignar la consulta a $query, para paginacion
		
	//ACA VA LAS FUNCIONES DE PAGINACION, SIEMPRE DESPUES DE LA CONSULTA MAESTRA
	// configuracion Smarty tambien ya debe estar hecha
	//require_once("../lib/paginacion.php");
	//------------- fin paginacion
		
	//vaciamos los registros ya PAGINADOS/FILTRADOS a una matriz para el template
	$tablaDatos = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$tablaDatos[] = array('codigo'  => $row['codigo'],
								'nivel' => $row['nivel'],
								'descripcion' => $row['descripcion'],
								'imagen' => $row['imagen'],
								'comando' => $row['comando'],
								'verimagen' => $row['verimagen'],
								'vertexto' => $row['vertexto'],
								'activo' => $row['activo']);
	}
	$smarty->assign('tablaDatos',$tablaDatos);
	
//	$smarty->assign('id',		$id);
	$smarty->assign('Modulos'	,$Modulos);
	$smarty->assign('moduloSel',$moduloSel);
		
	$smarty->display('../templates/admmenu.html');

?>
