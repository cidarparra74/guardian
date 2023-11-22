<?php

	//determinar DEL MODELO DE CONTRATO
unset($link);
	require('../lib/conexionMNU.php');
	if(isset($_SESSION['idcontrato']))
		$idcontrato = $_SESSION['idcontrato'] ;
	else
		$idcontrato = 0;
	$smarty->assign('idcontrato',$idcontrato);
	$conactual = 'Ninguno';
	//recuperando los contratos del usuario
	$sql= "SELECT * FROM contratos_fijos ";
	//echo $sql;
	$query = consulta($sql);
	$contratos=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$contratos[]= array('id' => $row["idcontrato"],
							'clase' => $row["clase"]);
	}
	
	$sql = "SELECT TOP 1 enable_ws FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	if($row["enable_ws"]=='A' or $row["enable_ws"]=='N'){ // recepcion caso baneco por nro de caso y BISA
		//buscar todos los nrocaso existentes en informes_legales y 
		//que no esten en tabla NCASO_CFINAL(NROCASO, IDFINAL)
		$id_us_actual = $_SESSION["idusuario"]; //usuario guardian en sesion   ---, ile.ci_cliente
		$id_almacen = $_SESSION["id_almacen"];
		$sql= "SELECT DISTINCT convert(int,ile.nrocaso) nro, ile.cliente, nc.importelinea, nc.importeprestamo
		FROM informes_legales ile INNER JOIN usuarios us ON us.id_usuario = ile.id_us_comun 
		INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
		INNER JOIN ncaso_cfinal nc ON ile.nrocaso = nc.nrocaso
		WHERE nc.idfinal='0' AND ile.nrocaso<>'' AND ofi.id_almacen = '$id_almacen' ORDER BY nro";
		//AND ile.estado = 'pub' -- esto en la aprobacion (SELECT id_almacen 
			//FROM oficinas o1 INNER JOIN usuarios u1 ON o1.id_oficina = u1.id_oficina 
			//WHERE u1.id_usuario = $id_us_actual)
		//,nc.seguroDegravamen nc.numeroCuotas, nc.Tasa1, nc.Tasa2
		$query = consulta($sql);
		$nrocasos= array();
		
		//$tipoope=$nrocaso.' '.$row["cliente"].' <br>';
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			if($row["importelinea"]>0)  
				if($row["importeprestamo"]==0) 
					$tipoope = "APERTURA LINEA"; 
				else 
					$tipoope = "BAJO LINEA";
			else
				$tipoope = "PRESTAMO";
			//buscamos el id del contrado para tipoope (clase)
			$contrato = 0;
			foreach($contratos as $var){
				if($var['clase']==substr($tipoope,0,1))
					$contrato = $var['id'];
			}
			$nrocasos[] = array('nrocaso'=>$row["nro"],
								'cliente'=>$row["cliente"],
								'tipoope'=>$tipoope,
								'contrato'=>$contrato);
		}
		//numeros sin informe legal
		$sql= "SELECT DISTINCT convert(int,nrocaso) nro,  
		importelinea, importeprestamo
		FROM ncaso_cfinal  
		WHERE idfinal='0' AND nrocaso NOT IN (
			SELECT nrocaso from informes_legales where nrocaso IS NOT NULL ) ORDER BY nro";
		$query = consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			if($row["importelinea"]>0)  
				if($row["importeprestamo"]==0) 
					$tipoope = "APERTURA LINEA"; 
				else 
					$tipoope = "BAJO LINEA";
			else
				$tipoope = "PRESTAMO";
			//buscamos el id del contrado para tipoope (clase)
			$contrato = 0;
			foreach($contratos as $var){
				if($var['clase']==substr($tipoope,0,1))
					$contrato = $var['id'];
			}
			$nrocasos[] = array('nrocaso'=>$row["nro"],
								'cliente'=>"(Sin informe legal)",
								'tipoope'=>$tipoope,
								'contrato'=>$contrato);
		}
		$smarty->assign('nrocasos',$nrocasos);
		$smarty->assign('ncaso','s');
	}else{
		$smarty->assign('ncaso','n');
	}
	
	$smarty->assign('contratos',$contratos);
	$smarty->assign('conactual',$conactual);
	$smarty->display('contratos/adicionar1_esp.html');
	die();
	
?>
