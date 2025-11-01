<?php

class RepoRol {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM rol WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            return new Rol(
                $fila['id'],
                $fila['nombre']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM rol");
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $roles = [];
        foreach ($filas as $fila) {
            $roles[] = new Rol(
                $fila['id'],
                $fila['nombre']
            );
        }

        return $roles;
    }

    public static function save($rol) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO rol (nombre) VALUES (?)");
        $stmt->execute([
            $rol->getNombre()
        ]);
        $rol->setId($con->lastInsertId());
    }

    public static function update($rol) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE rol SET nombre = ? WHERE id = ?");
        $stmt->execute([
            $rol->getNombre(),
            $rol->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM rol WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>