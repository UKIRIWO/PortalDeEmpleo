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

        /* Compruebo si el usuario es empresa */
        if (Login::esEmpresa()) {
            $empresa = RepoEmpresa::findByIdUser($idUser);
            $ofertas = self::findByIdEmpresa($empresa->getId());
        } else {
            /* Si es alumno */
            $alumno = RepoAlumno::findByIdUser($idUser);

            $idAlumno = $alumno->getId();

            $ciclos = RepoEstudios::findCiclosByAlumnoId($idAlumno);

            if (empty($ciclos)) return [];

            $variables = DB::cantidadVariables(count($ciclos));

            //SELECT DISTINCT ofer.* -> Se cogen todas las ofertas pero sin repetir.

            //JOIN oferta_ciclo ofer_cicl ON ofer_cicl.id_oferta_fk = ofer.id
            //Solo se incluyen las ofertas que tengan al menos un ciclo asociado en la tabla oferta_ciclo.

            //LEFT JOIN solicitud s -> devuelve todas las ofertas con solicitudes
            // y las que no tiene solicitudes tambien se devuelve pero con los apartados de solicitud en Null

            // AND s.id_alumno_fk = ? solo devuelve las ofertas con solicitudes hechas por este alumno

            // AND s.estado IN ('pendiente','aceptada') con esto solo rellena los campos de join solicitud las que estén como pendientes o aceptadas
            // así dejando los datos de solicitud como null las demás, por ejemplo las que no tienen solicitud o su solicitud es rechazada

            //WHERE ofer_cicl.id_ciclo_fk IN (?, ?, ?)
            //Cada ? representa un ciclo al que pertenece el alumno. ej:(2, 5, 7)
            //Así filtrando las ofertas que no tengan uno de los ciclos de alumno

            //AND s.id IS NULL -> Solo deja las ofertas que los campos de de solicitud sean null
            //con esto solo quedan las ofertas que:
            //1. Tengan uno de los ciclos que tiene el alumno
            //2. No tenga solicitudes del alumno
            //3. Si tiene solicitud solo puede ser rechazada


            $sql = "
                SELECT DISTINCT ofer.* 
                FROM oferta ofer
                JOIN oferta_ciclo ofer_cicl ON ofer_cicl.id_oferta_fk = ofer.id
                LEFT JOIN solicitud soli 
                    ON soli.id_oferta_fk = ofer.id
                    AND soli.id_alumno_fk = ?
                    AND soli.estado IN ('pendiente','aceptada')
                WHERE ofer_cicl.id_ciclo_fk IN ($variables)
                AND soli.id IS NULL
            ";
            $stmt = $con->prepare($sql);


            //pongo el id alumno al principio ya que lo primero que se pide
            //es la ? de idAlumno y despés se piden las ? de cada ciclo
            $parametros = $ciclos;
            array_unshift($parametros, $idAlumno);

            $stmt->execute($parametros);
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
