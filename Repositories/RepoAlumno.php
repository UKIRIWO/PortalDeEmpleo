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

    /**
     * Guarda un alumno junto con su usuario en una transacción
     * Este es el ÚNICO método para guardar alumnos
     * @param User $user Usuario asociado al alumno
     * @param Alumno $alumno Datos del alumno
     * @return bool true si se guardó correctamente, false si hubo error
     */
    public static function save($user, $alumno) {
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
            $stmt = $con->prepare("INSERT INTO alumno (id_user_fk, dni, nombre, ape1, ape2, curriculum, fecha_nacimiento, direccion, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $idUser,
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
            $alumno->setIdUserFk($idUser);
            
            // Confirmar transacción
            $con->commit();
            return true;
            
        } catch (Exception $e) {
            // Revertir cambios si hay error
            $con->rollBack();
            $errorMsg = "Error al guardar alumno con usuario: " . $e->getMessage();
            error_log($errorMsg);
            // Mostrar el error en pantalla para debug
            echo "<p style='color: red;'><strong>Error MySQL:</strong> " . $e->getMessage() . "</p>";
            return false;
        }
    }

    public static function update($alumno) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE alumno SET dni = ?, nombre = ?, ape1 = ?, ape2 = ?, curriculum = ?, fecha_nacimiento = ?, direccion = ?, foto = ? WHERE id = ?");
        $stmt->execute([
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

    /**
     * Elimina un alumno y su usuario asociado
     * Este es el ÚNICO método para eliminar alumnos
     * @param int $id ID del alumno
     * @return bool true si se eliminó correctamente, false si hubo error
     */
    public static function delete($id) {
        $con = DB::getConnection();
        
        try {
            // Obtener el id del usuario antes de eliminar
            $alumno = self::findById($id);
            if ($alumno == null) {
                return false;
            }
            
            $idUser = $alumno->getIdUserFk();
            
            // Iniciar transacción
            $con->beginTransaction();
            
            // Eliminar el usuario (CASCADE eliminará automáticamente el alumno, estudios y solicitudes)
            $stmt = $con->prepare("DELETE FROM user WHERE id = ?");
            $stmt->execute([$idUser]);
            
            $con->commit();
            return true;
            
        } catch (Exception $e) {
            $con->rollBack();
            error_log("Error al eliminar alumno con usuario: " . $e->getMessage());
            return false;
        }
    }
}
?>