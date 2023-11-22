<?php
$partes = array();

//verificamos si el contrato tiene partes
if(isset($_REQUEST["hdnCi"])){
	//tiene partes
	//recuperamos todo el conjunto de datos dispuestos en arreglos
	$hdnCi=$_REQUEST["hdnCi"];
	$hdnEmi=$_REQUEST["hdnEmi"];
	$hdnTipo=$_REQUEST["hdnTipo"];
	$hdnTipoLit=$_REQUEST["hdnTipoLit"];
	$hdnProcede=$_REQUEST["hdnProcede"];
	$hdnPais=$_REQUEST["hdnPais"];
	$hdnOcupa=$_REQUEST["hdnOcupa"];
	$hdnDirec=$_REQUEST["hdnDirec"];
	$hdnEstCivil=$_REQUEST["hdnEstCivil"];
	$hdnRol=$_REQUEST["hdnRol"];
	$hdnNombre=$_REQUEST["hdnNombre"];
	//$i=0;
	//recorremos el arreglo de los CI y utilizamos el mismo indice para los demas
	//nor armamos un arreglo de partes
	
	foreach($hdnCi as $key => $ci){
		$nombre = strtoupper($hdnNombre[$key]);
		$redaccion = $nombre." con ".$hdnTipoLit[$key]. " Nro. ".$ci." ".$hdnEmi[$key].
			" .|.mayor de edad, estado civil ".$hdnEstCivil[$key]." de nacionalidad ".$hdnProcede[$key].
			" profesión u ocupación ".$hdnOcupa[$key].", hábil por derecho, con domicilio en".
			$hdnDirec[$key].", .|.que en adelante se denominará ".$hdnRol[$key];
		
		$partes[] = array('ci' => $ci,
						'emi' => $hdnEmi[$key],   
						'tipo' => $hdnTipo[$key],
						'procede' => $hdnProcede[$key],
						'pais' => $hdnPais[$key],
						'ocupa' => $hdnOcupa[$key],
						'direc' => $hdnDirec[$key],
						'eciv' => $hdnEstCivil[$key], 
						'rol' => $hdnRol[$key],  
						'nombre' => $nombre,  
						'redaccion' => $redaccion);  
		//$i++;
	}
	
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

// leemos el contenido de cada clausula y reemplazamos los valores de las variables
//
//seleccionamos todas las clausulas del contrato, mas las opcionales seleccionadas, mas los incisos seleccionados
	$sql="SELECT r.idclausula, r.posicion, cl.titulo, nu.idnumeral, nu.titulo as inciso, cl.contenido as contcla, nu.contenido as contnum
FROM rel_cc r INNER JOIN clausula cl ON r.idclausula=cl.idclausula 
LEFT JOIN (SELECT IDCLAUSULA, nro_correlativo, idnumeral, titulo, contenido 
FROM numeral WHERE idnumeral IN ($incisos)) nu ON nu.idclausula=cl.idclausula 
WHERE r.idcontrato= $idcontrato AND (r.opcional=0 OR cl.idclausula IN ($opcional))  ORDER BY r.posicion, nu.nro_correlativo";
	$query = consulta($sql);
	//echo $sql;
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
			
	//		foreach($principal as $key => $valor){
	//			if($valor["id"]==$idclau && $valor["idnumeral"]=='0' && $valor["idtexto"]!=''){
	//				//reemplazamos el contenido
	//				$esto = "<<".$valor["idtexto"].",0>>";
	//				$poresto = $valor["contenido"];
	//				$texto = str_replace($esto, $poresto, $texto);
	//			}
	//		}
			//vemos si hay se ha armado incisos para la clausula anterior
			if($txtinciso != ''){
				//reemplazamos en la clausula anterior, indicada por $cci-1
				$esto = "<<INCISOS,3>>";
				$textoAnt = str_replace($esto, $txtinciso, $clausulas[$cci-1]['texto']);
				$clausulas[$cci-1]['texto'] = $textoAnt;
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
			$idnumeral = $row["idnumeral"];
			$texto = $row["contnum"];
			$idnumeral = $row["idnumeral"];
			//armamos el texto para reemplazarlo en la clausula
			//Buscamos el primer caracter en blanco (desde ahi normalmente empieza la clausula
			$posi = stripos($texto, ' ');
			if ($posi !== false) {
		//		echo "$posi";
				//tomamos a partir del caracter en blanco adelante
				$txtinciso .= substr($texto,$posi+1);
				$txtinciso = str_replace('}', '', $txtinciso);
			}


			//buscamos si tiene variables
	//		foreach($principal as $key => $valor){
	//			if($valor["id"]==$idclau && $valor["idnumeral"]==$idnumeral && $valor["idtexto"]!=''){
	//				//reemplazamos el contenido
	//				$esto = "<<".$valor["idtexto"].",0>>";
	//				$poresto = $valor["contenido"];
	//				$texto = str_replace($esto, $poresto, $texto);
	//			}
	//		}
		/*	$clausulas[]= array('id' => $idclau,
							'titulo' => $row["inciso"],
							'idnumeral' => $idnumeral,
							'texto' => $texto,
						'posicion'=>$row["posicion"]);	*/
		}
		
	}
	//unset($valor);
//
//hasta aqui ya estan preparadas las claausulas para generar el contrato

//armar el XML
//suponiendo que el encabezado es siempre elmismo para cualquier contrato, tenemos:
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
					'cabecera' 		=> $row["cabecera"],
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

//armamos el XML base con DOM:


//creamos el DOM
$doc = new DOMDocument('1.0', 'UTF-8');
$doc->formatOutput = true;
$raiz = $doc->createElement('NewDataSet'); //Creamos un elemento
$doc->appendChild($raiz); //lo pegamos al documento raiz
//$raiz->setAttribute("xmlns", "http://10.0.0.9/");
	//*******************************************************
	//para la parte donde ira la cabecera del dataset
	$nivel0 = $doc->createElement("skema"); //creamos un nivel 
		//$elemento = $doc->createElement("skemaNode"); //creamos elemento para el nivel0
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
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['cabecera'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("pie"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['pie'])   );
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
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['cabecera2'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("pie2"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['pie2'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("numerar_clausulas"); 
	$elemento->appendChild (  $doc->createTextNode( $elcontrato['numerar_clausulas'])   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("cprotocolo"); 
	$elemento->appendChild (  $doc->createTextNode( '')   );
		$nivel2->appendChild($elemento);
	$elemento = $doc->createElement("pprotocolo"); 
	$elemento->appendChild (  $doc->createTextNode( '')   );
		$nivel2->appendChild($elemento);
	 
	//- <contrato diffgr:id="contrato1" msdata:rowOrder="0" diffgr:hasChanges="inserted">
		//atributos del contrato
	//	$nivel2->setAttribute("diffgr:id", "contrato1");
	//	$nivel2->setAttribute("msdata:rowOrder", "0");
	//	$nivel2->setAttribute("diffgr:hasChanges", "inserted");
//adicionamos ala raiz el nivel 1	
$nivel1->appendChild($nivel2);   //pegamos a la raiz


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
	$ccontent = utf8_encode($valor['texto']);
//	$ccontent = str_replace("'","''''",$ccontent);
	$elemento->appendChild (  $doc->createTextNode( $ccontent)   );
		$nivel2->appendChild($elemento);
	//atributos de la clausula
	//	$nivel2->setAttribute("diffgr:id", "clausulas".$i);
	//	$nivel2->setAttribute("msdata:rowOrder", ($i-1));
	//	$nivel2->setAttribute("diffgr:hasChanges", "inserted");
	//<clausulas diffgr:id="clausulas6" msdata:rowOrder="5" diffgr:hasChanges="inserted">
	//adicionamos el nodo al contenedor de clausulas	
	$nivel1->appendChild($nivel2);
}//foreach
unset($valor);
//fin ciclo

// para las variables, recorremos $principal where idtexto!=''
$i=0;
foreach($principal as $valor){
	if($valor['idtexto']!=''){
		$i++;
		///
		$nivel2 = $doc->createElement("variables"); 
		///
		$elemento = $doc->createElement("idclausula");
		$elemento->appendChild (  $doc->createTextNode( $valor['id'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("tipo");
		$elemento->appendChild (  $doc->createTextNode( $valor['tipo'])   );
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
		$elemento->appendChild (  $doc->createTextNode( utf8_encode($valor['descripcion']))   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("contenido");
		$elemento->appendChild (  $doc->createTextNode( utf8_encode($valor['contenido']))   );
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
		$elemento->appendChild (  $doc->createTextNode('')   );
			$nivel2->appendChild($elemento);
		//atributos de las variables
	//	$nivel2->setAttribute("diffgr:id", "variables".$i);
	//	$nivel2->setAttribute("msdata:rowOrder", ($i-1));
	//	$nivel2->setAttribute("diffgr:hasChanges", "inserted");
		//adicionamos a la raiz el nivel 1	
		$nivel1->appendChild($nivel2);
	}
}
unset($valor);
//para las partes

$i=0;
foreach($partes as $valor){

		$i++;
		$nivel2 = $doc->createElement("personas"); //creamos un nivel
		///
		$elemento = $doc->createElement("posicion");
		$elemento->appendChild (  $doc->createTextNode( $i   ));
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("ci");
		$elemento->appendChild (  $doc->createTextNode( $valor['ci'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("expedido");
		$elemento->appendChild (  $doc->createTextNode($valor['emi'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("nombre");
		$elemento->appendChild (  $doc->createTextNode( $valor['nombre'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("domicilio");
		$elemento->appendChild (  $doc->createTextNode(utf8_encode($valor['direc']))   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("calidad");
		$elemento->appendChild (  $doc->createTextNode( utf8_encode($valor['rol']))   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("parrafo");
		$elemento->appendChild (  $doc->createTextNode( '')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("dom_especial");
		$elemento->appendChild (  $doc->createTextNode( '')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("edocivil");
		$elemento->appendChild (  $doc->createTextNode($valor['eciv'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("nacionalidad");
		$elemento->appendChild (  $doc->createTextNode($valor['procede'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("profesion");
		$elemento->appendChild (  $doc->createTextNode(utf8_encode($valor['ocupa']))   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("tipo_documento");
		$elemento->appendChild (  $doc->createTextNode($valor['tipo'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("pais");
		$elemento->appendChild (  $doc->createTextNode($valor['pais'])   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("redaccion");
		$elemento->appendChild (  $doc->createTextNode(utf8_encode($valor['redaccion']))   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("razonsocial");
		$elemento->appendChild (  $doc->createTextNode('')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("nit");
		$elemento->appendChild (  $doc->createTextNode('')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("nromatricula");
		$elemento->appendChild (  $doc->createTextNode('')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("personanatural");
		$elemento->appendChild (  $doc->createTextNode('1')   );
			$nivel2->appendChild($elemento);
		$elemento = $doc->createElement("representante");
		$elemento->appendChild (  $doc->createTextNode('')   );
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
//$nivel1->setAttribute("xmlns", "");
// <NewDataSet xmlns="">

//$nivel0->appendChild($nivel1);
//$nivel0->setAttribute("xmlns:msdata", "urn:schemas-microsoft-com:xml-msdata");
//$nivel0->setAttribute("xmlns:diffgr", "urn:schemas-microsoft-com:xml-diffgram-v1");
//<diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">
$raiz->appendChild($nivel1);
//
/// vaciamos el XML base a una variable
$XMLbase = $doc->saveXML(); //Y la salida que dará el XML 
//$doc->save("c:\Inetpub\wwwroot\guardian2\compilado\p.xml");
/// transformamos el XML base al XML final 
$XMLfinal = str_replace("<skema>skemaContent</skema>",$skemaDef,$XMLbase);
//$XMLfinal = addslashes($XMLfinal);
$XMLfinal = str_replace("<NIVELdeCONTROL>","",$XMLfinal);
$XMLfinal = str_replace("</NIVELdeCONTROL>","",$XMLfinal);
//decodificamos caracteres especiales
$XMLfinal = utf8_decode($XMLfinal);
//reemplazamos comilla simple por dos comillas simples para no generar error en sql INSERT
$XMLfinal = str_replace("'","''''",$XMLfinal);
//reemplazamos caracteres extraños
$XMLfinal = str_replace("&#13;","",$XMLfinal);
//echo $XMLfinal;
//guardamos en la tabla, antes recuperamos login del usuario en glogin
$glogin=$_SESSION['glogin'];
$fecha = date("Y-m-d H:i:s");
$fecha = "CONVERT(DATETIME,'$fecha',102)";


//en Contrato_Final:
	
//insertamos nuevo contrato, si es primera insersion
if(!isset($_SESSION['idfinal'])){
$sql = "INSERT INTO contrato_final (idcontrato, login, fechahora, contenido_sec, firmado, eliminado, ultimo_login) 
VALUES ('$idcontrato', '$glogin', $fecha, '$XMLfinal', 0, 0, '$glogin')";
ejecutar($sql);
//obtenemos el idfinal
	$sql = "SELECT max(idfinal) as ultimo FROM contrato_final";
	$query = consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$idfinal = $row["ultimo"];
	//echo $idfinal;
	$_SESSION['idfinal'] = $idfinal;
}else{
	$sql = "UPDATE contrato_final SET fechahora=$fecha, contenido_sec='$XMLfinal' WHERE idfinal='$idfinal'";
	ejecutar($sql);
}
	//llamar al Web Service para crear el doc en WORD
$resulta=0;
require('ws_sec.php');
//mostrar el DOC
if($resulta==0){
	//se ha generado el DOC
	$alert = "Se ha guardado el contrato, puede abrir el documento en MS-WORD";
}else{
	$alert =  "Atenci&oacute;n! Se ha guardado el contrato pero no se pudo generar el documento en MS-WORD";
}

//$contrato = $_SESSION['contrato'];
$smarty->assign('contrato',$contrato);
$smarty->assign('alert',$alert);
$smarty->assign('idfinal',$idfinal);
	$smarty->display('contratos/armar.html');
	die();
	
?>