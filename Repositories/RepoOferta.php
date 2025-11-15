<?php

namespace Repositories;

use Models\Oferta;
use Helpers\Login;

class RepoOferta
{

    public static function findById($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM oferta WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Oferta(
                $fila['id'],
                $fila['id_empresa_fk'],
                $fila['fecha_inicio'],
                $fila['fecha_fin'],
                $fila['titulo'],
                $fila['descripcion']
            );
        }

        return null;
    }


    public static function findByIdEmpresa($idEmpresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM oferta WHERE id_empresa_fk = ?");
        $stmt->execute([$idEmpresa]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Oferta(
                $fila['id'],
                $fila['id_empresa_fk'],
                $fila['fecha_inicio'],
                $fila['fecha_fin'],
                $fila['titulo'],
                $fila['descripcion']
            );
        }

        return null;
    }


    public static function findAll()
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM oferta");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ofertas = [];
        foreach ($filas as $fila) {
            $ofertas[] = new Oferta(
                $fila['id'],
                $fila['id_empresa_fk'],
                $fila['fecha_inicio'],
                $fila['fecha_fin'],
                $fila['titulo'],
                $fila['descripcion']
            );
        }

        return $ofertas;
    }

    public static function findByEmpresaId($idEmpresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM oferta WHERE id_empresa_fk = ?");
        $stmt->execute([$idEmpresa]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ofertas = [];
        foreach ($filas as $fila) {
            $ofertas[] = new Oferta(
                $fila['id'],
                $fila['id_empresa_fk'],
                $fila['fecha_inicio'],
                $fila['fecha_fin'],
                $fila['titulo'],
                $fila['descripcion']
            );
        }

        return $ofertas;
    }

    public static function save($oferta)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO oferta (id_empresa_fk, fecha_inicio, fecha_fin, titulo, descripcion) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $oferta->getIdEmpresaFk(),
            $oferta->getFechaInicio(),
            $oferta->getFechaFin(),
            $oferta->getTitulo(),
            $oferta->getDescripcion()
        ]);
        $oferta->setId($con->lastInsertId());
    }


    public static function findByIdUser($idUser)
    {
        $ofertas = [];
        $con = DB::getConnection();

        /* 1. Comprobar si el usuario es empresa */
        if (Login::esEmpresa($idUser)) {
            $empresa = RepoEmpresa::findById($idUser);

            $filas = self::findByEmpresaId($empresa->getId());

            foreach ($filas as $fila) {
                $ofertas[] = new Oferta(
                    $fila['id'],
                    $fila['id_empresa_fk'],
                    $fila['fecha_inicio'],
                    $fila['fecha_fin'],
                    $fila['titulo'],
                    $fila['descripcion']
                );
            }

        } else {
            /* 2. Si es alumno */
            $alumno = RepoAlumno::findByIdUser($idUser);

            $idAlumno = $alumno->getId();

            // Obtener todos los ciclos que ha cursado
            $stmt = $con->prepare("SELECT id_ciclo_fk FROM estudios WHERE id_alumno_fk = ?");
            $stmt->execute([$idAlumno]);
            $ciclos = $stmt->fetchAll(\PDO::FETCH_COLUMN);

            if (empty($ciclos)) return [];

            $variables = DB::cantidadVariables(count($ciclos));

            $sql = "
                SELECT DISTINCT ofer.* 
                FROM oferta ofer
                JOIN oferta_ciclo ofer_cicl ON ofer_cicl.id_oferta_fk = ofer.id
                WHERE ofer_cicl.id_ciclo_fk IN ($variables)
            ";
            $stmt = $con->prepare($sql);
            $stmt->execute($ciclos);
            $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $ofertas = [];
            foreach ($filas as $fila) {
                $ofertas[] = new Oferta(
                    $fila['id'],
                    $fila['id_empresa_fk'],
                    $fila['fecha_inicio'],
                    $fila['fecha_fin'],
                    $fila['titulo'],
                    $fila['descripcion']
                );
            }
        }

        return $ofertas;
    }


    public static function update($oferta)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE oferta SET id_empresa_fk = ?, fecha_inicio = ?, fecha_fin = ?, titulo = ?, descripcion = ? WHERE id = ?");
        $stmt->execute([
            $oferta->getIdEmpresaFk(),
            $oferta->getFechaInicio(),
            $oferta->getFechaFin(),
            $oferta->getTitulo(),
            $oferta->getDescripcion(),
            $oferta->getId()
        ]);
    }

    public static function delete($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM oferta WHERE id = ?");
        $stmt->execute([$id]);
    }
}
