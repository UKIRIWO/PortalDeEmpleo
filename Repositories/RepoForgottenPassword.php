<?php
namespace Repositories;
use Models\ForgottenPassword;
class RepoForgottenPassword {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM forgotten_password WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new ForgottenPassword(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['token'],
                $fila['old_password'],
                $fila['fecha_creacion'],
                $fila['fecha_expiracion'],
                $fila['used']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM forgotten_password");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $forgottenPasswords = [];
        foreach ($filas as $fila) {
            $forgottenPasswords[] = new ForgottenPassword(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['token'],
                $fila['old_password'],
                $fila['fecha_creacion'],
                $fila['fecha_expiracion'],
                $fila['used']
            );
        }

        return $forgottenPasswords;
    }

    public static function findByToken($token) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM forgotten_password WHERE token = ?");
        $stmt->execute([$token]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new ForgottenPassword(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['token'],
                $fila['old_password'],
                $fila['fecha_creacion'],
                $fila['fecha_expiracion'],
                $fila['used']
            );
        }

        return null;
    }

    public static function save($forgottenPassword) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO forgotten_password (id_user_fk, token, old_password, fecha_creacion, fecha_expiracion, used) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $forgottenPassword->getIdUserFk(),
            $forgottenPassword->getToken(),
            $forgottenPassword->getOldPassword(),
            $forgottenPassword->getFechaCreacion(),
            $forgottenPassword->getFechaExpiracion(),
            $forgottenPassword->getUsed()
        ]);
        $forgottenPassword->setId($con->lastInsertId());
    }

    public static function update($forgottenPassword) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE forgotten_password SET id_user_fk = ?, token = ?, old_password = ?, fecha_creacion = ?, fecha_expiracion = ?, used = ? WHERE id = ?");
        $stmt->execute([
            $forgottenPassword->getIdUserFk(),
            $forgottenPassword->getToken(),
            $forgottenPassword->getOldPassword(),
            $forgottenPassword->getFechaCreacion(),
            $forgottenPassword->getFechaExpiracion(),
            $forgottenPassword->getUsed(),
            $forgottenPassword->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM forgotten_password WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>