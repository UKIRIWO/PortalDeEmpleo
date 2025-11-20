<?php

namespace Repositories;

class RepoToken
{

    public static function findByUserId($idUser)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM token WHERE id_user_fk = ?");
        $stmt->execute([$idUser]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    public static function findByToken($token)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM token WHERE token = ?");
        $stmt->execute([$token]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }


    public static function save($idUser, $token)
    {
        try {
            $con = DB::getConnection();
            $stmt = $con->prepare("INSERT INTO token (id_user_fk, token) VALUES (?, ?)");
            $stmt->execute([$idUser, $token]);
            return true;
        } catch (\PDOException $e) {
            error_log("Error al guardar token: " . $e->getMessage());
            return false;
        }
    }


    public static function update($idUser, $token)
    {
        try {
            $con = DB::getConnection();
            $stmt = $con->prepare("UPDATE token SET token = ? WHERE id_user_fk = ?");
            $stmt->execute([$token, $idUser]);
            return true;
        } catch (\PDOException $e) {
            error_log("Error al actualizar token: " . $e->getMessage());
            return false;
        }
    }


    public static function delete($idUser)
    {
        try {
            $con = DB::getConnection();
            $stmt = $con->prepare("DELETE FROM token WHERE id_user_fk = ?");
            $stmt->execute([$idUser]);
            return true;
        } catch (\PDOException $e) {
            error_log("Error al eliminar token: " . $e->getMessage());
            return false;
        }
    }


    public static function deleteAll()
    {
        try {
            $con = DB::getConnection();
            $stmt = $con->prepare("DELETE FROM token");
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            error_log("Error al eliminar todos los tokens: " . $e->getMessage());
            return false;
        }
    }

    public static function findUserByToken($token)
    {
        $con = DB::getConnection();

        $stmt = $con->prepare("
        SELECT u.id, u.nombre_Usuario, t.token
        FROM token t
        JOIN user u ON t.id_user_fk = u.id
        WHERE t.token = ?
    ");
        $stmt->execute([$token]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
