<?php
//print_r($_REQUEST);
//die();
$id= $_REQUEST['id'];

$smarty->assign('id',$id);
$smarty->assign('p_usuario',$nombre_us_actual);

$smarty->display('reportes/infor_docus_imp.html');
die();
?>