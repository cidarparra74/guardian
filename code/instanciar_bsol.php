<?php
//  esto es para bsol, para asignar manualmente el nro de instancia y operacion


require_once("../lib/setup.php");
$smarty = new bd;	

require_once('../lib/verificar.php');
require_once("../lib/fechas.php");
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	
	//href
	$carpeta_entrar="_main.php?action=instanciar_bsol.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "instanciar_bsol";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//filtro de la ventana
	if(!isset($_REQUEST['filtro'])){
		$del_filtro= "nada";
		$_SESSION["arch_prop_filtro_nombres"]= "";
		$_SESSION["arch_prop_filtro_ci"]= "";
		
		$_SESSION["arch_filtro"]= "";
	}
	else{
		//$del_filtro=$filtro;
		$del_filtro= $_SESSION["arch_filtro"];
	}
	
	if(isset($_REQUEST['buscar_boton'])){
		$del_filtro="";
		$band=0;
		
		//nombres
		$aux=$_REQUEST['filtro_nombres'];
		if($aux != ""){
			if($band == 0){
				$del_filtro= " p.nombres LIKE '%$aux%' ";
				$band=1;
			}
		}//fin de nombres
		
		
		//ci
		$aux=$_REQUEST['filtro_ci'];
		if($aux != ""){
			if($band == 0){
				$del_filtro= " p.ci LIKE '%$aux%' ";
				$band=1;
			}
			else{
				$del_filtro= $del_filtro."AND p.ci LIKE '%$aux%' ";
			}
		}//fin de ci
		
		
		$filtro_nombres= $_REQUEST['filtro_nombres'];
		$filtro_ci= $_REQUEST['filtro_ci'];
		
		//variables de sesion
		$_SESSION["arch_prop_filtro_nombres"]= $filtro_nombres;
		$_SESSION["arch_prop_filtro_ci"]= $filtro_ci;
	}//fin del if de buscar_boton
	else{
		$filtro_nombres= $_SESSION["arch_prop_filtro_nombres"];
		$filtro_ci= $_SESSION["arch_prop_filtro_ci"];
	}
	
	//filtro de la ventana
	$_SESSION["arch_filtro"]= $del_filtro;
	$smarty->assign('filtro',$del_filtro);
	//valores del filtro
	
	$smarty->assign('filtro_nombres',$filtro_nombres);
	$smarty->assign('filtro_ci',$filtro_ci);
	

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/

	//guardar datos de la carpeta
	if(isset($_REQUEST['guardardatos'])){
		
		$id= $_REQUEST["id"];
		$nrocaso= $_REQUEST["nrocaso"];
		$cuenta= $_REQUEST["cuenta"];
		$operacion= $_REQUEST["operacion"];
		$suboperacion= $_REQUEST["suboperacion"];
		
		$sql= "UPDATE carpetas SET nrocaso='$nrocaso',operacion='$operacion', suboperacion='$suboperacion' WHERE id_carpeta='$id' ";
		ejecutar($sql);
		// cuenta='$cuenta', 
		//echo $sql;
	}
	
	//ver carpetas
	$ids_carpeta= array();
	if(isset($_REQUEST['vercarpetas'])){
		$id_prop = $_REQUEST['vercarpetas'];
			//nombre del propietario y codigo mis
		$sql= "SELECT nombres, ci FROM propietarios WHERE id_propietario='$id_prop' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$titulo_nombre= $resultado["nombres"];
		$titulo_mis= $resultado["ci"];
		$smarty->assign('titulo_nombre',$titulo_nombre);
		$smarty->assign('titulo_mis',$titulo_mis);
				
		$sql= "SELECT c.id_carpeta, c.creacion_carpeta, c.carpeta, o.nombre AS o_nombre, tc.tipo_bien AS tipo_carpeta, tc.id_tipo_bien, 
		o.id_almacen, c.operacion, o.id_oficina, c.cuenta, tc.cuenta as ctabien,
		tc.con_inf_legal as coninf, tc.con_recepcion as conrec, fecha_devolucion as fdev, c.id_informe_legal, c.nrocaso
		FROM carpetas c, oficinas o, tipos_bien tc 
		WHERE c.id_oficina=o.id_oficina 
		AND c.id_tipo_carpeta=tc.id_tipo_bien AND c.id_propietario='$id_prop' ORDER BY c.operacion ";
		//echo $sql;
		$query = consulta($sql);

		$i=0;
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$aux_c= explode(" ",$row["creacion_carpeta"]);
			//CAMBIAR LAS FECHA
			$meslit = dateDMESY(dateDMY($aux_c[0]));

			if($row["id_informe_legal"]!='') $idlegal = $row["id_informe_legal"];
			else $idlegal = '0';
			
			$ids_carpeta[$i]= array('id' => $row["id_carpeta"],
									'id_tc' => $row["id_tipo_bien"],
									'fecha' => $meslit,
									'carpeta' => $row["carpeta"],
									'operacion' => $row["operacion"],
									'cuenta' => trim($row["cuenta"]),
									'o_nombre' => $row["o_nombre"],
									'tipo_carpeta' => $row["tipo_carpeta"],
									'id_ilegal' => $idlegal ,
									'nrocaso' => $row["nrocaso"],
									'fdev' => $row["fdev"]);
			
			$i++;
		}

		
	}
	$smarty->assign('ids_carpeta',$ids_carpeta);
	
	
	//modificar datos de la carpeta
	if(isset($_REQUEST['info_carpeta'])){
		$id= $_REQUEST["id"];
		$sql =  "SELECT ca.nrocaso, ca.cuenta, ca.operacion, ca.suboperacion, cl.nombres, cl.ci, tbi.tipo_bien, cl.id_propietario ".
				"FROM carpetas ca ".
				"LEFT JOIN propietarios cl ON cl.id_propietario = ca.id_propietario ".
				"LEFT JOIN tipos_bien tbi ON tbi.id_tipo_bien = ca.id_tipo_carpeta ".
				"WHERE ca.id_carpeta = $id ";
		//		echo $sql;
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$smarty->assign('id',$id);
		$smarty->assign('id_p',$resultado["id_propietario"]);
		$smarty->assign('cliente',$resultado["nombres"]);
		$smarty->assign('ci',$resultado["ci"]);
		$smarty->assign('tipo_bien',$resultado["tipo_bien"]);
		$smarty->assign('nrocaso',$resultado["nrocaso"]);
		$smarty->assign('cuenta',$resultado["cuenta"]);
		$smarty->assign('operacion',$resultado["operacion"]);
		$smarty->assign('suboperacion',$resultado["suboperacion"]);
		
		$smarty->assign('veroperacion','1');
	
	}
	
/****************fin de valores para la ventana*************************/
/****************fin de valores para la ventana*************************/


/**********************valores por defecto*************************/
/**********************valores por defecto*************************/

//recuperando los datos para la ventana
$ids_propietario= array();

if($del_filtro != "nada"){

	$sql= "SELECT TOP 15 p.id_propietario, p.nombres, p.mis, p.ci, 
	p.direccion, p.telefonos, COUNT(c.id_carpeta) AS cantidad 
	FROM propietarios p LEFT JOIN carpetas c 
	ON c.id_propietario=p.id_propietario 
	WHERE  $del_filtro 
	GROUP BY p.id_propietario, p.nombres, p.mis, p.ci, p.direccion, p.telefonos 
	ORDER BY p.nombres ";

	$query= consulta($sql);

	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_propietario[] = array( 'ids' => $row["id_propietario"],
									'nombres' => $row["nombres"],
									'mis' => $row["mis"],
									'ci' => $row["ci"],
									'direccion' => $row["direccion"],
									'telefonos' => $row["telefonos"],
									'tiene_carpeta' => $row["cantidad"]);
	}
}
	$smarty->assign('ids_propietario',$ids_propietario);

	$smarty->display('instanciar_bsol.html');
	die();

?>
	
