<?php
// echo getcwd() . "\n";
 if(!file_exists("../lib/setupSEC.php"))
	 chdir("../../code");

require_once("../lib/setupSEC.php");
$smarty = new bd;	
//require_once('../lib/verificar.php');

function noexiste($tabla, $campo){
	//verifica si el $campo existe en la $tabla
	$ssql="SELECT sc.name FROM syscolumns AS sc INNER JOIN sysobjects AS so ON
	sc.id=so.id AND	sc.name='$campo' AND so.name='$tabla' ";
	$qquery= consulta($ssql);
	if($rrow = $qquery->fetchRow(DB_FETCHMODE_ASSOC)){
		$resulta = $rrow["name"];
	}else{
		$resulta = '0';
	}
	if($resulta==$campo)
		return false;
	else
		return true;
}

function longitud($tabla, $campo){
	//verifica si el $campo existe en la $tabla
	$ssql="SELECT character_maximum_length AS flen  FROM information_schema.columns  
			WHERE table_name = '$tabla' AND column_name = '$campo'";
	$qquery= consulta($ssql);
	if($rrow = $qquery->fetchRow(DB_FETCHMODE_ASSOC)){
		$resulta = $rrow["flen"];
	}else{
		$resulta = '0';
	}
	
	return $resulta;
}

if(isset($_REQUEST['proceder'])){
	$cambios = '';
	$cnt=0;
	
	// ************************************************ aqui las actualizaciones ******************
	
	//15/05/2015
	if(noexiste('clausula','descri')){
		$sql = "alter table clausula add descri varchar(200) default ''";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
		$sql = "update clausula set descri=titulo ";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//21/05/2015
	if(noexiste('vinculo','idcontrato')){
		$sql = "CREATE TABLE vinculo (idcontrato INT, idclausula INT, vinculo INT)";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>CREATE TABLE vinculo</strong><br />";
	}else{
		$cnt++;
	}

	//21/05/2015 -> para bisa contratos auto
	if(noexiste('RELACION','CI')){
		$sql = "CREATE TABLE RELACION(
	[CI] [varchar](12) NULL,
	[NROCASO] [varchar](20) NULL,
	[RELACION] [varchar](2) NULL,
	[USUARIO] [varchar](10) NULL,
	[DEVICE] [varchar](10) NULL,
	[FECHA] [datetime] NULL,
	[IPADD] [varchar](15) NULL,
	[PGMNAME] [varchar](10) NULL,
	[JOBNUM] [varchar](6) NULL
) ON [PRIMARY]";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>CREATE TABLE RELACION</strong><br />";
	}else{
		$cnt++;
	}
	
	//26/05/2015
	if(noexiste('contrato_final','contenido_rtf')){
		$sql = "ALTER TABLE contrato_final ADD  contenido_rtf ntext NULL";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//10/07/2015 **INDICA SI SE MUESTRA EL TITULO DE LA CLAUSULA EN EL CONTRATO	
	if(noexiste('rel_cc','sintitulo')){
		$sql = "ALTER TABLE rel_cc ADD sintitulo CHAR(1) NULL";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
		$sql= "UPDATE rel_cc SET sintitulo='N'";
		ejecutar($sql);
	}else{
		$cnt++;
	}
	
	//27/08/2015 **INDICA SI es una clausula que depende de la existencia de otra	
	if(noexiste('rel_cc','dependiente')){
		$sql = "ALTER TABLE rel_cc ADD dependiente CHAR(1) NULL";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
		$sql= "UPDATE rel_cc SET dependiente='N'";
		ejecutar($sql);
	}else{
		$cnt++;
	}
	
	//actualizaciones a la DB a partir del 01/09/2015. victor rivas BISA
	if(noexiste('persona','usuario')){ 
		$sql= "ALTER TABLE persona ADD [usuario] [varchar](10) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE persona ADD [device] [varchar](10) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE persona ADD [fecha] [datetime] NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE persona ADD [ipadd] [varchar](15) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE persona ADD [pgmname] [varchar](10) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE persona ADD [jobnum] [varchar](6) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		//$sql= "ALTER TABLE persona ADD [tipopp] [varchar](1) NULL";  
		//ejecutar($sql);
		//$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;	
	}
	
	
	//06/10/2015 **INDICA SI inicialmente se pedira si es persona NATURAL o JURIDICA	
	if(noexiste('contrato','tipopersona')){
		$sql = "ALTER TABLE contrato ADD tipopersona CHAR(1) DEFAULT '' ";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
		$sql= "UPDATE contrato SET tipopersona=''";
		ejecutar($sql);
	}else{
		$cnt++;
	}
	
	//06/10/2015 **INDICA SI es una clausula que se restringe segun tipo de persona natural o juridica	
	if(noexiste('rel_cc','tipopersona')){
		$sql = "ALTER TABLE rel_cc ADD tipopersona CHAR(1) NULL";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
		$sql= "UPDATE rel_cc SET tipopersona='-'";
		ejecutar($sql);
	}else{
		$cnt++;
	}
	
	//09/10/2015 **parametros para tipos de contratos	
	if(noexiste('parametros_c','cta_ahorro')){
		$sql = "CREATE TABLE parametros_c(
					[cta_ahorro] [INT] NULL,
					[cta_corriente] [INT] NULL,
					[conjunta] [INT] NULL,
					[indistinta] [INT] NULL,
					[servicios] [INT] NULL
				) ON [PRIMARY]";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>CREATE TABLE parametros_c</strong><br />";
	}else{
		$cnt++;
	}
	
	//13/10/2015 **parametros para tipos de contratos	
	if(noexiste('parametros_c','servadic')){
		$sql = "ALTER TABLE parametros_c ADD servadic INT NULL";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//13/10/2015 **parametros para tipos de contratos	
	if(noexiste('parametros_c','fallconj')){
		$sql = "ALTER TABLE parametros_c ADD fallconj INT NULL";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
		$sql = "ALTER TABLE parametros_c ADD fallindi INT NULL";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//14/10/2015 **parametros para tipos de contratos	
	if(noexiste('parametros_c','servtarindi')){
		$sql = "ALTER TABLE parametros_c ADD servtarindi INT NULL";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
		$sql = "ALTER TABLE parametros_c ADD servtarjeta INT NULL";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	
	
	// ********************************* hasta aqui ********************
	
	//ir poniendo arriba de esta linea las demas actualizaciones
	$smarty->assign('cambios',$cambios);
	$ok=1;
	
}else{
	$ok=0;
}
	$smarty->assign('ok',$ok);
	$smarty->display('adm/update_DB/update_DBsec.html');

die();
?>