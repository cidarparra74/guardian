<?php
require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/verificar.php');


	//cargando para el overlib
//	include("../lib/cargar_overlib.php");
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton'])){
		$id_perfil_abo = $_REQUEST["id_perfil_abo"];
		$id_perfil_ope = $_REQUEST["id_perfil_ope"];
		$id_perfil_cat = $_REQUEST["id_perfil_cat"];
		$enable_mail = $_REQUEST["enable_mail"];
		$enable_ws = $_REQUEST["enable_ws"];
		$enable_mis = $_REQUEST["enable_mis"];
		$enable_ilsin = $_REQUEST["enable_ilsin"];
		$enable_prop = $_REQUEST["enable_prop"];
		$enable_login = $_REQUEST["enable_login"];
		$logo01 = $_REQUEST["logo01"];
		$il_estado_fin = $_REQUEST["il_estado_fin"];
		
		$rep_recepcion = $_REQUEST["rep_recepcion"];
		$mailSender = $_REQUEST["mailSender"];
		
		$enable_deldoc = $_REQUEST["enable_deldoc"];
		$autosolicita = $_REQUEST["autosolicita"];
		$trasladar = $_REQUEST["trasladar"];
		$rutatmp = $_REQUEST["rutatmp"];
		$rutadoc = $_REQUEST["rutadoc"];
		$extension = $_REQUEST["extension"];
		$tipodoc = $_REQUEST["tipodoc"];
		
		$url1 = $_REQUEST["url1"];
		$url2 = $_REQUEST["url2"];
		$url3 = $_REQUEST["url3"];
		$url4 = $_REQUEST["url4"];
		$url5 = $_REQUEST["url5"];
		$smtp = $_REQUEST["smtp"];
		
		$long_login = $_REQUEST["long_login"];
		$long_pass = $_REQUEST["long_pass"];
		$enable_catofi = $_REQUEST["enable_catofi"];
		
		$sql = "UPDATE [opciones]
				SET [enable_mail] = '$enable_mail'
				,[enable_ws] = '$enable_ws'
				,[enable_mis] = '$enable_mis'
				,[enable_ilsin] = '$enable_ilsin'
				,[ws_url1] = '$url1'
				,[ws_url2] = '$url2'
				,[mail_smtp] = '$smtp'
				,[enable_prop] = '$enable_prop'
				,[enable_login] = '$enable_login'
				,[ws_url3] = '$url3'
				,[logo01] = '$logo01'
				,[ws_url4] = '$url4'
				,[ws_url5] = '$url5'
				,[il_estado_fin] = '$il_estado_fin'
				,[rep_recepcion] = '$rep_recepcion'
				,[mail_remite] = '$mailSender'
				,[id_perfil_abo] = '$id_perfil_abo'
				,[id_perfil_ope] = '$id_perfil_ope'
				,[id_perfil_cat] = '$id_perfil_cat'
				,[long_login] = '$long_login'
				,[long_pass] = '$long_pass'
				,[enable_deldoc] = '$enable_deldoc'
				,[autosolicita] = '$autosolicita' 
				,[trasladar] = '$trasladar'
				,[rutatmp] = '$rutatmp' 
				,[rutadoc] = '$rutadoc' 
				,[extension] = '$extension' 
				,[tipodoc] = '$tipodoc' 
				,[enable_catofi] = '$enable_catofi'";
				
		ejecutar($sql);
		$disable = 'readonly';
		$ok='S';
	}else{
	
		/***************************************************************/
		//valores por defecto
		/***************************************************************/
		
	$sql= "select * from perfiles where activo = 'S'"; 
	$query= consulta($sql);
	$i=0;
	$perfiles = array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$perfiles[$i]= array('id' => $row["id_perfil"],
							'perfil' => $row["perfil"]);
		$i++;
	}
		$smarty->assign('perfiles',$perfiles);
		
		
		$ok='N';
		$sql = "SELECT * FROM opciones ";
		$query= consulta($sql);
		if($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$enable_mail = $row["enable_mail"];
			$enable_ws = $row["enable_ws"];
			$enable_mis = $row["enable_mis"];
			$enable_ilsin = $row["enable_ilsin"];
			$enable_prop = $row["enable_prop"];
			$enable_login = $row["enable_login"];
			$enable_ncaso = $row["enable_ncaso"];
			$url1 = $row["ws_url1"];
			$url2 = $row["ws_url2"];
			$url3 = $row["ws_url3"];
			$url4 = $row["ws_url4"];
			$url5 = $row["ws_url5"];
			$smtp = $row["mail_smtp"];
			$logo01 = $row["logo01"];
			$il_estado_fin = $row["il_estado_fin"];
			$rep_recepcion = $row["rep_recepcion"];
			$mailSender = $row["mail_remite"];
			$id_perfil_abo = $row["id_perfil_abo"];
			$id_perfil_ope = $row["id_perfil_ope"];
			$id_perfil_cat = $row["id_perfil_cat"];
			$long_login = $row["long_login"];
			$long_pass = $row["long_pass"];
			$enable_deldoc = $row["enable_deldoc"];
			$autosolicita = $row["autosolicita"];
			$trasladar = $row["trasladar"];
			$rutatmp = $row["rutatmp"];
			$rutadoc = $row["rutadoc"];
			$extension = $row["extension"];
			$tipodoc = $row["tipodoc"];
			$enable_catofi = $row["enable_catofi"];
			$disable = '';
		}else{
			$enable_mail = 'S';
			$enable_ws = 'S';
			$enable_mis = 'N';
			$enable_ilsin = 'S';
			$enable_prop = 'S';
			$disable = '';
			$il_estado_fin = 'ROJO;AMARILLO;VERDE';
			$rep_recepcion = 'imprimiendo_recepcion.html';
			$mailSender = 'guardian@noreply.com';
			$long_login = 0;
			$long_pass = 0;
			$enable_catofi = 0;
			
			$sql = "INSERT INTO [opciones]
           ([enable_mail]
           ,[enable_ws]
           ,[enable_mis]
		   ,[enable_ilsin]
           ,[enable_prop]
           ,[enable_login]
           ,[enable_ncaso]
           ,[il_estado_fin]
		   ,[rep_recepcion]
		   ,[mail_remite]
		   ,[long_login]
		   ,[long_pass]
		   ,[enable_deldoc]
		   ,[autosolicita]
		   ,[trasladar]
		   ,[tipodoc]
		   ,[enable_catofi])
			VALUES
           ('N'
           ,'N'
           ,'S'
		   ,'S'
           ,'S'
           ,'N'
           ,'N'
           ,'ROJO;AMARILLO;VERDE'
		   ,'imprimiendo_recepcion.html'
		   ,'guardian@noreply.com'
		   ,'0'
		   ,'0'
		   ,'N'
		   ,'N'
		   ,'N'
		   ,'W'
		   ,'N')";
			
			ejecutar($sql);
			//echo $sql;
		}
	}
	//
	$smarty->assign('ok',$ok);
	$smarty->assign('url1',$url1);
	$smarty->assign('url2',$url2);
	$smarty->assign('url3',$url3);
	$smarty->assign('url4',$url4);
	$smarty->assign('url5',$url5);
	$smarty->assign('smtp',$smtp);
	$smarty->assign('enable_mail',$enable_mail);
	$smarty->assign('disable',$disable);
	$smarty->assign('enable_ws',$enable_ws);
	$smarty->assign('enable_mis',$enable_mis);
	$smarty->assign('enable_ilsin',$enable_ilsin);
	$smarty->assign('enable_prop',$enable_prop);
	$smarty->assign('enable_login',$enable_login);
	$smarty->assign('mailSender',$mailSender);
	$smarty->assign('logo01',$logo01);
	$smarty->assign('il_estado_fin',$il_estado_fin);
	$smarty->assign('rep_recepcion',$rep_recepcion); 
	$smarty->assign('id_perfil_abo',$id_perfil_abo);
	$smarty->assign('id_perfil_ope',$id_perfil_ope);
	$smarty->assign('id_perfil_cat',$id_perfil_cat);
	$smarty->assign('long_login',$long_login);
	$smarty->assign('long_pass',$long_pass);
	$smarty->assign('enable_deldoc',$enable_deldoc);
	$smarty->assign('autosolicita',$autosolicita);
	$smarty->assign('trasladar',$trasladar);
	$smarty->assign('rutatmp',$rutatmp);
	$smarty->assign('rutadoc',$rutadoc);
	$smarty->assign('extension',$extension);
	$smarty->assign('tipodoc',$tipodoc);
	$smarty->assign('enable_catofi',$enable_catofi);
	
	$smarty->display('adm/opciones/opciones.html');
	die();
?>