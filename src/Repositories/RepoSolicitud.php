<?php

namespace Repositories;

use Models\Solicitud;

class RepoSolicitud
{

    public static function findById($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM solicitud WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Solicitud(
                $fila['id'],
                $fila['id_oferta_fk'],
                $fila['id_alumno_fk'],
                $fila['fecha_solicitud'],
                $fila['estado'],
                $fila['favorito']
            );
        }

        return null;
    }

    public static function findByEmpresaId($idEmpresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("
            SELECT s.*, o.titulo as oferta_titulo, 
                   a.nombre as alumno_nombre, a.ape1 as alumno_ape1, 
                   a.email as alumno_email, a.dni as alumno_dni
            FROM solicitud s
            JOIN oferta o ON s.id_oferta_fk = o.id
            JOIN alumno a ON s.id_alumno_fk = a.id
            WHERE o.id_empresa_fk = ?
            ORDER BY o.id, s.fecha_solicitud DESC
        ");
        $stmt->execute([$idEmpresa]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $filas;
    }

    public static function findByAlumnoId($idAlumno)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("
            SELECT s.*, o.titulo as oferta_titulo, o.descripcion as oferta_descripcion,
                   e.nombre as empresa_nombre, o.fecha_inicio, o.fecha_fin
            FROM solicitud s
            JOIN oferta o ON s.id_oferta_fk = o.id
            JOIN empresa e ON o.id_empresa_fk = e.id
            WHERE s.id_alumno_fk = ?
            ORDER BY s.favorito DESC, s.fecha_solicitud DESC
        ");
        $stmt->execute([$idAlumno]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $filas; // Devolvemos array asociativo con toda la info
    }

    public static function findAll()
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM solicitud");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $solicitudes = [];
        foreach ($filas as $fila) {
            $solicitudes[] = new Solicitud(
                $fila['id'],
                $fila['id_oferta_fk'],
                $fila['id_alumno_fk'],
                $fila['fecha_solicitud'],
                $fila['estado'],
                $fila['favorito']
            );
        }

        return $solicitudes;
    }

    public static function findAllAdmin()
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("
        SELECT s.*, 
               o.titulo as oferta_titulo, o.descripcion as oferta_descripcion,
               e.nombre as empresa_nombre,
               a.nombre as alumno_nombre, a.ape1 as alumno_ape1,
               a.email as alumno_email, a.dni as alumno_dni
        FROM solicitud s
        JOIN oferta o ON s.id_oferta_fk = o.id
        JOIN empresa e ON o.id_empresa_fk = e.id
        JOIN alumno a ON s.id_alumno_fk = a.id
        ORDER BY e.nombre, o.id, s.fecha_solicitud DESC
    ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findByOfertaId($idOferta)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM solicitud WHERE id_oferta_fk = ?");
        $stmt->execute([$idOferta]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $solicitudes = [];
        foreach ($filas as $fila) {
            $solicitudes[] = new Solicitud(
                $fila['id'],
                $fila['id_oferta_fk'],
                $fila['id_alumno_fk'],
                $fila['fecha_solicitud'],
                $fila['estado'],
                $fila['favorito']
            );
        }

        return $solicitudes;
    }

    public static function save($solicitud)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO solicitud (id_oferta_fk, id_alumno_fk, fecha_solicitud, estado, favorito) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $solicitud->getIdOfertaFk(),
            $solicitud->getIdAlumnoFk(),
            $solicitud->getFechaSolicitud(),
            $solicitud->getEstado(),
            $solicitud->getFavorito(),
        ]);
        $solicitud->setId($con->lastInsertId());
    }

    public static function update($solicitud)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE solicitud SET id_oferta_fk = ?, id_alumno_fk = ?, fecha_solicitud = ?, estado = ?, favorito = ? WHERE id = ?");
        $stmt->execute([
            $solicitud->getIdOfertaFk(),
            $solicitud->getIdAlumnoFk(),
            $solicitud->getFechaSolicitud(),
            $solicitud->getEstado(),
            $solicitud->getFavorito(),
            $solicitud->getId()
        ]);
    }

    public static function delete($id)
    {


        try {
            $con = DB::getConnection();
            $stmt = $con->prepare("DELETE FROM solicitud WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (\PDOException $e) {
            error_log("Error al borrar solicitud: " . $e->getMessage());
            return false;
        }
    }

    public static function updateFavorito($idSolicitud, $favorito)
    {
        try {
            $con = DB::getConnection();

            $stmt = $con->prepare("
                                    UPDATE solicitud 
                                    SET favorito = ?
                                    WHERE id = ?
                                ");

            $stmt->execute([$favorito, $idSolicitud]);
            return true;
        } catch (\PDOException $e) {
            error_log("Error al actualizar estado: " . $e->getMessage());
            return false;
        }
    }

    public static function getCurriculumBySolicitudId($idSolicitud)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("
            SELECT a.curriculum 
            FROM solicitud s
            JOIN alumno a ON s.id_alumno_fk = a.id
            WHERE s.id = ?
        ");
        $stmt->execute([$idSolicitud]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $resultado ? $resultado['curriculum'] : null;
    }

    public static function updateEstado($id, $estado)
    {
        try {
            $con = DB::getConnection();
            $stmt = $con->prepare("UPDATE solicitud SET estado = ? WHERE id = ?");
            $stmt->execute([$estado, $id]);
            return true;
        } catch (\PDOException $e) {
            error_log("Error al actualizar estado: " . $e->getMessage());
            return false;
        }
    }
}
