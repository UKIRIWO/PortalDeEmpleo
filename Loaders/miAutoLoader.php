<?php

spl_autoload_register(function ($clase){
    $carpetas = ["API", "APIMOCK", "Controllers", "Helpers", "Models", "Public", "Repositories", "Templates", "Views"];

    $base = realpath(__DIR__ . "/../");

    foreach ($carpetas as $carpeta) {
        $fichero = $base . '/' . $carpeta . '/' . $clase . '.php';
        if (file_exists($fichero)) {
            require_once $fichero;
            break;
        }
    }
});
