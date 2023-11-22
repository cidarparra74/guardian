<?php
	//ARMADO DEL CONTRATO FINAL (XML)
 
$partes = array();
ini_set('odbc.defaultlrl','1048576'); //esto en caso de tener imagenes en la cabecera resulta necesario
//verificamos si el contrato tiene partes
if(isset($_REQUEST["hdnCi"])){
	//tiene partes
	//recuperamos todo el conjunto de datos dispuestos en arreglos
	$hdnCi=$_REQUEST["hdnCi"];
	$hdnEmi=$_REQUEST["hdnEmi"];
	$hdnTipo=$_REQUEST["hdnTipo"];
//	$hdnTipoLit=$_REQUEST["hdnTipoLit"];
	if( isset($_REQUEST["hdnProcede"])){
		$hdnProcede=$_REQUEST["hdnProcede"];
		$hdnPais=$_REQUEST["hdnPais"];
		$hdnOcupa=$_REQUEST["hdnOcupa"];
		$hdnDirec=$_REQUEST["hdnDirec"];
		$hdnEstCivil=$_REQUEST["hdnEstCivil"];
		$hdnRedaccion=$_REQUEST["hdnRedaccion"];
		$hdnParrafo=$_REQUEST["hdnParrafo"];
	}else $hdnProcede='---';
	$hdnRol=$_REQUEST["hdnRol"];
	$hdnNombre=$_REQUEST["hdnNombre"];
	$hdnControl=$_REQUEST["hdnControl"];
	$elNit=$_REQUEST["elNit"];  //indica el codigo del tipo de doc para NIT (si esta definido, si no =0)
	$posi=$_REQUEST["posi"];

	//recorremos el arreglo de los CI y utilizamos el mismo indice para los demas
	//nos armamos un arreglo de partes
	
	foreach($hdnCi as $key => $ci){
		$nombre = strtoupper(utf8_decode($hdnNombre[$key]));
		//echo $nombre . ' - ' . utf8_decode($nombre);
	//	echo $hdnControl[$key];
		if($hdnControl[$key]=='0'){
			//persona natural
				//buscamos el literal del tipo de documento
				$Tipo = $hdnTipo[$key];
				$sql = "SELECT descripcion, codigo FROM tipo_documento WHERE codigo = '$Tipo'";
				$query = consulta($sql);
				$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
				if($Tipo == $row['codigo']){
					$tipolit = $row['descripcion'];
				}else{
					$tipolit = "";
				}
				if($hdnProcede!='---'){
					$redaccion = $nombre." con ".$tipolit. " Nro. ".$ci." ".$hdnEmi[$key].
						" ".$hdnRedaccion[$key].", .|.que en adelante se denominará ".$hdnRol[$key];

					$partes[] = array('posi' => $posi[$key],
								'ci' => $ci,
								'emi' => $hdnEmi[$key],   
								'tipo' => $hdnTipo[$key],
								'procede' => $hdnProcede[$key],
								'pais' => $hdnPais[$key],
								'ocupa' => $hdnOcupa[$key],
								'direc' => utf8_decode($hdnDirec[$key]),
								'eciv' => $hdnEstCivil[$key], 
								'rol' => $hdnRol[$key],  
								'nombre' => $nombre,  
								'redaccion' => $redaccion,  
								'parrafo' => $hdnParrafo[$key]);  
				}else{
					$redaccion = $nombre." con ".$tipolit. " Nro. ".$ci." ".$hdnEmi[$key].
						", .|.que en adelante se denominará ".$hdnRol[$key];

					$partes[] = array('posi' => $posi[$key],
								'ci' => $ci,
								'emi' => $hdnEmi[$key],   
								'tipo' => $hdnTipo[$key],
								'procede' => '',
								'pais' => '',
								'ocupa' => '',
								'direc' => '',
								'eciv' => '', 
								'rol' => $hdnRol[$key],  
								'nombre' => $nombre,  
								'redaccion' => $redaccion,  
								'parrafo' => '');
				}				
				//actualizamos tabla personas
				//insertamos si es nuevo, actualizamos si ya existe
				$emi = $hdnEmi[$key];
				$sql = "SELECT ci FROM persona WHERE ci = '$ci' ";
				$query = consulta($sql);
				$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
				
				if($hdnProcede!='---'){
					if($ci == $row['ci']){
						//existe => actualizamos
						//esto optimizar para guardadr solo si se hicieron cambios !!!!!
						$sql="UPDATE persona SET tipo_documento='".$hdnTipo[$key]."', pais='".$hdnPais[$key].
						"', nombre='$nombre', domicilio='".utf8_decode($hdnDirec[$key])."', edocivil='".$hdnEstCivil[$key].
						"', nacionalidad='".$hdnProcede[$key]."', profesion='".$hdnOcupa[$key].
						"', parrafo='".$hdnParrafo[$key]."' WHERE ci = '$ci' AND personanatural = '1'";
					}else{
						//no existe => insertamos
						$sql="INSERT INTO persona (
						ci, expedido, tipo_documento, pais, nombre, domicilio, edocivil, dom_especial, nacionalidad, profesion, nit, razonsocial, nromatricula, personanatural, representante, parrafo) VALUES (
						'$ci','$emi','".$hdnTipo[$key]."','".$hdnPais[$key]."','$nombre','".utf8_decode($hdnDirec[$key])."', '".$hdnEstCivil[$key]."','','".$hdnProcede[$key]."','".$hdnOcupa[$key]."','0','','','1','','".$hdnParrafo[$key]."')";
					}
				}else{
					if($ci == $row['ci']){
						//existe => actualizamos
						//esto optimizar para guardadr solo si se hicieron cambios !!!!!
						$sql="UPDATE persona SET tipo_documento='".$hdnTipo[$key].
						"', pais='', nombre='$nombre', domicilio='', edocivil='', nacionalidad='', profesion='', parrafo='' WHERE ci = '$ci' AND personanatural = '1'";
					}else{
						//no existe => insertamos
						$sql="INSERT INTO persona (
						ci, expedido, tipo_documento, pais, nombre, domicilio, edocivil, dom_especial, nacionalidad, profesion, nit, razonsocial, nromatricula, personanatural, representante, parrafo) VALUES (
						'$ci','$emi','".$hdnTipo[$key]."','','$nombre','', '','','','','0','','','1','','')";
					}
				}
				//echo $sql;
				ejecutar($sql);
		}else{
			//persona juridica
			$redaccion = $nombre." con NIT Nro. ".$ci.", Matricula de Comercio Nro. ".$hdnProcede[$key].", con domicilio en ".utf8_decode($hdnDirec[$key]).", representada(o) por ".$hdnParrafo[$key].", .|.que en adelante se denominará ".$hdnRol[$key];
			$partes[] = array('posi' => $posi[$key],
								'ci' => $ci, //es el NIT
								'emi' => '--',  //en blanco 
								'tipo' => $elNit, //por defecto el tipo de doc es NIT jala de la tabla tipo_documento
								'procede' => $hdnProcede[$key], //es la matricula
								'pais' => $hdnPais[$key], //el pais
								'ocupa' => $hdnOcupa[$key], //en blanco
								'direc' => utf8_decode($hdnDirec[$key]), //es el domicilio
								'eciv' => $hdnEstCivil[$key],  // en blanco
								'rol' => $hdnRol[$key],    // el rol
								'nombre' => $nombre,  // la razon social
								'redaccion' => $redaccion,  // redaccion
								'parrafo' => $hdnParrafo[$key]);   //es representante
								
				//actualizamos tabla personas
				//insertamos si es nuevo, actualizamos si ya existeAND personanatural = '2'
				$emi = $hdnEmi[$key];
				$sql = "SELECT ci FROM persona WHERE ci = '$ci' ";
				$query = consulta($sql);
				$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
				if($ci == $row['ci']){
					//existe => actualizamos
					//esto optimizar para guardadr solo si se hicieron cambios !!!!!
					$sql="UPDATE persona SET tipo_documento='$elNit', pais='".$hdnPais[$key].
					"', nombre='$nombre', razonsocial='$nombre' , dom_especial='".utf8_decode($hdnDirec[$key]).
					"', nromatricula='".$hdnProcede[$key]."', representante='".
					$hdnParrafo[$key]."' WHERE ci = '$ci' AND personanatural = '2'";
				}else{
					//no existe => insertamos
					$sql="INSERT INTO persona (
					ci, expedido, tipo_documento, pais, nombre, domicilio, edocivil, dom_especial, nacionalidad, profesion, nit, razonsocial, nromatricula, personanatural, representante, parrafo) VALUES (
					'$ci','','$elNit','".$hdnPais[$key]."','$nombre','', '','".utf8_decode($hdnDirec[$key])."','','','$ci','$nombre','".$hdnProcede[$key]."','2','".$hdnParrafo[$key]."','')";
				}
				ejecutar($sql);
		}
	}
	asort($partes);
}else{
	//no existen partes en el contrato
	
}
//guardamos las partes en una sesion
$_SESSION["partes"] = $partes;

//recuperamos todo lo generado
$idcontrato = $_SESSION['idcontrato'];
$contrato = $_SESSION['contrato'];
$opcional = $_SESSION['opcional'];
$incisos = $_SESSION['incisos'];
$principal = $_SESSION['principal'];
//echo $opcional; die();
/*
	$excluir = '';
	$tipo = $_SESSION['tipo'];  //indica cual clausula se eligio ente indistinta y conjunta
	if($tipo != 'U'){
		//recuperando los parametros del contrato
		if($tipo == 'C')
			$sql= "SELECT indistinta as excluir1, fallindi as excluir2 FROM parametros_c";
		else
			$sql= "SELECT conjunta   as excluir1, fallconj as excluir2 FROM parametros_c";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$excluir = ' AND r.idclausula not in ('.$row["excluir1"]. ', '.$row["excluir2"].')';
	}else{
		$sql= "SELECT indistinta, conjunta, fallindi, fallconj FROM parametros_c";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$excluir = ' AND r.idclausula not in ('.$row["indistinta"] . ', '.$row["conjunta"]. ', '.$row["fallindi"]. ', '.$row["fallconj"].')';
	}
	*/
// leemos el contenido de cada clausula y reemplazamos los valores de las variables
//
//seleccionamos todas las clausulas del contrato, mas las opcionales seleccionadas, mas los incisos seleccionados
	$sql="SELECT r.idclausula, r.posicion, cl.titulo, nu.idnumeral, nu.titulo as inciso, cl.contenido as contcla, nu.contenido as contnum
FROM rel_cc r INNER JOIN clausula cl ON r.idclausula=cl.idclausula 
LEFT JOIN (SELECT IDCLAUSULA, nro_correlativo, idnumeral, titulo, contenido 
FROM numeral WHERE idnumeral IN ($incisos)) nu ON nu.idclausula=cl.idclausula 
WHERE r.idcontrato= $idcontrato AND (r.opcional=0 OR cl.idclausula IN ($opcional))    
ORDER BY r.posicion, nu.nro_correlativo";
	//echo $sql; die();
	
	$query = consulta($sql);
	$clausulas=array();
	$idclau = '';
	$txtinciso = '';
	$cci=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		//al recorrer las clausulas aprovechamos para reemplazar el contenido de los incisos, si tuviere
		if($idclau != $row["idclausula"]){
			$idclau = $row["idclausula"];
			$texto = $row["contcla"];
			//esto es una clausula
			//buscamos si tiene incisos
			//vemos si se ha armado incisos para la clausula anterior
			if($txtinciso != ''){
				//reemplazamos en la clausula anterior, indicada por $cci-1
				$esto = "<<INCISOS,3>>\par";
				$laClausula = $clausulas[$cci-1]['texto'];
				$posi = strrpos($laClausula, $esto);
				if($posi !== false){
					$laClausula = str_replace($esto, "<<INCISOS,3>>", $laClausula);
					//$txt = substr($texto,0,$posi-1);
				} //else{
					//$esto = "<<INCISOS,3>>";
					//$textoAnt = str_replace($esto, $txtinciso, $laClausula);
				//}
				$esto = "<<INCISOS,3>>";
				$posi = strrpos($laClausula, $esto);
				if($posi !== false){
					$laClausula = str_replace($esto, $txtinciso, $laClausula);
				}
				//$textoAnt = str_replace($esto, $txtinciso, $laClausula);
				$laClausula = str_replace('\f1\fs17\par', '\par', $laClausula);
				$clausulas[$cci-1]['texto'] = $laClausula;
				//empezamos de nuevo
				$txtinciso = '';
			}
			
			$cci++;
			$clausulas[]= array('id' => $idclau,
						'titulo' => $row["titulo"],
						'idnumeral' => '0',
						'texto' => $texto,
						'posicion'=>$row["posicion"],
						'nro'=>$cci);
		}
		if($row["idnumeral"]!=''){
			//este es un inciso
		//	$idnumeral = $row["idnumeral"];
			$texto = trim($row["contnum"]);
			//echo $texto; echo "<br /><br />";
			$texto = str_replace('\f1\fs17\par', '\par', $texto);
			//echo $texto; echo "<br /><br />";
			//armamos el texto para reemplazarlo en la clausula
			//Buscamos \pard (desde ahi 'normalmente' empieza el inciso
			$posi = stripos($texto, '\pard');
			if ($posi !== false) { //encontrado
				//tomamos a partir de la posicion que sigue a \pard
				$elinciso = substr($texto,$posi+5);
				$posi = stripos($elinciso, 'f1\\fs17\\par');
				if($posi !== false){
					$elinciso = str_replace('\f1\fs17\par', '\par', $elinciso);
			//		echo $clausulas[$cci-1]['titulo']; echo "<br />";
			//		
				}
					//echo $elinciso; echo "<br /><br />";
					$tamanio = strlen($elinciso);
					$posi = strrpos($elinciso, '}');
					if($posi !== false){
						$txtinciso .= substr($elinciso,0,$posi-1).' ';
						//$txtinciso .= substr($elinciso,0,$tamanio-6).'\par';
					}else{
						$txtinciso .= substr($elinciso,0,$tamanio).' ';
					}
				//echo $txtinciso; echo "<br /><br />";
			}else{
			//no se encontro!
				//buscamos desde \rtf1 que siempre existe hasta '}'
				$elinciso = trim($texto);
				$tamanio = strlen($elinciso);
				$txtinciso .= substr($elinciso,6,$tamanio-1).'  ';
			}
			
		}
	}
	//para el caso que la ultima clausula tenga inciso:
	if($txtinciso != ''){
		//reemplazamos en la clausula anterior, indicada por $cci-1
		$esto = "<<INCISOS,3>>";
		//$txtinciso = str_replace('\\f1\\fs17\\par', '\par', $txtinciso);
		//$txtinciso .= '\par';
		$textoAnt = str_replace($esto, $txtinciso, $clausulas[$cci-1]['texto']);
		$clausulas[$cci-1]['texto'] = $textoAnt;
	
	}		
		//die();		
	//unset($valor);
//
//hasta aqui ya estan preparadas las claausulas para generar el contrato

//armar el XML
//suponiendo que el encabezado es siempre el mismo para cualquier contrato, tenemos:
$skemaDef = '<xs:schema id="NewDataSet" xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata">
    <xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true">
      <xs:complexType>
        <xs:choice minOccurs="0" maxOccurs="unbounded">
          <xs:element name="contrato">
            <xs:complexType>
              <xs:sequence>
                <xs:element name="titulo" type="xs:string" minOccurs="0" />
                <xs:element name="cabecera" type="xs:string" minOccurs="0" />
                <xs:element name="pie" type="xs:string" minOccurs="0" />
                <xs:element name="idcontrato" type="xs:int" minOccurs="0" />
                <xs:element name="codtipo" type="xs:int" minOccurs="0" />
                <xs:element name="codmateria" type="xs:int" minOccurs="0" />
                <xs:element name="habilitado" type="xs:int" minOccurs="0" />
                <xs:element name="codentidad" type="xs:int" minOccurs="0" />
                <xs:element name="con_firma" type="xs:int" minOccurs="0" />
                <xs:element name="partesfirmantes" type="xs:string" minOccurs="0" />
                <xs:element name="cabecera2" type="xs:string" minOccurs="0" />
                <xs:element name="pie2" type="xs:string" minOccurs="0" />
                <xs:element name="numerar_clausulas" type="xs:int" minOccurs="0" />
				<xs:element name="con_firma_abogado" type="xs:int" minOccurs="0" />
                <xs:element name="cprotocolo" type="xs:string" minOccurs="0" />
                <xs:element name="pprotocolo" type="xs:string" minOccurs="0" />
              </xs:sequence>
            </xs:complexType>
          </xs:element>
          <xs:element name="clausulas">
            <xs:complexType>
              <xs:sequence>
                <xs:element name="posicion" type="xs:int" minOccurs="0" />
                <xs:element name="idclausula" type="xs:int" minOccurs="0" />
                <xs:element name="titulo" type="xs:string" minOccurs="0" />
                <xs:element name="contenido" type="xs:string" minOccurs="0" />
              </xs:sequence>
            </xs:complexType>
          </xs:element>
          <xs:element name="variables">
            <xs:complexType>
              <xs:sequence>
                <xs:element name="idclausula" type="xs:int" minOccurs="0" />
                <xs:element name="tipo" type="xs:int" minOccurs="0" />
                <xs:element name="miembro" type="xs:int" minOccurs="0" />
                <xs:element name="idvariable" type="xs:string" minOccurs="0" />
                <xs:element name="desctabla" type="xs:string" minOccurs="0" />
                <xs:element name="desctexto" type="xs:string" minOccurs="0" />
                <xs:element name="contenido" type="xs:string" minOccurs="0" />
                <xs:element name="esglobal" type="xs:int" minOccurs="0" />
                <xs:element name="nrofila" type="xs:int" minOccurs="0" />
                <xs:element name="nrocolumna" type="xs:int" minOccurs="0" />
                <xs:element name="adicional" type="xs:string" minOccurs="0" />
              </xs:sequence>
            </xs:complexType>
          </xs:element>
          <xs:element name="personas">
            <xs:complexType>
              <xs:sequence>
                <xs:element name="posicion" type="xs:int" minOccurs="0" />
                <xs:element name="ci" type="xs:string" minOccurs="0" />
                <xs:element name="expedido" type="xs:string" minOccurs="0" />
                <xs:element name="nombre" type="xs:string" minOccurs="0" />
                <xs:element name="domicilio" type="xs:string" minOccurs="0" />
                <xs:element name="calidad" type="xs:string" minOccurs="0" />
                <xs:element name="parrafo" type="xs:string" minOccurs="0" />
                <xs:element name="dom_especial" type="xs:string" minOccurs="0" />
                <xs:element name="edocivil" type="xs:string" minOccurs="0" />
                <xs:element name="nacionalidad" type="xs:string" minOccurs="0" />
                <xs:element name="profesion" type="xs:string" minOccurs="0" />
                <xs:element name="tipo_documento" type="xs:int" minOccurs="0" />
                <xs:element name="pais" type="xs:int" minOccurs="0" />
                <xs:element name="redaccion" type="xs:string" minOccurs="0" />
                <xs:element name="razonsocial" type="xs:string" minOccurs="0" />
                <xs:element name="nit" type="xs:string" minOccurs="0" />
                <xs:element name="nromatricula" type="xs:string" minOccurs="0" />
                <xs:element name="personanatural" type="xs:string" minOccurs="0" />
                <xs:element name="representante" type="xs:string" minOccurs="0" />
                <xs:element name="aceptante" type="xs:boolean" minOccurs="0" />
              </xs:sequence>
            </xs:complexType>
          </xs:element>
        </xs:choice>
      </xs:complexType>
    </xs:element>
  </xs:schema>';

//leemos los datos del contrato desde la BD
$sql="SELECT * FROM contrato WHERE idcontrato = $idcontrato";
$query=consulta($sql);
$row=$query->fetchRow(DB_FETCHMODE_ASSOC);
$elcontrato = array('idcontrato'	=> $row["idcontrato"],
					'titulo' 		=> $row["titulo"],
					'cabecera' 		=> trim($row["cabecera"]),
					'pie' 			=> $row["pie"],
					'codtipo' 		=> $row["codtipo"],
					'codmateria' 	=> $row["codmateria"],
					'habilitado' 	=> $row["habilitado"],
					'codentidad' 	=> $row["codentidad"],
					'con_firma' 	=> $row["con_firma"],
					'partesfirmantes' => $row["partesfirmantes"],
					'cabecera2' 	=> $row["cabecera2"],
					'pie2' 			=> $row["pie2"],
					'numerar_clausulas' => $row["numerar_clausulas"]);
//recuperando tipo de persona mayor de edad o menor de edad
	//$tipopersona = $row["tipopersona"];
	
	
//armamos el XML base con DOM:

//creamos el DOM
$doc = new DOMDocument('1.0', 'UTF-8');
$doc->formatOutput = true;
$raiz = $doc->createElement('NewDataSet'); //Creamos un elemento
$doc->appendChild($raiz); //lo pegamos al documento raiz

	//*******************************************************
	//para la parte donde ira la cabecera del dataset
	$nivel0 = $doc->createElement("skema"); //creamos un nivel 
		$nivel0->appendChild (  $doc->createTextNode( 'skemaContent')   );
	$raiz->appendChild($nivel0); //insertamos skema a la raiz
	//*******************************************************
	//para el primer contenedor (de dos) del cuerpo base
//	$nivel0 = $doc->createElement("NIVEL0");
	//<diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">
	//*******************************************************
	//para el segundo contenedor 
	$nivel1 = $doc->createElement("NIVELdeCONTROL");
	// <NewDataSet xmlns="">

	//para los datos del contrato
	$nivel2 = $doc->createElement("contrato"); //creamos un nivel 
	//este sera el contenido del nivel 1
	$elemento = $doc->createElement("titulo"); //creamos elemento para el nivel2
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['titulo'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("cabecera"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['cabecera']."\n" )   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("pie"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['pie']."\n" )   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("idcontrato"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['idcontrato'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("codtipo"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['codtipo'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("codmateria"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['codmateria'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("habilitado"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['habilitado'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("codentidad"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['codentidad'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("con_firma"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['con_firma'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("partesfirmantes"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['partesfirmantes'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("cabecera2"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['cabecera2']."\n" )   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("pie2"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['pie2']."\n" )   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("numerar_clausulas"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['numerar_clausulas'])   );
		$nivel2->appendChild($elemento);
	// <con_firma_abogado>1</con_firma_abogado>
	$elemento = $doc->createElement("con_firma_abogado"); 
	$elemento->appendChild($doc->createTextNode(''));  
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("cprotocolo"); 
	$elemento->appendChild (  $doc->createTextNode('')   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("pprotocolo"); 
	$elemento->appendChild (  $doc->createTextNode('')   );
		$nivel2->appendChild($elemento);

//adicionamos ala raiz el nivel 1	
$nivel1->appendChild($nivel2);   //pegamos a la raiz

//para tipos conjunto o indistinto
$tipo = $_SESSION["tipo"] ;

	
	
	
//para las clausulas:
//esto va en un ciclo para todas las clausulas
$i=0;
foreach($clausulas as $valor){
	$i++;
	///
	$nivel2 = $doc->createElement("clausulas"); //creamos un nodo clausula
	///	
	$elemento = $doc->createElement("posicion");//creamos un elemento para el nodo
	$elemento->appendChild (  $doc->createTextNode( $valor['nro'])   ); //definimos el contenido del elemnto
		$nivel2->appendChild($elemento); //insertamos el elemento al nodo
	$elemento = $doc->createElement("idclausula");
	$elemento->appendChild (  $doc->createTextNode( $valor['id'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("titulo");
	$elemento->appendChild (  $doc->createTextNode( $valor['titulo'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("contenido");
	$ccontent = trim($valor['texto']);
	//ver si es clausula de manejo
	/*
	$union = '';
	$pos = strpos($ccontent, '<<MANEJO,8>>', 0);
	if ($pos !== false) {
		//existe, 
		if($tipo == 'C')
		//como conjunto
		$union = ' y ';
		else
		//como indistinto
		$union = ' o ';
		
	}
	if ($union != '') {
		$nombres = '';
		$union2 = '';
		foreach($partes as $key => $valor){
			//if($tipopersona=='M' ){
			if(strpos($valor['rol'],'Menor de edad',0)=== false){
				$nombres .= $union2 . $valor['nombre'] ;
				$union2 = $union ;
			}
			//}else{
			//	$nombres .= $union2 . $valor['nombre'] ;
			//	$union2 = $union ;
			//}
		}
		$ccontent = str_replace("<<MANEJO,8>>", $nombres, $ccontent);
	}
	*/
	//if($ccontent!='') $ccontent.="\n";
//	$ccontent = str_replace("'","''''",$ccontent);
	$ccontent = str_replace("\\pard\n\\pard","\\pard",$ccontent);
	$elemento->appendChild (  $doc->createTextNode( $ccontent)   );
		$nivel2->appendChild($elemento);
	//adicionamos el nodo al contenedor de clausulas	
	$nivel1->appendChild($nivel2);
}//foreach
unset($valor);
//fin ciclo
/////////////////// para las PARTES FIRMANTES clausula
$nivel2 = $doc->createElement("clausulas"); //creamos un nodo clausula
	$elemento = $doc->createElement("posicion");//creamos un elemento para el nodo
	$elemento->appendChild (  $doc->createTextNode( '-1')   ); //definimos el contenido del elemnto
		$nivel2->appendChild($elemento); //insertamos el elemento al nodo
	$elemento = $doc->createElement("idclausula");
	$elemento->appendChild (  $doc->createTextNode( '0')   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("titulo");
	$elemento->appendChild (  $doc->createTextNode('PARTES FIRMANTES')   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("contenido");
	$ccontent = $elcontrato['partesfirmantes']."\n";
	$elemento->appendChild (  $doc->createTextNode( $ccontent)   );
		$nivel2->appendChild($elemento);
	$nivel1->appendChild($nivel2);

/////////*********************************
//titulo
 // echo '<pre>';
 // print_r($principal);
 // die();
//eliminamos de $principal las mismas variables dentro una misma clausula
$idc='0';
$var='x8';
foreach($principal as $key => $valor){
	if($valor['id']==$idc && $valor['idtexto']==$var){
		$principal[$key]['idtexto'] ='';
	}
	$idc=$valor['id'];
	$var=$valor['idtexto'];
}
/* echo "<br />";
print_r($principal);
echo '</pre>';
die(); */
// para las variables, recorremos $principal where idtexto!=''
$i=0;
foreach($principal as $valor){
	if($valor['idtexto']!='' && $valor['titulo']!='[tabla]'){
		$i++;
		$adicional = '';
		//vemos si tiene contenido adicional
		if($valor['esglobal']=='4'){
			$sqladd = "SELECT adicional FROM var_texto_valores WHERE idtexto='".$valor['idtexto'].
					"' AND valor='".$valor['contenido']."'";
			$query=consulta($sqladd);
			$row=$query->fetchRow(DB_FETCHMODE_ASSOC);
			$adicional = $row["adicional"];
		}
		///
		$nivel2 = $doc->createElement("variables"); 
		///
		$elemento = $doc->createElement("idclausula");
		$elemento->appendChild (  $doc->createTextNode( $valor['id'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("tipo"); //$valor['tipo']
		$elemento->appendChild (  $doc->createTextNode( '0')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("miembro");
		$elemento->appendChild (  $doc->createTextNode('1')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("idvariable");
		$elemento->appendChild (  $doc->createTextNode( $valor['idtexto'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("desctabla");
		$elemento->appendChild (  $doc->createTextNode('')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("desctexto");
		$elemento->appendChild (  $doc->createTextNode( $valor['descripcion'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("contenido");
		$elemento->appendChild (  $doc->createTextNode( $valor['contenido'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("esglobal");
		$elemento->appendChild (  $doc->createTextNode( $valor['esglobal'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("nrofila");
		$elemento->appendChild (  $doc->createTextNode('0')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("nrocolumna");
		$elemento->appendChild (  $doc->createTextNode('0')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("adicional");
		$elemento->appendChild (  $doc->createTextNode($adicional)   );
			$nivel2->appendChild($elemento);

		//adicionamos a la raiz el nivel 1	
		$nivel1->appendChild($nivel2);
	}elseif($valor['titulo']=='[tabla]'){
		//es tabla, armamos de manera distinta
		/*
		  <variables>
			<idclausula>265</idclausula>
			<tipo>1</tipo>
			<miembro>1</miembro>
			<idvariable>det_pren_ffp</idvariable>
			<desctabla>Detalle de la Prenda</desctabla>
			<desctexto>Cant.</desctexto>
			<contenido>1</contenido>
			<esglobal>0</esglobal>
			<nrofila>1</nrofila>
			<nrocolumna>1</nrocolumna>
			<adicional />
		  </variables>
		*/
		//es tipo tabla, vemos cuantas col. tiene y el titulo de cada una
		
		$valores=$valor['valores'];
		$matriz = $valor['contenido'];
/* echo "<pre>";
print_r($matriz);
echo "</pre>";
echo $matriz[0][0];
die();*/ 
		//$ncols = count($valores);
		$i=0;
		foreach($matriz as $columnas){
			$j=0;
			foreach($columnas as $celda){
			$nivel2 = $doc->createElement("variables"); 
			///
			$elemento = $doc->createElement("idclausula");
			$elemento->appendChild (  $doc->createTextNode( $valor['id'])   );
				$nivel2->appendChild($elemento);
			$elemento = $doc->createElement("tipo"); //$valor['tipo']
			$elemento->appendChild (  $doc->createTextNode( '1')   );
				$nivel2->appendChild($elemento);
			$elemento = $doc->createElement("miembro");
			$elemento->appendChild (  $doc->createTextNode($j+1)   );
				$nivel2->appendChild($elemento);
			$elemento = $doc->createElement("idvariable");
			$elemento->appendChild (  $doc->createTextNode( $valor['idtexto'])   );
				$nivel2->appendChild($elemento);
			$elemento = $doc->createElement("desctabla");
			$elemento->appendChild (  $doc->createTextNode($valor['descripcion'])   );
				$nivel2->appendChild($elemento);
			$elemento = $doc->createElement("desctexto");
			$elemento->appendChild (  $doc->createTextNode( $valores[$j]['titulo'])   );
				$nivel2->appendChild($elemento);
			$elemento = $doc->createElement("contenido");
			$elemento->appendChild (  $doc->createTextNode( $celda)   );
				$nivel2->appendChild($elemento);
			$elemento = $doc->createElement("esglobal");
			$elemento->appendChild (  $doc->createTextNode( '0')   );
				$nivel2->appendChild($elemento);
			$elemento = $doc->createElement("nrofila");
			$elemento->appendChild (  $doc->createTextNode($i+1)   );
				$nivel2->appendChild($elemento);
			$elemento = $doc->createElement("nrocolumna");
			$elemento->appendChild (  $doc->createTextNode($j+1)   );
				$nivel2->appendChild($elemento);
			$elemento = $doc->createElement("adicional");
			$elemento->appendChild (  $doc->createTextNode('')   );
				$nivel2->appendChild($elemento);
			//adicionamos a la raiz el nivel 1	
			$nivel1->appendChild($nivel2);
			$j++;
		}
		$i++;
		}
	}
}
unset($valor);
unset($adicional);
//para las partes

$i=0;
foreach($partes as $key => $valor){
	
		$i++;
		$nivel2 = $doc->createElement("personas"); //creamos un nivel
		///
		$elemento = $doc->createElement("posicion");
		$elemento->appendChild (  $doc->createTextNode( $i  ));
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("ci");
		$elemento->appendChild (  $doc->createTextNode( $valor['ci'])  );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("expedido");
		$elemento->appendChild (  $doc->createTextNode( $valor['emi'])  );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("nombre");
		$elemento->appendChild (  $doc->createTextNode( $valor['nombre'])  );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("domicilio");
		if($hdnControl[$key]=='0')
			$elemento->appendChild (  $doc->createTextNode( $valor['direc'])  );
		else
			$elemento->appendChild (  $doc->createTextNode( '')  );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("calidad");
		$elemento->appendChild (  $doc->createTextNode( $valor['rol'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("parrafo");
		$elemento->appendChild (  $doc->createTextNode( $valor['parrafo'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("dom_especial");
		if($hdnControl[$key]=='0')
			$elemento->appendChild (  $doc->createTextNode( '')   );
		else
			$elemento->appendChild (  $doc->createTextNode( $valor['direc'] )   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("edocivil");
		$elemento->appendChild (  $doc->createTextNode($valor['eciv'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("nacionalidad");
		if($hdnControl[$key]=='0')
			$elemento->appendChild (  $doc->createTextNode($valor['procede'])   );
		else
			$elemento->appendChild (  $doc->createTextNode('')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("profesion");
		$elemento->appendChild (  $doc->createTextNode($valor['ocupa'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("tipo_documento");
		$elemento->appendChild (  $doc->createTextNode($valor['tipo'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("pais");
		$elemento->appendChild (  $doc->createTextNode($valor['pais'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("redaccion");
		$elemento->appendChild (  $doc->createTextNode($valor['redaccion'])   );
			$nivel2->appendChild($elemento);
			$elemento = $doc->createElement("razonsocial");
		if($hdnControl[$key]=='0')	
			$elemento->appendChild (  $doc->createTextNode('')   );
		else
			$elemento->appendChild (  $doc->createTextNode($valor['nombre'])  );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("nit");
		$elemento->appendChild (  $doc->createTextNode('')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("nromatricula");
		if($hdnControl[$key]=='0')
			$elemento->appendChild (  $doc->createTextNode('')   );
		else
			$elemento->appendChild (  $doc->createTextNode($valor['procede'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("personanatural");
	//	echo 'xml:'.$hdnControl[$key];
		if($hdnControl[$key]=='0')
			$elemento->appendChild (  $doc->createTextNode('1')   );
		else
			$elemento->appendChild (  $doc->createTextNode('2')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("representante");
		if($hdnControl[$key]=='0')
			$elemento->appendChild (  $doc->createTextNode('')   );
		else
			$elemento->appendChild (  $doc->createTextNode($valor['parrafo'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("aceptante");
		$elemento->appendChild (  $doc->createTextNode('false')   );
			$nivel2->appendChild($elemento);
		//atributos de las personas
	//	$nivel2->setAttribute("diffgr:id", "personas".$i);
	//	$nivel2->setAttribute("msdata:rowOrder", ($i-1));
	//	$nivel2->setAttribute("diffgr:hasChanges", "inserted");
		
		//adicionamos a la raiz el nivel 1	
		$nivel1->appendChild($nivel2);
		//
}
unset($valor);

$raiz->appendChild($nivel1);
//
/// vaciamos el XML base a una variable
$XMLbase = $doc->saveXML(); //Y la salida que dará el XML 
//$doc->save("c:\Inetpub\wwwroot\guardian2\compilado\p.xml");
/// transformamos el XML base al XML final 
$XMLfinal = str_replace("<skema>skemaContent</skema>",$skemaDef,$XMLbase);
//$XMLfinal = addslashes($XMLfinal);
$XMLfinal = str_replace("  <NIVELdeCONTROL>","",$XMLfinal);
$XMLfinal = str_replace("  </NIVELdeCONTROL>","",$XMLfinal);
//para casos en que no haya seleccionado inciso
$XMLfinal = str_replace("<<INCISOS,3>>","",$XMLfinal);
//borramos lineas vacias 
$XMLfinal = str_replace("\n\n","\n",$XMLfinal);
//reemplazamos la cabecera
$XMLfinal = str_replace("encoding=\"UTF-8\"","standalone=\"yes\"",$XMLfinal);

//decodificamos caracteres especiales
//$XMLfinal = utf8_decode($XMLfinal);
//reemplazamos comilla simple por dos comillas simples para no generar error en sql INSERT
$XMLfinal = str_replace("'","''",$XMLfinal);
//reemplazamos caracteres extraños
$XMLfinal = str_replace("&#13;","",$XMLfinal);
//echo $XMLfinal;
//guardamos en la tabla, antes recuperamos login del usuario en glogin
$glogin=$_SESSION['glogin'];
$fecha = date("Y-m-d H:i:s");
$fecha = "CONVERT(DATETIME,'$fecha',102)";


//en Contrato_Final:

if(isset($_SESSION['nrocaso'])){
	$nrocaso = $_SESSION["nrocaso"];
}else{
	$nrocaso = '0';
}
	
//insertamos nuevo contrato, si es primera insercion
if(!isset($_SESSION['idfinal'])){
$sql = "INSERT INTO contrato_final (idcontrato, login, fechahora, contenido_sec, firmado, eliminado, ultimo_login, nrocaso) 
VALUES ('$idcontrato', '$glogin', $fecha, '$XMLfinal', 0, 0, '$glogin', '$nrocaso')";
ejecutar($sql);
//obtenemos el idfinal
	$sql = "SELECT max(idfinal) as ultimo FROM contrato_final WHERE login='$glogin'";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$idfinal = $row["ultimo"];
	$_SESSION['idfinal'] = $idfinal;
}else{
	$idfinal = $_SESSION['idfinal'] ;
	$sql = "UPDATE contrato_final SET fechahora=$fecha, contenido_sec='$XMLfinal' WHERE idfinal='$idfinal'";
	ejecutar($sql);
}

	//llamar al Web Service para crear el doc en WORD
$resulta=0;
require('ws_sec.php');
//mostrar el DOC
if($resulta==0){
	//se ha generado el DOC
	$alert = "Se ha guardado el contrato, puede abrir el documento.";
}else{
	$alert = "Atenci&oacute;n! Se ha guardado el contrato pero no se pudo generar el documento.";
}

		//ACCEDEMOS AL GUARDIAN
		unset($link);
		require('../lib/conexionMNU.php');
	//leemos tipo de doc habilitado
	$sql = "SELECT TOP 1 tipodoc FROM opciones";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	if($row['tipodoc']!=''){
		$tipodoc=$row['tipodoc'];
	}else{
		$tipodoc='W';
	}
//vemos si hay nrocaso para BANECO
	if($nrocaso!='0'){
		//se supone que este nro de caso ya esta guardado en ncaso_cfinal de guardian
		//asi que lo actualizamos
		//$sql="INSERT INTO ncaso_cfinal (nrocaso,idfinal) VALUES ('$nrocaso','$idfinal')";
		$sql="UPDATE ncaso_cfinal SET idfinal='$idfinal' WHERE nrocaso = '$nrocaso' AND idfinal = '0'";
		ejecutar($sql);
	}

	
//vemos si puede abrir el .DOC
if( isset($_SESSION["quien"])){
	$smarty->assign('quien',$_SESSION["quien"]);
}else{
	$smarty->assign('quien','1');
}
if( isset($_SESSION['word'])){
	$smarty->assign('word',$_SESSION['word']);
}else{
	$smarty->assign('word','s');
}
//$contrato = $_SESSION['contrato'];
$smarty->assign('contrato',$contrato);
$smarty->assign('nombre',$partes[0]['nombre']);
$smarty->assign('alert',$alert);
$smarty->assign('idfinal',$idfinal);
$smarty->assign('tipodoc',$tipodoc);
	$smarty->display('contratos/armar.html');
	die();
	
?>