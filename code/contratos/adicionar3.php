<?php
	//SELECCION DE INCISOS OPCIONALES
//vemos que exista la sesion del contrato seleccionado
if(!isset($_SESSION['idcontrato'])){	die("No se defini&oacute; contrato");}
	//recuperamos valores
	$idcontrato = $_SESSION['idcontrato'];
	$contrato = $_SESSION['contrato'];
	$cantidad = $_SESSION['cantidad'];

	//vemos si habia clausulas opcionales
	$opcional = '0';
	if($cantidad>0){
		//vemos cuales se han seleccionado
		for($i=0; $i<=$cantidad; $i++){
			$checkbox = 'opc_'.$i;
			$clausula = 'clau_'.$i;
			if(isset($_REQUEST["$checkbox"]))
				$opcional .= ','.$_REQUEST["$clausula"];
		}
	}
	
	//echo $opcional;
	$convinculo = $opcional;
	$servicio = $_SESSION["servicio"];
	$tipo = $_SESSION["tipo"];
	// ver si hay serv.  adic
	if($servicio == 'S'){
		$sql= "SELECT servicios FROM parametros_c";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$opcional .= ','.$row["servicios"];
	}
	//complementamos con posibles clausulas vinculadas
	$sql="SELECT v.vinculo FROM vinculo v WHERE v.idcontrato = $idcontrato and v.idclausula in ($convinculo)";
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
				$opcional .= ','.$row["vinculo"];
	}
	
	//echo $sql;
	
	//echo $tipo;
	
	//verificamos si es indistinto
	if($tipo == 'I'){
		$sql= "SELECT indistinta, fallindi, servtarindi, servtarjeta FROM parametros_c";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$opcional .= ','. $row["indistinta"];
		$opcional .= ','. $row["fallindi"];
		//si es indistinto y ademas tiene servicio incluir la clausula Tarjetas Cuentas colectivas
		//if($servicio == 'S')
		//	$opcional .= ','. $row["servtarindi"];
		//else
			//$opcional .= ','. $row["servtarjeta"];
	}
	//verificamos si es conjunto
	if($tipo == 'C'){
		$sql= "SELECT conjunta , fallconj FROM parametros_c";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$opcional .= ','. $row["conjunta"];
		$opcional .= ','. $row["fallconj"];
	}
	
	//echo $opcional;
	//
	$_SESSION['opcional'] = $opcional;
	//echo $opcional;
	//vemos las demas clausulas que tienen incisos
	$sql= "SELECT cl.idclausula, cl.titulo, nu.idnumeral, nu.nro_correlativo, nu.titulo as inciso, nu.excluyente 
	FROM numeral nu 
	INNER JOIN clausula cl ON cl.idclausula=nu.idclausula 
	INNER JOIN rel_cc rc ON rc.idclausula=cl.idclausula 
	WHERE rc.idcontrato= $idcontrato AND (rc.opcional=0 OR cl.idclausula IN ($opcional))
	ORDER BY rc.posicion, nu.nro_correlativo";
	
	//echo $sql;
	$query = consulta($sql);
	$i=0;
	$clausulas=array();
	if(isset($_SESSION['incisos'])){
		$marcados = explode(',',$_SESSION['incisos']);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			if(in_array($row["idnumeral"],$marcados) && $row["idnumeral"]!='0')
				$marcado = 'checked';
			else
				$marcado = '';
			$clausulas[]= array('id' => $row["idclausula"],
								'titulo' => htmlentities($row["titulo"],ENT_IGNORE),
								'idnumeral' => $row["idnumeral"],
								'inciso' => htmlentities($row["inciso"],ENT_IGNORE),
								'excluye' => $row["excluyente"],
								'marcado' => $marcado);
			$i++;
		}
	}else{
		$clant = 'x';
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$marcado = '';
			if($row["idclausula"]!=$clant && $row["excluyente"]=='1'){
				//primer elemento de la misma clausula, vemos de q este marcado
				$clant = $row["idclausula"];
				$marcado='checked';
			}
			
			$clausulas[]= array('id' => $row["idclausula"],
								'titulo' => htmlentities($row["titulo"],ENT_IGNORE),
								'idnumeral' => $row["idnumeral"],
								'inciso' => htmlentities($row["inciso"],ENT_IGNORE),
								'excluye' => $row["excluyente"],
								'marcado' => $marcado);
					$i++;
			
		}
	}
	// echo "<pre>";  print_r($clausulas); die();
	//
	if(isset($_SESSION['tipoope'])){
		$smarty->assign('tipoope',$_SESSION['tipoope']);
	}
	//vemos si existen incisos que elegir
	if($i>=1){
		$smarty->assign('clausulas',$clausulas);
		$smarty->assign('contrato',$contrato);
		$smarty->display('contratos/adicionar3.html');
	}else{
		//no incisos, pasamos directamente al paso 4
		include("./contratos/adicionar4.php");
	}

	die();
	
?>