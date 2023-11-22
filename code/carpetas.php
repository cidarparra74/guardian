<?php
//session_start();
//movimientos_carpetas
require_once("../lib/setup.php");
$smarty = new bd;	

require_once('../lib/verificar.php');
require_once("../lib/fechas.php");
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	$sql = "SELECT TOP 1 enable_ws, enable_ncaso, trasladar, enable_catofi, rutadoc FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	$rutadoc = $row["rutadoc"];
	$enable_catofi = $row["enable_catofi"];
//	$enable_ncaso = $row["enable_ncaso"];
	$smarty->assign('enable_ws',$enable_ws);
	$smarty->assign('trasladar',$row["trasladar"]);
	
	//href
	$carpeta_entrar="_main.php?action=carpetas.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "carpetas";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	//filtro de la ventana
	if(isset($_REQUEST["carpeta_propietario"])){
		//echo "entra desde la seleccion del propietario?";
		$_SESSION["carpeta_id"]= $_REQUEST["id_cp"];
		$solover = $_REQUEST["carpeta_propietario"];
		$filtro_id_carpeta= $_REQUEST["id_cp"];
		$filtro_carpeta= "AND c.id_propietario='$filtro_id_carpeta' ";	
	}else{
		$filtro_id_carpeta= $_SESSION["carpeta_id"];
		if(isset($_REQUEST["solover"])) 
			$solover = $_REQUEST["solover"]; 
		else 
			$solover = 'acc';
		$filtro_carpeta= "AND c.id_propietario='$filtro_id_carpeta' ";
	}
	
	//nombre del propietario y codigo mis
	$sql= "SELECT nombres, mis FROM propietarios WHERE id_propietario='$filtro_id_carpeta' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo_nombre= $resultado["nombres"];
	$titulo_mis= $resultado["mis"];
	
	$smarty->assign('titulo_nombre',$titulo_nombre);
	$smarty->assign('titulo_mis',$titulo_mis);

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	//descargar archivo
	if(isset($_REQUEST['descargar'])){
		include("./carpetas/descargar.php");
	}
	//eliminar archivo adjunto
	if(isset($_REQUEST['eliadjunto'])){
		include("./carpetas/eliadjunto.php");
		include("./carpetas/documentos.php");
	}
	
	//adicionar
	if(isset($_REQUEST['adicionar'])){
		include("./carpetas/adicionar.php");
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton_x'])){
		include("./carpetas/adicionando.php");
	}
	
	//trasladar
	if(isset($_REQUEST['trasladar'])){
		include("./carpetas/trasladar.php");
	}
	
	//trasladando
	if(isset($_REQUEST['trasladar_boton_x'])){
		include("./carpetas/trasladando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("./carpetas/modificar.php");
	}
	
	//modificando
	if(isset($_REQUEST['modificar_boton_x'])){
		include("./carpetas/modificando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modopera'])){
		include("./carpetas/modificar_operacion.php");
	}
	
	//modificando
	if(isset($_REQUEST['operacion_boton_x'])){
		include("./carpetas/modificando_operacion.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("./carpetas/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton_x'])){
		include("./carpetas/eliminando.php");
	}
	
	//prestar carpeta
	if(isset($_REQUEST["prestar_carpeta"])){
		include("./carpetas/prestar.php");
	}
	
	//prestando carpeta
	if(isset($_REQUEST['prestar_boton_x'])){
		include("./carpetas/prestando.php");
	}
	
	//modificar prestamo carpeta
	if(isset($_REQUEST["modificar_prestamo"])){
		include("./carpetas/modificar_prestamo.php");
	}
	
	//modificando prestamo carpeta
	if(isset($_REQUEST['modificar_prestamo_boton_x'])){
		include("./carpetas/modificando_prestamo.php");
	}
	
	//eliminar prestamo carpeta
	if(isset($_REQUEST["eliminar_prestamo"])){
		include("./carpetas/eliminar_prestamo.php");
	}
	
	//eliminando prestamo carpeta
	if(isset($_REQUEST['eliminar_prestamo_boton_x'])){
		include("./carpetas/eliminando_prestamo.php");
	}
	
	//impresion del reporte de la carpeta
	if(isset($_REQUEST['reporte_carpeta'])){
		include("./carpetas/reporte_carpeta.php");
	}
	
	//impresion del reporte de documentos de la carpeta
	if(isset($_REQUEST['reporte_docs'])){
		include("./carpetas/reporte_docs.php");
	}
	
	//entrar a documentos de la carpeta
	if(isset($_REQUEST['documentos'])){
		include("./carpetas/documentos.php");
	}
	//guardando docuemntos de la carpeta
	if(isset($_REQUEST['guardar_documentos'])){
		//echo "entraa guardar"; si entra
		include("./carpetas/guardando.php");
		
	}
	
	//devolver la carpeta al propietario
	if(isset($_REQUEST["devolver_cliente"])){
		include("./carpetas/devolver_propietario.php");
	}
	
	//devolviendo la carpeta al propietario
	if(isset($_REQUEST['boton_devolver_propietario_x'])){
		include("./carpetas/devolviendo_propietario.php");
	}
	
	//devolver docs de la carpeta al propietario
	if(isset($_REQUEST["devolver_documen"])){
		include("./carpetas/documentos_dev.php");
	}
	//devolviendo docs al propietario
	if(isset($_REQUEST['boton_devolver_docs_x'])){
		include("./carpetas/documentos_dev.php");
	}
	//imprimir devolviendo docs al propietario
	if(isset($_REQUEST['imprimir_dev'])){
		include("./carpetas/documentos_devimp.php");
	}
	
	///esto se ha movido a aprobar_nrocaso
/*	if(isset($_REQUEST['contabiliza'])){
		//echo "entraa guardar"; 
		$asiento = $_REQUEST['contabiliza'];
		include("./carpetas/contabilizar.php");
		
	}
	*/
	if(isset($_REQUEST['decontabiliza'])){
		//echo "entra a decontabilizar"; 
		include("./carpetas/decontabilizar.php");
		//
	}
	
	//informacion de la carpeta sobre movimientos
	if(isset($_REQUEST["info_carpeta"])){
		include("./carpetas/info_carpeta.php");
	}
	
	
	//para ver editar los documentos de recepcion
	if(isset($_REQUEST["verrecep"])){
			if(isset($_REQUEST['id'])){
				$id = $_REQUEST['id'];
			}
			//adicionar.php tambien permite modificar. Victor
			$esRecepcion = 0;
			$cat = 3; //para habilitar eliminacion de docs de recepcion	
			include("./ver_informe_legal/documentos1.php"); 
	}
	
	//para imprimir documentos de recepcion
	if(isset($_REQUEST["imprimir_recepcion"])){
		include("ver_informe_legal/imprimir_recepcion.php"); 
	}
	
	//quitando un documento ya recepcionado
	if(isset($_REQUEST["quitar_doc"])){
		$esRecepcion = 0; //para saber si adicionando.php es llamado desde recepcion.php
		$cat = 3; //para habilitar eliminacion de docs de recepcion	
		include("./ver_informe_legal/documentos1.php");  
		
	}
	
	//Guardar Informe con sus documentos
	if(isset($_REQUEST["guardar_doc_infor"])){
		$cat = 0;
		include("./ver_informe_legal/guardar_infordocu.php");
	}
	
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana
//filtro de propietarios
$sql= "SELECT c.id_carpeta, c.creacion_carpeta, c.carpeta, p.nombres AS p_nombres, 
p.mis AS p_mis, o.nombre AS o_nombre, tc.tipo_bien AS tipo_carpeta, tc.id_tipo_bien, 
o.id_almacen, c.operacion, o.id_oficina, c.cuenta, tc.cuenta as ctabien,
tc.con_inf_legal as coninf, tc.con_recepcion as conrec, fecha_devolucion as fdev, c.id_informe_legal, c.nrocaso
FROM carpetas c, propietarios p, oficinas o, tipos_bien tc 
WHERE c.id_oficina=o.id_oficina AND c.id_propietario=p.id_propietario 
AND c.id_tipo_carpeta=tc.id_tipo_bien $filtro_carpeta ORDER BY c.operacion ";
// echo $sql;
$query = consulta($sql);

$ids_carpeta= array();
$o_nombre= array();
$tipo_carpeta= array();
$puede_prestar=array();
$los_estados=array();
$nombres_us=array();
$fecha_autoriza_archivo=array();
$puede_modificar=array();

$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$aux_c= explode(" ",$row["creacion_carpeta"]);
	//CAMBIAR LAS FECHA
	$meslit = dateDMESY(dateDMY($aux_c[0]));
	if($row["id_informe_legal"]!='') $idlegal = $row["id_informe_legal"];
	else $idlegal = '0';
	//vemos si esta habilitado catastro por oficina, no por recinto
	if($enable_catofi=='S')
		$idalmace = $row["id_oficina"];
	else		
		$idalmace = $row["id_almacen"];
		
	$ids_carpeta[$i]= array('id' => $row["id_carpeta"],
							'id_tc' => $row["id_tipo_bien"],
							'fecha' => $meslit,
							'carpeta' => $row["carpeta"],
							'operacion' => $row["operacion"],
							'cuenta' => trim($row["cuenta"]),
							'coninf' => $row["coninf"],
							'ctabien' => trim($row["ctabien"]),
							'p_nombres' => $row["p_nombres"],
							'o_nombre' => $row["o_nombre"],
							'id_oficina' => $row["id_oficina"],
							'tipo_carpeta' => $row["tipo_carpeta"],
							'id_almacen' => $idalmace,
							'id_ilegal' => $idlegal ,
							'nrocaso' => $row["nrocaso"],
							'fdev' => $row["fdev"]);
	//fecha de creacion de la carpeta
	
	//para ver si se puede prestar la carpeta
	//(m.id_estado='8' AND m.flujo='1') --> CARPETA DEVUELTA AL CLIENTE
	// m.flujo=0 --> CARPETA EN MOV
	//m.flujo=1 -->FLUJO CERRADO
	$aux= $row["id_carpeta"];
	$sql_a= "SELECT m.id_carpeta, m.auto_arch, m.id_estado, m.flujo, m.id_us_archivo, u.nombres 
	FROM movimientos_carpetas m LEFT JOIN usuarios u ON m.id_us_corriente=u.id_usuario
	WHERE ((m.id_estado='8' AND m.flujo='1') OR m.flujo=0) AND m.id_carpeta='$aux'  ";
	$result_a= consulta($sql_a);
	$row_a= $result_a->fetchRow(DB_FETCHMODE_ASSOC);
	
	if($row["coninf"]=='N' && $row["conrec"]=='N' ){  // //quitamos esta condicion para que salgan los docs adicionlaes y cancelados para bsol
		$puede_prestar[$i]= "xx";
		$puede_modificar[$i]= "no";
		$los_estados[$i]=$row_a["id_estado"]; //'0';
	}else{
		//para ver si el de archivo puede modificar el prestamo, que empezo desde el
		$fecha_autoriza_archivo[$i]= $row_a["auto_arch"];
		$id_us_actual = $_SESSION['idusuario'];
		if($row_a == null){
			if($enable_ws=='S'){ //BCO SOL
				$puede_prestar[$i]="xx";
			}else{
				$puede_prestar[$i]="si";
			}
			$los_estados[$i]='0';
			$nombres_us[$i]="";
		}else{
			if($row_a["id_us_archivo"] == $id_us_actual){
				$puede_modificar[$i]= "si";
			}else{
				$puede_modificar[$i]= "no";
			}
			$puede_prestar[$i]="no";
			$los_estados[$i]=$row_a["id_estado"];
			$nombres_us[$i]= $row_a["nombres"];
		}
	}
	$i++;
}

	$smarty->assign('ids_carpeta',$ids_carpeta);

	if($enable_catofi=='S')
		$smarty->assign('id_almacen',$_SESSION["id_oficina"]);
	else
		$smarty->assign('id_almacen',$_SESSION["id_almacen"]);
		
	$smarty->assign('puede_prestar',$puede_prestar);
	$smarty->assign('puede_modificar',$puede_modificar);
	$smarty->assign('los_estados',$los_estados);
	$smarty->assign('idprop',$filtro_id_carpeta);
	$smarty->assign('nombres_us',$nombres_us);
	$smarty->assign('fecha_autoriza_archivo',$fecha_autoriza_archivo);
	if($solover=='vrb')
		$smarty->display('carpetasver.html');
	else
		$smarty->display('carpetas.html');
	die();

?>
