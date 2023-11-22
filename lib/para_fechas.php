<?php
class p_fecha{
	function devolver_fecha($bd_f, $fecha){
		$retorno=$fecha;
		if($bd_f == "mssql"){
			$aux= explode("-", $fecha);
			$retorno= $aux[0]."-".$aux[1]."-".$aux[2];
		}
		return $retorno;
	}
	
	function devolver_fecha_calendario($bd_f, $fecha){
		$retorno=$fecha;
		if($bd_f == "mssql"){
			$aux= explode("/", $fecha);
			$para_mes= $aux[1];
			switch($para_mes){
				case "ENE":
					$aux_mes="01";
				break;
				case "FEB":
					$aux_mes="02";
				break;
				case "MAR":
					$aux_mes="03";
				break;
				case "ABR":
					$aux_mes="04";
				break;
				case "MAY":
					$aux_mes="05";
				break;
				case "JUN":
					$aux_mes="06";
				break;
				case "JUL":
					$aux_mes="07";
				break;
				case "AGO":
					$aux_mes="08";
				break;
				case "SEP":
					$aux_mes="09";
				break;
				case "OCT":
					$aux_mes="10";
				break;
				case "NOV":
					$aux_mes="11";
				break;
				case "DIC":
					$aux_mes="12";
				break;
			}
			
			$retorno= $aux[2]."/".$aux_mes."/".$aux[0];
		}
		return $retorno;
	}
	
	//insercion de fechas
	function devolver_fecha_insertar($bd_f, $fecha){
		$retorno=$fecha;
		if($bd_f == "mssql"){
			$aux= explode("/", $fecha);
			$para_mes= $aux[1];
			switch($para_mes){
				case "ENE":
					$aux_mes="01";
				break;
				case "FEB":
					$aux_mes="02";
				break;
				case "MAR":
					$aux_mes="03";
				break;
				case "ABR":
					$aux_mes="04";
				break;
				case "MAY":
					$aux_mes="05";
				break;
				case "JUN":
					$aux_mes="06";
				break;
				case "JUL":
					$aux_mes="07";
				break;
				case "AGO":
					$aux_mes="08";
				break;
				case "SEP":
					$aux_mes="09";
				break;
				case "OCT":
					$aux_mes="10";
				break;
				case "NOV":
					$aux_mes="11";
				break;
				case "DIC":
					$aux_mes="12";
				break;
			}
			
			//$retorno= $aux[2]."-".$aux[0]."-".$aux_mes;
			$retorno=$aux[2]."-".$aux_mes."-".$aux[0];
			$retorno= "CONVERT(DATETIME,'$retorno',102)";
		}
		return $retorno;
	}
	
	function formar_fecha($fecha, $separador, $formato_devolver, $formato_envio){
		$retorno= "//";
		if($formato_devolver == "dd/MMM/yyyy"){

			if($formato_envio == "yyyy-mm-dd"){

				$aux= explode($separador, $fecha);
				$aux_d= $aux[1];
				switch($aux_d){
					case "01":
						$aux2="ENE";
					break;
					case "02":
						$aux2="FEB";
					break;
					case "03":
						$aux2="MAR";
					break;
					case "04":
						$aux2="ABR";
					break;
					case "05":
						$aux2="MAY";
					break;
					case "06":
						$aux2="JUN";
					break;
					case "07":
						$aux2="JUL";
					break;
					case "08":
						$aux2="AGO";
					break;
					case "09":
						$aux2="SEP";
					break;
					case "10":
						$aux2="OCT";
					break;
					case "11":
						$aux2="NOV";
					break;
					case "12":
						$aux2="DIC";
					break;
				}
				
				$retorno= $aux[2]."/".$aux2."/".$aux[0];

			}
		}

		return $retorno;
	}
	
	
}
?>
