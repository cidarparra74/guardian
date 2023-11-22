<?php
// cargarmos funciones propias
	require_once('../lib/conexionMNU.php');
	
	if(isset($_GET['ci'])){
		$nombres = '';
		$documento=$_GET["ci"];
		
		//buscamos siempre primero en el WS
		//verificar si esta habilitado el WS
		$sql = "SELECT TOP 1 enable_ws FROM opciones";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$enable_ws = $row["enable_ws"];
		
		if($enable_ws == 'S'){
			//bsol
			$Pais 	 = '1';
			$TipoDoc = '1';
			
			//require('../code/ws_cliente.php'); 
		}else{
			//ver si entramos baneco
			if($enable_ws == 'A'){
				require('../code/ws_empresa_baneco.php');
				
			}else{
				if($enable_ws == 'C'){
					// es cidre
					
					//$nrodecaso = '';
					//require('../code/ws_cliente_cidre.php');
				}else{
					
				}
			}
		}
		
		
		if(trim($nombres) != ''){
			//existe en WS, lo insertamos en tabla propietarios si no existe o lo actualizamos
			$sql = "SELECT id_propietario, ci 
			FROM propietarios WHERE ci = '$documento' ";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($documento == $row['ci']){
				$id_propietario = $row['id_propietario'];
			}else{
				//no existe lo insertamos
				$ecivil = '2'; //es tipo civil=1 o comercial=2
				$fecha_actual= date("Y-m-d H:i:s");
				$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";
				$sql= "INSERT INTO propietarios (nombres, ci, direccion, 
					telefonos, creacion_propietario, estado_civil, nit, emision, mis,
					personanatural, razonsocial, nromatricula, representante, id_tipo_identificacion) 
					VALUES('$nombres', '$documento', '$direccion', 
					'$telefonos', $fecha_actual, '$ecivil', '$documento', '', '$documento',
					'2', '$nombres', '', '', '6') ";
				ejecutar($sql);
				//pero necesitamos el idpropietario!!
				$sql = "SELECT MAX(id_propietario) AS idp FROM propietarios where ci='$documento'";
				$query = consulta($sql);
				$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
				$id_propietario = $row["idp"];
			}
			echo $nombres."|".$direccion."|".$id_propietario."|0";
		}else{
			// no es ningun banco con WS, buscamos en el mismo guardian
			$sql = "SELECT * FROM propietarios WHERE ci = '$documento' ";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($documento == $row['ci']){
				//existe, leemos datos 
				$nombres = trim($row['nombres']);
				$direccion = trim($row['direccion']);
				$id_propietario = $row['id_propietario'];
				echo $nombres."|".$direccion."|".$id_propietario."|0";
				//die();
			}else{
				// no hay en guardian
				//no es bsol, ni baneco, no hay
				//no existe
				echo "El documento indicado no existe!||0|0";
			}
			
		}
		
	}else{
		echo "?||0|0";
	}
		
?>