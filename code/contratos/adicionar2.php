<?php
	//SELECCION DE LAS CLAUSULAS OPCIONALES

	// VERIFICAMOS SI SE ELIGIO EL CONTRATO MODELO
	if(!isset($_REQUEST['idcontrato'])){
			die("Error fatal, realize de nuevo el contrato");
	}else{
		$idcontrato = $_REQUEST['idcontrato'];
		$contrato = $_REQUEST['contrato'];
	}
	if(isset($_REQUEST['nrocaso'])){
		
		$nrocaso = $_REQUEST['nrocaso'];
		$_SESSION['nrocaso'] = $nrocaso; //y nos olvidamos hasta el momento de las variables
		unset($link);
		require('../lib/conexionMNU.php');
		$sql = "SELECT TOP 1 enable_ws FROM opciones";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$enable_ws = $row["enable_ws"];
		if($row["enable_ws"]=='A' OR $row["enable_ws"]=='N'){
			// recpcion caso baneco por nro de caso
			$sql= "SELECT nc.segurodegravamen, nc.numerocuotas, nc.tasa1, nc.tasa2, nc.importelinea, nc.importeprestamo, 
			il.cliente, nc.tiposeguro, nc.codigogarantia, nc.id_banca, nc.agencia
			FROM ncaso_cfinal nc LEFT JOIN informes_legales il ON il.nrocaso=nc.nrocaso WHERE nc.nrocaso='$nrocaso'";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$tipoope=$nrocaso.' '.$row["cliente"].' <br>';
			$to='x';
			if($row["importelinea"]>0){
					if($row["importeprestamo"]==0){
						$tipoope .= "(APERTURA LINEA, "; 
						$to='A';
					}else{ 
						$tipoope .= "(BAJO LINEA, ";
						$to='B';
					}
			}else{
					$tipoope .= "(PRESTAMO, ";
					$to='P';
			}
			
			//jalamos el nombre del contrato
			$tipoope .= "SEGURO DESGRAVAMEN: ".$row["segurodegravamen"].", " ;
			if($row["tasa1"]>0 && $row["tasa2"]>0){$tipoope .= "TASA MIXTA, ";}
			elseif($row["tasa1"]>0 && $row["tasa2"]==0){$tipoope .= "TASA FIJA, ";}
			elseif($row["tasa1"]==0 && $row["tasa2"]>0){$tipoope .= "TASA VARIABLE, ";}
			elseif($row["tasa1"]==0 && $row["tasa2"]==0){$tipoope .= "SIN TASA, ";}
			if($row["numerocuotas"]==1){
				$tipoope .= "PAGO UNICO," ;
			}else{
				$tipoope .= "PAGOS SECUENCIALES,";
			}
			$tipoope .= "TipoSeguro:". $row["tiposeguro"] ;
			$tipoope .= ", TipoGar:". $row["codigogarantia"].')' ;
			$_SESSION['tipoope'] = $tipoope;
			$smarty->assign('tipoope',$tipoope);
			
		}
		unset($link);
		require('../lib/conexionSEC.php');
		if($contrato =='' && $idcontrato != 0){
			$sql= "SELECT c.titulo FROM contrato c WHERE c.idcontrato=$idcontrato";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$contrato= $row["titulo"];
		}
		
	}
		
		$_SESSION['idcontrato'] = $idcontrato;
		$_SESSION['contrato'] = $contrato;
		
		//verificamos si habra restriccion segun tipo de persona natural o juridica
		$sql= "SELECT c.tipopersona FROM contrato c WHERE c.idcontrato=$idcontrato";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$verpersona= $row["tipopersona"];
		if($verpersona!='S') $verpersona = 'N';  // esto por que en alguinos casos verpersona puede ser vacio
		
		//VERIFICAMOS SI HAY CLAUSULAS OPCIONALES
		//$sql= "SELECT r.posicion, r.idclausula, c.titulo FROM rel_cc r, clausula c 
		//WHERE r.idcontrato = $idcontrato AND r.idclausula=c.idclausula AND r.opcional =1 ORDER BY r.posicion";
		$sql= "SELECT r.posicion, r.idclausula, c.descri as titulo, r.dependiente
				FROM rel_cc r, clausula c 
				LEFT JOIN vinculo v on v.vinculo = c.idclausula
				WHERE r.idcontrato = $idcontrato AND r.idclausula=c.idclausula AND r.opcional =1 and v.idclausula is null
				ORDER BY r.posicion";
		//echo $sql; die();
		$query = consulta($sql);
		$opcionales=array();
		$i=0;
		if(isset($_SESSION['opcional'])){
			$marcados = explode(',',$_SESSION['opcional']);
			while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
				// verificar si ya se ha seleccionado antes
				if(in_array($row["idclausula"],$marcados))
					$marcado = 'checked';
				else
					$marcado = '';
				$opcionales[]= array('id' => $row["idclausula"],
									'titulo' => htmlentities($row["titulo"],ENT_IGNORE),
									'marcado' => $marcado,
									'dependiente' => $row["dependiente"]);
				$i++;
			}
		}else{
			while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
				$opcionales[]= array('id' => $row["idclausula"],
									'titulo' => htmlentities($row["titulo"],ENT_IGNORE),
									'marcado' => '',
									'dependiente' => $row["dependiente"]);
				$i++;
			}
		}
		//echo $i;
		//vemos si existen clausulas opcionales que elegir
		
		$_SESSION['verpersona'] = $verpersona;
		
		if($i>=1 or $verpersona=='S'){
			
			$smarty->assign('opcionales',$opcionales);
			$smarty->assign('contrato',$contrato);
			$smarty->assign('verpersona',$verpersona);
			$_SESSION['cantidad'] = $i;
			$smarty->display('contratos/adicionar2.html');
		}else{
			//no existen clausulas opcionales, pasamos directamente al paso 3
			$_SESSION['cantidad'] = 0;
			include("./contratos/adicionar3.php");
		}
	
	die();
	
?>