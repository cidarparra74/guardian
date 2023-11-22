<?php
$page = addslashes((string) $_POST['variable']);

if(!isset($page))
{
    die("No se encontró archivo de proceso");
} 
else if ((string) $page && is_string($page))
{
 if(file_exists($page.'.php'))
 {
       include($page.'.php');
 } 
 else 
 {
       die("No se encontró archivo de proceso");
 } 
}
?>