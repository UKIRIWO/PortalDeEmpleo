<?php
namespace Repositories;
use Models\Familia;
class RepoFamilia {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM familia WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Familia(
                $fila['id'],
                $fila['nombre']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM familia");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $familias = [];
        foreach ($filas as $fila) {
            $familias[] = new Familia(
                $fila['id'],
                $fila['nombre']
            );
        }

        return $familias;
    }

    public static function save($familia) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO familia (nombre) VALUES (?)");
        $stmt->execute([
            $familia->getNombre()
        ]);
        $familia->setId($con->lastInsertId());
    }

    public static function update($familia) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE familia SET nombre = ? WHERE id = ?");
        $stmt->execute([
            $familia->getNombre(),
            $familia->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM familia WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>