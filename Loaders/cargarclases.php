<?php
spl_autoload_register(function($clase)
{
    $fichero=$_SERVER['DOCUMENT_ROOT'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/')).'/Clases/'.$clase.'.php';
    if(file_exists($fichero))
    {
        require_once $fichero;
    }
});
