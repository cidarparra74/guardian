<?php

	//$smarty->assign('existentes',$existentes);
	//recuperando los lugares de emision
	$sql= "SELECT * FROM emisiones ";
	$query = consulta($sql);
	$i=0;
	$expedido=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$expedido[$i]= $row["emision"];
		$i++;
	}
	$smarty->assign('expedido',$expedido);

	//recuperando los tipos de indentificacion
	$sql= "SELECT * FROM tipos_identificacion ORDER BY identificacion ";
	$query = consulta($sql);
	$i=0;
	$tipodocs=array();
	$elNit = '0';
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipodocs[$i]= array('id' => $row["id_tipo"],
								'descri' => $row["identificacion"]);
		if(trim($row["identificacion"])=='NIT' || trim($row["identificacion"])=='N.I.T.') 
			$elNit = $row["id_tipo"];
		$i++;
	}
	$smarty->assign('tipodocs',$tipodocs);
	$smarty->assign('elNit',$elNit);
	
	//recuperando los lugares de emision
	$sql= "SELECT emision FROM emisiones ORDER BY emision ";
	$query = consulta($sql);
	$i=0;
	$expedido=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if(trim($row["emision"])!='--'){ //esto por si pone su propio '--'
			$expedido[$i]= array('id' => $row["emision"],
								'descri' => $row["emision"]);
			$i++;
		}
	}
	$smarty->assign('expedido',$expedido);
	
	//recuperando los lugares de emision
	$sql= "SELECT emision FROM emisiones ORDER BY emision ";
	$query = consulta($sql);
	$i=0;
	$expedido=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if(trim($row["emision"])!='--'){ //esto por si pone su propio '--'
			$expedido[$i]= array('id' => $row["emision"],
								'descri' => $row["emision"]);
			$i++;
		}
	}
	$smarty->assign('expedido',$expedido);
	//recuperando los paises
	$sql= "SELECT * FROM pais ";
	$query = consulta($sql);
	$i=0;
	$paises=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$paises[$i]= array('id' => $row["codigo"],
									'descri' => $row["descripcion"]);
		$i++;
	}
	$smarty->assign('paises',$paises);
	
	
	$smarty->display('personas/adicionar2.html');
	die();
	
?>
