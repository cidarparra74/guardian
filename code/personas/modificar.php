<?php

	$id= $_REQUEST['id'];
	

	//recuperando los tipos de indentificacion
	$sql= "SELECT * FROM tipos_identificacion ORDER BY identificacion ";
	$query = consulta($sql);
	$i=0;
	$tipodocs=array();
	//$elNit = '0';
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipodocs[$i]= array('id' => $row["id_tipo"],
								'descri' => $row["identificacion"]);
		//if(trim($row["identificacion"])=='NIT' || trim($row["identificacion"])=='N.I.T.') 
		//	$elNit = $row["codigo"];
		$i++;
	}
	$smarty->assign('tipodocs',$tipodocs);
	//$smarty->assign('elNit',$elNit);
	
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

	//datos del cliente
	$sql = "SELECT * FROM propietarios WHERE id_propietario='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$smarty->assign('id',$id);
	$smarty->assign('control',$resultado["personanatural"]-1);
	$smarty->assign('txtCI',$resultado["ci"]);
	$smarty->assign('emision',$resultado["emision"]);
	$smarty->assign('selTipo',$resultado["id_tipo_identificacion"]);
	$smarty->assign('txtNombre',$resultado["nombres"]);
	$smarty->assign('txtProcede',$resultado["nacionalidad"]);
	$smarty->assign('txtTelef',$resultado["telefonos"]);
	$smarty->assign('txtDireccion',$resultado["direccion"]);
	$smarty->assign('txtOcupa',$resultado["profesion"]);
	$smarty->assign('selEstCivil',$resultado["estado_civil"]);
	$smarty->assign('selPais',$resultado["pais"]);
	
	$smarty->assign('txtMatricula',$resultado["nromatricula"]);
	$smarty->assign('txtRepresenta',$resultado["representante"]);

	
	
	$smarty->display('personas/modificar2.html');
	die();
?>
