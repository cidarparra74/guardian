<?php
if(!isset($_POST['idcontrato'])) return;

	$idcontrato = $_POST['idcontrato'] ;
	//obtenemos los datos del contrato, el titulo, la cabecera y el pie
	$sql = "select titulo, cabecera, pie from contrato where idcontrato = $idcontrato ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo = $row["titulo"];
	$cabecera = $row["cabecera"];
	$pie = $row["pie"];
	
	//obtenemos las clausulas del contrato
	$sql = "select cl.idclausula, cl.contenido from rel_cc rc 
	inner join clausula cl on cl.idclausula = rc.idclausula 
	where rc.idcontrato = $idcontrato order by posicion";
	$query = consulta($sql);
	$clausulas=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$clausulas[] = array('idc' => $row["idclausula"],
								'contenido' => $row["contenido"]);
	}
	
	//obtenemos los incisos de las clausulas del contrato
	$sql = "select nu.idclausula, contenido  from numeral nu where nu.idclausula in (select rc.idclausula from rel_cc rc 
	inner join clausula cl on cl.idclausula = rc.idclausula 
	where rc.idcontrato =  $idcontrato
	) order by nu.idclausula";
	$query = consulta($sql);
	$incisos=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$incisos[] = array('idc' => $row["idclausula"],
								'contenido' => $row["contenido"]);
	}
	
	// una clausula puede tener varios incisos, marcamos el cambio de clausula con $idcl
	$idcl = 0; $texto = '';
	//reemplazamos los incisos en las clausulas que tengan
	foreach($incisos as $inciso){
		if($idcl != $inciso['idc'] and $idcl != 0){
			//buscamos en inciso
			$clau = ''; $pos=0;
			foreach($clausulas as $key=>$clausula){
				if($clausula['idc']==$idcl){
					$clau = $clausula['contenido'];
					$pos=$key;
					break;
				}
			}
			//reemplazamos el inciso en la clausula en lugar de la marca <incisos,3>
			if($pos!=0){
				$clausulas[$pos]['contenido'] = str_replace('<<INCISOS,3>>', $texto, $clau);
			}
			$texto = '';
		}
			
			//ponemos el contenido en formato para insertar
			$texto .= substr(str_replace("{\rtf1",'',trim($inc)),0,-1) ."\par";
			$idcl = $inciso['idc'];
		
	}
	
	// armamos el contrato, ponemos cabecera + clausulas + pie
	
	//quitamos corchete final a la cabecera
	
	$contrato = substr(trim($cabecera),0,-1);
	
	//ponemos clausulas
	foreach($clausulas as $key=>$clausula){
		//quitamos el inicio y final de la clausula
		$clau = $clausula['contenido'];
		$texto .= substr(str_replace("{\rtf1",'',trim($clau)),0,-1) ."\par";
		//adicionamos al contrato
		$contrato .= $texto;
		
	}
	
	//adicionamos el pie
	$contrato .= str_replace("{\rtf1",'',trim($pie))
	
die();
?>