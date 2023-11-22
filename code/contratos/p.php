<?php
	$matriz=array();
	$matriz[0][0] = 'a';
	$matriz[0][1] = 'b';
	$matriz[0][2] = 'c';
	$matriz[0][3] = 'd';
	echo "<pre>";
	print_r($matriz);
	
	$matriz2=$matriz;
	$matriz2[1][0] = 'e';
	$matriz2[1][1] = 'f';
	$matriz2[1][2] = 'g';
	$matriz2[1][3] = 'h';
	echo "<pre>";
	print_r($matriz2);
	
?>