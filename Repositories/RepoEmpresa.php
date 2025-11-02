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

    /**
     * Guarda una empresa junto con su usuario en una transacción
     * Este es el ÚNICO método para guardar empresas
     * @param User $user Usuario asociado a la empresa
     * @param Empresa $empresa Datos de la empresa
     * @return bool true si se guardó correctamente, false si hubo error
     */
    public static function save($user, $empresa) {
        $con = DB::getConnection();
        
        try {
            // Iniciar transacción
            $con->beginTransaction();
            
            // Insertar usuario
            $stmt = $con->prepare("INSERT INTO user (nombre_usuario, password, id_rol_fk) VALUES (?, ?, ?)");
            $stmt->execute([
                $user->getNombreUsuario(),
                $user->getPassword(),
                $user->getIdRolFk()
            ]);
            $idUser = $con->lastInsertId();
            $user->setId($idUser);
            
            // Insertar empresa
            $stmt = $con->prepare("INSERT INTO empresa (id_user_fk, direccion, persona_de_contacto, correo_de_contacto, telefono_de_contacto, logo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $idUser,
                $empresa->getDireccion(),
                $empresa->getPersonaDeContacto(),
                $empresa->getCorreoDeContacto(),
                $empresa->getTelefonoDeContacto(),
                $empresa->getLogo()
            ]);
            $empresa->setId($con->lastInsertId());
            $empresa->setIdUserFk($idUser);
            
            // Confirmar transacción
            $con->commit();
            return true;
            
        } catch (Exception $e) {
            // Revertir cambios si hay error
            $con->rollBack();
            $errorMsg = "Error al guardar empresa con usuario: " . $e->getMessage();
            error_log($errorMsg);
            // Mostrar el error en pantalla para debug
            echo "<p style='color: red;'><strong>Error MySQL:</strong> " . $e->getMessage() . "</p>";
            return false;
        }
    }

    public static function update($empresa) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE empresa SET direccion = ?, persona_de_contacto = ?, correo_de_contacto = ?, telefono_de_contacto = ?, logo = ? WHERE id = ?");
        $stmt->execute([
            $empresa->getDireccion(),
            $empresa->getPersonaDeContacto(),
            $empresa->getCorreoDeContacto(),
            $empresa->getTelefonoDeContacto(),
            $empresa->getLogo(),
            $empresa->getId()
        ]);
    }

    /**
     * Elimina una empresa y su usuario asociado
     * Este es el ÚNICO método para eliminar empresas
     * También elimina automáticamente por CASCADE: ofertas, oferta_ciclo y solicitudes
     * @param int $id ID de la empresa
     * @return bool true si se eliminó correctamente, false si hubo error
     */
    public static function delete($id) {
        $con = DB::getConnection();
        
        try {
            // Obtener el id del usuario antes de eliminar
            $empresa = self::findById($id);
            if ($empresa == null) {
                return false;
            }
            
            $idUser = $empresa->getIdUserFk();
            
            // Iniciar transacción
            $con->beginTransaction();
            
            // Eliminar el usuario (CASCADE eliminará automáticamente: empresa -> ofertas -> oferta_ciclo y solicitudes)
            $stmt = $con->prepare("DELETE FROM user WHERE id = ?");
            $stmt->execute([$idUser]);
            
            $con->commit();
            return true;
            
        } catch (Exception $e) {
            $con->rollBack();
            error_log("Error al eliminar empresa con usuario: " . $e->getMessage());
            return false;
        }
    }
}
?>