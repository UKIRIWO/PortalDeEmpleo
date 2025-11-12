<?php

namespace Repositories;

use Models\Alumno;
use Models\User;

class RepoAlumno
{

    public static function findById($id)
    {
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

    public static function findAll()
    {
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

    public static function findByIdWithoutCurriculum($id)
    {
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

    public static function findAllWithoutCurriculum()
    {
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

    public static function save($user, $alumno)
    {
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

    public static function update($alumno)
    {
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

    public static function delete($id)
    {
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

            $stmt = $con->prepare("DELETE FROM alumno WHERE id = ?");
            $stmt->execute([$id]);

            $con->commit();
            return true;
        } catch (\Exception $e) {
            $con->rollBack();
            error_log("Error al eliminar alumno con usuario: " . $e->getMessage());
            return false;
        }
    }


    public static function saveMassive($alumnosData, $cicloId, $fechaInicio = null, $fechaFin = null)
    {
        $exitosos = [];
        $errores = [];

        foreach ($alumnosData as $alumnoData) {
            try {
                // Validar campos obligatorios
                if (
                    empty($alumnoData['dni']) || empty($alumnoData['nombre']) ||
                    empty($alumnoData['ape1']) || empty($alumnoData['correo'])
                ) {
                    $errores[] = [
                        'alumno' => $alumnoData,
                        'error' => 'Campos obligatorios incompletos (dni, nombre, ape1, correo)'
                    ];
                    continue;
                }

                // Generar username automáticamente
                $username = self::generarUsername($alumnoData['nombre'], $alumnoData['ape1']);

                // Generar password automáticamente
                $password = self::generarPassword($alumnoData['dni'], $alumnoData['nombre']);

                // Crear usuario
                $user = new User(
                    null,
                    $username,
                    password_hash($password, PASSWORD_DEFAULT),
                    3 // rol alumno
                );

                // Crear alumno
                $alumno = new Alumno(
                    null,
                    null,
                    $alumnoData['dni'],
                    $alumnoData['correo'],
                    $alumnoData['nombre'],
                    $alumnoData['ape1'],
                    null, //ape2
                    null, // curriculum
                    null, // fecha_nacimiento
                    null, // direccion
                    null  // foto
                );

                // Guardar alumno con usuario
                $con = DB::getConnection();
                $con->beginTransaction();

                try {
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

                    // Insertar en tabla estudios (relación con ciclo)
                    $stmt = $con->prepare("INSERT INTO estudios (id_alumno_fk, id_ciclo_fk, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)");
                    $stmt->execute([
                        $alumno->getId(),
                        $cicloId,
                        $fechaInicio,
                        $fechaFin
                    ]);

                    // Commit si todo fue bien
                    $con->commit();

                    // Guardar en exitosos con credenciales
                    $exitosos[] = [
                        'alumno' => $alumnoData,
                        'username' => $username,
                        'password' => $password,
                        'id' => $alumno->getId()
                    ];
                } catch (\PDOException $e) {
                    // Rollback en caso de error
                    $con->rollBack();

                    // Determinar tipo de error
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        if (strpos($e->getMessage(), 'alumno.dni') !== false) {
                            $errorMsg = 'El DNI ya existe en la base de datos';
                        } elseif (strpos($e->getMessage(), 'user.nombre_usuario') !== false) {
                            $errorMsg = 'El nombre de usuario ya existe';
                        } else {
                            $errorMsg = 'Registro duplicado';
                        }
                    } else {
                        $errorMsg = 'Error de base de datos: ' . $e->getMessage();
                    }

                    $errores[] = [
                        'alumno' => $alumnoData,
                        'error' => $errorMsg
                    ];
                }
            } catch (\Exception $e) {
                $errores[] = [
                    'alumno' => $alumnoData,
                    'error' => $e->getMessage()
                ];
            }
        }

        return [
            'exitosos' => $exitosos,
            'errores' => $errores,
            'total' => count($alumnosData)
        ];
    }

    /**
     * Genera un username único a partir del nombre y apellido
     */
    private static function generarUsername($nombre, $apellido)
    {
        // Formato: nombre.apellido (todo en minúsculas, sin espacios ni caracteres especiales)
        $username = strtolower($nombre . '.' . $apellido);
        $username = preg_replace('/[^a-z0-9.]/', '', $username);

        // Si ya existe, añadir número
        $usernameOriginal = $username;
        $contador = 1;
        while (\Repositories\RepoUser::findByUsername($username) !== null) {
            $username = $usernameOriginal . $contador;
            $contador++;
        }

        return $username;
    }

    /**
     * Genera una contraseña automática
     * Formato: últimos 3 dígitos del DNI + 2 primeras letras del nombre en mayúscula
     */
    private static function generarPassword($dni, $nombre)
    {
        $ultimosTresDni = substr(preg_replace('/[^0-9]/', '', $dni), -3);
        $primerasLetrasNombre = strtoupper(substr($nombre, 0, 2));
        return $ultimosTresDni . $primerasLetrasNombre;
    }
}
