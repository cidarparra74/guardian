<?php	
	// inicializamos para casos de paginación
	//$pagina = $_REQUEST["pageno"];
	if (!$_REQUEST["pageno"]) {
		$inicio = 0;
		$pagina = 1;
		$_SESSION["mysql"] = $sql;
	}else{
		$pagina = $_REQUEST["pageno"];
		$inicio = ($pagina - 1) * NRO_REGISTROS_PAG;
		$sql = $_SESSION["mysql"];
	} 
	$total_paginas = 0;
	// Ejecutamos la consulta
	$query = consulta($sql);
	//contamos los registros resultantes
	$total_registros = contar($query);
	//vemos si habra paginacion
	if($total_registros > NRO_REGISTROS_PAG){
		// paginamos
		$sql = $sql ."  LIMIT $inicio, ".NRO_REGISTROS_PAG;
		$query = consulta($sql);
		$total_paginas = ceil($total_registros / NRO_REGISTROS_PAG);
		$pinicial = 1;
		//Total paginas excede al nro permitido?
		if($total_paginas > NRO_MAX_PAGINAS){
			//preparamos valores para botones de navegacion
			if($pagina-1>0){
				$pagPrevia = $pagina-1;
			}else{
				$pagPrevia = 0;
			}
			if($pagina+1<=$total_paginas){
				$pagSiguiente = $pagina+1;
			}else{
				$pagSiguiente = 0;
			}
			$smarty->assign('pagPrevia',$pagPrevia);
			$smarty->assign('pagSiguiente',$pagSiguiente);
			
			//para el vector de paginas
			//Vector para mostrar maximo 'NRO_MAX_PAGINAS' paginas a la vez
			$mitad = (int)(NRO_MAX_PAGINAS/2);
			if($pagina <= NRO_MAX_PAGINAS){
				$pinicial = 1;
			}elseif(($pagina - NRO_MAX_PAGINAS) > 0){
				if (($pagina - NRO_MAX_PAGINAS) <= NRO_MAX_PAGINAS)
					$pinicial += NRO_MAX_PAGINAS; 				
				else
					$pinicial = (floor($pagina / NRO_MAX_PAGINAS) * NRO_MAX_PAGINAS) + 1;				
			}
		}
		$npaginas = array();
		for($i=0;$i<NRO_MAX_PAGINAS;$i++){
			if($i+1 <= $total_paginas && $pinicial <= $total_paginas){
				$npaginas[$i] = $pinicial++;				
			}else{$npaginas[$i] = 0;}
		}

		$smarty->assign('npaginas',$npaginas);
	}else{$pinicial = 1;}
	$smarty->assign('total_paginas',$total_paginas);
	$smarty->assign('pagina',$pagina);	
?>