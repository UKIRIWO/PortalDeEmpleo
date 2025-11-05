<?php
/* 
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
*/

namespace Loaders;
spl_autoload_register(function ($clase) {

    $clase = str_replace('\\', '/', $clase);

    $fichero = __DIR__ . '/../' . $clase . '.php';

    if (file_exists($fichero)) {
        require_once $fichero;
    } 
});
