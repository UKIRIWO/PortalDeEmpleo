<?php

spl_autoload_register(function ($clase){
    $carpetas = ["API", "APIMOCK", "Controllers", "Helpers", "Models", "Public", "Repositories", "Templates", "Views"];
    foreach ($carpetas as $carpeta) {
        $fichero = $_SERVER['DOCUMENT_ROOT'] . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')). '/' . $carpeta . '/' . $clase . '.php';
        if (file_exists($fichero)) {
            require_once $fichero;
            break;
        }
    }
});
