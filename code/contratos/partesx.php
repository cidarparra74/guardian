<?
require_once('xajax/xajax.inc.php'); //incluimos la libreria xajax

function eliminarFila($id_campo, $cant_campos){
	$respuesta = new xajaxResponse();
	$respuesta->addRemove("rowDetalle_$id_campo"); //borro el detalle que indica el parametro id_campo
	-- $cant_campos; //Resto uno al numero de campos y si es cero borro todo
	if($cant_campos == 0){
		$respuesta->addRemove("rowDetalle_0");
		$respuesta->addAssign("num_campos", "value", "0"); //dejo en cero la cantidad de campos para seguir agregando si asi lo desea el usuario
		$respuesta->addAssign("cant_campos", "value", "0");
	}
    $respuesta->addAssign("cant_campos", "value", $cant_campos);    
	return $respuesta;
}

function cancelar(){  //elimina todo el contenido de la tabla y vuelve a cero los contadores
    
    $respuesta = new xajaxResponse();

    $respuesta->addRemove("tbDetalle"); //vuelve a crear la tabla vacia
    $respuesta->addCreate("tblDetalle", "tbody", "tbDetalle");
    $respuesta->addAssign("num_campos", "value", "0");
	$respuesta->addAssign("cant_campos", "value", "0");
    return $respuesta;
}

function agregarFila($formulario){
    $respuesta = new xajaxResponse();    
	extract($formulario);	
	$id_campos = $cant_campos = $num_campos+1;
    $str_html_td1 = $txtNombre . '<input type="hidden" id="hdnNombre_' . $id_campos . '" name="hdnNombre_' . $id_campos . '" value="' . $txtNombre . '"/>' ;
    $str_html_td2 = "$txtEdad" . '<input type="hidden" id="hdnEdad_' . $id_campos . '" name="hdnEdad_' . $id_campos . '" value="' . $txtEdad . '"/>' ;
    $str_html_td3 = "$txtDireccion" . '<input type="hidden" id="hdnDireccion_' . $id_campos . '" name="hdnDireccion_' . $id_campos . '" value="' . $txtDireccion . '"/>' ;
    $str_html_td4 = "$selSexo" . '<input type="hidden" id="hdnSexo_' . $id_campos . '" name="hdnSexo_' . $id_campos . '" value="' . $selSexo . '"/>' ;
    $str_html_td5 = "$selEstCivil" . '<input type="hidden" id="hdnEstCivil_' . $id_campos . '" name="hdnEstCivil_' . $id_campos . '" value="' . $selEstCivil . '"/>' ;
    $str_html_td6 = '<img src="images/actions/delete.png" width="16" height="16" alt="Eliminar" onclick="if(confirm(\'Realmente desea eliminar este detalle?\')){xajax_eliminarFila('. $id_campos .', proyecto.cant_campos.value);}"/>';
    $str_html_td6 .= '<input type="hidden" id="hdnIdCampos_'. $id_campos .'" name="hdnIdCampos[]" value="'. $id_campos .'" />';

    if($num_campos == 0){ // creamos un encabezado de lo contrario solo agragamos la fila
		$respuesta->addCreate("tbDetalle", "tr", "rowDetalle_0");
        $respuesta->addCreate("rowDetalle_0", "th", "tdDetalle_01");    //creamos los campos
        $respuesta->addCreate("rowDetalle_0", "th", "tdDetalle_02");
        $respuesta->addCreate("rowDetalle_0", "th", "tdDetalle_03");
        $respuesta->addCreate("rowDetalle_0", "th", "tdDetalle_04");
        $respuesta->addCreate("rowDetalle_0", "th", "tdDetalle_05");
        $respuesta->addCreate("rowDetalle_0", "th", "tdDetalle_06");

        $respuesta->addAssign("tdDetalle_01", "innerHTML", "Nombre");   //asignamos el contenido
        $respuesta->addAssign("tdDetalle_02", "innerHTML", "Edad");
        $respuesta->addAssign("tdDetalle_03", "innerHTML", "Direccion");
        $respuesta->addAssign("tdDetalle_04", "innerHTML", "Sexo");
        $respuesta->addAssign("tdDetalle_05", "innerHTML", "Estado Civil");
        $respuesta->addAssign("tdDetalle_06", "innerHTML", "Eliminar");
	}
    $idRow = "rowDetalle_$id_campos";
    $idTd = "tdDetalle_$id_campos";
	$respuesta->addCreate("tbDetalle", "tr", $idRow);
    $respuesta->addCreate($idRow, "td", $idTd."1");     //creamos los campos
    $respuesta->addCreate($idRow, "td", $idTd."2");
    $respuesta->addCreate($idRow, "td", $idTd."3");
    $respuesta->addCreate($idRow, "td", $idTd."4");
    $respuesta->addCreate($idRow, "td", $idTd."5");
    $respuesta->addCreate($idRow, "td", $idTd."6");
/*
 *     Esta parte podria estar dentro de algun ciclo iterativo  */
    
    $respuesta->addAssign($idTd."1", "innerHTML", $str_html_td1);   //asignamos el contenido
    $respuesta->addAssign($idTd."2", "innerHTML", $str_html_td2);
    $respuesta->addAssign($idTd."3", "innerHTML", $str_html_td3);
    $respuesta->addAssign($idTd."4", "innerHTML", $str_html_td4);
    $respuesta->addAssign($idTd."5", "innerHTML", $str_html_td5);
    $respuesta->addAssign($idTd."6", "innerHTML", $str_html_td6);

/*  aumentamos el contador de campos  */

	$respuesta->addAssign("num_campos","value", $id_campos);
	$respuesta->addAssign("cant_campos" ,"value", $id_campos);    
	return $respuesta;
}

function guardar($formulario){
//comentaré todo lo que tenga que ver con la Base de Datos
    $flag = 0;
    extract($formulario);
    $respuesta = new xajaxResponse();
//    $conn = new conexionBD ( ); //Genera una nueva coneccion
//	$conn->EjecutarSQL("BEGIN TRANSACTION A1");

// al guardar los numeros de las lineas nos aseguramos que si borran una no perderemos las referencias.
    foreach($hdnIdCampos as $id){      // Así recorro cada campo en cada linea
//	Guardo la consulta en una cadena
        $Str_SQL = "INSERT INTO PERSONAS(
                        NOMBRE, EDAD, DIRECCION,
                        SEXO, ESTCIVIL)
                        VALUES(
                        '" . $formulario['hdnNombre_' . $id] . "', '" . $formulario['hdnEdad_' . $id] . "', '" . $formulario['hdnDireccion_' . $id] . "',
                        '" . $formulario['hdnSexo_' . $id] . "', '". $formulario['hdnEstCivil_' . $id] . "')";

//        if(!$conn->EjecutarSQL($Str_SQL)){  //CONTROL DE ERRORES.  muy importante, si no guarda uno, no guarda nada.
//            $conn->EjecutarSQL("ROLLBACK TRANSACTION A1");
//            $flag = 1;
//            $MSG = "Ha ocurrido un error al insertar los datos de la persona.\nPor favor, intentelo nuevamente.";
//        }
    $respuesta->addAlert($Str_SQL);
	}
    if($flag == 0){
//		$conn->EjecutarSQL("COMMIT TRANSACTION A1");
		$MSG = "Datos guardados con exito";
	}
    
    $respuesta->addAlert($MSG);
    return $respuesta;

}



$xajax=new xajax();         // Crea un nuevo objeto xajax
$xajax->setCharEncoding("iso-8859-1"); // le indica la codificación que debe utilizar
$xajax->decodeUTF8InputOn();            // decodifica los caracteres extraños
$xajax->registerFunction("agregarFila"); //Registramos la función para indicar que se utilizará con xajax.
$xajax->registerFunction("cancelar");
$xajax->registerFunction("eliminarFila");
$xajax->registerFunction("guardar");
$xajax->processRequests();
?>

<html>
<meta http-equiv="Pragma"content="no-cache">
<meta http-equiv="expires"content="0">
<head>
<?php $xajax->printJavascript("xajax"); //imprime el codigo javascript necesario para que funcione todo. ?>
<LINK REL='stylesheet' type='text/css' href='../styles/php.css'>
</head>
<body>
<div align="center">
<form name="proyecto" id="proyecto" action="" method="post">
    <input type="hidden" id="num_campos" name="num_campos" value="0" />
    <input type="hidden" id="cant_campos" name="cant_campos" value="0" />
<table width="620" border="1" cellpadding="0" cellspacing="0" class="borde_lista">
	<tr class="tabla_titulo_color">
		<td width="620" colspan="2" valign="middle" height="30" class="borde_lista"><div align="center"> 
            Adici&oacute;n de Partes</div>
		</td>
	</tr>
	<tr class="tabla_titulo_contenido"> 
        <td width="200" class="borde_tabla" align="right">Nombre:</td>
		<td width="420" class="borde_tabla">
        <input type="text" id="txtNombre" name="txtNombre" value="" class="input" /> </td>
      </tr>
	<tr class="tabla_titulo_contenido"> 
        <td width="200" class="borde_tabla" align="right">Edad:</td>
		<td width="420" class="borde_tabla">
        <input type="text" id="txtEdad" name=" align="right"txtEdad" value="" class="input" /> </td>
      </tr>
	 <tr class="tabla_titulo_contenido"> 
        <td width="200" class="borde_tabla" align="right">Direccion:</td>
		<td width="420" class="borde_tabla">
        <input type="text" id="txtDireccion" name="txtDireccion" value="" class="input" /> </td>
      </tr>
	 <tr class="tabla_titulo_contenido"> 
        <td width="200" class="borde_tabla" align="right">Sexo:</td>
		<td width="420" class="borde_tabla">
            <select id="selSexo" name="selSexo" class="input">
                <option value="-">Seleccione</option>
                <option value="Hombre">Hombre</option>
                <option value="Mujer">Mujer</option>
            </select></td>
      </tr>
	 <tr class="tabla_titulo_contenido"> 
        <td width="200" class="borde_tabla" align="right">Estado Civil:</td>
		<td width="420" class="borde_tabla">
            <select id="selEstCivil" name="selEstCivil" class="input">
                <option value="-">Seleccione</option>
                <option value="Soltero">Soltero</option>
                <option value="Casado">Casado</option>
                <option value="Viudo">Viudo</option>
            </select> </td>
      </tr>
	  <tr class="tabla_titulo_contenido">
		<td width="620" colspan="2" valign="middle" height="30" class="borde_tabla" align="center"> 
        <input type="button" id="btnAgregar" name="btnAgregar" value="Agregar Persona" class="boton" onclick="xajax_agregarFila(xajax.getFormValues('proyecto'));" />&nbsp;
   
		</td>
	</tr>
</table>
<br><br>
     <table width="700" id="tblDetalle" class="listado"><tbody id="tbDetalle"></tbody></table>
	<br><br> 
	 <input type="button" id="btnAgregar" name="btnAgregar" value="Guardar" class="boton" onclick="xajax_guardar(xajax.getFormValues('proyecto'));" />&nbsp;
	<input type="reset" id="btnCancel" name="btnCancel" value="Cancelar" class="boton" onclick="if(confirm('Realmente desea vaciar todo el detalle?')){xajax_cancelar();}" />&nbsp;
	
<input type="submit" name="adicionar6" value="Siguiente" class="boton">

</form>
</div>
</body>
</html>