<?php
// cargarmos funciones propias
	require('../lib/conexionSEC.php');
	
	if(isset($_GET['ci']) && isset($_GET["emi"])){
		//es persona natural
		$emi=$_GET["emi"];
		$ci=$_GET["ci"];
		// antes buscamos el codigo de la ciudad de emision para el caso de q la busqueda sea por WS
		$sql2= "SELECT * FROM expedido WHERE descripcion = '$emi'";
		$query2 = consulta($sql2);
		$row2= $query2->fetchRow(DB_FETCHMODE_ASSOC);
		$emicod = $row2["codigo"];
		// 
		$sql = "SELECT * FROM persona WHERE personanatural = 1 AND ci = '$ci' AND expedido = '$emi'";
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
				$row['edocivil']."|$emicod";
		}else{
			// buscamos sin emision
			$sql = "SELECT * FROM persona WHERE personanatural = 1 AND ci = '$ci'";
			$query = consulta($sql);
			$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($ci == $row['ci']){
				//existe, leemos datos
				$sql2= "SELECT * FROM expedido WHERE descripcion = '".$row['expedido']."'";
				$query2 = consulta($sql2);
				$row2= $query2->fetchRow(DB_FETCHMODE_ASSOC);
				$emicod = $row2["codigo"];
				echo $row['tipo_documento']."|".
					utf8_encode($row['nombre'])."|".
					$row['nacionalidad']."|".
					$row['pais']."|".
					utf8_encode($row['profesion'])."|".
					utf8_encode($row['domicilio'])."|".
					utf8_encode($row['parrafo'])."|".
					$row['edocivil']."|$emicod";
			}else{
				//buscamos en guardian
				require('../lib/conexionMNU.php');
				// buscamos sin emision
				$sql = "SELECT * FROM propietarios WHERE personanatural = 1 AND ci = '$ci'";
				$query = consulta($sql);
				$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
				if($ci == $row['ci']){
					//existe, leemos datos
					$emicod = $row["emision"];
					echo $row['id_tipo_identificacion']."|".
						utf8_encode($row['nombres'])."|".
						$row['nacionalidad']."|".
						$row['pais']."|".
						utf8_encode($row['profesion'])."|".
						utf8_encode($row['direccion'])."|".
						utf8_encode($row['parrafo'])."|".
						$row['estado_civil']."|$emicod";
				}else{
					// buscamos en el WS
					// verificamos que banco es
					require('../lib/conexionMNU.php');
					$sql2="SELECT TOP 1 enable_ws FROM opciones ";
					$query2 = consulta($sql2);
					$row2= $query2->fetchRow(DB_FETCHMODE_ASSOC);
					if($row2["enable_ws"]=='A'){
					 //$ci no incluye emision 
						$ci_cliente = trim($ci).$emi;   
						$nombre = '';
						// no existe en sec, buscamos con el WS los datos personales 
						require('../code/ws_cliente_baneco2.php');
						if($nombre != ''){
								//$emision = substr($ci_cliente,-2,2);
								$ecivil = substr($estadocivil,0,1);
								echo "1|".
									$nombre."|".
									"BOLIVIANA|".
									"1|".
									$profesion."|".
									$direccion."||".
									$ecivil."|$emicod";
						}else{
								echo "?|$ci_cliente|||||||0";
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
								echo "1|".
									$nombres."|".
									"BOLIVIANA|".
									"1|".
									$profesion."|".
									$direccion."||".
									$ecivil."|$emicod";
						}else{
							
								echo "?|$ci_cliente|||||||0";
							
						}
					}else{
							
								echo "?|sin WS $ci_cliente|||||||0";
							
					}
				}
			}
		
		}
	}else{
		//es persona juridica
				$ci=$_GET["ci"];
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
				}//buscamos en guardian
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
						echo "?|P.Juridica|||||||11";
				}
	}
		
?>