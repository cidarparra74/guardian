<?php
$mailSender = '';
/* // para php.ini:
[Date]
; Defines the default timezone used by the date functions
date.timezone = "America/La_Paz"
*/

ini_set('date.timezone', 'America/La_Paz');

function error_html($msg, $sql){
	require_once("../templates/sql_err.html");
	die();
}

//================ Funcion para consultas Select =================
function consulta($sql)
{
	global $link;
	$cresult = $link->query($sql);
	$db = new DB();
	$err = $db->isError($cresult);
	if ($err){
		$errmsg =$cresult->getMessage();
		//$b64 = $cresult->getDebugInfo();
		//$b64 = base64_encode($b64);
		// $errmsg .= '<br/><br/><b>Debug Info:</b>&nbsp;<span>'.$b64.'</span>';
		error_html( $errmsg,$sql );
		}
	return $cresult;	
}

//============================ funcion que devuelve la fila completa de una consulta anterior ====================
function registro(& $result)
{
	//global $query;
	//return $result->fetchRow(DB_FETCHMODE_ASSOC);
	
} 
//============================= Funcion para consultas insert, create , update, alter tablas =============
function ejecutar($sql)
{
 	global $link;
	//$sql = filterXSS($sql);
	//$sql = htmlspecialchars($sql, ENT_NOQUOTES);
	$cresult = $link->query($sql);
	$db = new DB();
	$err = $db->isError($cresult);
	if ($err){
		$errmsg =$cresult->getMessage();
		error_html( $errmsg,$sql );
	}else{
		bitacora($sql);
	}
}
//============================= Funcion para consultas insert, create , update, alter tablas =============
function ejecutar_sin_filter($sql)
{
 	global $link;
	$cresult = $link->query($sql);
	$db = new DB();
	$err = $db->isError($cresult);
	if ($err){
		$errmsg =$cresult->getMessage();
		error_html( $errmsg ,$sql);
	}else{
		bitacora($sql);
	}
}
//============================= Funcion para consultas insert, create , update, alter tablas =============
function ejecutar_con_filter($sql)
{
 	global $link;
	$sql = filterXSS($sql);
	$cresult = $link->query($sql);
	$db = new DB();
	$err = $db->isError($cresult);
	if ($err){
		$errmsg =$cresult->getMessage();
		error_html( $errmsg,$sql );
	}else{
		bitacora($sql);
	}
}
//============================= Funcion para consultas insert, create , update, alter tablas desde el WS =============
function ejecutarWS($sql)
{
 	global $link;
	$cresult = $link->query($sql);
	$db = new DB();
	$err = $db->isError($cresult);
	if ($err){
		return 1;
	}else{
		return 0;
	}
}
//================================ funcion para cerrar la conexion a la base de datos ==============================
function cerrar()
{
 global $link;
 //mssql_close($link); 
 unset($link);
}

//=================================== funcion para control de errores =====================================
function error()
{
 global $link;
 $cadenaerror = mssql_errormessage($link);	
 return $cadenaError;
}

//============================= registro de la bitacora de actualizaciones a la DB =======================
function bitacora($consultasql)
{
	$_SESSION["authenticated"] = true;
	global $link;
	if(isset($_SESSION["idusuario"])){
	$idusbtcr= $_SESSION["idusuario"];
	$fechabtcr = date("Y-m-d H:i:s");
	$fechabtcr = "CONVERT(DATETIME,'$fechabtcr',102)";
	$csql = str_replace('"','�',$consultasql) ;
	$csql = str_replace("'",'�',$csql) ;
	$cresult=$link->query("INSERT INTO bitacora (fecha, idusuario, consultasql, idmodulo) VALUES ($fechabtcr, '$idusbtcr', '$csql', 0)");
	}
	//	if (DB::isError($cresult)) die( " No se pudo realizar la consulta en script INSERT INTO bitacora (fecha, idusuario, consultasql, idmodulo) VALUES ($fechabtcr, '$idusbtcr', '$csql', 0) " );
}


/**
* Removes all XSS attacks that came in the input.
*
* Function taken from:
*
* http://quickwired.com/smallprojects/php_xss_filter_function.php
*
* @param mixed $val The Value to filter
* @return mixed
*/
function filterXSS($val) {
	 // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
	 // this prevents some character re-spacing such as <java\0script>
	 // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
	 // htmlspecialchars
	 $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);
	 
	 // straight replacements, the user should never need these since they're normal characters
	 // this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
	 $search = 'abcdefghijklmnopqrstuvwxyz';
	 $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	 $search .= '1234567890!@#$%^&*()';
	 $search .= '~`";:?+/={}[]-_|\'\\';
	 for ($i = 0; $i < strlen($search); $i++) {
		 // ;? matches the ;, which is optional
		 // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
		 
		 // &#x0040 @ search for the hex values
		 $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
		 // @ @ 0{0,7} matches '0' zero to seven times
		 $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
	 }

	 // now the only remaining whitespace attacks are \t, \n, and \r   ---- quitado el 'xml', 'link'
	 $ra1 = Array('javascript', 'vbscript', '--', 'applet', 'blink', 'embed', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound');
	 $ra2 = Array('onabort', 'onactivate', 'onafterupdate', 'onbeforeactivate',  'onbeforedeactivate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'ondblclick', 'ondrag', 'ondrop', 'onerror', 'onfilterchange', 'onfinish', 'onfocus', 'onkeydown', 'onkeypress', 'onkeyup',  'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
	 $ra = array_merge($ra1, $ra2);
	 $found = true; // keep replacing as long as the previous round replaced something

	 while ($found == true) {
		$val_before = $val;
		for ($i = 0; $i < sizeof($ra); $i++) {
			$pattern = '/';
			for ($j = 0; $j < strlen($ra[$i]); $j++) {
				if ($j > 0) {
					$pattern .= '(';
					$pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
					$pattern .= '|(&#0{0,8}([9][10][13]);?)?';
					$pattern .= ')?';
				}
				$pattern .= $ra[$i][$j];
			}
			$pattern .= '/i';
			$replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
			$val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
			if ($val_before == $val) {
				// no replacements were made, so exit the loop
				$found = false;
			}
		}
	 }

	 return $val;
}

function enviaCorreo(){
	$sql = "SELECT TOP 1 enable_mail, mail_smtp, mail_remite FROM opciones" ;
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	global $mailSender;
	if($row["enable_mail"]=='S'){
		//establecemos servidor
		ini_set('SMTP',$row["mail_smtp"]);
		$mailSender=$row["mail_remite"];
		return true;
	}else{
		return false;
	}
}
?>