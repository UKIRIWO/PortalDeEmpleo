<?php

spl_autoload_register(function ($clase){
    $carpetas = ["API", "APIMOCK", "Controllers", "Helpers", "Models", "Public", "Repositories", "Views"];

    $base = realpath(__DIR__ . "/../");

    foreach ($carpetas as $carpeta) {
        $fichero = $base . '/' . $carpeta . '/' . $clase . '.php';
        if (file_exists($fichero)) {
            require_once $fichero;
            break;
        }
    }
});


/* 
spl_autoload_register(function ($clase) {

    $clase = str_replace('App\\', '', $clase);
    $clase = str_replace('\\', '/', $clase);

    $fichero = __DIR__ . '/../../' . $clase . '.php';

    if (file_exists($fichero)) {
        require_once($fichero);
    } else { //try
        echo "<!-- NO ENCONTRADO: $fichero -->";
    }
});
*/