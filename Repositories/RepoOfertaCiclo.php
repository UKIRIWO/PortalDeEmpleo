<?php
namespace Repositories;
use Models\OfertaCiclo;
class RepoOfertaCiclo {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM oferta_ciclo WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new OfertaCiclo(
                $fila['id'],
                $fila['id_ciclo_fk'],
                $fila['id_oferta_fk']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM oferta_ciclo");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ofertasCiclos = [];
        foreach ($filas as $fila) {
            $ofertasCiclos[] = new OfertaCiclo(
                $fila['id'],
                $fila['id_ciclo_fk'],
                $fila['id_oferta_fk']
            );
        }

        return $ofertasCiclos;
    }

    public static function findByOfertaId($idOferta) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM oferta_ciclo WHERE id_oferta_fk = ?");
        $stmt->execute([$idOferta]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ofertasCiclos = [];
        foreach ($filas as $fila) {
            $ofertasCiclos[] = new OfertaCiclo(
                $fila['id'],
                $fila['id_ciclo_fk'],
                $fila['id_oferta_fk']
            );
        }

        return $ofertasCiclos;
    }

    public static function findByCicloId($idCiclo) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM oferta_ciclo WHERE id_ciclo_fk = ?");
        $stmt->execute([$idCiclo]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ofertasCiclos = [];
        foreach ($filas as $fila) {
            $ofertasCiclos[] = new OfertaCiclo(
                $fila['id'],
                $fila['id_ciclo_fk'],
                $fila['id_oferta_fk']
            );
        }

        return $ofertasCiclos;
    }

    public static function save($ofertaCiclo) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO oferta_ciclo (id_ciclo_fk, id_oferta_fk) VALUES (?, ?)");
        $stmt->execute([
            $ofertaCiclo->getIdCicloFk(),
            $ofertaCiclo->getIdOfertaFk()
        ]);
        $ofertaCiclo->setId($con->lastInsertId());
    }

    public static function update($ofertaCiclo) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE oferta_ciclo SET id_ciclo_fk = ?, id_oferta_fk = ? WHERE id = ?");
        $stmt->execute([
            $ofertaCiclo->getIdCicloFk(),
            $ofertaCiclo->getIdOfertaFk(),
            $ofertaCiclo->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM oferta_ciclo WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function deleteByOfertaAndCiclo($idOferta, $idCiclo) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM oferta_ciclo WHERE id_oferta_fk = ? AND id_ciclo_fk = ?");
        $stmt->execute([$idOferta, $idCiclo]);
    }
}
?>