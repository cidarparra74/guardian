<?php
//esto para la primera vez que ingresa a crear un contrato
	$id = $_REQUEST['id'];
	$firma = $_REQUEST['firma'];
	if($firma=='3')
		$firma = '1';
	elseif($firma=='4')
		$firma = '0';
	$sql= "SELECT co.titulo, 
(CASE WHEN PATINDEX('%<personas>%', cf.contenido_sec) > 0 THEN substring(cf.contenido_sec, patindex('%<nombre>%', cf.contenido_sec)+ 8, (patindex('%</nombre>%', cf.contenido_sec)-patindex('%<nombre>%', cf.contenido_sec)-8)) ELSE '' END) cliente,
CONVERT(VARCHAR(10), cf.fechahora, 103) AS fecha , 
CONVERT(VARCHAR(10), cf.fechahora, 108) AS hora, 
cf.firmado,
cf.ultimo_login AS modifica
FROM contrato_final cf LEFT JOIN contrato co 
ON cf.idcontrato = co.idcontrato 
WHERE idfinal ='$id'"; 
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('etiqueta', $etiqueta);
	$smarty->assign('titulo', $row["titulo"]);
	$smarty->assign('cliente', $row["cliente"]);
	$smarty->assign('fecha', $row["fecha"]);
	$smarty->assign('hora', substr($row["hora"],0,5));
	$smarty->assign('firma', $firma);
	$smarty->assign('modifica', $row["modifica"]);

	$smarty->assign('id',$id);
	
	//para correo de notificacion
	if($etiqueta=='  Notificar  '){
		//jalamos usuarios de la regional q tengan correo
		require('../lib/conexionMNU.php');
		$sql= "SELECT id_perfil_abo FROM opciones"; 
		$query = consulta($sql);
		$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
		$id_perfil_abo = $row["id_perfil_abo"];
		if($id_perfil_abo =='') $smarty->assign('alert',"Falta configurar el perfil abogado.");
		
		$id_almacen = $_SESSION['id_almacen'];
		$sql= "SELECT id_usuario, us.nombres FROM usuarios us
		INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina
		WHERE us.correoe <> '' AND ofi.id_almacen='$id_almacen' AND us.id_perfil = '$id_perfil_abo' "; 
		$query = consulta($sql);
		$usuarios = array();
		while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$usuarios[] = array('id'=>$row["id_usuario"], 
			'nombre'=>trim($row["nombres"])); 
		}
		require('../lib/conexionSEC.php');
		$smarty->assign('usuarios',$usuarios);
	}
	
	$smarty->display('contratos/firma.html');
	die();
	
?>
