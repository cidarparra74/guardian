<?php
// cargarmos funciones propias
	require_once('../lib/conexionMNU.php');
	
	if(isset($_GET['ci'])){
		$nombres = '';
		$emi=$_GET["emi"];
		$ci=$_GET["ci"];
		$xcu= $_GET['xcu'];
		$sql = "SELECT * FROM propietarios WHERE ci = '$ci' AND emision = '$emi'";
		$query = consulta($sql);
		$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
		if($ci == $row['ci']){
			//existe, leemos datos 
			$nombres = utf8_encode($row['nombres']);
			$direccion = utf8_encode($row['direccion']);
			$id_propietario = $row['id_propietario'];
			echo $nombres."|".$direccion."|".$id_propietario."|".$xcu;
		}else{
			//aqui buscamos solo por ci en guardian
			$sql = "SELECT * FROM propietarios WHERE ci = '$ci'";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($ci == $row['ci']){
				//existe, lo tomamos por valido
				$nombres = utf8_encode($row['nombres']);
				$direccion = utf8_encode($row['direccion']);
				$id_propietario = $row['id_propietario'];
				echo $nombres."|".$direccion."|".$id_propietario."|".$xcu;
				die();
			}
			//aqui buscamos por el WS
			//verificar si esta habilitado el WS
			$sql = "SELECT TOP 1 enable_ws FROM opciones";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$enable_ws = $row["enable_ws"];
			if($enable_ws == 'S'){
				//bsol
				$Pais 	 = '1';
				$TipoDoc = '1';
				$documento=$ci.$emi;
				require_once('ws_cliente.php');
			}else{
				//ver si entramos baneco
				if($enable_ws == 'A'){
					$documento=$ci; //nos guardamos para luego insertar en la base
					$ci_cliente = $ci.$emi;
					//$nrodecaso = '';
					require_once('ws_cliente_baneco2.php');
				}else{
					//no es bsol, ni baneco, ni guardian, no hay
					$nombres = '';
				}
			}
			if(trim($nombres) != ''){
					//existe, lo insertamos directamente en tabla propietarios
					$ecivil = substr($estadocivil,0,1);
					$fecha_actual= date("Y-m-d H:i:s");
					$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
					$sql= "INSERT INTO propietarios (nombres, ci, direccion, 
						telefonos, creacion_propietario, estado_civil, nit, emision, mis) 
						VALUES('$nombres', '$documento', '$direccion', 
						'$telefonos', $fecha_actual, '$ecivil', '', '$emi', '$documento') ";
					ejecutar($sql);
					//pero necesitamos el idpropietario!!
					$sql = "SELECT MAX(id_propietario) AS idp FROM propietarios ";
					$query = consulta($sql);
					$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
					$id_propietario = $row["idp"];
					echo $nombres."|".$direccion."|".$id_propietario."|".$xcu;
			}else{
					//no existe
					echo "El documento indicado no existe!||0|".$xcu;
			}
		}
	}else{
		echo "?||0|".$xcu;
	}
		
	
?>