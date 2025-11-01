<?php //<?php

class RepoUser {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

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

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM user");
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    public static function save($user) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO user (nombre_usuario, password, id_rol_fk) VALUES (?, ?, ?)");
        $stmt->execute([
            $user->getNombreUsuario(),
            $user->getPassword(),
            $user->getIdRolFk()
        ]);
        $user->setId($con->lastInsertId());
    }

    public static function update($user) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE user SET nombre_usuario = ?, password = ?, id_rol_fk = ? WHERE id = ?");
        $stmt->execute([
            $user->getNombreUsuario(),
            $user->getPassword(),
            $user->getIdRolFk(),
            $user->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM user WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>