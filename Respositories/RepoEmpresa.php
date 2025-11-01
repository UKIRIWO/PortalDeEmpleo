<?php

class RepoEmpresa {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM empresa WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            return new Empresa(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['direccion'],
                $fila['persona_de_contacto'],
                $fila['correo_de_contacto'],
                $fila['telefono_de_contacto'],
                $fila['logo']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM empresa");
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $empresas = [];
        foreach ($filas as $fila) {
            $empresas[] = new Empresa(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['direccion'],
                $fila['persona_de_contacto'],
                $fila['correo_de_contacto'],
                $fila['telefono_de_contacto'],
                $fila['logo']
            );
        }

        return $empresas;
    }

    public static function save($empresa) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO empresa (id_user_fk, direccion, persona_de_contacto, correo_de_contacto, telefono_de_contacto, logo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $empresa->getIdUserFk(),
            $empresa->getDireccion(),
            $empresa->getPersonaDeContacto(),
            $empresa->getCorreoDeContacto(),
            $empresa->getTelefonoDeContacto(),
            $empresa->getLogo()
        ]);
        $empresa->setId($con->lastInsertId());
    }

    public static function update($empresa) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE empresa SET id_user_fk = ?, direccion = ?, persona_de_contacto = ?, correo_de_contacto = ?, telefono_de_contacto = ?, logo = ? WHERE id = ?");
        $stmt->execute([
            $empresa->getIdUserFk(),
            $empresa->getDireccion(),
            $empresa->getPersonaDeContacto(),
            $empresa->getCorreoDeContacto(),
            $empresa->getTelefonoDeContacto(),
            $empresa->getLogo(),
            $empresa->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM empresa WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>