<?php
	session_start();
	// cargarmos funciones propias
	require_once('../lib/conexionMNU.php');
	require_once('../lib/codificar.php');
	require_once('../lib/verificar.php');
	
	$status = '';
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
			//verificamos q no exista el login
			$login = encode($_REQUEST["USlogin"]);
			$sql = "SELECT id_usuario FROM usuarios WHERE login = '$login'";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($row['id_usuario'] == ''){
				// realizamos INSERT
				$sql = "INSERT INTO usuarios (nombres, login, password, activo, id_perfil) ".
				"values ('".encode($_REQUEST["nombre"])."','$login','". 
				crypt($_REQUEST["password"],'vic')."','".$_REQUEST["activo"]."','".$_REQUEST["idperfil"]."')";
				ejecutar($sql);
				$status = 'Usuario Creado';
				//vemos si continuamos adicionando o volvemos a la lista
				if(isset($_REQUEST["inserta1"])){
					// continuamos añadiendo registros
					require_once("../code/admusuarioABM.php");
					die();
				}
			}else{
				//el login ya existe
				$status = 'Login de Usuario ya existe!';
				require_once("../code/admusuarioABM.php");
				die();
			}
		}elseif(isset($_REQUEST['edita'])){
			//------------------------------
			// realizamos UPDATE
			if(isset($_REQUEST["PWactual"])){
			///Actualizamos Password
				$sql = "UPDATE usuarios SET password = '"
				. crypt($_REQUEST["passmod"],'vic')."' WHERE id_usuario = $id ";
				ejecutar($sql);
			}else{
				$login = encode($_REQUEST["USlogin"]);
				$sql = "SELECT id_usuario FROM usuarios WHERE login = '$login' AND id_usuario <> $id";
				$query = consulta($sql);
				$row = $query->fetchRow(DB_FETCHMODE_ASSOC);

				if($row['id_usuario'] == ''){
					///Actualizamos datos
					$sql = "UPDATE usuarios SET nombres='".encode($_REQUEST["nombre"])."', login='$login'". 
					", activo = '".$_REQUEST["activo"].
					"', id_perfil = '".$_REQUEST["idperfil"].
					"' WHERE id_usuario = $id ";
				ejecutar($sql);
				}else{
					//el login ya existe
					$status = 'Login de Usuario ya existe!';
					require_once("../code/admusuarioABM.php");
					die();
				}
			}
			
		}elseif(isset($_REQUEST['elimina'])){
			//------------------------------
			// realizamos UPDATE a cambio de DELETE ya que no se borran usuarios
			$sql = "UPDATE usuarios SET activo = 'N' WHERE id_usuario = $id  ";
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
	if (!$_REQUEST["btnFiltro"]){
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
	$criterio[] = array('campo' => 'nombres',   'descri' => 'Nombre de Usuario');
	//$criterio[] = array('campo' => 'idperfil', 'descri' => 'Perfil');
	$smarty->assign('Fnrocampos', count($criterio));
	$smarty->assign('Fcriterio',$criterio);
	//-----------fin definicion filtro
	
	// aca poner el nombre de este archivo!!!!
	$modulopaginador = "admusuario.php";
	$smarty->assign('modulopaginador',$modulopaginador);
	
	//ACA VA LA CONSULTA MAESTRA PARA EL LISTADO DE LA PANTALLA
	//leemos la tabla con todos los datos requeridos, siempre en $sql para paginacion
	$sql = "SELECT us.id_usuario, us.nombres, us.login, pe.perfil, us.id_perfil, us.activo
			FROM usuarios us LEFT JOIN perfiles pe ON us.id_perfil = pe.id_perfil 
			WHERE us.activo != '-' ".$filtro." ORDER BY us.nombres";
	$query = consulta($sql);  //siempre asignar la consulta a $query, para paginacion
	
	//ACA VA LAS FUNCIONES DE PAGINACION, SIEMPRE DESPUES DE LA CONSULTA MAESTRA
	// configuracion Smarty tambien ya debe estar hecha
	require_once("../lib/paginacion.php");
	//------------- fin paginacion
		
	//vaciamos los registros PRINCIPALES a una matriz para el template
	$usuarios = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$usuarios[] = array('idusr'    => $row['id_usuario'],
							'nombre'   => decode($row['nombres']),
							'login'    => decode($row['login']),
							'perfil'   => $row['perfil'],
							'activo'   => $row['activo']);
	}
	$smarty->assign('usuarios',$usuarios);
	
		
	//------------
	$smarty->display('../templates/admusuario.html');

?>
