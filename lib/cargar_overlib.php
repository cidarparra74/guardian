<?php 
/*
	$fichero= fopen("../lib/acc.txt","r");
	$buffer=array();
	$i=0;
	while (!feof($fichero)) {
		$buffer[$i] = fgets($fichero, 10);
		$i++;
	}
	*/
	/*
	color_cabecera: #990000
	color_txt_cabecera: white
	color_contenido: #FFFFC0
	color_txt_contenido: black
	color_de_scroll: #006CD9
	#F08229
	*/
	/*
	$color_cabecera= trim($buffer[0]);
	$color_txt_cabecera= trim($buffer[1]);
	$color_contenido= trim($buffer[2]);
	$color_txt_contenido= trim($buffer[3]);
	//scroll para las ventanas
	$color_de_scroll= trim($buffer[4]);
	
	fclose($fichero);
	*/
	$color_cabecera= '#990000';
	$color_txt_cabecera= 'white';
	$color_contenido= '#FFFFC0';
	$color_txt_contenido= 'black';
	//scroll para las ventanas
	$color_de_scroll= '#006CD9';
	
	$smarty->assign('acc_color_cabecera',$color_cabecera);
	$smarty->assign('acc_color_txt_cabecera',$color_txt_cabecera);
	$smarty->assign('acc_color_contenido',$color_contenido);
	$smarty->assign('acc_color_txt_contenido',$color_txt_contenido);
	
	$smarty->assign('color_de_scroll',$color_de_scroll);
//overlib('Buscar doc. cliente',CAPTION,'Buscar');


?>