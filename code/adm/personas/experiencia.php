<?php
	
	// Todos los ABM requieren llamado a libreria de base de datos
	
require_once("../lib/setup.php");
$smarty = new bd;	
//require_once('../lib/conexionMNU.php');

	
	// cargarmos libreria propias de este modulo y variables locales
	

	//  MOSTRAR
	if(isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
		
	}else{die();}
$sql =  "SELECT apellidos, nombres FROM personas WHERE id_persona = '$id'";
$query= consulta($sql);
$row = $query->fetchRow(2);
if($row["apellidos"]!=''){
	$smarty->assign('nombres',$row["apellidos"].' '.$row["nombres"]);
	
}

//todas las garantias peritables mas las ya signadas a $id
$sql =  "SELECT  tb.tipo_bien, tb.id_tipo_bien, op.id_tipo_bien as tbien
		FROM tipos_bien tb  
		LEFT JOIN tipobien_persona op 
			ON  op.id_tipo_bien = tb.id_tipo_bien AND op.id_persona = '$id'
		WHERE tb.con_perito = 'S' 
		ORDER BY  tb.tipo_bien";
/*	echo "<pre>";
	echo $sql;
	echo "</pre>"; */  
	$query= consulta($sql);
	$tiposbien = array();
	$i=0;

	while($row = $query->fetchRow(2)){
		if($row["tbien"]!='')
			$asigna = 1;
		else
			$asigna = 0;
		$tiposbien[$i] = array('id'	  => $row["id_tipo_bien"],
							'nombre'  => $row["tipo_bien"],
							'tiene'	  => $asigna);
		$i++;
	}

	//pasamos datos principales al form
	$smarty->assign('tiposbien',$tiposbien);
	$smarty->assign('id',$id);
		
	$smarty->display('./adm/personas/experiencia.html');
	die();
?>
