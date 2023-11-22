<?php
// cargarmos funciones propias
	require_once('../lib/conexionMNU.php');
	
	if(isset($_GET['ci'])){
		$nombres = '';
		$emi=$_GET["emi"];
		$ci=$_GET["ci"];
		$xcu= $_GET['xcu'];
		
		//buscamos siempre primero en el WS
		//verificar si esta habilitado el WS
		$sql = "SELECT TOP 1 enable_ws FROM opciones";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$enable_ws = $row["enable_ws"];
		
		if($enable_ws == 'S'){
			//bsol
			$Pais 	 = '1';
			if($emi<>'PE')
				$TipoDoc = '1';
			else
				$TipoDoc = '3';
			$documento=$ci; //nos guardamos para luego insertar en la base
			if($emi!='--')
				$ci_cliente=$ci.$emi;
			else
				$ci_cliente=$ci;
				//desactivasmos lo sigte ya que damos prioridad a db guardian en bsol
			$ecivil = ' ';
		//	require('../code/ws_cliente.php'); 
			if($emision='' and $emi<>'' and $emi<>'--')
				$emision = $emi;
			$ecivil = substr($ecivil,0,1);
		}else{
			//ver si entramos baneco
			if($enable_ws == 'A'){
				$documento=$ci; //nos guardamos para luego insertar en la base
				$ci_cliente = $ci.$emi;
				//$nrodecaso = '';
				require('../code/ws_cliente_baneco2.php');
				$nombres = $nombre;
				$ecivil = substr($estadocivil,0,1);
			}else{
				if($enable_ws == 'C'){
					// es cidre
					$documento=$ci; //nos guardamos para luego insertar en la base
					$ci_cliente = $ci.$emi;
					//$nrodecaso = '';
					require('../code/ws_cliente_cidre.php');
					$ecivil = substr($estadocivil,0,1);
				}else{
					
				}
			}
			
		}
		
		
		if(trim($nombres) != ''){
			//existe en WS, lo insertamos en tabla propietarios si no existe o lo actualizamos
			$sql = "SELECT id_propietario, ci, emision
			FROM propietarios WHERE ci = '$ci' AND (emision = '$emi' OR emision = '')";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			$id_propietario = $row['id_propietario'];
			if($ci == $row['ci']){
			//die("existe en db");
				//vemos si se cambio algun dato
			
				$sql = "UPDATE propietarios SET nombres = '$nombres' WHERE id_propietario = '$id_propietario' AND nombres <> '$nombres' ";
				ejecutar($sql);
				$sql = "UPDATE propietarios SET direccion = '$direccion' WHERE id_propietario = '$id_propietario' AND direccion <> '$direccion' ";
				ejecutar($sql);
				$sql = "UPDATE propietarios SET telefonos = '$telefonos' WHERE id_propietario = '$id_propietario' AND telefonos <> '$telefonos' ";
				ejecutar($sql);
				$sql = "UPDATE propietarios SET estado_civil = '$ecivil' WHERE id_propietario = '$id_propietario' AND estado_civil <> '$ecivil' ";
				ejecutar($sql);
				$sql = "UPDATE propietarios SET profesion = '$profesion' WHERE id_propietario = '$id_propietario' AND profesion <> '$profesion' ";
				ejecutar($sql);
				$sql = "UPDATE propietarios SET emision = '$emision' WHERE id_propietario = '$id_propietario' AND emision <> '$emision' ";
				ejecutar($sql);
				
			}else{
			//die("no existe en db");
				//no existe lo insertamos
				$fecha_actual= date("Y-m-d H:i:s");
				$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
				$sql= "INSERT INTO propietarios (nombres, ci, direccion, 
					telefonos, creacion_propietario, estado_civil, nit, emision, mis,
					personanatural, profesion) 
					VALUES('$nombres', '$documento', '$direccion', 
					'$telefonos', $fecha_actual, '$ecivil', '', '$emi', '$documento',
					'1', '$profesion') ";
				ejecutar($sql);
				//pero necesitamos el idpropietario!!
				$sql = "SELECT MAX(id_propietario) AS idp FROM propietarios where ci='$documento'";
				$query = consulta($sql);
				$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
				$id_propietario = $row["idp"];
			}
			echo $nombres."|".$direccion."|".$id_propietario."|".$xcu."|".$emi;
		}else{
			// no existe en ws o ...
			// no es ningun banco con WS, buscamos en el mismo guardian
			$sql = "SELECT * FROM propietarios WHERE ci = '$ci' AND emision = '$emi'";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($ci == $row['ci']){
				//existe, leemos datos 
				$nombres = trim($row['nombres']);
				$direccion = trim($row['direccion']);
				//$nombres = utf8_encode($row['nombres']);
				//$direccion = utf8_encode($row['direccion']);
				$id_propietario = $row['id_propietario'];
				echo $nombres."|".$direccion."|".$id_propietario."|".$xcu."|".$emi;
				//die();
			}else{
				//aqui buscamos solo por ci en guardian
				$sql = "SELECT * FROM propietarios WHERE ci = '$ci'";
				$query = consulta($sql);
				$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
				if($ci == $row['ci']){
					//existe, lo tomamos por valido
					$nombres = trim($row['nombres']);
					$direccion = trim($row['direccion']);
					//$nombres = utf8_encode($row['nombres']);
					//$direccion = utf8_encode($row['direccion']);
					$id_propietario = $row['id_propietario'];
					$emi = $row['emision'];
					echo $nombres."|".$direccion."|".$id_propietario."|".$xcu."|".$emi;
					//die();
				}else{
					// no hay en guardian
					//no es bsol, ni baneco, no hay
					//no existe
					echo "El documento indicado no existe!||0|".$xcu."|".$emi;
					//$nombres = '';
				}
			}
			
		}
		
	}else{
		echo "?||0|".$xcu."|".$emi;
	}
		
?>