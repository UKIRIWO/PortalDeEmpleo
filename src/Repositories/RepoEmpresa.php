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

            $con->commit();
            return true;
        } catch (\Exception $e) {
            $con->rollBack();
            $errorMsg = "Error al guardar empresa con usuario: " . $e->getMessage();
            error_log($errorMsg);
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

    public static function findAllPaginated($limit, $offset)
    {
        $con = DB::getConnection();

        $stmt = $con->prepare("SELECT * FROM empresa LIMIT $limit OFFSET $offset");
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

    public static function countAll()
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT COUNT(*) as total FROM empresa");
        $stmt->execute();
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }

    public static function findByNombrePaginated($nombre, $limit, $offset)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM empresa WHERE nombre LIKE ? LIMIT ? OFFSET ?");
        $terminoBusqueda = "%" . $nombre . "%";
        $stmt->bindValue(1, $terminoBusqueda, \PDO::PARAM_STR);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, \PDO::PARAM_INT);
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

    public static function countByNombre($nombre)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT COUNT(*) as total FROM empresa WHERE nombre LIKE ?");
        $terminoBusqueda = "%" . $nombre . "%";
        $stmt->execute([$terminoBusqueda]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }


    //---------------------------------------------Estadisticas-----------------------------------------------//

    public static function contarOfertas($idEmpresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT COUNT(*) as total FROM oferta WHERE id_empresa_fk = ?");
        $stmt->execute([$idEmpresa]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }

    public static function contarOfertasActivas($idEmpresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT COUNT(*) as total FROM oferta WHERE id_empresa_fk = ? AND fecha_fin >= CURDATE()");
        $stmt->execute([$idEmpresa]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }


    public static function contarSolicitudes($idEmpresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("
        SELECT COUNT(*) as total 
        FROM solicitud s
        INNER JOIN oferta o ON s.id_oferta_fk = o.id
        WHERE o.id_empresa_fk = ?
    ");
        $stmt->execute([$idEmpresa]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }


    public static function contarSolicitudesPorEstado($idEmpresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("
        SELECT s.estado, COUNT(*) as total
        FROM solicitud s
        INNER JOIN oferta o ON s.id_oferta_fk = o.id
        WHERE o.id_empresa_fk = ?
        GROUP BY s.estado
    ");
        $stmt->execute([$idEmpresa]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public static function solicitudesPorOferta($idEmpresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("
        SELECT o.titulo, COUNT(s.id) as total
        FROM oferta o
        LEFT JOIN solicitud s ON o.id = s.id_oferta_fk
        WHERE o.id_empresa_fk = ?
        GROUP BY o.id, o.titulo
        ORDER BY total DESC
        LIMIT 10
    ");
        $stmt->execute([$idEmpresa]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public static function solicitudesPorMes($idEmpresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("
        SELECT 
            DATE_FORMAT(s.fecha_solicitud, '%Y-%m') as mes,
            COUNT(*) as total
        FROM solicitud s
        INNER JOIN oferta o ON s.id_oferta_fk = o.id
        WHERE o.id_empresa_fk = ? 
        AND s.fecha_solicitud >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(s.fecha_solicitud, '%Y-%m')
        ORDER BY mes ASC
    ");
        $stmt->execute([$idEmpresa]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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

            $con->commit();
            return true;
        } catch (\Exception $e) {
            $con->rollBack();
            $errorMsg = "Error al guardar empresa con usuario: " . $e->getMessage();
            error_log($errorMsg);
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

            $con->commit();
            return true;
        } catch (\Exception $e) {
            $con->rollBack();
            error_log("Error al aprobar empresa candidata: " . $e->getMessage());
            return false;
        }
    }
}
