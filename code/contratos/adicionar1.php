<?php

	//SELECCION DEL MODELO DE CONTRATO

	if(isset($_SESSION['idcontrato']))
		$idcontrato = $_SESSION['idcontrato'] ;
	else
		$idcontrato = 0;
	$smarty->assign('idcontrato',$idcontrato);
	$conactual = 'Ninguno';
	

		if($quien=='10')
			$sql= "SELECT c.idcontrato, c.titulo
			 FROM contrato c
			 WHERE c.habilitado=1 
			 ORDER BY c.titulo";
		else
			$sql= "SELECT cu.idusuario, c.idcontrato, c.titulo
			 FROM contrato c, contratousuario cu
			 WHERE cu.idusuario = '$USER' AND cu.idcontrato=c.idcontrato AND
			 c.habilitado=1 
			 ORDER BY c.titulo";

		 
	$query = consulta($sql);
	$contratos=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if($idcontrato != 0 && $idcontrato == $row["idcontrato"]){
			$conactual = $row["titulo"];
		}
		$contratos[]= array('id' => $row["idcontrato"],
								'titulo' => $row["titulo"]);
	}
	//print_r($contratos);
	//ver si es para baneco y verificar los nrocaso nuevos
	//verificar si esta habilitado el WS
	//cerrar();
	unset($link);
	require('../lib/conexionMNU.php');
	$sql = "SELECT TOP 1 enable_ws FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	if($enable_ws=='A' || $enable_ws=='C'){ // recpcion caso bnaneco por nro de caso
		//buscar todos los nrocaso existentes en informes_legales y 
		//que esten en tabla NCASO_CFINAL(NROCASO, IDFINAL) con idfinal=0
		$id_us_actual = $_SESSION["idusuario"]; //usuario guardian en sesion   ---, ile.ci_cliente
		
		if($quien=='10'){
			$id_almacen = $_SESSION["filtro_almacen"];
			$sql= "SELECT DISTINCT ile.nrocaso nro, ile.cliente, 
			nc.importelinea, nc.importeprestamo 
			FROM informes_legales ile INNER JOIN usuarios us ON us.id_usuario = ile.id_us_comun 
			INNER JOIN ncaso_cfinal nc ON ile.nrocaso = nc.nrocaso
			WHERE nc.idfinal='0' AND ile.nrocaso<>'' AND ofi.id_almacen = '$id_almacen' ORDER BY ile.nrocaso";
		}else{
			$id_almacen = $_SESSION["id_almacen"];
			$sql= "SELECT DISTINCT ile.nrocaso nro, ile.cliente, 
			nc.importelinea, nc.importeprestamo 
			FROM informes_legales ile INNER JOIN usuarios us ON us.id_usuario = ile.id_us_comun 
			INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
			INNER JOIN ncaso_cfinal nc ON ile.nrocaso = nc.nrocaso
			WHERE nc.idfinal='0' AND ile.nrocaso<>'' AND ofi.id_almacen = '$id_almacen' ORDER BY ile.nrocaso";
		}//echo $sql;
		$query = consulta($sql);
		$nrocasos= array();
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			if($row["importelinea"]>0)  
				if($row["importeprestamo"]==0) 
					$tipoope = "(APERTURA LINEA)"; 
				else 
					$tipoope = "(BAJO LINEA)";
			else
				$tipoope = "(PRESTAMO)";
			
			$nrocasos[] = array('nrocaso'=>$row["nro"],
								'cliente'=>$row["cliente"],
								'tipoope'=>$tipoope);
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
					$tipoope = "(APERTURA LINEA)"; 
				else 
					$tipoope = "(BAJO LINEA)";
			else
				$tipoope = "(PRESTAMO)";
			
			$nrocasos[] = array('nrocaso'=>$row["nro"],
								'cliente'=>"(Sin informe legal)",
								'tipoope'=>$tipoope);
		}
		$smarty->assign('nrocasos',$nrocasos);
		$smarty->assign('ncaso','s');
	}elseif($enable_ws=='N' ){ // recpcion caso bisa / CIDRE
		//buscar todos los nrocaso existentes en informes_legales y 
		//que esten en tabla NCASO_CFINAL(NROCASO, IDFINAL) con idfinal=0
		$id_us_actual = $_SESSION["idusuario"]; //usuario guardian en sesion   ---, ile.ci_cliente
		$id_almacen = $_SESSION["id_almacen"];
		
		$sql= "SELECT DISTINCT convert(int,ile.nrocaso) nro, ile.cliente, 
		nc.importelinea, nc.importeprestamo 
		FROM informes_legales ile INNER JOIN usuarios us ON us.id_usuario = ile.id_us_comun 
		INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
		INNER JOIN ncaso_cfinal nc ON ile.nrocaso = nc.nrocaso
		WHERE nc.idfinal='0' AND ile.nrocaso<>'' AND ofi.id_almacen = '$id_almacen' ORDER BY nro";
		//echo $sql;
		$query = consulta($sql);
		$nrocasos= array();
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$nrocasos[] = array('nrocaso'=>$row["nro"],
								'cliente'=>$row["cliente"],
								'tipoope'=>'');
		}
		//numeros sin informe legal
		$sql= "SELECT DISTINCT convert(int,nrocaso) nro,  
		importelinea, importeprestamo
		FROM ncaso_cfinal 
		WHERE idfinal='0' AND nrocaso NOT IN (
			SELECT nrocaso FROM informes_legales WHERE nrocaso IS NOT NULL ) ORDER BY nro";
		$query = consulta($sql);
		while($row2= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$nombres='';
			$nrocaso = $row2["nro"];
			if($enable_ws=='C'){
				require("../code/ws_datoscliente.php");
			}
			if($nombres==''){
				$nombres = "(Sin informe legal)";
			}
			$nrocasos[] = array('nrocaso'=>$nrocaso,
								'cliente'=>$nombres,
								'tipoope'=>'');
		}
		$smarty->assign('nrocasos',$nrocasos);
		$smarty->assign('ncaso','s');  // esto para bisa se cambio de 's' a 'n', resturado luego del pase
	}else{
		$smarty->assign('ncaso','n');
	}
	
	$smarty->assign('contratos',$contratos);
	$smarty->assign('conactual',$conactual);
	$smarty->display('contratos/adicionar1.html');
	die();
	
?>
