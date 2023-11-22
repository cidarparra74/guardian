<?php


$id= $_REQUEST["id"];
$id_us_actual = $_SESSION["idusuario"];

	$sql="SELECT il.estado, case when il.bandera is null then '' else il.bandera end final, tb.bien
		FROM informes_legales il INNER JOIN tipos_bien tb 
		ON tb.id_tipo_bien = il.id_tipo_bien
		WHERE il.id_informe_legal='$id'";
	$query = consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$estado		= $resultado["estado"];
	$bandera	= $resultado["final"];
	//$bien		= $resultado["bien"];
	if($estado!='' ){
		// existe un informe legal, lo guardamos e indicamos en el
		// campo 'bandera' que si tiene i.l.
		//guardamos el il
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
		$sql="UPDATE informes_legales_bk 
			SET justifica='(I.L. original no eliminado, solo copiado)',
			id_us_elim='$id_us_actual'
			WHERE id_informe_legal='$id' ";
		ejecutar($sql);
	}
		echo "Copiado"
		
?>