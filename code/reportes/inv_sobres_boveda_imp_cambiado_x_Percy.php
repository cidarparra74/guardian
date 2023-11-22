<?php

$gaveta= $_REQUEST['gaveta'];
/*$tipo= $_REQUEST['tipo'];*/
$oficina= $_REQUEST['oficina'];
$posicion_gaveta= $_REQUEST['posicion_gaveta'];

$consulta="";


if($oficina != "todos"){
	if($consulta == ""){
		$consulta=$consulta."WHERE o.id_oficina='$oficina' ";
	}
	else{
		$consulta=$consulta."AND o.id_oficina='$oficina' ";
	}
}
if($gaveta != "todos"){
	if($consulta == ""){
		if($posicion_gaveta == "todos"){
			$consulta=$consulta."WHERE p.mis LIKE '%$gaveta' ";
		}
		else{
			$consulta=$consulta."WHERE p.mis LIKE '%$posicion_gaveta$gaveta' ";
		}
	}
	else{
		if($posicion_gaveta == "todos"){
			$consulta=$consulta."AND p.mis LIKE '%$gaveta' ";
		}
		else{
			$consulta=$consulta."AND p.mis LIKE '%$posicion_gaveta$gaveta' ";
		}
	}
}


$sql_del= "DELETE FROM tmp_inv_sobres_boveda ";
ejecutar($sql_del);

$sql= "SELECT p.mis, p.nombres, o.nombre, m.id_estado, m.flujo ";
$sql.= "FROM (propietarios p inner join carpetas c on c.id_propietario=p.id_propietario ";
$sql.= "inner join oficinas o on c.id_oficina=o.id_oficina inner join tipos_bien t on c.id_tipo_carpeta=t.id_tipo_bien) ";
$sql.= "LEFT JOIN movimientos_carpetas m ON m.id_carpeta=c.id_carpeta AND (m.flujo!='1' OR m.id_estado='8') $consulta ORDER BY p.mis, m.id_estado ";
$result= consulta($sql);
$mis_anterior="";
$mis_actual="";
$bande=0;
$sobre=0;
while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
	$p_mis= $row["mis"];
	$p_nombre= $row["nombres"];
	$p_agencia= $row["nombre"];
	
	if($bande == 0){
		$bande=1;
		$mis_anterior=$row["mis"];
		$nombre_anterior= $row["nombres"];
		$agencia_anterior= $row["nombre"];
		//verificamos el conteo
		if($row["id_estado"] == null || $row["id_estado"]<4){
				$sobre++;
		}
	}
	else{
		
		$mis_actual= $row["mis"];
		if($mis_actual == $mis_anterior){
			//verificamos el conteo
			$aux_a= $row["id_estado"];
			//echo "aa.$aux_a.<br>";
			if($aux_a == null || $aux_a["id_estado"]<4){
				$sobre++;
				//echo "acc: $sobre <br>";
			}
			$mis_anterior=$mis_actual;
			$nombre_anterior= $p_nombre;
			$agencia_anterior= $p_agencia;
		}
		else{
			if($sobre>0){
				$sql_in= "INSERT INTO tmp_inv_sobres_boveda(mis, nombres, agencia, sobre)";
				$sql_in.= "VALUES('$mis_anterior', '$nombre_anterior', '$agencia_anterior', '1')";
			}
			else{
				$sql_in= "INSERT INTO tmp_inv_sobres_boveda(mis, nombres, agencia, sobre)";
				$sql_in.= "VALUES('$mis_anterior', '$nombre_anterior', '$agencia_anterior', '0')";
			}
			ejecutar($sql_in);
			$sobre=0;
			$mis_anterior=$mis_actual;
			$nombre_anterior= $p_nombre;
			$agencia_anterior= $p_agencia;
			//verificamos el conteo
			if($row["id_estado"] == null || $row["id_estado"]<4){
					$sobre++;
			}
		}
	}
}//fin del while
if($mis_actual == $mis_anterior){
	if($sobre>0){
		$sql_in= "INSERT INTO tmp_inv_sobres_boveda(mis, nombres, agencia, sobre)";
		$sql_in.= "VALUES('$mis_anterior', '$nombre_anterior', '$agencia_anterior', '1')";
	}
	else{
		$sql_in= "INSERT INTO tmp_inv_sobres_boveda(mis, nombres, agencia, sobre)";
		$sql_in.= "VALUES('$mis_anterior', '$nombre_anterior', '$agencia_anterior', '0')";
	}
	ejecutar($sql_in);	
}

$smarty->display('reportes/inv_sobres_boveda_imp.html');
die();
?>