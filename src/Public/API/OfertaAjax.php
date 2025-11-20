<?php
namespace API;

include_once __DIR__ . '/../../Loaders/miAutoLoader.php';

use Repositories\RepoFamilia;
use Repositories\RepoCiclo;

header("Content-Type: application/json");

if (!isset($_GET['action'])) {
    echo json_encode(["error" => "Acción no especificada"]);
    exit;
}

switch ($_GET['action']) {

    case 'familias':
        $familias = RepoFamilia::findAll();
        $respuesta = [];

        foreach ($familias as $familia) {
            $respuesta[] = [
                "id" => $familia->getId(),
                "nombre" => $familia->getNombre()
            ];
        }

        echo json_encode($respuesta);
        break;


    case 'ciclos':
        if (!isset($_GET['familia_id'])) {
            echo json_encode(["error" => "Falta familia_id"]);
            exit;
        }

        $ciclos = RepoCiclo::findByFamilia($_GET['familia_id']);
        $respuesta = [];

        foreach ($ciclos as $ciclo) {
            $respuesta[] = [
                "id" => $ciclo->getId(),
                "nombre" => $ciclo->getNombre(),
                "nivel" => $ciclo->getNivel(),
            ];
        }

        echo json_encode($respuesta);
        break;


    default:
        echo json_encode(["error" => "Acción no válida"]);
}
