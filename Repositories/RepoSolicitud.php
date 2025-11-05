<?php
namespace Repositories;
use Models\Solicitud;
class RepoSolicitud {

    public static function findById($id) {
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
                $fila['estado']
            );
        }

        return null;
    }

    public static function findAll() {
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
                $fila['estado']
            );
        }

        return $solicitudes;
    }

    public static function findByOfertaId($idOferta) {
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
                $fila['estado']
            );
        }

        return $solicitudes;
    }

    public static function findByAlumnoId($idAlumno) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM solicitud WHERE id_alumno_fk = ?");
        $stmt->execute([$idAlumno]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $solicitudes = [];
        foreach ($filas as $fila) {
            $solicitudes[] = new Solicitud(
                $fila['id'],
                $fila['id_oferta_fk'],
                $fila['id_alumno_fk'],
                $fila['fecha_solicitud'],
                $fila['estado']
            );
        }

        return $solicitudes;
    }

    public static function save($solicitud) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO solicitud (id_oferta_fk, id_alumno_fk, fecha_solicitud, estado) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $solicitud->getIdOfertaFk(),
            $solicitud->getIdAlumnoFk(),
            $solicitud->getFechaSolicitud(),
            $solicitud->getEstado()
        ]);
        $solicitud->setId($con->lastInsertId());
    }

    public static function update($solicitud) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE solicitud SET id_oferta_fk = ?, id_alumno_fk = ?, fecha_solicitud = ?, estado = ? WHERE id = ?");
        $stmt->execute([
            $solicitud->getIdOfertaFk(),
            $solicitud->getIdAlumnoFk(),
            $solicitud->getFechaSolicitud(),
            $solicitud->getEstado(),
            $solicitud->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM solicitud WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>