<?php 
//inicializando
///if(session_id()=='') session_start();
	require_once('../lib/DB.php');
	require_once("../lib/codificar.php");
	require_once("../lib/class.inputfilter.php");
	$ifilter = new InputFilter();

	$sms = "Error al conectarse con la Base de Datos. Intente lo siguiente:";
		
//permitimos solo un par de intentos
if(isset($_SESSION['zxz'])){
	$_SESSION['zxz'] = $_SESSION['zxz'] + 1 ;
}else{
	$_SESSION['zxz'] = 0;
}
if($_SESSION['zxz'] >3){
	die("Demasiados intentos fallidos... Cierre el navegador y vuelva a intentar.");
}
$oksave = 0;


if(isset($_POST['probar'])){
	$usuario = $ifilter->process($_POST['usuario']); //$_POST['usuario'];
	$password = $ifilter->process($_POST['password']); //$_POST['password'];
	$host = $ifilter->process($_POST['host']); //$_POST['host'];
	$bdName = $ifilter->process($_POST['bdName']); //$_POST['bdName'];
	$dsn = "odbc://$usuario:$password@$host/$bdName";
	$options = array(
		'debug'       => 2,
		'portability' => DB_PORTABILITY_ALL,
	);
	echo "<script>console.log('Console: " . $dsn . "' );</script>";
	$link =& (new DB)->connect($dsn, $options); //conectamos con el servidor 
	if (DB::isError($link))
	{	
		echo DB::errorMessage($link);
		//$sms = "Conexi&oacute;n con la Base de Datos exitosa.<br>";
		//$oksave = 1;
		//$sms = "Error al conectarse con la Base de Datos.<br>";
	}else{
		$sms = "Conexi&oacute;n con la Base de Datos exitosa.<br>";
		$oksave = 1;
	}
}else{
	if(isset($_POST['guardar'])){
		$usuario = $ifilter->process($_POST['usuario']); //trim($_POST['usuario']);
		$password = $ifilter->process($_POST['password']); //trim($_POST['password']);
		$host = $ifilter->process($_POST['host']); //$_POST['host'];
		$bdName = $ifilter->process($_POST['bdName']); //$_POST['bdName'];
		$file = "../config.ini";
//el contenido
$variable1 = "[guardian] 
usuario = \"".encode(trim($usuario))."\"; 
password = \"".encode(trim($password))."\"; 
host= \"$host\"; 
bdName = \"$bdName\"; 
 ";
//------------
		if (!$file_handle = fopen($file,"w")){
			$sms = "No se puede crear el archivo"; 
		}elseif (!fwrite($file_handle, $variable1 )){
			$sms = "No se puede escribir en el archivo"; 
			fclose($file_handle);
		}else{ 
			$sms = "Se han guardado los datos satisfactoriamente"; 
			fclose($file_handle);
		}
		
	}else{
		$usuario = ""; 
		$password = ""; 
		$host= "localhost"; 
		$bdName = "";
	}
}

?>
<html>
<head>
<title>Guardian Pro</title>
<script>
function mostrarOcultarTablas(id){
mostrado=0;
elem = document.getElementById(id);
if(elem.style.display=='block') 
   mostrado=1;
elem.style.display='none';
if(mostrado!=1)elem.style.display='block';
}
</script>
</head>
<body>
<div align="center">
<br>
<?php echo $sms; ?>
<br>
<br>
1. <a href="../index.html">Reintentar iniciar Sesi&oacute;n en Guardian Pro (recomendado)</a>
<br><br>
2. <a href="javascript:mostrarOcultarTablas('tabla1')">Configurar la conexi&oacute;n</a>
<form action='_gencla.php' method="POST">                      
<div id="tabla1" style="display: none">                     
	<table border="1" cellspacing="0" cellpadding="4" frame="box" bordercolor="#dcdcdc" rules="none" style="border-collapse: collapse;" >
  <tr>
	<th style="color: #fff; font-weight: normal; background:#000;" colspan="2">DATOS DE CONEXION GUARDIAN</td>
  </tr>
	<tr>
	<td style="color: #000000; font-weight: normal;">Servidor:</td>
	<td><input type="text" size="50" name="host" value="<?php echo $host;?>"></td>
	</tr>
	<tr>
	<td style="color: #000000; font-weight: normal;">Nombre ODBC:</td>
	<td><input type="text" size="50" name="bdName" value="<?php echo $bdName;?>"></td>
	</tr>
	<tr>
	<td style="color: #000000; font-weight: normal;">Usuario:</td>
	<td><input type="text" size="50" name="usuario" value="<?php echo $usuario;?>"></td>
	</tr>

	<tr>
	<td style="color: #000000; font-weight: normal;">Contrase�a:</td>
	<td><input type="text" size="50" name="password" value="<?php echo $password; ?>"></td>
	</tr>

	<tr>
	<td></td>
	<td align="right"><input type="submit" value="Probar Conexi&oacute;n" name="probar"> 
	<?php if($oksave == 1){ ?> <input type="submit" value=" Guardar "  name="guardar"></td> <?php } ?>
	</tr>
	</table>
</div>
<br>
<br>
</form>
</div>
</body>
</html>