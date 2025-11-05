<?php
namespace Repositories;
use Models\Oferta;
class RepoOferta {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM oferta WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Oferta(
                $fila['id'],
                $fila['id_empresa_fk'],
                $fila['fecha_inicio'],
                $fila['fecha_fin'],
                $fila['titulo'],
                $fila['descripcion']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM oferta");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ofertas = [];
        foreach ($filas as $fila) {
            $ofertas[] = new Oferta(
                $fila['id'],
                $fila['id_empresa_fk'],
                $fila['fecha_inicio'],
                $fila['fecha_fin'],
                $fila['titulo'],
                $fila['descripcion']
            );
        }

        return $ofertas;
    }

    public static function findByEmpresaId($idEmpresa) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM oferta WHERE id_empresa_fk = ?");
        $stmt->execute([$idEmpresa]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ofertas = [];
        foreach ($filas as $fila) {
            $ofertas[] = new Oferta(
                $fila['id'],
                $fila['id_empresa_fk'],
                $fila['fecha_inicio'],
                $fila['fecha_fin'],
                $fila['titulo'],
                $fila['descripcion']
            );
        }

        return $ofertas;
    }

    public static function save($oferta) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO oferta (id_empresa_fk, fecha_inicio, fecha_fin, titulo, descripcion) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $oferta->getIdEmpresaFk(),
            $oferta->getFechaInicio(),
            $oferta->getFechaFin(),
            $oferta->getTitulo(),
            $oferta->getDescripcion()
        ]);
        $oferta->setId($con->lastInsertId());
    }

    public static function update($oferta) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE oferta SET id_empresa_fk = ?, fecha_inicio = ?, fecha_fin = ?, titulo = ?, descripcion = ? WHERE id = ?");
        $stmt->execute([
            $oferta->getIdEmpresaFk(),
            $oferta->getFechaInicio(),
            $oferta->getFechaFin(),
            $oferta->getTitulo(),
            $oferta->getDescripcion(),
            $oferta->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM oferta WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>