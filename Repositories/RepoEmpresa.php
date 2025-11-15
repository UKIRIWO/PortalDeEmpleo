<?php

namespace Repositories;

use Models\Empresa;

class RepoEmpresa
{

    public static function findById($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM empresa WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Empresa(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['nombre'],
                $fila['direccion'],
                $fila['persona_de_contacto'],
                $fila['correo_de_contacto'],
                $fila['telefono_de_contacto'],
                $fila['logo']
            );
        }

        return null;
    }

    public static function findByIdUser($idUser)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM empresa WHERE id_user_fk = ?");
        $stmt->execute([$idUser]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Empresa(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['nombre'],
                $fila['direccion'],
                $fila['persona_de_contacto'],
                $fila['correo_de_contacto'],
                $fila['telefono_de_contacto'],
                $fila['logo']
            );
        }

        return null;
    }

    public static function findAll()
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM empresa");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $empresas = [];
        foreach ($filas as $fila) {
            $empresas[] = new Empresa(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['nombre'],
                $fila['direccion'],
                $fila['persona_de_contacto'],
                $fila['correo_de_contacto'],
                $fila['telefono_de_contacto'],
                $fila['logo']
            );
        }

        return $empresas;
    }


    public static function saveWithUser($user, $empresa)
    {
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
            $stmt = $con->prepare("INSERT INTO empresa (id_user_fk, nombre, direccion, persona_de_contacto, correo_de_contacto, telefono_de_contacto, logo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $idUser,
                $empresa->getNombre(),
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
        } catch (\Exception $e) {
            // Revertir cambios si hay error
            $con->rollBack();
            $errorMsg = "Error al guardar empresa con usuario: " . $e->getMessage();
            error_log($errorMsg);
            // Mostrar el error en pantalla para debug
            echo "<p style='color: red;'><strong>Error MySQL:</strong> " . $e->getMessage() . "</p>";
            return false;
        }
    }


    public static function save($empresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO empresa (id_user_fk, nombre, direccion, persona_de_contacto, correo_de_contacto, telefono_de_contacto, logo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $empresa->getIdUserFk(),
            $empresa->getNombre(),
            $empresa->getDireccion(),
            $empresa->getPersonaDeContacto(),
            $empresa->getCorreoDeContacto(),
            $empresa->getTelefonoDeContacto(),
            $empresa->getLogo()
        ]);
        $empresa->setId($con->lastInsertId());
    }


    public static function update($empresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE empresa SET nombre = ?, direccion = ?, persona_de_contacto = ?, correo_de_contacto = ?, telefono_de_contacto = ?, logo = ? WHERE id = ?");
        $stmt->execute([
            $empresa->getNombre(),
            $empresa->getDireccion(),
            $empresa->getPersonaDeContacto(),
            $empresa->getCorreoDeContacto(),
            $empresa->getTelefonoDeContacto(),
            $empresa->getLogo(),
            $empresa->getId()
        ]);
    }

    public static function delete($id)
    {
        $con = DB::getConnection();

        try {
            // Obtener el id del usuario antes de eliminar
            $empresa = self::findById($id);
            if ($empresa == null) {
                return false;
            }

            $idUser = $empresa->getIdUserFk();

            // Eliminar el usuario (CASCADE eliminará automáticamente: empresa -> ofertas -> oferta_ciclo y solicitudes)
            $stmt = $con->prepare("DELETE FROM user WHERE id = ?");
            $stmt->execute([$idUser]);

            return true;
        } catch (\Exception $e) {
            $con->rollBack();
            error_log("Error al eliminar empresa con usuario: " . $e->getMessage());
            return false;
        }
    }

    //-----------------------------------------------Candidatas-----------------------------------------------//
    public static function findByIdCandidata($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM empresa_candidata WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Empresa(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['nombre'],
                $fila['direccion'],
                $fila['persona_de_contacto'],
                $fila['correo_de_contacto'],
                $fila['telefono_de_contacto'],
                $fila['logo']
            );
        }

        return null;
    }

    public static function findAllCandidata()
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM empresa_candidata");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $empresas = [];
        foreach ($filas as $fila) {
            $empresas[] = new Empresa(
                $fila['id'],
                $fila['id_user_fk'],
                $fila['nombre'],
                $fila['direccion'],
                $fila['persona_de_contacto'],
                $fila['correo_de_contacto'],
                $fila['telefono_de_contacto'],
                $fila['logo']
            );
        }

        return $empresas;
    }


    public static function saveCandidata($user, $empresa)
    {
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
            $stmt = $con->prepare("INSERT INTO empresa_candidata (id_user_fk, nombre, direccion, persona_de_contacto, correo_de_contacto, telefono_de_contacto, logo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $idUser,
                $empresa->getNombre(),
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
        } catch (\Exception $e) {
            // Revertir cambios si hay error
            $con->rollBack();
            $errorMsg = "Error al guardar empresa con usuario: " . $e->getMessage();
            error_log($errorMsg);
            // Mostrar el error en pantalla para debug
            echo "<p style='color: red;'><strong>Error MySQL:</strong> " . $e->getMessage() . "</p>";
            return false;
        }
    }

    public static function deleteCandidata($id)
    {
        $con = DB::getConnection();

        try {
            $empresa = self::findByIdCandidata($id);
            if ($empresa == null) {
                return false;
            }

            $idUser = $empresa->getIdUserFk();

            $con->beginTransaction();

            $stmt = $con->prepare("DELETE FROM user WHERE id = ?");
            $stmt->execute([$idUser]);

            $con->commit();
            return true;
        } catch (\Exception $e) {
            $con->rollBack();
            error_log("Error al eliminar empresa con usuario: " . $e->getMessage());
            return false;
        }
    }

    public static function aprobarCandidata($id)
    {
        $con = DB::getConnection();

        try {
            // Buscar la empresa candidata
            $empresaCandidata = self::findByIdCandidata($id);
            if ($empresaCandidata == null) {
                return false;
            }
            
            $con->beginTransaction();

            // Insertar en la tabla empresa
            
            $stmt = $con->prepare("
            INSERT INTO empresa (id_user_fk, nombre, direccion, persona_de_contacto, correo_de_contacto, telefono_de_contacto, logo)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
            
            $stmt->execute([
                $empresaCandidata->getIdUserFk(),
                $empresaCandidata->getNombre(),
                $empresaCandidata->getDireccion(),
                $empresaCandidata->getPersonaDeContacto(),
                $empresaCandidata->getCorreoDeContacto(),
                $empresaCandidata->getTelefonoDeContacto(),
                $empresaCandidata->getLogo()
            ]);

            // Eliminar de la tabla empresa_candidata
            $stmt = $con->prepare("DELETE FROM empresa_candidata WHERE id = ?");
            $stmt->execute([$id]);

            // (Opcional) Actualizar rol del usuario a “empresa aprobada” (por ejemplo, id_rol_fk = 2)
            $stmt = $con->prepare("UPDATE user SET id_rol_fk = 2 WHERE id = ?");
            $stmt->execute([$empresaCandidata->getIdUserFk()]);

            $con->commit();
            return true;
        } catch (\Exception $e) {
            $con->rollBack();
            error_log("Error al aprobar empresa candidata: " . $e->getMessage());
            return false;
        }
    }
}
