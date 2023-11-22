<?php

$id_us_actual = $_SESSION["idusuario"];

$cliente= $_REQUEST["cliente"];
$numero_documento= $_REQUEST["ci_cliente"];
$tipo_bien= $_REQUEST["tipo_bien"];
if(isset($_REQUEST["motivo"]))
	$motivo= $_REQUEST["motivo"];
//para bsol se jala de un combo:
if(isset($_REQUEST["motivo_id"])){
	$motivo= '';
	$id_objeto= $_REQUEST["motivo_id"];
	if($id_objeto!='--'){
		$sql= "SELECT * FROM objetos WHERE id_objeto='$id_objeto' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$motivo=$resultado["objeto"];
	}
}
$id_propietario= $_REQUEST["id_propietario"];
$nrobien= $_REQUEST["nrobien"];
$recepcionadox='';
if(isset($_REQUEST["recepcionadox"]))
$recepcionadox= $_REQUEST["recepcionadox"];
 
	$nrocaso= $_REQUEST["nrocaso"];
	$noportunidad= $_REQUEST["noportunidad"];  //para bsol

	
	
	$estado = "rec";

$fecha= date("Y-m-d H:i");
$fecha="CONVERT(DATETIME,'$fecha',102)";
$id_oficina = $_SESSION["id_oficina"];
//recuperamos el siguiente numero de informe
	$sql= "SELECT MAX(id_informe_legal) AS maximo FROM informes_legales ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$numero_informe=bcadd($resultado["maximo"],1,0);

	//para caso bisa, manejamos el nro de i.l. como nro de caso para ligar a contratos
	if($nrocaso=='' and $noportunidad==0) $nrocaso = $numero_informe;

//  usamos nrocaso como cuenta en bsol
//insertando

$sql= "INSERT INTO informes_legales(id_informe_legal, id_us_comun, id_tipo_bien, cliente, id_propietario,
					ci_cliente, fecha, puede_operar, numero_informe, habilitar_informe, 
					estado, motivo, montoprestamo, fecha_recepcion, nrobien, nrocaso,  sincarpeta, id_oficina, noportunidad, inf_agencia) 
					VALUES('$numero_informe', '$id_us_actual', '$tipo_bien', '$cliente', '$id_propietario',
					'$numero_documento', $fecha, '1', '$numero_informe', '0',  
					'$estado', '$motivo','', $fecha, '$nrobien','$nrocaso',  '?', $id_oficina, '$noportunidad', '$recepcionadox') ";
					
// lo siguiente para que pase a documentos.php
$id = $numero_informe;
ejecutar($sql);

// asignamos perito 
//// para guardar el perito usamos el ID_perito en INFORMES_LEGALES 
if($esRecepcion == 1){

	//tenemos el tipo de garantia en $tipo_bien
	//tenemos la oficina en $_SESSION["id_oficina"]
	//$id_oficina = $_SESSION["id_oficina"];
	$id_almacen = $_SESSION["id_almacen"];
	//buscamos peritos que sepan de la garantia y sean de la oficina
	$sql = "SELECT pe.id_persona 
	FROM personas pe 
	INNER JOIN tipobien_persona tp 
		ON tp.id_persona=pe.id_persona AND tp.id_tipo_bien = $tipo_bien
		AND pe.id_oficina = '$id_almacen'"; //id_oficina el tabla personas ahora es = id_almacen
	
	//INNER JOIN oficina_persona op ON op.id_responsable=pe.id_persona AND op.id_oficina = $id_oficina 
	$query = consulta($sql);
	$peritos = array();
	$i = 0;
	$peritos[0]=  0;
	$listaPeritos = '';
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$peritos[$i]= $row["id_persona"];
		$listaPeritos .= $row["id_persona"].',';
		$i++;
	}
	//hacemos que el ultimo elemento sea siempre igual al primero
	$peritos[$i]=$peritos[0]; //esto hacemos para que el que le siga al ultimo perito sea el primero 
	$listaPeritos .= '-1';
	//vemos cual de los existentes ha sido el ultimo asignado
	//solo si existen peritos
	if($i>0){
		//buscamos el perito asignado recientemente de entre los seleccionados
		$sql="SELECT id_persona as id_perito, COUNT(*) AS carga 
		FROM personas LEFT JOIN informes_legales
			ON id_perito = id_persona 			
		WHERE id_persona IN ($listaPeritos) GROUP BY id_persona ORDER BY COUNT(*)";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		//vemos si hay alguno
		if($row["id_perito"]!=NULL && $row["id_perito"]!=''){
			$id_persona = $row["id_perito"];
			//tenemos el siguiente perito en $asignado, actualizamos informes legales
			$sql = "UPDATE informes_legales SET id_perito = $id_persona WHERE id_informe_legal = $id ";
			ejecutar($sql);
			
			// enviamos al WS el perito asignado BSOL
			if($noportunidad!='0'){
				//
				$estado = '0';
				require_once('ws_peritos_bsol.php');
			}
		}
	}
}
?>