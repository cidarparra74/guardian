<?php
require_once('../lib/fechas.php');
	
	$id= $_REQUEST["id"]; //es id id_carpeta

	//recuperando los datos de la carpeta
	$sql= "SELECT c.carpeta, t.tipo_bien, o.nombre, p.mis, p.nombres FROM carpetas c, tipos_bien t, oficinas o, propietarios p WHERE c.id_tipo_carpeta=t.id_tipo_bien AND c.id_oficina=o.id_oficina AND c.id_carpeta='$id' AND c.id_propietario=p.id_propietario ";
	$result= consulta($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$mostrar_propietario= "Propietario : ".$resultado["nombres"]."<br>MIS : ".$resultado["mis"];
	$mostrar_carpeta= "Tipo : ".$resultado["tipo_bien"]."<br>Of&nbsp;&nbsp;&nbsp;&nbsp;: ".$resultado["nombre"]."<br>Obs&nbsp;&nbsp;: ".$resultado["carpeta"];
	//$p_mis= $resultado["mis"];
	
	$fecha_actual= date("d-m-Y");
	
	$smarty->assign('id_carpeta',$id);
	$smarty->assign('fecha_dev',$fecha_actual);
	$smarty->assign('mostrar_propietario',$mostrar_propietario);
	$smarty->assign('mostrar_carpeta',$mostrar_carpeta);
				
	$smarty->display('carpetas/devolver_propietario.html');
	die();
?>
