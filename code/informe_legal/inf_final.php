<?php
require_once('../lib/fechas.php');
	$sql= "SELECT * FROM informes_legales WHERE id_informe_legal='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	
$id_oficina = $_SESSION["id_oficina"];

	$cliente= $resultado["cliente"];
	$id_tipo_bien= $resultado["id_tipo_bien"];
	$id_us_comun= $resultado["id_us_comun"];
	$inf_nro_esc= $resultado["inf_nro_esc"];
	$inf_nro_asi= $resultado["inf_nro_asi"];
	$inf_nro_mat= $resultado["inf_nro_mat"];
	$inf_fch_grav= $resultado["inf_fch_grav"];
	$inf_fch_esc= $resultado["inf_fch_esc"];
	$inf_nota= $resultado["inf_nota"];
	$inf_fch_ini= dateDMY($resultado["inf_fch_ini"]);
	$inf_fch_fin= dateDMY($resultado["inf_fch_fin"]);
	//verificamos fechas
	if($inf_fch_ini == '--'){
		$inf_fch_ini= '';
	}
	if($inf_fch_fin == '--'){
		$inf_fch_fin= '';
	}
	
	$moneda = 'Bs';
	$inf_gravmonto = 0;
	$inf_obs= $resultado["inf_obs"];		
	$id_notario= $resultado["id_notario"];
	$id_tramitador= $resultado["id_tramitador"];
	$monto= explode(' ',$resultado["inf_gravmonto"]);
	if($monto[0])
	$inf_gravmonto = $monto[0];
	if(isset($monto[1]))
	$moneda = $monto[1];
	$inf_plazo= $resultado["inf_plazo"];
	$nrocaso= $resultado["nrocaso"];
	$id_entidad= $resultado["id_entidad"];
	$montoprestamo = $resultado["montoprestamo"];

	if($tibien=='1' && $inf_nro_mat==''){
		//jalamos registro D.R. del I.L.
		//recpueramo los datos del reg d.r.  del inmueble
		$sql= "SELECT registro_dr FROM informes_legales_inmuebles WHERE id_informe_legal='$id' ";
		//echo $sql;
		$query = consulta($sql);
		
		if($resultado= $query->fetchRow(DB_FETCHMODE_ASSOC))
			$inf_nro_mat = $resultado['registro_dr'];
	}
	//vemos si hay numero de escritura para buscar y si es reg nuevo
	/*
	if($id_notario == '' && $id_tramitador == '' && isset($_nro_esc)){
		$sql= "SELECT inf_fch_esc, id_notario, id_tramitador, inf_gravmonto, inf_plazo FROM informes_legales WHERE inf_nro_esc='$_nro_esc' ";
		//echo $sql;
		$query = consulta($sql);
		if($resultado= $query->fetchRow(DB_FETCHMODE_ASSOC){
			$inf_nro_esc= $_nro_esc;
			$inf_fch_esc= $resultado["inf_fch_esc"];
			$id_notario= $resultado["id_notario"];
			$id_tramitador= $resultado["id_tramitador"];
			$inf_gravmonto= $resultado["inf_gravmonto"];
			$inf_plazo= $resultado["inf_plazo"];
			$montoprestamo = $resultado["montoprestamo"];
		}
	}
	*/
	//recuperando tramitadores
	$sql= "SELECT id_persona, nombres, apellidos FROM personas WHERE tipo_rol = 'T' ";
	$query = consulta($sql);
	$i=0;
	$tramitadores=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tramitadores[]= array ('id' => $row["id_persona"],
								'nombre' => $row["apellidos"] . ' ' . $row["nombres"]);
		$i++;
	}
	//notarios
	$sql= "SELECT pe.id_persona, pe.nombres, pe.apellidos 
	FROM personas pe
	INNER JOIN oficina_persona op 
				ON id_responsable = pe.id_persona
				WHERE pe.tipo_rol = 'N' AND op.id_oficina = $id_oficina ";
	$query = consulta($sql);
	$i=0;
	$notarios=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$notarios[]= array ('id'=>$row["id_persona"],
							'nombre'=> $row["apellidos"] . ' ' . $row["nombres"]);
		$i++;
	}
// Aseguradoras 
$sql= "SELECT * FROM entidades";
	$query = consulta($sql);
	$i=0;
	$entidades=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$entidades[]= array ('id'=>$row["id"],
							'entidad'=> $row["entidad"]);
		$i++;
	}
	$smarty->assign('entidades',$entidades);
	
	$smarty->assign('tramitadores',$tramitadores);
	$smarty->assign('notarios',$notarios);
	
	$smarty->assign('inf_nro_esc',$inf_nro_esc);
	$smarty->assign('inf_nro_asi',$inf_nro_asi);
	$smarty->assign('inf_nro_mat',$inf_nro_mat);
	$smarty->assign('inf_fch_grav',$inf_fch_grav);
	$smarty->assign('inf_fch_esc',$inf_fch_esc);
	$smarty->assign('inf_nota',$inf_nota);
	$smarty->assign('inf_fch_ini',$inf_fch_ini);
	$smarty->assign('inf_fch_fin',$inf_fch_fin);
	$smarty->assign('tibien',$tibien);
	$smarty->assign('inf_obs',$inf_obs);
	$smarty->assign('id_notario', $id_notario);
	$smarty->assign('id_tramitador', $id_tramitador);
	$smarty->assign('id',$id);
	$smarty->assign('cliente',$cliente);
	$smarty->assign('id_tipo_bien',$id_tipo_bien);
	$smarty->assign('inf_gravmonto',$inf_gravmonto);
	$smarty->assign('moneda',$moneda);
	$smarty->assign('inf_plazo',$inf_plazo);
	$smarty->assign('nrocaso',$nrocaso);
	$smarty->assign('id_entidad',$id_entidad);
	$smarty->assign('montoprestamo',$montoprestamo);

	$smarty->display('informe_legal/inf_final.html');
	die();

?>