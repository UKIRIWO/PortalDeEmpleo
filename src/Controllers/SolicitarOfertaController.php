<?php

namespace Controllers;

use Models\Solicitud;
use Repositories\RepoSolicitud;

include_once __DIR__ . '/../rutaLoader.php';

class SolicitarOfertaController
{

    public function procesarSolicitud()
    {
        $idAlumno = $_POST['id_alumno'];
        $idOferta = $_POST['id_oferta'];

        $registro = new Solicitud(
            null,
            $idOferta,
            $idAlumno,
            date('Y-m-d'),
            'pendiente'
            
        );

        RepoSolicitud::save($registro);

        header("Location: index.php?menu=Ofertas");
        exit;
    }
}
