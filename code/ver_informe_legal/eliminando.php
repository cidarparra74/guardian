<?php


$id= $_REQUEST["id"];

if($acc == 'mv'){
		// movemos
		$sql= "UPDATE informes_legales SET estado='rec' WHERE id_informe_legal='$id' ";
		ejecutar($sql);
		//reportamos al ws caso baneco
		if($enable_ws == 'A'){
			//OBTENEMOS NRO CASO
			$sql= "SELECT nrocaso FROM informes_legales WHERE id_informe_legal='$id' ";
			$query = consulta($sql);
			$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
			$nrocaso= $resultado["nrocaso"];
			$estado = 0;
			require_once('ws_estadopro_baneco.php');
		}
}else{
		$cual= $_REQUEST["cual"];
		//para saber si existe el dato de Justificacion para elimiancion
			$justifica='';
			$id_us_elim='';
		if($cual=='ope'){
			$justifica=$_REQUEST["justifica"];
			$id_us_elim=$_SESSION["idusuario"];
		}
		//vemos si hay un informe legal elaborado
		//cuando borran desde recepcion normalmente no habra i.l.
		$sql="SELECT il.estado, case when il.bandera is null then '' else il.bandera end final, tb.bien
			FROM informes_legales il INNER JOIN tipos_bien tb 
			ON tb.id_tipo_bien = il.id_tipo_bien
			WHERE il.id_informe_legal='$id'";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$estado		= $resultado["estado"];
		$bandera	= $resultado["final"];
		//$bien		= $resultado["bien"];
		if($estado=='pub' or $estado=='npu' or ($estado=='ace' and $bandera!='') ){
			// existe un informe legal, lo guardamos e indicamos en el
			// campo 'bandera' que si tiene i.l.
			//guardamos el il, pero primero verificamos si no existe una copia previa
			//esto no deberia ocurir pero por actualizaciones se encontraron algunos casos
			$sql="SELECT id_informe_legal FROM informes_legales WHERE id_informe_legal='$id'";
			$query = consulta($sql);
			$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($resultado["id_informe_legal"]==$id){
				//ya existe!
				//CORREGIR LA SIGTE CONSULTA
				$sql="UPDATE informes_legales_bk  
						SELECT id_informe_legal  ,
						id_tipo_bien  ,
						id_propietario  ,
						GETDATE() as fecha_eliminacion,
						otras_observaciones  ,
						garantia_contrato  ,
						nota  ,
						conclusiones  ,
						numero_informe,
						tradicion  ,
						fecha_aceptacion  ,
						usr_acep  ,
						'i' as bandera,
						nrocaso ,
						'' as justifica,
						0 as id_us_elim					
						FROM informes_legales WHERE id_informe_legal='$id'";					
				//ejecutar($sql);
			}else{
				$sql="INSERT INTO informes_legales_bk  
						SELECT id_informe_legal  ,
						id_tipo_bien  ,
						id_propietario  ,
						GETDATE() as fecha_eliminacion,
						otras_observaciones  ,
						garantia_contrato  ,
						nota  ,
						conclusiones  ,
						numero_informe,
						tradicion  ,
						fecha_aceptacion  ,
						usr_acep  ,
						'i' as bandera,
						nrocaso ,
						'' as justifica,
						0 as id_us_elim					
						FROM informes_legales WHERE id_informe_legal='$id'";
				ejecutar($sql);
			}
			$sql="UPDATE informes_legales_bk 
				SET justifica='$justifica',
				id_us_elim='$id_us_elim'
				WHERE id_informe_legal='$id' ";
			ejecutar($sql);
		}else{
			// no tiene informe legal, pero guardamos la justificacion
			//guardamos el il, pero primero verificamos si no existe una copia previa
			//esto no deberia ocurir pero por actualizaciones se encontraron algunos casos
			$sql="SELECT id_informe_legal FROM informes_legales WHERE id_informe_legal='$id'";
			$query = consulta($sql);
			$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($resultado["id_informe_legal"]==$id){
				//ya existe!
				//CORREGIR LA SIGTE CONSULTA
				$sql="UPDATE informes_legales_bk  
						SELECT id_informe_legal  ,
						id_tipo_bien  ,
						id_propietario  ,
						GETDATE() as fecha_eliminacion,
						otras_observaciones  ,
						garantia_contrato  ,
						nota  ,
						conclusiones  ,
						numero_informe,
						tradicion  ,
						fecha_aceptacion  ,
						usr_acep  ,
						'x' as bandera,
						nrocaso ,
						'' as justifica,
						0 as id_us_elim					
						FROM informes_legales WHERE id_informe_legal='$id'";					
				//ejecutar($sql);
			}else{
				$sql="INSERT INTO informes_legales_bk  
						SELECT id_informe_legal  ,
						id_tipo_bien  ,
						id_propietario  ,
						GETDATE() as fecha_eliminacion,
						otras_observaciones  ,
						garantia_contrato  ,
						nota ,
						conclusiones  ,
						numero_informe,
						tradicion  ,
						fecha_aceptacion  ,
						usr_acep  ,
						'x' as bandera,
						nrocaso  ,
						'' as justifica,
						0 as id_us_elim 
						FROM informes_legales WHERE id_informe_legal='$id'";
				ejecutar($sql);
			}
			$sql="UPDATE informes_legales_bk 
				SET justifica='$justifica',
				id_us_elim='$id_us_elim'
				WHERE id_informe_legal='$id' ";
			ejecutar($sql);
			
			//borramos
			$sql= "DELETE FROM informes_legales_documentos WHERE id_informe_legal=$id ";
			ejecutar($sql);
			
			$sql= "DELETE FROM informes_legales_inmuebles WHERE id_informe_legal=$id ";
			ejecutar($sql);
			
			$sql= "DELETE FROM informes_legales_vehiculos WHERE id_informe_legal=$id ";
			ejecutar($sql);
			
			$sql= "DELETE FROM informes_legales_pj WHERE id_informe_legal=$id ";
			ejecutar($sql);
			
			$sql= "DELETE FROM informes_legales_propietarios WHERE id_informe_legal=$id ";
			ejecutar($sql);
		}
		
		//borramos los que no se necesitan
		//eliminando
		$sql= "DELETE FROM informes_legales WHERE id_informe_legal='$id' ";
		ejecutar($sql);
		
		$sql= "DELETE FROM documentos_informe WHERE din_inf_id=$id ";
		ejecutar($sql);
		
		$sql= "DELETE FROM informes_legales_fechas WHERE id_informe_legal=$id ";
		ejecutar($sql);
		//eliminando carpeta, si tiene
		$sql= "SELECT id_carpeta FROM carpetas WHERE id_informe_legal=$id ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$id_carpeta = $resultado["id_carpeta"];
		if($id_carpeta != ''){
			$sql= "DELETE FROM carpetas WHERE id_carpeta=$id_carpeta ";
			ejecutar($sql);
			$sql= "DELETE FROM documentos_propietarios WHERE id_carpeta=$id_carpeta ";
			ejecutar($sql);
			$sql= "DELETE FROM movimientos_carpetas WHERE id_carpeta=$id_carpeta ";
			ejecutar($sql);
		}
		
		
}

?>