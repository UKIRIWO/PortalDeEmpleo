<?php

namespace Repositories;

use Models\User;

class RepoUser
{

    public static function findById($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new User(
                $fila['id'],
                $fila['nombre_usuario'],
                $fila['password'],
                $fila['id_rol_fk']
            );
        }

        return null;
    }

    public static function findAll()
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM user");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $users = [];
        foreach ($filas as $fila) {
            $users[] = new User(
                $fila['id'],
                $fila['nombre_usuario'],
                $fila['password'],
                $fila['id_rol_fk']
            );
        }

        return $users;
    }

    public static function save($user)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO user (nombre_usuario, password, id_rol_fk) VALUES (?, ?, ?)");
        $stmt->execute([
            $user->getNombreUsuario(),
            $user->getPassword(),
            $user->getIdRolFk()
        ]);
        $user->setId($con->lastInsertId());
    }

    public static function update($user)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE user SET nombre_usuario = ?, password = ?, id_rol_fk = ? WHERE id = ?");
        $stmt->execute([
            $user->getNombreUsuario(),
            $user->getPassword(),
            $user->getIdRolFk(),
            $user->getId()
        ]);
    }

    public static function delete($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM user WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function findByUsername($nombreUsuario)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM user WHERE nombre_usuario = ?");
        $stmt->execute([$nombreUsuario]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new User(
                $fila['id'],
                $fila['nombre_usuario'],
                $fila['password'],
                $fila['id_rol_fk']
            );
        }

        return null;
    }

    public static function findUserWithRoleById($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT u.*, r.nombre as rol_nombre
                              FROM user u 
                              JOIN rol r ON u.id_rol_fk = r.id 
                              WHERE u.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function verificarUsuario($nombre_usuario, $password)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM user WHERE nombre_usuario = ?");
        $stmt->execute([$nombre_usuario]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        $resultado = ($fila && password_verify($password, $fila['password'])) ? $fila : false;
        return $resultado;
    }
}
