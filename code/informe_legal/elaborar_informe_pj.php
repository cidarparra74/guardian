<?php
require_once("../lib/setup.php");
//$smarty = new bd;	 
require_once('../lib/conexionMNU.php');
require_once('../lib/verificar.php');
require_once("../lib/cargar_overlib.php");

	$id= $_REQUEST['id'];
	
	//leemos parametros especiales
	$sql= "SELECT TOP 1 il_estado_fin, enable_ws FROM opciones ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//vemos si se ppuede adicionar propietarios 

	$il_estado=explode(';',$resultado["il_estado_fin"]);
	$smarty->assign('il_estado',$il_estado);
	//para identificar que banco es
	$enable_ws = $resultado["enable_ws"];
	$smarty->assign('enable_ws',$enable_ws);
	
	
	//recuperamos los datos del informe legal
	$sql= "SELECT il.*, tb.tipo_bien, tb.bien, 
	pr.nombres, pr.ci, pr.direccion, pr.estado_civil, pr.nromatricula 
		FROM informes_legales  il 
		 INNER JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien 
		 LEFT JOIN propietarios pr ON pr.id_propietario = il.id_propietario 
		 WHERE il.id_informe_legal='$id' ";
	//echo $sql; 
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	//datos principales
	$id_tipo_bien   = $resultado['id_tipo_bien'];
	$id_us_comun	= $resultado['id_us_comun'];
	$id_titular		= $resultado['id_titular'];
	$tipo_bien		= $resultado["tipo_bien"];
	$bien			= $resultado["bien"];
	$bandera		= $resultado['bandera'];
	$cliente		= $resultado['nombres'];
	$ci_cliente		= $resultado['ci'];
	$id_propietario	= $resultado['id_propietario'];
	$direccion		= $resultado["direccion"];
	$matricula		= $resultado["nromatricula"];
	$motivo			= $resultado['motivo'];
	$tipoPer		= $resultado['estado_civil'];  //tipo de personeria: civil o comercial
	//$importe = explode(" ", $resultado["montoprestamo"]);

	if($resultado["fecha"]!= ""){
		$aux_c = explode(" ",$resultado["fecha"]);
		$fecha = dateDMESY(dateDMY($aux_c[0]));
	}else{
		$fecha = "";
	}
	if($id_propietario==""){
		$smarty->assign('isblank', '1');
	}else{
		$smarty->assign('isblank', '0');
	}
	
	$fecha_actual= date("d/m/Y");
	
	$smarty->assign('id',		   $id);
	$smarty->assign('id_us_comun', $id_us_comun);
	$smarty->assign('nrocaso', $resultado['nrocaso']);
	$smarty->assign('id_tipo_bien',$id_tipo_bien);
	$smarty->assign('bien',$tipo_bien);
	$smarty->assign('bandera',$bandera);
	$smarty->assign('cliente',     $cliente);
	$smarty->assign('ci_cliente',  $ci_cliente);
	$smarty->assign('id_propietario',  $id_propietario);
	$smarty->assign('direccion',		$direccion);
	$smarty->assign('fechaold',		$fecha);
	$smarty->assign('fecha',		$fecha_actual);
	$smarty->assign('motivo',       $motivo);
		
	// datos secundarios
	
	$otras_observaciones	= str_replace('<br />','',$resultado['otras_observaciones']);
	$conclusiones			= $resultado['conclusiones'];
	$numero_informe			= $resultado["numero_informe"];
	//se ha cambiado la tabla tipos_bien ara que soporte mas bienes ue los definidos, 
	//utilizamos campo 'bien' para definir si es Inmueble (1),  maquinaria (2) o Vehiculo (3)
	$tipo_bien = '5';
	$smarty->assign('otras_observaciones',$otras_observaciones);
	$smarty->assign('conclusiones',		  $conclusiones);
	$smarty->assign('numero_informe',	  $numero_informe);
	$smarty->assign('tipo_bien',	      $tipo_bien);
	
	
	//recpueramos los datos secundarios adicionales del informe legal
	$sql= "SELECT il.*, convert(varchar(10),fecha_vence,103) as fecha_venc 
	, convert(varchar(10),fecha_matri,103) as fecha_matr 
	, convert(varchar(10),fecha_escri,103) as fecha_escr 
		FROM informes_legales_pj  il WHERE il.id_informe_legal='$id' ";
	//echo $sql; 
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	if($resultado['matricula']!='') //tb tiene en tabla propietario una matricula
		$matricula	= $resultado['matricula']; //pero se da preferencia ala tabla i.l.pj
	
	$smarty->assign('tipo_sociedad',  $resultado['tipo_sociedad']);
	$smarty->assign('actividad',      $resultado["actividad"]); //actividad principal
	$smarty->assign('duracion',       $resultado['duracion']);
	$smarty->assign('direccion',       $resultado['direccion']);
	$smarty->assign('fecha_vence',    $resultado['fecha_venc']);
	$smarty->assign('fecha_matri',    $resultado['fecha_matr']);
	$smarty->assign('nro_escritura',  $resultado['nro_escritura']);
	$smarty->assign('fecha_escri',    $resultado['fecha_escr']);
	$smarty->assign('notario',        $resultado['notario']);
	$smarty->assign('matricula', $matricula);
	// para la nomina de directores
	$nomina = array();
	$nomina_dir = explode('|',$resultado['nomina_dir']);
	foreach($nomina_dir as $dir){
		if($dir != ''){
		$persona = explode(';',$dir);
		$nomina[] = array('nombre'=>$persona[0],
						   'cargo'=>$persona[1],
							  'ci'=>$persona[2]);
		}
	}
	$smarty->assign('nomina', $nomina);
	//****************************************************************************************************
	// REcuperamos los documentos correspondientes al tipo de bien y los que tenga ya guardados el I.L.
	$sql= " SELECT count(*) as ndocs FROM informes_legales_documentos
			WHERE id_informe_legal = $id ";
	$query = consulta($sql);

	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//para ver si NO_tomar _encuenta estara marcado o no; y si esprimera ves no lo estara
	if($row["ndocs"] > 0){$hay = 0;}else{$hay = 1;}
	//para ver si jalamos los docs del registro recepcion
	if($row["ndocs"] == 0){
		$sql= " SELECT din_doc_id, din_tip_doc, fojas FROM documentos_informe WHERE din_inf_id = $id ";
		$query = consulta($sql);
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$id_documento		= $row["din_doc_id"];
			$fojas				= $row["fojas"];
			$id_tipo_documento	= $row["din_tip_doc"];
			$sql = "INSERT INTO informes_legales_documentos 
					(id_informe_legal, id_documento, id_tipo_documento, fojas) VALUES 
					($id, '$id_documento', '$id_tipo_documento', '$fojas')";
			ejecutar($sql);
		}
	}
	$sql= " SELECT lista1.*, lista2.*
			FROM 
				(SELECT doc.id_documento as iddoc1, doc.documento, doc.vencimiento, doc.meses_vencimiento, tiene_fecha, con_numero
					FROM (documentos doc
					INNER JOIN tipos_bien_documentos tbd ON tbd.id_documento = doc.id_documento )
				WHERE tbd.id_tipo_bien = $id_tipo_bien) lista1
			LEFT JOIN 
				(SELECT id_documento as iddoc2, id_tipo_documento, numero, fecha, fojas, observaciones, 
				fecha_vencimiento, tiene_observacion, tomar_en_cuenta FROM informes_legales_documentos
				WHERE id_informe_legal = $id ) lista2
			ON lista1.iddoc1 = lista2.iddoc2 ORDER BY lista1.documento";

	$query = consulta($sql);
	
		
	$docus = array();
	$infor = array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$docus[$i] = array( 'id_documento'		=> $row["iddoc1"],
							'documento' 		=> $row["documento"],
							'vencimiento' 		=> $row["vencimiento"],
							'meses_vencimiento'	=> $row["meses_vencimiento"],
							'tiene_fecha' 		=> $row["tiene_fecha"],
							'con_numero' 		=> $row["con_numero"] );
		
		if($row["id_tipo_documento"] != null){
			//pueden haber algunos valores
			$ids_tipo_documento	= $row["id_tipo_documento"];
			$numero				= $row["numero"];
			$NO_tomar_en_cuenta	= 0;
			$observaciones		= $row["observaciones"];
			$fojas				= $row["fojas"];
			$tiene_observacion	= $row["tiene_observacion"];
		}else{
			// no hay ningun valor para informes_legales_documentos
			$ids_tipo_documento	= 0;
			$numero				= '';
			$NO_tomar_en_cuenta	= (1 - $hay);
			$observaciones		= '';
			$fojas				= 0;
			$tiene_observacion	= 0;
		}
		$aux_a = $row["fecha"];
		//echo $aux_a;
		if($aux_a != null){
			$aux_c		= explode(" ",$aux_a);
			$fechas= dateDMESY(dateDMY($aux_c[0]));
		}
		else{
			$fechas		="";
		}
		$aux_a= $row["fecha_vencimiento"];
		if($aux_a != null){
			$aux_c				= explode(" ",$row["fecha_vencimiento"]);
			$fechas_vencimiento = dateDMESY(dateDMY($aux_c[0]));
		}
		else{
			$fechas_vencimiento	="";
		}
		
		$infor[$i] = array( 'ids_tipo_documento'	=> $ids_tipo_documento,
							'numero' 				=> $numero,
							'NO_tomar_en_cuenta' 	=> $NO_tomar_en_cuenta,
							'tiene_observacion' 	=> $tiene_observacion,
							'fechas' 				=> $fechas,
							'fechas_vencimiento' 	=> $fechas_vencimiento,
							'fojas' 				=> $fojas,
							'observaciones' 		=> $observaciones);
		$i++;
	}
	$smarty->assign('cantidad_documentos',$i);
	$smarty->assign('docus',$docus);
	$smarty->assign('infor',$infor);

	// sociedades
	$sql= "SELECT * FROM sociedades ";
	if($tipoPer =='1' or $tipoPer == '2') $sql .= " WHERE tipo = '$tipoPer'";
	$query = consulta($sql);
	$sociedades=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$sociedades[] = array('id'  => $row["id_sociedad"],
								'sociedad' => $row["sociedad"]);
	}
	$smarty->assign('sociedades',$sociedades);
	
	//recuperamos los tipos de documentos
	$sql= "SELECT id_tipo_documento, tipo FROM tipos_documentos ORDER BY tipo ";
	$query = consulta($sql);
	$tipodocs= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipodocs[]= array( 'id'		=> $row["id_tipo_documento"],
							  'tipo'	=> $row["tipo"]);
	}
	$smarty->assign('tipodocs',$tipodocs);
	
	//poderes
	$sql= "SELECT po.id_poder, po.numero, po.fojas, td.tipo, convert(varchar(10),po.fecha,103) as fechap
		FROM poderes po 
		INNER JOIN tipos_documentos td ON td.id_tipo_documento  = po.id_tipo_documento
		WHERE po.id_informe_legal = '$id' ORDER BY po.fecha ";
	$query = consulta($sql);
	
	$poderes= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$poderes[]= array( 'id'		=> $row["id_poder"],
						  'numero'	=> $row["numero"],
						  'fecha'	=> $row["fechap"],
						  'tipo'	=> $row["tipo"],
						  'fojas'	=> $row["fojas"]);
	}
	$smarty->assign('poderes',$poderes);
	
	
	$smarty->display('informe_legal/elaborar_informe_pj.html');
	die();
	
?>
