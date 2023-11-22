<?php

	$id= $_REQUEST['id'];
	
	//recuperando los datos de la carpeta
	$sql= "SELECT c.carpeta, t.tipo, o.nombre FROM carpetas c, tipos_bien t, oficinas o WHERE c.id_tipo_carpeta=t.id_tipo_bien AND c.id_oficina=o.id_oficina AND c.id_carpeta='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$mostrar_carpeta= "Tipo : ".$resultado["tipo_bien"]."<br>Of&nbsp;&nbsp;&nbsp;&nbsp;: ".$resultado["nombre"]."<br>Obs&nbsp;&nbsp;: ".$resultado["carpeta"];
			
	//usuarios del sistema
	//id del usuario actual
	$login_acc= $_SESSION['nombreUsu'];
	$password_acc= md5($_SESSION['passwordUsu']);
	$sql= "SELECT id_usuario FROM usuarios WHERE login='$login_acc' AND password='$password_acc' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_us_actual= $resultado["id_usuario"];
///AND id_perfil!='1'
	$sql= "SELECT id_usuario, nombres FROM usuarios WHERE id_usuario!='$id_us_actual'  ORDER BY nombres ";
	$query = consulta($sql);
	$ids_usuario= array();
	$nombres= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_usuario[$i]= $row["id_usuario"];
		$nombres[$i]= $row["nombres"];
		
		$i++;
	}
	
	//recuperando los datos del prestamo
	$sql= "SELECT * FROM movimientos_carpetas WHERE id_carpeta='$id' AND flujo='0' AND id_estado='4' ";
	//echo "$sql <br>";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id_movimiento= $resultado["id_movimiento_carpeta"];
	$aux_fecha= $resultado["arch_corr_plazo"];
	$aux_a= explode(" ",$aux_fecha);
	$plazo_fecha= $aux_a[0];
	$plazo_hora= $aux_a[1];
	$id_us_corriente= $resultado["id_us_corriente"];
	$observacion= $resultado["obs_4"];
	
	
	$smarty->assign('id_movimiento',$id_movimiento);
	$smarty->assign('plazo_fecha',$plazo_fecha);
	$smarty->assign('plazo_hora',$plazo_hora);
	$smarty->assign('id_us_corriente',$id_us_corriente);
	$smarty->assign('observacion',$observacion);
	
	
	$smarty->assign('ids_usuario',$ids_usuario);
	$smarty->assign('nombres',$nombres);
	
	$smarty->assign('mostrar_carpeta',$mostrar_carpeta);
	$smarty->assign('id',$id);
			
	$smarty->display('../templates/carpetas/eliminar_prestamo.html');
	die();
?>
