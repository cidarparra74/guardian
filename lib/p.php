<?php

	require_once('nro2lit.php');
	$num=1234.5;

	$V=new EnLetras();
	echo "<h2>". $V->ValorEnLetras($num,"") ."</h2>";
 $num=100.599;
 echo "<h2>". $V->ValorEnLetras($num,"") ."</h2>";
 $num=1004.259;
 echo "<h2>". $V->ValorEnLetras($num,"") ."</h2>";
 $num=15230.458;
 echo "<h2>". $V->ValorEnLetras($num,"") ."</h2>";
 ?>