<?php
namespace Repositories;
use Models\Ciclo;

class RepoCiclo {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM ciclo WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Ciclo(
                $fila['id'],
                $fila['nombre'],
                $fila['nivel'],
                $fila['familia_fk']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM ciclo");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ciclos = [];
        foreach ($filas as $fila) {
            $ciclos[] = new Ciclo(
                $fila['id'],
                $fila['nombre'],
                $fila['nivel'],
                $fila['familia_fk']
            );
        }

        return $ciclos;
    }

    
    public static function findByFamilia($familiaId) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM ciclo WHERE familia_fk = ?");
        $stmt->execute([$familiaId]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ciclos = [];
        foreach ($filas as $fila) {
            $ciclos[] = new Ciclo(
                $fila['id'],
                $fila['nombre'],
                $fila['nivel'],
                $fila['familia_fk']
            );
        }

        return $ciclos;
    }
    
    public static function save($ciclo) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO ciclo (nombre, nivel, familia_fk) VALUES (?, ?, ?)");
        $stmt->execute([
            $ciclo->getNombre(),
            $ciclo->getNivel(),
            $ciclo->getFamiliaFk()
        ]);
        $ciclo->setId($con->lastInsertId());
    }

    public static function update($ciclo) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE ciclo SET nombre = ?, nivel = ?, familia_fk = ? WHERE id = ?");
        $stmt->execute([
            $ciclo->getNombre(),
            $ciclo->getNivel(),
            $ciclo->getFamiliaFk(),
            $ciclo->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM ciclo WHERE id = ?");
        $stmt->execute([$id]);
    }
}