<?php
//session_start();
//lo siguiente es importante para que el campo contenido_sec sea leido correctamente
ini_set('odbc.defaultlrl','1048576');

		$idfinal = $_REQUEST['id'];
		unset($_SESSION['contrato']);
		unset($_SESSION['cantidad']);
		unset($_SESSION['principal']);
		unset($_SESSION['partes']);
		//buscamos el contrato 
	
		$sql= "SELECT f.contenido_sec, c.titulo, f.idcontrato, 
			patindex('%</xs:schema>%', f.contenido_sec)+ 12 as inicio  
		FROM contrato_final f LEFT JOIN contrato c
		ON f.idcontrato = c.idcontrato 
		 WHERE f.idfinal='$idfinal'";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		
		$inicio = $row['inicio'];
		$contenido_sec = $row['contenido_sec'];
		$contrato = $row['titulo'];
		$_SESSION['idcontrato'] = $row['idcontrato'];
		$_SESSION['idfinal'] = $idfinal;
		//hasta donde leer
		$final = strpos($contenido_sec, "</NewDataSet>")-1;
		$longitud = $final - $inicio ;
		
		$contenidoXml = substr($contenido_sec, $inicio, $longitud);
		
		$contenidoXml = "<?xml version=\"1.0\"?>\n".$contenidoXml."\n  </lasper>";
		
	/*	strpos(cadena1, cadena2). Busca la cadena2 dentro de cadena1 indicándonos la posición en la que se encuentra. 
		str_replace(cadena1, cadena2, texto). Reemplaza la cadena1 por la cadena2 en el texto */
		
		//agrupamos clausulas
		$contenidoXml = str_replace("</contrato>", "</contrato>\n<lasclau>", $contenidoXml);
		$inicio = strpos($contenidoXml, "<variables>");
		$limite = strlen($contenidoXml) - $inicio + 12;

		$contenidoXml = substr_replace($contenidoXml,"</lasclau>\n  <lasvar>\n    ", $inicio, -$limite);
		
		$inicio = strpos($contenidoXml, "<personas>");
		$limite = strlen($contenidoXml) - $inicio + 11;
		$contenidoXml = substr_replace($contenidoXml,"</lasvar>\n  <lasper>\n    ", $inicio, -$limite);
/*	
	$arxiu="archivo2.txt";
	$f = fopen($arxiu,"w");
	fputs($f,$contenidoXml);
	fclose($f);
*/
		
		//procesamos solo variables
		$inicio = strpos($contenidoXml, "<lasvar>");
		$final = strpos($contenidoXml, "</lasvar>")+9;
		$longitud = $final - $inicio ;
		$variablesXML = substr($contenidoXml, $inicio, $longitud);
		$doc = new DOMDocument('1.0', 'UTF-8');
		if(!$doc->loadXML(utf8_encode($variablesXML))) {
		//if(!$doc->loadXML($variablesXML)) {
			echo "ERROR EN VARIABLES";
		}
		$idtexto=''; //para mas abajo en tablas
		//$duplicados = array(); //para casos en q se duplica la var
		//$lasclausulas = '0'; //para ver que clausulas tiene el contrato, usado en modpartes.php
		$nodos = $doc->getElementsByTagName( "variables" );
		$variables=array();
		foreach( $nodos as $variable ){
			$idclausulas = $variable->getElementsByTagName( "idclausula" );
			$idvariables = $variable->getElementsByTagName( "idvariable" );
			$contenidos = $variable->getElementsByTagName( "contenido" );
			$tipodato = $variable->getElementsByTagName( "tipo" ); //para ver si es var o tabla
			//$lasclausulas .= ','.$idclausulas;
			//para el tipo lo sacamos de la db, ya que el tipo de nodo se refiere a 0=var 1=tabla
			//tipo es tipo de variable
			$sql = "SELECT esglobal, eslista, lineas, tipo FROM var_texto WHERE idtexto = '".$idvariables->item(0)->nodeValue."'";
			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($tipodato->item(0)->nodeValue=='0'){
				$desctextos = $variable->getElementsByTagName( "desctexto" );
				//ver si la variable esta duplicada
				$idtxt=$idvariables->item(0)->nodeValue;
				$duple = 0;
				foreach($variables as $var){
					if($var['idtexto']==$idtxt) $duple = 1;
				}
				if($duple==0)
					$contenido=utf8_decode($contenidos->item(0)->nodeValue);
				else
					$contenido='__duple__';
				$variables[]=array('idclausula'=>$idclausulas->item(0)->nodeValue, 
								'idtexto'=>$idtxt,
								'contenido'=>$contenido, 
								'descripcion'=>utf8_decode($desctextos->item(0)->nodeValue), 
								'esglobal'=>$row['esglobal'],
								'eslista'=>$row['eslista'],
								'lineas'=>$row['lineas'],
								'tipo'=>$row['tipo'],
								'headers'=>array(),
								'matriz'=>array());
				
			}else{
				$miembro = $variable->getElementsByTagName( "miembro" );
				$desctabla = $variable->getElementsByTagName( "desctabla" );
				$idtexto2 = $idvariables->item(0)->nodeValue;
				if($miembro->item(0)->nodeValue=='1' && $idtexto2 != $idtexto){
					//es el primer elemento
					//es tipo tabla, vemos cuantas col. tiene y el titulo de cada una
					$sql2="SELECT idtabla, titulo, nrocolumna FROM campo_fila WHERE idtabla = '$idtexto2' ORDER BY nrocolumna";
					$query2 = consulta($sql2);
					$valores=array();
					while($row2= $query2->fetchRow(DB_FETCHMODE_ASSOC)){
						$valores[] = array(	'idtabla' => $row2["idtabla"],
											'titulo' => $row2["titulo"],
											'nrocolumna' => $row2["nrocolumna"]);
					}//desctextos
					$variables[]=array('idclausula'=>$idclausulas->item(0)->nodeValue, 
								'idtexto'=>$idvariables->item(0)->nodeValue,
								'contenido'=>'[tabla]', 
								'descripcion'=>utf8_decode($desctabla->item(0)->nodeValue), 
								'esglobal'=>$row['esglobal'],
								'eslista'=>$row['eslista'],
								'lineas'=>$row['lineas'],
								'tipo'=>$row['tipo'],
								'headers'=>$valores,
								'matriz'=>array());
					$marcador = count($variables)-1; //nos marca la posicion de la var contenedora de matriz
					$idtexto=$idvariables->item(0)->nodeValue;
				}
				//aqui añadimos a matriz los valores mientras se trate de la misma var
				//obtenemos fila y col de la celda
				$nrofila = $variable->getElementsByTagName( "nrofila" );
				$nrocolumna = $variable->getElementsByTagName( "nrocolumna" );
				$nfila=$nrofila->item(0)->nodeValue;
				$ncol =$nrocolumna->item(0)->nodeValue;
				//obtenemos contenido de la celda
				$contenido = utf8_decode($contenidos->item(0)->nodeValue);
				//cargamos el contenido q tenia anteriormente la matriz
				$matriz = $variables[$marcador]['matriz'];
				//adicionamos una celda
				$matriz[$nfila-1][$ncol-1] = $contenido;
				//guardamos la matriz
				$variables[$marcador]['matriz']=$matriz;
			}
			
		}
		/*
		echo "<pre>";
		print_r($variables); die;
		*/
		//$existe
		unset($doc);
		
		//$_SESSION['lasclausulas'] = $lasclausulas;
		
		//procesamos solo personas
		$inicio = strpos($contenidoXml, "<lasper>");
		$final = strpos($contenidoXml, "</lasper>")+9;
		$longitud = $final - $inicio ;
		$personasXML = substr($contenidoXml, $inicio, $longitud);
/*
	$arxiu="archivo1.txt";
	$f = fopen($arxiu,"w");
	fputs($f,$personasXML);
	fclose($f);
*/		
//		echo utf8_encode($personasXML);
		$doc = new DOMDocument('1.0', 'UTF-8');
		if(!$doc->loadXML(utf8_encode($personasXML))) {
		//if(!$doc->loadXML($variablesXML)) {
			echo "ERROR EN PERSONAS"; 
			//utf8_encode($personasXML)
		}
		$nodos = $doc->getElementsByTagName( "personas" );
		$partes=array();
		foreach( $nodos as $variable ){
			$tipopersonas = $variable->getElementsByTagName( "personanatural" );
			$tipopersona = $tipopersonas->item(0)->nodeValue;
			
			$idvariables = $variable->getElementsByTagName( "ci" );
			$ci = $idvariables->item(0)->nodeValue;
			 
			$contenidos = $variable->getElementsByTagName( "expedido" );
			$expedido = $contenidos->item(0)->nodeValue;
			
			$calidads = $variable->getElementsByTagName( "calidad" );
			$calidad = $calidads->item(0)->nodeValue;
			
			$redaccions = $variable->getElementsByTagName( "redaccion" );
			$redaccion = $redaccions->item(0)->nodeValue;
			
		//	$parrafos = $variable->getElementsByTagName( "representante" );
		//	$parrafo = $parrafos->item(0)->nodeValue;
			
			//para los demas datos lo sacamos de la db
			if($tipopersona == '1'){
			$sql = "SELECT * FROM persona WHERE ci = '$ci' and personanatural='1'";
			}else{
			$sql = "SELECT * FROM persona WHERE ci = '$ci' and personanatural='2'";
			}

			$query = consulta($sql);
			$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
			if($tipopersona =='1'){
				//persona natural
				$dom = $row['domicilio']; 
				$mat = $row['nacionalidad']; 
				$nom = $row['nombre'];
				$parrafo =  $row['parrafo'];
			}else{
				// persona juridica
				$dom = $row['dom_especial']; 
				$mat = $row['nromatricula']; 
				$nom = $row['razonsocial'];
				$parrafo =  $row['representante'];
			}
			
			//dom_especial, nromatricula, 
			$partes[]=array('ci'=>$ci, 
								'emi'=>$expedido,
								'tipo'=>$row['tipo_documento'],
								'pais'=>$row['pais'],
								'nombre'=>$nom,
								'direc'=>$dom,
								'eciv'=>$row['edocivil'],
								'procede'=>$mat,
								'ocupa'=>$row['profesion'],
								'rol'=>$calidad,  
								'redaccion' => utf8_decode($redaccion),
								'parrafo' => $parrafo,
								'control' => $tipopersona-1);
			//control
		}
		unset($doc);
		$i=0;
		//armamos el arreglo principal de variables
		$principal = array();
		foreach( $variables as $variable ){
			$valores=array();
			$myvalor = $variable["contenido"];
			//	echo $myvalor ;
			if($myvalor!='[tabla]'){
				if($variable["esglobal"]=='1' || $variable["esglobal"]=='4'){
					//estos son valores para la lista
					$sql="SELECT idtexto, valor FROM  var_texto_valores WHERE idtexto = '".$variable["idtexto"]."' ORDER BY valor";
					$query = consulta($sql);
					while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
						$valores[]= array('id' => $row["idtexto"],
											'valor' => $row["valor"]);
					}
				}
				$titulo = '0';
			}else{
				//es tabla, los valores cambian por los encabezados
				$titulo = '[tabla]';
				$valores = $variable["headers"];
				$myvalor = $variable["matriz"];
			}
			$principal[] = array('id' =>$variable["idclausula"],
								'titulo' =>$titulo,
								'idnumeral' =>'0',
								'inciso' =>'0',
								'nro_correlativo' =>'0',
								'idtexto' =>$variable["idtexto"],
								'contenido' =>   $myvalor ,
								'esglobal' =>   $variable["esglobal"],
								'lineas' =>   $variable["lineas"],
								'eslista' =>   $variable["eslista"],
								'tipo' =>   $variable["tipo"],
								'descripcion' =>   $variable["descripcion"],
								'valores' =>  $valores,
								'ind' =>   $i);
			$i++;
		}
	
	require_once('contratos/modvariables.php');
	die();
?>
