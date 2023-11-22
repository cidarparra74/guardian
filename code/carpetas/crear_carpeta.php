<?php
	//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	
	$usuario= $_SESSION["idusuario"];
	$id_informe_legal = $_REQUEST['id'];
	$tipo_carpeta = $_REQUEST['tipo_carpeta'];
	$carpeta = $_REQUEST['carpeta'];
	$nrobien = $_REQUEST['nrobien'];
	$id_oficina = $_REQUEST['oficina'];
	$id_propietario = $_REQUEST['id_propietarix'];
	$nrocaso = $_REQUEST['nrocaso'];
	$operacion = '';
	// 25/09/2012 el nro de operacion se lo asigna a momento de contabilizar
	// 26/09/2012 solicitaron que se asigne el nro y se puso uno opcion para actualizarlo
	
	if($enable_ws == 'S'){ //es bsol
		//if($nrocaso!='')
			//require("ws_desembolso_bsol.php");
		
	}
	
//fecha actual
$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

//en algun caso el propietario no se creo correctamente, verificamos esto
$sql= "SELECT id_propietario FROM propietarios WHERE id_propietario = '$id_propietario' ";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
if($id_propietario != $row["id_propietario"]){
	//error no existe el propietario
	if($enable_ws == 'S'){ //es bsol
		//echo "error"; die();
				require_once('ws_nrocaso_bsol.php');
				$Pais 	 = $paisDoc;
				$TipoDoc = $tipoDoc;
				$ci_cliente=$documento; //para el ws siguiente:
				require_once('ws_cliente.php');
				//-----------------------------------
				// lo siguiente se repite mas abajo en la busqueda x cuenta :
				if(trim($nombres) != ''){
					//existe, lo insertamos directamente en tabla propietarios
					$fecha_actual= date("Y-m-d H:i:s");
					$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
					/*$sql= "INSERT INTO propietarios (nombres, ci, direccion, 
						telefonos, creacion_propietario, estado_civil, nit, emision, mis) 
						VALUES('$nombres', '$ci_cliente', '$direccion', 
						'$telefonos', $fecha_actual, '$ecivil', '$nrocaso', '$emision', '$ci_cliente') "; */
					//$ci_cliente ya tiene emision
					//$emision tiene la emision
					$ci_cliente = str_replace($emision, "", $ci_cliente);
					$sql= "INSERT INTO propietarios (nombres, ci, direccion, 
								telefonos, creacion_propietario, estado_civil, nit, emision, mis,
								personanatural, profesion, nacionalidad, pais) 
								VALUES('$nombres', '$ci_cliente', '$direccion', 
								'$telefonos', $fecha_actual, '$ecivil', '$nrocaso', '$emision', '$ci_cliente',
								'1', '$profesion', '$nacionalidad', '1') ";
					ejecutar($sql);
					//pero necesitamos el idpropietario!!
					//para asegurarnos de obtener el id correcto temporalmente ponemos
					//en el nit el nro de instancia y luego consultamos:
					$sql = "SELECT MAX(id_propietario) as idp 
						FROM propietarios WHERE ci='$ci_cliente' AND nit = '$nrocaso'";
					$query = consulta($sql);
					$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
					$id_propietario = $row["idp"];
					//ponemos en vacio el nit para dejar como estaba
					$sql = "UPDATE propietarios SET nit = '' WHERE id_propietario = '$id_propietario' ";
					ejecutar($sql);
					$sql= "UPDATE informes_legales SET id_propietario = '$id_propietario' 
					WHERE id_informe_legal = '$id_informe_legal' ";
					ejecutar($sql);
				}else{
					//no existe
					die('No existe el registro en propietarios. (WS)');
				}
		
	}elseif($enable_ws == 'A'){ //es baneco
		
	}
}



//insertamps en carpetas
$sql= "INSERT INTO carpetas (id_oficina, id_propietario, id_usuario, 
	carpeta, id_tipo_carpeta, creacion_carpeta, operacion, id_informe_legal, nrocaso) 
	VALUES('$id_oficina', '$id_propietario', '$usuario', 
	'$nrobien', '$tipo_carpeta', $fecha_actual, '$operacion', '$id_informe_legal', '$nrocaso') ";
	//echo $sql; die();
ejecutar($sql);
//echo $sql;
// jalamos nro de carpeta generado
$sql="SELECT MAX(id_carpeta) as idc FROM carpetas WHERE id_informe_legal = '$id_informe_legal' ";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
$id_carpeta = $row["idc"];


//jalamos los documentos
$sql="SELECT din_doc_id, din_tip_doc, fechareg, fojas, obs 
FROM documentos_informe WHERE din_inf_id = '$id_informe_legal'";
$query = consulta($sql);

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$id = $row["din_doc_id"];
	$id_tipo = $row["din_tip_doc"];
	$obs = $row["obs"];
	$fojas = $row["fojas"];
	// la fecha del doc no se tiene seria mejor dejarla en blanco
	//$fecha = $row["fechareg"];
	//$sql="INSERT INTO documentos_propietarios (id_carpeta, id_documento, id_tipo_documento, observacion, fecha_documento, numero_hojas) VALUES ";
	//$sql .= "($id_carpeta, $id, $id_tipo, '$obs', CONVERT(DATETIME,'$fecha',102), '$fojas')";
	$sql="INSERT INTO documentos_propietarios (id_carpeta, id_documento, id_tipo_documento, observacion, numero_hojas) VALUES ";
	$sql .= "($id_carpeta, $id, $id_tipo, '$obs', '$fojas')";
	
	ejecutar($sql);
	
}
// indicamos q el il tiene carpeta
$sql= "UPDATE informes_legales SET sincarpeta = 'N' WHERE id_informe_legal = '$id_informe_legal' ";
ejecutar($sql);

?>