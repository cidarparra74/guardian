<?php
	//ARMADO DEL CONTRATO FINAL (XML)
	
$partes = array();
ini_set('odbc.defaultlrl','1048576');
//verificamos si el contrato tiene partes
if(isset($_REQUEST["hdnCi"])){
	//tiene partes
	//recuperamos todo el conjunto de datos dispuestos en arreglos
	$hdnCi=$_REQUEST["hdnCi"];
	$hdnEmi=$_REQUEST["hdnEmi"];
	$hdnTipo=$_REQUEST["hdnTipo"];
//	$hdnTipoLit=$_REQUEST["hdnTipoLit"];
	$hdnProcede=$_REQUEST["hdnProcede"];
	$hdnPais=$_REQUEST["hdnPais"];
	$hdnOcupa=$_REQUEST["hdnOcupa"];
	$hdnDirec=$_REQUEST["hdnDirec"];
	$hdnEstCivil=$_REQUEST["hdnEstCivil"];
	$hdnRol=$_REQUEST["hdnRol"];
	$hdnNombre=$_REQUEST["hdnNombre"];
	$hdnRedaccion=$_REQUEST["hdnRedaccion"];
	$hdnParrafo=$_REQUEST["hdnParrafo"];
	$hdnControl=$_REQUEST["hdnControl"];
	$elNit=$_REQUEST["elNit"];  //indica el codigo del tipo de doc para NIT (si esta definido, si no =0)
	$posi=$_REQUEST["posi"];
	//print_r($posi);
	//die;
	//recorremos el arreglo de los CI y utilizamos el mismo indice para los demas
	//nor armamos un arreglo de partes
	
	foreach($hdnCi as $key => $ci){
		$nombre = strtoupper($hdnNombre[$key]);
		//buscamos el literal del tipo de documento
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
				
				if(strpos($hdnRedaccion[$key],'.|.')==0){
					$redaccion = $nombre." con ".$tipolit. " Nro. ".$ci." ".$hdnEmi[$key].
					" ".$hdnRedaccion[$key].", .|.que en adelante se denominará ".$hdnRol[$key];
				}else{
					$redaccion = $hdnRedaccion[$key];
				}

				$partes[] = array('posi' => $posi[$key],
								'ci' => $ci,
								'emi' => $hdnEmi[$key],   
								'tipo' => $hdnTipo[$key],
								'procede' => $hdnProcede[$key],
								'pais' => $hdnPais[$key],
								'ocupa' => $hdnOcupa[$key],
								'direc' => $hdnDirec[$key],
								'eciv' => $hdnEstCivil[$key], 
								'rol' => $hdnRol[$key],  
								'nombre' => $nombre,  
								'redaccion' => $redaccion,  
								'parrafo' => $hdnParrafo[$key]);  

				//actualizamos tabla personas
				//insertamos si es nuevo, actualizamos si ya existe
				$emi = $hdnEmi[$key];
				$sql = "SELECT ci FROM persona WHERE ci = '$ci'";
				//echo $sql;
				$query = consulta($sql);
				$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
				if($ci == $row['ci']){
					//existe => actualizamos
					//esto optimizar para guardadr solo si se hicieron cambios !!!!!
					$sql="UPDATE persona SET tipo_documento='".$hdnTipo[$key]."', pais='".$hdnPais[$key].
					"', nombre='$nombre', domicilio='".$hdnDirec[$key]."', edocivil='".$hdnEstCivil[$key].
					"', nacionalidad='".$hdnProcede[$key]."', profesion='".$hdnOcupa[$key].
					"', parrafo='".$hdnParrafo[$key]."' WHERE ci = '$ci' AND personanatural = '1'";
				}else{
					//no existe => insertamos
					$sql="INSERT INTO persona (
					ci, expedido, tipo_documento, pais, nombre, domicilio, edocivil, dom_especial, nacionalidad, profesion, nit, razonsocial, nromatricula, personanatural, representante, parrafo) VALUES (
					'$ci','$emi','".$hdnTipo[$key]."','".$hdnPais[$key]."','$nombre','".$hdnDirec[$key]."', '".$hdnEstCivil[$key]."','','".$hdnProcede[$key]."','".$hdnOcupa[$key]."','0','','','1','','".$hdnParrafo[$key]."')";
				}
				ejecutar($sql);
		}else{
			//persona juridica
			
			if(strpos($hdnRedaccion[$key],'.|.')==0){
					$redaccion = $nombre." con NIT Nro. ".$ci.", Matricula de Comercio Nro. ".$hdnProcede[$key].", con domicilio en ".$hdnDirec[$key].", representada(o) por ".$hdnParrafo[$key].", .|.que en adelante se denominará ".$hdnRol[$key];
				}else{
					$redaccion = $hdnRedaccion[$key];
				}
			$partes[] = array('posi' => $posi[$key], 
								'ci' => $ci, //es el NIT
								'emi' => '--',  //en blanco 
								'tipo' => $elNit, //por defecto el tipo de doc es NIT jala de la tabla tipo_documento
								'procede' => $hdnProcede[$key], //es la matricula
								'pais' => $hdnPais[$key], //el pais
								'ocupa' => '', //en blanco
								'direc' => $hdnDirec[$key], //es el domicilio
								'eciv' => $hdnEstCivil[$key],  // en blanco
								'rol' => $hdnRol[$key],    // el rol
								'nombre' => $nombre,  // la razon social
								'redaccion' => $redaccion,  // redaccion
								'parrafo' => $hdnParrafo[$key]);   //es representante
								
				//actualizamos tabla personas
				//insertamos si es nuevo, actualizamos si ya existe AND personanatural = '2'
				$emi = $hdnEmi[$key];
				$sql = "SELECT ci FROM persona WHERE ci = '$ci'";
				$query = consulta($sql);
				$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
				if($ci == $row['ci']){
					//existe => actualizamos
					//esto optimizar para guardadr solo si se hicieron cambios !!!!!
					$sql="UPDATE persona SET tipo_documento='$elNit', pais='".$hdnPais[$key].
					"', nombre='$nombre', razonsocial='$nombre' , dom_especial='".$hdnDirec[$key].
					"', nromatricula='".$hdnProcede[$key]."', representante='".
					$hdnParrafo[$key]."' WHERE ci = '$ci' AND personanatural = '2'";
				}else{
					//no existe => insertamos
					$sql="INSERT INTO persona (
					ci, expedido, tipo_documento, pais, nombre, domicilio, edocivil, dom_especial, nacionalidad, profesion, nit, razonsocial, nromatricula, personanatural, representante, parrafo) VALUES (
					'$ci','','$elNit','".$hdnPais[$key]."','$nombre','', '','".$hdnDirec[$key]."','','','$ci','$nombre','".$hdnProcede[$key]."','2','".$hdnParrafo[$key]."','')";
				}
				ejecutar($sql);
		}
	}
	asort($partes);
	//echo "<pre>";
	//print_r($partes);
	//die();
}else{
	//no existen partes en el contrato
}
//guardamos las partes en una sesion
$_SESSION["partes"] = $partes;

//recuperamos todo lo generado
$idfinal = $_SESSION['idfinal'];
$idcontrato = $_SESSION['idcontrato'];
$contrato = $_SESSION['contrato'];
$principal = $_SESSION['principal'];


//jalamos el XML del contrato original
$sql= "SELECT f.contenido_sec, patindex('%<variables>%', f.contenido_sec)-4 as final  
		FROM contrato_final f WHERE f.idfinal='$idfinal'";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);

$final = $row['final'];
$contenido_sec = $row['contenido_sec'];
//hasta donde leer
//$final = strpos($contenido_sec, "</NewDataSet>")-1;
//$longitud = $final - $inicio ;

$contenidoXml = substr($contenido_sec, 0, $final);
	
/*	
	$arxiu="archivop.txt";
	$f = fopen($arxiu,"w");
	fputs($f,$contenidoXml);
	fclose($f);
*/

//creamos el DOM
$doc = new DOMDocument('1.0', 'UTF-8');
$doc->formatOutput = true;
$raiz = $doc->createElement('NewDataSet'); //Creamos un elemento
$doc->appendChild($raiz); //lo pegamos al documento raiz

//eliminamos de $principal las mismas variables dentro una misma clausula
$idc='0';
$var='x';
foreach($principal as $key => $valor){
	if($valor['id']==$idc && $valor['idtexto']==$var){
		$principal[$key]['idtexto'] ='';
	}
	$idc=$valor['id'];
	$var=$valor['idtexto'];
}

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
		$raiz->appendChild($nivel2);
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
			$raiz->appendChild($nivel2);
			//$nivel1->appendChild($nivel2);
			$j++;
		}
		$i++;
		}
	}
}
unset($valor);
unset($adicional);

//$raiz->appendChild($nivel2);

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
		
		//adicionamos a la raiz el nivel 1	
		$raiz->appendChild($nivel2);
		//
}
unset($valor);

//$raiz->appendChild($nivel2);
//
/// vaciamos el XML base a una variable
$XMLbase = $doc->saveXML(); //Y la salida que dará el XML 
//$doc->save("c:\appserv\www\guardian2\p.xml");

//$XMLfinal = addslashes($XMLfinal);
$XMLfinal = str_replace("<NewDataSet>","",$XMLbase);
$XMLfinal = str_replace("<?xml version=\"1.0\" encoding=\"UTF-8\"?>","",$XMLfinal);
//borramos lineas vacias
$XMLfinal = str_replace("\n\n","\n",$XMLfinal);
//reemplazamos la cabecera
//$XMLfinal = str_replace("encoding=\"UTF-8\"","standalone=\"yes\"",$XMLfinal);
//para casos en que no haya seleccionado inciso
$XMLfinal = str_replace("<<INCISOS,3>>","",$XMLfinal);
//decodificamos caracteres especiales
//$XMLfinal = utf8_decode($XMLfinal);
//reemplazamos comilla simple por dos comillas simples para no generar error en sql INSERT
$XMLfinal = str_replace("'","''",$XMLfinal);
$contenidoXml = str_replace("'","''",$contenidoXml);

//reemplazamos caracteres extraños
$XMLfinal = str_replace("&#13;","",$XMLfinal);
//unimos todo
$XMLfinal = $contenidoXml . $XMLfinal ;


//guardamos en la tabla, antes recuperamos login del usuario en glogin
$glogin=$_SESSION['glogin'];
$fecha = date("Y-m-d H:i:s");
$fecha = "CONVERT(DATETIME,'$fecha',102)";


//en Contrato_Final:

//insertamos nuevo contrato, si es primera insersion
if(!isset($_SESSION['idfinal'])){
	echo "Error Idfinal sin definir.";
}else{
	$sql = "UPDATE contrato_final SET fechahora=$fecha, contenido_sec='$XMLfinal', ultimo_login='$glogin' WHERE idfinal='$idfinal'";

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
//vemos si puede abrir el .DOC
if( isset($_SESSION["quien"])){
	$smarty->assign('quien',$_SESSION["quien"]);
}else{
	$smarty->assign('quien','1');
}
/*
//vemos si hay nrocaso para BANECO
if(isset($_SESSION['nrocaso'])){
	$nrocaso = $_SESSION["nrocaso"];
	if($nrocaso!='0'){
		//ACCEDEMOS AL GUARDIAN
		unset($link);
		require('../lib/conexionMNU.php');
		//se supone que este nro de caso ya esta guardado en ncaso_cfinal de guardian
		//asi que lo actualizamos
		//$sql="INSERT INTO ncaso_cfinal (nrocaso,idfinal) VALUES ('$nrocaso','$idfinal')";
		$sql="UPDATE ncaso_cfinal SET idfinal='$idfinal' WHERE nrocaso = '$nrocaso' AND idfinal = '0'";
		ejecutar($sql);
	}
}
*/

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

$smarty->assign('tipodoc',$tipodoc);

//$contrato = $_SESSION['contrato'];
$smarty->assign('nombre',$partes[0]['nombre']);
$smarty->assign('contrato',$contrato);
$smarty->assign('alert',$alert);
$smarty->assign('idfinal',$idfinal);
	$smarty->display('contratos/armar.html');
	die();
	
?>