<?php

$id= $_REQUEST['id'];
$smarty->assign('id',$id);

$smarty->display('informe_legal/imprimir_infinal.html');
die();
?>