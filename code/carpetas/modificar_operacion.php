<?php
	$sql = "SELECT TOP 1 enable_ws, enable_ncaso FROM opciones";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$enable_ws = $row["enable_ws"];
	
	$id= $_REQUEST['id'];
//cainstancia y cacuenta es para stock
	$sql= "SELECT ca.carpeta, ca.operacion, il.nrocaso, ca.suboperacion, ca.nrocaso as cainstancia,
	ofi.nombre as oficina, tb.tipo_bien, ca.id_informe_legal, il.instancia, ca.cuenta  as cacuenta
	FROM carpetas ca 
	INNER JOIN oficinas ofi ON ofi.id_oficina = ca.id_oficina
	INNER JOIN tipos_bien tb ON tb.id_tipo_bien = ca.id_tipo_carpeta
	LEFT JOIN informes_legales il ON il.id_informe_legal = ca.id_informe_legal
	WHERE id_carpeta='$id' ";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$smarty->assign('id',$id);
	$smarty->assign('carpeta',$resultado["carpeta"]);
	$smarty->assign('operacion',$resultado["operacion"]);
	$smarty->assign('suboperacion',$resultado["suboperacion"]);
	$smarty->assign('oficina',$resultado["oficina"]);
	$smarty->assign('tipo_bien',$resultado["tipo_bien"]);
	$id_inf = $resultado["id_informe_legal"];
		$filas = -1;
		$instancia = $resultado["instancia"];
		$operacion = $resultado["operacion"];
		$suboperacion = $resultado["suboperacion"];
		$nrocaso = $resultado["nrocaso"];
		
	if($id_inf!='' and $id_inf!='0'){
		
		if($operacion == '' || $instancia == '' || $operacion == '0' || $instancia == '0' ){
			//vemos con que operacion esta en el WS
				//require("ws_desembolso_bsol.php");  // por nro de caso
				require("ws_instancia_bsol.php");  // por numero de cuenta
				if(isset($lista)){
					$filas = count($lista);
					$smarty->assign('varios',$lista);
				}else{
					$filas = 0;
					$smarty->assign('operacionws','ws error');
					$smarty->assign('suboperacionws','ws error');
					$smarty->assign('nrocasows','ws error');
				}
			
			
		}//julio/2015
		else{
			if($instancia==$nrocaso and $operacion == ''){
				//son operaciones anteriores al cambio de instancia por cuenta
				//buscamos operacion por instancia
				require("ws_desembolso_bsol.php");  // por nro de caso/instancia
				if($operacion != ''){
					$lista[] = array( 'operacion' =>$operacion, 'instancia' =>$instancia, 'suboperacion' =>$suboperacion );
					$smarty->assign('varios',$lista);
				}
			}
		}
	}else{
		//sin informe legal, el stock
		
		$nrocaso = $resultado["cacuenta"];
		$instancia = $resultado["cainstancia"];
	}
	//
	$smarty->assign('instancia',$instancia);
	$smarty->assign('filas',$filas);
	$smarty->assign('enable_ws',$enable_ws);
	$smarty->assign('cuenta',$nrocaso);
	
	$smarty->display('carpetas/modificar_operacion.html');
	die();
?>
