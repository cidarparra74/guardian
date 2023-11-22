<?php
// cargarmos funciones propias
	
	require('../lib/conexionMNU.php');
	if(isset($_GET['ci']) && isset($_GET["emi"])){
		
		//es persona natural
		$emi=$_GET["emi"]; //en literal, ya no en numerico de sec
		$ci=$_GET["ci"];
		
		$ok=false;
		// buscamos en el WS
		// verificamos que banco es
		
		$sql2="SELECT TOP 1 enable_ws FROM opciones ";
		$query2 = consulta($sql2);
		$row2= $query2->fetchRow(DB_FETCHMODE_ASSOC);
		if($row2["enable_ws"]=='A'){
			//caso baneco 
			$ci_cliente = trim($ci).$emi;   
			$nombre = '';
			require('../code/ws_cliente_baneco2.php');
			if($nombre != ''){
					//$emision = substr($ci_cliente,-2,2);
					$ecivil = substr($estadocivil,0,1);
					$ok=true;
					echo "1|".
						$nombre."|".
						$nacionalidad."|".
						"1|".
						$profesion."|".
						$direccion."||".
						$ecivil."|$emi";
					
			}
		}elseif($row2["enable_ws"]=='C'){

			//caso cidre 
			$ci_cliente = trim($ci).$emi;   
			$nombre = '';
			// no existe en sec, buscamos con el WS los datos personales 
			require('../code/ws_cliente_cidre.php');
			if($nombres != ''){
					//$emision = substr($ci_cliente,-2,2);
					$ecivil = substr($estadocivil,0,1);
					$ok=true;
					echo "1|".
						$nombres."|".
						"BOLIVIANA|".
						"1|".
						$profesion."|".
						$direccion."||".
						$ecivil."|$emi";
			}
		}else{
			//otros casos (bisa)
				//echo "?|sin WS $ci_cliente|||||||0";
			//$ok=true;	
			$ci_cliente = trim($ci).$emi;
			$nombre = '';
		}
		//buscamos en guardian si antes no se encontraron resultados
		if(!$ok){	
			//require('../lib/conexionMNU.php');
			// buscamos sin emision
			$sql = "SELECT * FROM propietarios WHERE personanatural = 1 AND ci = '$ci'";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($ci == $row['ci']){
				//existe, leemos datos
				$emi = $row["emision"];
				echo $row['id_tipo_identificacion']."|".
					utf8_encode($row['nombres'])."|".
					$row['nacionalidad']."|".
					$row['pais']."|".
					utf8_encode($row['profesion'])."|".
					utf8_encode($row['direccion'])."||".
					$row['estado_civil']."|$emi";
			}else{
				//buscamos en SEC ?
				// antes buscamos el codigo de la ciudad de emision para el caso de q la busqueda sea por WS
				require('../lib/conexionSEC.php');
				$sql2= "SELECT * FROM expedido WHERE descripcion = '$emi'";
				$query2 = consulta($sql2);
				$row2= $query2->fetchRow(DB_FETCHMODE_ASSOC);
				$emicod = $row2["codigo"];
				// 
				$sql = "SELECT * FROM persona WHERE personanatural = 1 AND ci = '$ci' AND expedido = '$emicod'";
				$query = consulta($sql);
				$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
				if($ci == $row['ci']){
					//existe, leemos datos
					echo $row['tipo_documento']."|".
						utf8_encode($row['nombre'])."|".
						$row['nacionalidad']."|".
						$row['pais']."|".
						utf8_encode($row['profesion'])."|".
						utf8_encode($row['domicilio'])."|".
						utf8_encode($row['parrafo'])."|".
						$row['edocivil']."|$emi";
				}else{
					// buscamos sin emision
					$sql = "SELECT * FROM persona WHERE personanatural = 1 AND ci = '$ci'";
					$query = consulta($sql);
					$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
					if($ci == $row['ci']){
						echo $row['tipo_documento']."|".
							utf8_encode($row['nombre'])."|".
							$row['nacionalidad']."|".
							$row['pais']."|".
							utf8_encode($row['profesion'])."|".
							utf8_encode($row['domicilio'])."|".
							utf8_encode($row['parrafo'])."|".
							$row['edocivil']."|$emi";
					}else{
						echo "?|$ci_cliente|||||||-";
					}
				
				}
				
			}
		
		}
		
		
		
		
		
	}else{
		//es persona juridica
		$ci=$_GET["ci"];
		// verificamos que banco es
		
		$sql2="SELECT TOP 1 enable_ws FROM opciones ";
		$query2 = consulta($sql2);
		$row2= $query2->fetchRow(DB_FETCHMODE_ASSOC);
		if($row2["enable_ws"]=='A'){
			//caso baneco 
			$ci_cliente = trim($ci);   
			$nombre = '';
			require('../code/ws_cliente_baneco2.php');
			if($nombre != ''){
					//$emision = substr($ci_cliente,-2,2);
					$ecivil = substr($estadocivil,0,1);
					$ok=true;
					echo "1|".
						$nombre."|".
						"BOLIVIANA|".
						"1|".
						$profesion."|".
						$direccion."||".
						$ecivil."|?";
					
			}
		}else{
			require('../lib/conexionSEC.php'); 
				$sql = "SELECT * FROM persona WHERE personanatural = 2 AND ci = '$ci' ";
				$query = consulta($sql);
				$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
				if($ci == $row['ci']){
					//existe, leemos datos
					echo "0|".
						utf8_encode($row['nombre'])."|".
						$row['nromatricula']."|".
						$row['pais']."||".
						utf8_encode($row['dom_especial'])."|".
						utf8_encode($row['representante'])."||?";
				}else{//buscamos en guardian
					require('../lib/conexionMNU.php');
					// buscamos sin emision
					$sql = "SELECT * FROM propietarios WHERE personanatural = 2 AND ci = '$ci'";
					$query = consulta($sql);
					$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
					if($ci == $row['ci']){
						//existe, leemos datos
						echo "0|".
							utf8_encode($row['nombres'])."|".
							$row['nromatricula']."|".
							$row['pais']."||".
							utf8_encode($row['dom_especial'])."|".
							utf8_encode($row['representante'])."||?";
					}else{
							echo "?|P.Juridica|||||||?";
					}
				}
		}
	}
		
?>