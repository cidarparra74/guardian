<?php
/*
   contrato para ASISTENTE PLATAFORMA
*/
require_once("../lib/setup.php");
$smarty = new bd;
require_once('../lib/conexionSEC.php');
require_once('../lib/verificar.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//echo $glogin;
	//preparamos datos del usuario
	if(isset($_SESSION["USER"]) && $_SESSION["USER"]!=0){
		$USER = $_SESSION["USER"];
	}else{
		if(isset($_SESSION['glogin'])){
			$_SESSION["USER"]=$_SESSION['glogin'];
			$USER = $_SESSION['glogin'];
		}else{
			die("No se encontr&oacute; usuario con sesi&oacute;n activa!");
		}
	}
	//establecemos el tipo de contrato A=cja_ahorro, B=cta_cte
	if(isset($quien)){
		$_SESSION["quien"]=$quien;
	}else{
		$quien = $_SESSION["quien"];
	}
	
	//establecemos el tipo docuemnto word o open
	if(isset($_SESSION["tipodoc"])){
		$tipodoc = $_SESSION["tipodoc"];
	}//else no debiera ocurrir
	
	//definido en contratoslog.php (leido de tabla opciones)
	$smarty->assign('tipodoc',$tipodoc); 
	
	//vemos si son automaticos, si pued editar
	if(isset($_REQUEST["edit"])){
		$edita = $_REQUEST["edit"];
	}else{
		$edita = 's';
	}
	
		$edita = 'n';
	$smarty->assign('edita',$edita);
	
	//vemos si puede abrir en word
	//lo siguiente es para cuando entra a modificar el contrato:
	if(isset($_SESSION["word"])){
		$word = $_SESSION["word"];
	}else{
		// en teoria nunca entrara aqui
		$word = 'n';
		$_SESSION['word']=$word;
	}
	$smarty->assign('word',$word);

	
	//href
	$carpeta_entrar="_main.php?action=contratos_pla.php";
	$smarty->assign('carpeta_entrar',$carpeta_entrar);
	//form
	$carpeta_acc= "contratos_pla";
	$smarty->assign('carpeta_acc',$carpeta_acc);
	
	//vemos que grupo de ocntratos es (cja o  cta)
	if($quien =='A'){
		// es caja de ahorro
		$sql = "SELECT cta_ahorro as tipo FROM parametros_c ";
		
	}else{
		// es cta corriente
		$sql = "SELECT cta_corriente as tipo FROM parametros_c ";
	}
	//echo $sql;
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$tipo = $row["tipo"];
	//****************************************************************************************//
	
	//adicionar Paso 0, elegir contrato nuevo por primera vez
	if(isset($_REQUEST['adicionar'])){
		unset($_SESSION["partes"]);
		unset($_SESSION["principal"]);
		unset($_SESSION["idcontrato"]);
		unset($_SESSION['idfinal']);
		unset($_SESSION['incisos']);
		include("./contratos/adicionar_pla.php");
	}
	
	
	//adicionar Paso 2, seleccionar clausulas opcionales
	if(isset($_REQUEST['adicionar2'])){
		include("./contratos/adicionar2.php");
	}
	
	//adicionar Paso 3, seleccionar incisos
	if(isset($_REQUEST['adicionar3'])){
		include("./contratos/adicionar3.php");
	}
	
	//adicionar Paso 4, llenar variables
	if(isset($_REQUEST['adicionar4'])){
		include("./contratos/adicionar4.php");
	}
	
	//adicionar Paso 5, registrar partes
	if(isset($_REQUEST['adicionar5'])){
		include("./contratos/partes.php");
		die();
	}
	
	//adicionar Paso 6, generar contrato
	if(isset($_REQUEST['adicionar6'])){
		include("./contratos/armar.php");
		die();
	}
	
	//para variables especial
	if(isset($_REQUEST['variables_esp'])){
		include("./contratos/variables_esp.php");
		//include("./contratos/armar_esp0.php");
		die();
	}
	
	//para partes especial
	if(isset($_REQUEST['partes_esp'])){
		include("./contratos/partes_esp.php");
		die();
	}
	
	//adicionar Paso 6, generar contrato
	if(isset($_REQUEST['armar_esp'])){
		include("./contratos/armar_esp.php");
		die();
	}
	
	//adicionando
	if(isset($_REQUEST['adicionar_boton_x'])){
		include("./contratos/adicionando.php");
	}
	
	//modificar
	if(isset($_REQUEST['modificar'])){
		include("./contratos/modificar.php");
	}
	
	//modificando modificar_f
	if(isset($_REQUEST['modificar_p'])){
		include("./contratos/modpartes.php");
	}
	
	//modificando modificar_f
	if(isset($_REQUEST['modificar_f'])){
		include("./contratos/modarmar.php");
	}
	
	//eliminar
	if(isset($_REQUEST['eliminar'])){
		include("./contratos/eliminar.php");
	}
	
	//eliminando
	if(isset($_REQUEST['eliminar_boton_x'])){
		include("./contratos/eliminando.php");
	}
	
	//cancelando
	if(isset($_REQUEST['adicionar_boton_cancelar'])){
		unset($_SESSION['idcontrato']);
		unset($_SESSION['contrato']);
		unset($_SESSION['cantidad']);
		unset($_SESSION['idfinal']);
		unset($_SESSION['opcional']);
		unset($_SESSION['incisos']);
		unset($_SESSION['principal']);
		unset($_SESSION['partes']);
	}
	
	//firmando
	if(isset($_REQUEST['firmando'])){
		include("./contratos/firmando.php");
	}
	//firma
	if(isset($_REQUEST['firma'])){
		if($_REQUEST['firma']=='2')
		$etiqueta = '  Notificar  ';
		elseif($_REQUEST['firma']=='1')
		$etiqueta = 'Quitar Notificaci&oacute;n';
		elseif($_REQUEST['firma']=='3')
		$etiqueta = 'Firmar';
		elseif($_REQUEST['firma']=='4')
		$etiqueta = 'Quitar Firma';
		elseif($_REQUEST['firma']=='0')
		$etiqueta = 'Quitar Notificaci&oacute;n';
		
		include("./contratos/firma.php");
	}
	//firma
	if(isset($_REQUEST['gendoc'])){
		
		include("./contratos/generadoc.php");
	}
	
	//enviado correo
	if(isset($_REQUEST['enviarmail'])){
		
		include("./contratos/enviarmail.php");
	}
	
	
	//firma
	if(isset($_REQUEST['masopc'])){
		
		include("./contratos/vercontra.php");
	}
/****************fin de valores para la ventana*************************/
/***********************************************************************/

//recuperando los contratos del usuario
	/*
	$sql= "SELECT c.idcontrato, c.titulo, c.tipopersona
		 FROM contrato c, contratousuario cu
		 WHERE cu.idusuario = '$USER' AND cu.idcontrato=c.idcontrato AND
		 c.habilitado=1  AND c.codtipo = '$tipo' AND c.tipopersona <> 'J'
		 ORDER BY c.titulo";
		 */
	//echo $sql;
	$sql= "SELECT c.idcontrato, c.titulo, c.tipopersona
		 FROM contrato c
		 WHERE c.habilitado=1  AND c.codtipo = '$tipo' AND c.tipopersona <> 'J'
		 ORDER BY c.titulo";
	//echo $sql;
	$query = consulta($sql);
	$contratos=array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$contratos[]= array('id' => $row["idcontrato"],
							'titulo' => trim($row["titulo"]),
							'tipo' => $row["tipopersona"]);
	}

/**********************valores por defecto*************************/
/******************************************************************/


	$smarty->assign('contratos',$contratos);
	$smarty->assign('filas',count($contratos));
	$smarty->assign('quien',$quien);
	if($quien != 'C')
	$smarty->display('contratos_pla.html');
	//$smarty->display('contratos_ser.html');
	die();

?>