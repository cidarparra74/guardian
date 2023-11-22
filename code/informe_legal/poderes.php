<?php

	$id=$_REQUEST['poderes']; //id del informe legal
	$idp=$_REQUEST['idp']; //id del poder
	$smarty->assign('id',$id);
	$smarty->assign('idp',$idp);
	
	if($idp!='0'){
		//modificando poder
	
		$sql= "SELECT *, convert(varchar(10),fecha,103) as fechax FROM poderes WHERE id_poder='$idp' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$numero= $resultado["numero"];
		$notario= $resultado["notario"];
		$fecha= $resultado["fechax"];
		$otorgante= $resultado["otorgante"];
		$registro= $resultado["registro"];
		$fojas= $resultado["fojas"];
		$tipo= $resultado["id_tipo_documento"];

		$smarty->assign('numero',$numero);
		$smarty->assign('notario',$notario);
		$smarty->assign('fecha',$fecha);
		$smarty->assign('otorgante',$otorgante);
		$smarty->assign('registro',$registro);
		$smarty->assign('fojas',$fojas);
		$smarty->assign('tipo',$tipo);
	
		//recuperando apoderados
		$sql= "SELECT * FROM apoderados WHERE id_poder = '$idp' ";
		$query = consulta($sql);
		$apoderados=array();
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$apoderados[]= array ('id' => $row["id_apoderado"],
								'apoderado' => $row["apoderado"],
								'tipo' => $row["tipo"],
								'estado' => ($row["vigente"]=='S' ? 'Vigente' : 'Revocado'),
								'porcentaje' => $row["porcentaje"]);
		}
		$smarty->assign('apoderados',$apoderados);
	
	}else{
		//creamos el poder desde ya el registro del poder
	//	$sql= "INSERT INTO poderes (id_informe_legal) values ($id)";
	//	ejecutar($sql);
		//jalamos el id_poder
	//	$sql= "SELECT MAX(id_poder) as idp FROM poderes WHERE id_informe_legal = '$id' ";
		
	}
	/*
	//notarios
	$sql= "SELECT id_persona, nombres, apellidos FROM personas WHERE tipo_rol = 'N'  ";
	$query = consulta($sql);
	$i=0;
	$notarios=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$notarios[]= array ('id'=>$row["id_persona"],
							'nombre'=> $row["apellidos"] . ' ' . $row["nombres"]);
		$i++;
	}
	$smarty->assign('notarios',$notarios);
	*/
	//recuperamos los tipos de documentos
	$sql= "SELECT id_tipo_documento, tipo FROM tipos_documentos ORDER BY tipo ";
	$query = consulta($sql);
	$tipodocs= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipodocs[]= array( 'id'		=> $row["id_tipo_documento"],
							'tipo'	=> $row["tipo"]);
	}
	$smarty->assign('tipodocs',$tipodocs);

	$smarty->display('informe_legal/poderes.html');
	die();

?>