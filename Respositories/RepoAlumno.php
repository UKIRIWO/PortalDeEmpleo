<?php

class RepoAlumno {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM alumno WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            return new Alumno(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['dni'],
                $fila['nombre'],
                $fila['ape1'],
                $fila['ape2'],
                $fila['curriculum'],
                $fila['fecha_nacimiento'],
                $fila['direccion'],
                $fila['foto']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM alumno");
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $alumnos = [];
        foreach ($filas as $fila) {
            $alumnos[] = new Alumno(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['dni'],
                $fila['nombre'],
                $fila['ape1'],
                $fila['ape2'],
                $fila['curriculum'],
                $fila['fecha_nacimiento'],
                $fila['direccion'],
                $fila['foto']
            );
        }

        return $alumnos;
    }

    public static function save($alumno) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO alumno (id_user_fk, dni, nombre, ape1, ape2, curriculum, fecha_nacimiento, direccion, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $alumno->getIdUserFk(),
            $alumno->getDni(),
            $alumno->getNombre(),
            $alumno->getApe1(),
            $alumno->getApe2(),
            $alumno->getCurriculum(),
            $alumno->getFechaNacimiento(),
            $alumno->getDireccion(),
            $alumno->getFoto()
        ]);
        $alumno->setId($con->lastInsertId());
    }

    public static function update($alumno) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE alumno SET id_user_fk = ?, dni = ?, nombre = ?, ape1 = ?, ape2 = ?, curriculum = ?, fecha_nacimiento = ?, direccion = ?, foto = ? WHERE id = ?");
        $stmt->execute([
            $alumno->getIdUserFk(),
            $alumno->getDni(),
            $alumno->getNombre(),
            $alumno->getApe1(),
            $alumno->getApe2(),
            $alumno->getCurriculum(),
            $alumno->getFechaNacimiento(),
            $alumno->getDireccion(),
            $alumno->getFoto(),
            $alumno->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM alumno WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>