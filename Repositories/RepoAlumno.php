<?php

namespace Repositories;

use Models\Alumno;

class RepoAlumno{

    public static function findById($id){
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM alumno WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Alumno(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['dni'],
                $fila['email'],
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

    public static function findAll(){
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM alumno");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $alumnos = [];
        foreach ($filas as $fila) {
            $alumnos[] = new Alumno(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['dni'],
                $fila['email'],
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

    public static function findByIdWithoutCurriculum($id){
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT id, id_user_fk, dni, email, nombre, ape1, ape2, fecha_nacimiento, direccion, foto FROM alumno WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Alumno(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['dni'],
                $fila['email'],
                $fila['nombre'],
                $fila['ape1'],
                $fila['ape2'],
                null,
                $fila['fecha_nacimiento'],
                $fila['direccion'],
                $fila['foto']
            );
        }

        return null;
    }

    public static function findAllWithoutCurriculum(){
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT id, id_user_fk, dni, email, nombre, ape1, ape2, fecha_nacimiento, direccion, foto FROM alumno");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $alumnos = [];
        foreach ($filas as $fila) {
            $alumnos[] = new Alumno(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['dni'],
                $fila['email'],
                $fila['nombre'],
                $fila['ape1'],
                $fila['ape2'],
                null,  // curriculum como null
                $fila['fecha_nacimiento'],
                $fila['direccion'],
                $fila['foto']
            );
        }

        return $alumnos;
    }

    public static function save($user, $alumno){
        $con = DB::getConnection();

        try {
            // Iniciar transacción
            $con->beginTransaction();

            // Insertar usuario
            $stmt = $con->prepare("INSERT INTO user (nombre_usuario, password, id_rol_fk) VALUES (?, ?, ?)");
            $stmt->execute([
                $user->getNombreUsuario(),
                $user->getPassword(),
                $user->getIdRolFk()
            ]);
            $idUser = $con->lastInsertId();
            $user->setId($idUser);

            // Insertar alumno
            $stmt = $con->prepare("INSERT INTO alumno (id_user_fk, dni, email, nombre, ape1, ape2, curriculum, fecha_nacimiento, direccion, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $idUser,
                $alumno->getDni(),
                $alumno->getEmail(),
                $alumno->getNombre(),
                $alumno->getApe1(),
                $alumno->getApe2(),
                $alumno->getCurriculum(),
                $alumno->getFechaNacimiento(),
                $alumno->getDireccion(),
                $alumno->getFoto()
            ]);
            $alumno->setId($con->lastInsertId());
            $alumno->setIdUserFk($idUser);

            // Confirmar transacción
            $con->commit();
            return true;
        } catch (\Exception $e) {
            // Revertir cambios si hay error
            $con->rollBack();
            $errorMsg = "Error al guardar alumno con usuario: " . $e->getMessage();
            error_log($errorMsg);
            // Mostrar el error en pantalla para debug
            echo "<p style='color: red;'><strong>Error MySQL:</strong> " . $e->getMessage() . "</p>";
            return false;
        }
    }

    public static function update($alumno){
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE alumno SET dni = ?, email = ?, nombre = ?, ape1 = ?, ape2 = ?, curriculum = ?, fecha_nacimiento = ?, direccion = ?, foto = ? WHERE id = ?");
        $stmt->execute([
            $alumno->getDni(),
            $alumno->getEmail(),
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

    public static function delete($id){
        $con = DB::getConnection();

        try {

            $alumno = self::findById($id);
            if ($alumno == null) {
                return false;
            }

            $idUser = $alumno->getIdUserFk();

            $con->beginTransaction();

            $stmt = $con->prepare("DELETE FROM user WHERE id = ?");
            $stmt->execute([$idUser]);

            $con->commit();
            return true;
        } catch (\Exception $e) {
            $con->rollBack();
            error_log("Error al eliminar alumno con usuario: " . $e->getMessage());
            return false;
        }
    }
}