<?php
	session_start();
	// cargarmos funciones propias
	require_once('../lib/conexionMNU.php');
	require_once('../lib/verificar.php');
	
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
		if(isset($_REQUEST['inserta1']) or isset($_REQUEST['inserta2'])){
			//------------------------------
			// realizamos INSERT
			$activo = $_REQUEST["activo"];
			$nombre = $_REQUEST["nombre"];
			$sql = "INSERT INTO perfiles (perfil, activo) VALUES ('$nombre', '$activo' )";
			ejecutar($sql);
			//vemos si continuamos adicionando o volvemos a la lista
			if(isset($_REQUEST["inserta1"])){
				// continuamos añadiendo registros
				require_once("../code/admperfilABM.php");
				die();
				
			}
		}elseif(isset($_REQUEST['edita'])){
			//------------------------------
			// realizamos UPDATE
			$activo = $_REQUEST["activo"];
			$nombre = $_REQUEST["nombre"];
			$sql = "UPDATE perfiles SET perfil='$nombre', activo='$activo' WHERE id_perfil = $id ";
			ejecutar($sql);
		}elseif(isset($_REQUEST['elimina'])){
			//------------------------------
			// realizamos DELETE
			$sql = "DELETE FROM perfiles WHERE id_perfil = $id ";
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
	$criterio[] = array('campo' => 'perfil',   'descri' => 'Nombre de Perfil');
	//$criterio[] = array('campo' => 'idperfil', 'descri' => 'Id');
	$smarty->assign('Fnrocampos', count($criterio));
	$smarty->assign('Fcriterio',$criterio);
	//-----------fin definicion filtro
	
	// aca poner el nombre de este archivo!!!!
	$modulopaginador = "admperfil.php";
	$smarty->assign('modulopaginador',$modulopaginador);
	
	//ACA VA LA CONSULTA MAESTRA PARA EL LISTADO DE LA PANTALLA
	//leemos la tabla con todos los datos requeridos, siempre en $sql para paginacion
	//y siempre debe tener la clausula WHERE para los filtros
	$sql = "SELECT *
			FROM perfiles 
			WHERE activo != '-' ".$filtro." ORDER BY perfil ";
	$query = consulta($sql);  //siempre asignar la consulta a $query, para paginacion
		
	//ACA VA LAS FUNCIONES DE PAGINACION, SIEMPRE DESPUES DE LA CONSULTA MAESTRA
	// configuracion Smarty tambien ya debe estar hecha
//	require_once("../lib/paginacion.php");
	//------------- fin paginacion
		
	//vaciamos los registros ya PAGINADOS/FILTRADOS a una matriz para el template
	$perfiles = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$perfiles[] = array('idperfil'  => $row['id_perfil'],
								'nombre' => $row['perfil'],
								'activo' => $row['activo']);
	}
	$smarty->assign('perfiles',$perfiles);
		
		
	$smarty->display('../templates/admperfil.html');

?>
