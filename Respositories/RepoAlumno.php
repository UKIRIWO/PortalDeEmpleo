<?php


class RepoAlumno {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM ALUMNO WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Alumno(
                $fila['id'],
                $fila['idUser'],
                $fila['nombre'],
                $fila['curriculum']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM ALUMNO");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $alumnos = [];
        foreach ($filas as $fila) {
            $alumnos[] = new Alumno(
                $fila['id'],
                $fila['idUser'],
                $fila['nombre'],
                $fila['curriculum']
            );
        }

        return $alumnos;
    }

    public static function save($alumno) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO ALUMNO (idUser, nombre, curriculum) VALUES (?, ?, ?)");
        $stmt->execute([
            $alumno->getIdUser(),
            $alumno->getNombre(),
            $alumno->getCurriculum()
        ]);
        $alumno->setId($con->lastInsertId());
    }

    public static function update($alumno) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE ALUMNO SET nombre = ?, curriculum = ?, idUser = ? WHERE id = ?");
        $stmt->execute([
            $alumno->getNombre(),
            $alumno->getCurriculum(),
            $alumno->getIdUser(),
            $alumno->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM ALUMNO WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>