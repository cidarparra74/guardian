<?php
//
//verificar si esta habilitado el WS
	$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	//$enable_ws = $row["enable_ws"];
	$smarty->assign('enable_ws',$row["enable_ws"]);
	
$armar_consulta = "";
$f_cl = $_REQUEST["f_cl"];
$f_ci = $_REQUEST["f_ci"];
if($f_cl != ""){
	$armar_consulta.= "AND cliente LIKE '%$f_cl%' ";
}
if($f_ci != ""){
	$armar_consulta.= "AND ci_cliente LIKE '%$f_ci%' ";
}

$recinto = $_SESSION['id_almacen'];
$id_oficina = $_SESSION['id_oficina'];
//con q carga estan actualmente los peritos?
$sql="SELECT pe.apellidos, pe.nombres, 
CASE WHEN ile.carga IS NULL THEN 0 ELSE  ile.carga END carga, pe.id_persona 
FROM  personas pe LEFT JOIN
(SELECT il.id_perito, count(*) as carga 
FROM informes_legales il
WHERE il.id_perito <> 0 AND il.fecha >= GETDATE()-30 GROUP BY il.id_perito) ile
ON ile.id_perito = pe.id_persona
WHERE tipo_rol='P' AND pe.id_oficina = '$recinto' ORDER BY carga";

$query = consulta($sql);
$peritos= array();
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
$peritos[]= array('id_perito'=>$row["id_persona"],
					'perito'=>$row["apellidos"].' '.$row["nombres"],
					'carga'=>$row["carga"],);
}

$solicitudes= array();
if(count($peritos)>0){
	$minimo = $peritos[0]['carga'];

	$sql= "SELECT id_informe_legal, cliente, nrocaso, tb.tipo_bien 
	FROM informes_legales il, tipos_bien tb, usuarios us
	WHERE il.id_tipo_bien = tb.id_tipo_bien AND id_perito=0 AND il.id_us_comun= us.id_usuario 
	AND us.id_oficina = '$id_oficina' AND tb.con_perito='S' $armar_consulta 
	ORDER BY id_informe_legal DESC";

	$query = consulta($sql);

	$ind=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		//vemos quien tiene igual o menor carga q $minimo
		foreach($peritos as $key=>$per){
			//echo $per['carga']
			if($per['carga']<$minimo){
				$minimo=$per['carga'];
				$ind=$key;
			}
		}
		$peritos[$ind]['carga'] += 1; 
		$minimo=$peritos[$ind]['carga'];
		$solicitudes[]= array('id_informe_legal'=>$row["id_informe_legal"],
								'sol_tipo_bien'=>$row["tipo_bien"],
								'sol_cliente'=>$row["cliente"],
								'nrocaso'=>trim($row["nrocaso"]),
								'id_perito'=>$peritos[$ind]['id_perito'],
								'perito'=>$peritos[$ind]['perito']);
	}
}
	$smarty->assign('solicitudes',$solicitudes);
	
	$smarty->display('informe_legal/asignar_todos.html');
	die();
	
?>