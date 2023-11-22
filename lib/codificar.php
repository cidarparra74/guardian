<?php
	function encode($texto){
	  $resulta = '';
	  for ($ind = 1; $ind<= Strlen($texto); $ind++){
		   $car = substr($texto,$ind-1,1);
		   $resulta .= sprintf("%02X",ord($car));  
	  };
		return $resulta;
	}

	function decode($texto){
	  $resulta = '';
	  for ($ind = 1; $ind<= Strlen($texto); $ind=$ind+2){
		   $car = substr($texto,$ind-1,2);
		   $resulta .= chr(hextodec($car));
	  };
		return $resulta;
	}
	
	function hextodec($NumHex)
	{
		$Decimal = equivale(substr($NumHex,0,1)) * 16;
		$Decimal += equivale(substr($NumHex,1,1));
		return $Decimal;
	}
	
	function equivale($dato)
	{
		switch ($dato) {
			case 'A':
				$resulta = 10;
				break;
			case 'B':
				$resulta = 11;
				break;
			case 'C':
				$resulta = 12;
				break;
			case 'D':
				$resulta = 13;
				break;
			case 'E':
				$resulta = 14;
				break;
			case 'F':
				$resulta = 15;
				break;
			default:
				$resulta = intval($dato);
			}
		return $resulta;
	}

?>