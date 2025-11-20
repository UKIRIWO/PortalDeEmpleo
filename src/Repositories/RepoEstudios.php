<?php
namespace Repositories;
use Models\Estudios;

class RepoEstudios {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM estudios WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Estudios(
                $fila['id'],
                $fila['id_alumno_fk'],
                $fila['id_ciclo_fk'],
                $fila['fecha_inicio'],
                $fila['fecha_fin']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM estudios");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $estudios = [];
        foreach ($filas as $fila) {
            $estudios[] = new Estudios(
                $fila['id'],
                $fila['id_alumno_fk'],
                $fila['id_ciclo_fk'],
                $fila['fecha_inicio'],
                $fila['fecha_fin']
            );
        }

        return $estudios;
    }

    public static function findCiclosByAlumnoId($idAlumno) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT id_ciclo_fk FROM estudios WHERE id_alumno_fk = ?");
        $stmt->execute([$idAlumno]);
        $IdEstudios = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        return $IdEstudios;
    }

    public static function findByAlumnoId($idAlumno) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM estudios WHERE id_alumno_fk = ?");
        $stmt->execute([$idAlumno]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $estudios = [];
        foreach ($filas as $fila) {
            $estudios[] = new Estudios(
                $fila['id'],
                $fila['id_alumno_fk'],
                $fila['id_ciclo_fk'],
                $fila['fecha_inicio'],
                $fila['fecha_fin']
            );
        }

        return $estudios;
    }

    public static function save($estudios) {
        try {
            $con = DB::getConnection();
            $stmt = $con->prepare("INSERT INTO estudios (id_alumno_fk, id_ciclo_fk, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $estudios->getIdAlumnoFk(),
                $estudios->getIdCicloFk(),
                $estudios->getFechaInicio(),
                $estudios->getFechaFin()
            ]);
            $estudios->setId($con->lastInsertId());
            return true;
        } catch (\Exception $e) {
            error_log("Error al guardar estudios: " . $e->getMessage());
            return false;
        }
    }

    public static function update($estudios) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE estudios SET id_alumno_fk = ?, id_ciclo_fk = ?, fecha_inicio = ?, fecha_fin = ? WHERE id = ?");
        $stmt->execute([
            $estudios->getIdAlumnoFk(),
            $estudios->getIdCicloFk(),
            $estudios->getFechaInicio(),
            $estudios->getFechaFin(),
            $estudios->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM estudios WHERE id = ?");
        $stmt->execute([$id]);
    }
}