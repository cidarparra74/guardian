<?php

	/*******/
   /*****    USAMOS adicionar.php  PARA ADICION Y MODIFICACION A LA VEZ. VICTOR RIVAS */
  /*******/

if(isset($id)){
		// RECUPERAMOS DATOS DEL INF LEGAL
		$sql = "SELECT il.id_tipo_bien, pr.nombres as cliente , pr.ci, pr.emision, 
				il.numero_informe,  il.nrobien,  
				il.motivo, il.fecha_recepcion, il.nrocaso, il.noportunidad, il.inf_agencia
				FROM informes_legales il 
				inner join propietarios pr on pr.id_propietario = il.id_propietario
				WHERE il.id_informe_legal = $id " ;
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
//		$importe = explode(" ", $row["montoprestamo"]);montoprestamo,
		$smarty->assign('vertodo','S');
		$smarty->assign('id', $id);
		$smarty->assign('cliente', $row["cliente"]);
		$smarty->assign('ci_cliente', $row["ci"]);
		$smarty->assign('emision', $row["emision"]);
		//$smarty->assign('id_tipo_id', $row["id_tipo_identificacion"]);id_tipo_identificacion,
		$smarty->assign('id_tipo_bien', $row["id_tipo_bien"]);
		$smarty->assign('motivo', $row["motivo"]);
		$smarty->assign('nrobien', $row["nrobien"]);
		$smarty->assign('nrocaso', $row["nrocaso"]);
		$smarty->assign('recepcionadox', $row["inf_agencia"]);
		$smarty->assign('noportunidad', $row["noportunidad"]);
		$smarty->assign('alerta','NO');
		//vemos la clase de bien
		$sql = "SELECT bien FROM tipos_bien WHERE id_tipo_bien = ".$row["id_tipo_bien"] ;
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$bien = $row["bien"];
}else{
		//estamos adicionando
		if(!$id_propietario>0) die("id Propietario no existe");
		$smarty->assign('vertodo','S');
		$smarty->assign('id', '0');
		$smarty->assign('cliente',$nombres); //para bsol
		$smarty->assign('ci_cliente', $ci_cliente);
		$smarty->assign('emision', $emision);
		$smarty->assign('id_propietario', $id_propietario);
		if(isset($nrocaso)){ //en caso de bsol es cuenta o cero si existe nro de oportunidad, caso BECO es nrocaso nomas
			$smarty->assign('nrocaso',$nrocaso); 
			if(isset($noportunidad)) 
					$smarty->assign('noportunidad',$noportunidad); 
				else
					$smarty->assign('noportunidad','0'); 
		}else{
			$smarty->assign('nrocaso',''); //caso BISA
			$nrocaso = '';
			}

		//vemos I.L. anteriores de este usuario y para este propietario  ile.cliente,
		$sql = "SELECT ile.id_informe_legal,  tb.tipo_bien, tb.con_inf_legal, us.nombres , 
				ile.fecha_recepcion, ile.estado, ile.nrobien, ile.nrocaso
				FROM informes_legales ile 
				INNER JOIN usuarios us ON us.id_usuario  =ile.id_us_comun 
				INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina 
				INNER JOIN tipos_bien tb ON tb.id_tipo_bien=ile.id_tipo_bien 
						INNER JOIN propietarios pr on pr.id_propietario = ile.id_propietario
				WHERE ofi.id_oficina = (select o1.id_oficina FROM oficinas o1 
						INNER JOIN usuarios u1 on o1.id_oficina = u1.id_oficina 
				WHERE u1.id_usuario = '$id_us_actual') AND ile.id_propietario = '$id_propietario'
				ORDER BY ile.id_informe_legal DESC";
		//echo $sql;
		$query = consulta($sql);
		$anteriores = array();
		$mismo = '0';
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$aux= $row["fecha_recepcion"];
			$aux_1= explode(" ",$aux);
			$aux=dateDMESY(dateDMY($aux_1[0]));
			$anteriores[] = array('id_inf' => $row["id_informe_legal"],
			'tbien' => $row["tipo_bien"],
			'con_il' => $row["con_inf_legal"],
			'nombu' => $row["nombres"],
			'estado' => $row["estado"],
			'nrobien' => $row["nrobien"],
			'nrocaso' => $row["nrocaso"],
			'fecha' => $aux);
			if($row["nrocaso"]==$nrocaso && $nrocaso!='') $mismo='1';
		}
		
		$smarty->assign('mismo', $mismo);
		$smarty->assign('anteriores', $anteriores);
		$smarty->assign('alerta','OK');
		
		$bien = '0';
}	

	//recuperando los tipos de bien
	if(isset($cat)){
		if(isset($_SESSION["id_banca"]) and $_SESSION["id_banca"] > 0 and $cat!='0'){
			$id_banca = $_SESSION["id_banca"];
			$sql= "SELECT * FROM tipos_bien 
			WHERE con_recepcion = 'S' AND categoria = '$cat' AND id_banca = $id_banca 
			ORDER BY tipo_bien ";
		}else{
			if($enable_ws != 'S'){
				$sql= "SELECT * FROM tipos_bien WHERE con_recepcion = 'S' AND categoria = '$cat' ORDER BY tipo_bien ";
			}else{
				//si esta modificando filtrar los mismos tipos de bien
				if($bien!='0'){
					$sql= "SELECT * FROM tipos_bien WHERE con_recepcion = 'S' AND bien = $bien AND categoria = '$cat' ORDER BY tipo_bien ";
				}else{
					$sql= "SELECT * FROM tipos_bien WHERE con_recepcion = 'S' AND categoria = '$cat' ORDER BY tipo_bien ";
				}
			}
		}
	}else{
		//si esta modificando filtrar los mismos tipos de bien
		if($bien!='0'){
		$sql= "SELECT * FROM tipos_bien WHERE con_recepcion = 'S' AND bien = $bien ORDER BY tipo_bien ";
		}else{
		$sql= "SELECT * FROM tipos_bien WHERE con_recepcion = 'S' ORDER BY tipo_bien ";
		}
		$cat = 0;
	}
	//echo $sql;
	$query = consulta($sql);
	$tiposbien=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tiposbien[]= array('id' => $row["id_tipo_bien"],
							'descri' => $row["descripcion"]);
	}
	$smarty->assign('tiposbien',$tiposbien);
	
	$objetos=array();
	if($enable_ws == 'S'){
		//bsol usa los motivos  predefinidos
		$sql= "SELECT * FROM objetos ORDER BY objeto ";
		$query = consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$objetos[]= array('id' => $row["id_objeto"],
								'descri' => $row["objeto"]);
		}
	}
	$smarty->assign('objetos',$objetos);
	
	
	$smarty->assign('cat',$cat);
	
	$smarty->display('ver_informe_legal/adicionar.html');
	die();

?>
