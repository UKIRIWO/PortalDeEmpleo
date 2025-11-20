<?php $this->layout('layouts/Layout', ['titulo' => $titulo]); ?>

<?php $this->start('js') ?>
<script src="js/Modal.js"></script>
<script src="js/solicitudes.js"></script>
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<div class="solicitudes-container">
    <?php
    $rol = \Helpers\Login::getRol();
    
    if ($rol == "admin" || $rol == "empresa"): // ADMIN o EMPRESA ?>
        <h1><?= $rol == "admin" ? 'Todas las Solicitudes (Admin)' : 'Solicitudes Recibidas' ?></h1>
        
        <?php if (empty($solicitudes)): ?>
            <div class="empty-state">
                <h3>No hay solicitudes todavía</h3>
                <p>Cuando los alumnos soliciten ofertas, aparecerán aquí</p>
            </div>
        <?php else: ?>
            <?php
            // Creo un array de solicitudes agrupados por oferta
            $solicitudesPorOferta = [];
            foreach ($solicitudes as $solicitud) {
                //se coge el id de la oferta
                $idOferta = $solicitud['id_oferta_fk'];
                //si la oferta no existe dentro del array que acabamos de crear lo metemos
                if (!isset($solicitudesPorOferta[$idOferta])) {
                    //metemos la oferta y con ella, el titulo, el nombre de la empresa
                    // y se crea un array donde meteremos las solicitudes que tengan el id_oferta_fk de dicha oferta
                    $solicitudesPorOferta[$idOferta] = [
                        'oferta_titulo' => $solicitud['oferta_titulo'],
                        'empresa_nombre' => $solicitud['empresa_nombre'] ?? '',
                        'solicitudes' => []
                    ];
                }

                //metemos la solicitud al array interno de esa oferta
                $solicitudesPorOferta[$idOferta]['solicitudes'][] = $solicitud;
            }
            
            foreach ($solicitudesPorOferta as $grupo): ?>
                <div class="oferta-seccion">
                    <h3>
                        <!--Se pinta el titulo de la oferta-->
                        <?= $grupo['oferta_titulo'] ?>
                        <!--Si eres admin se pintará el nombre de la empresa a la que pertenece la oferta-->
                        <?php if ($rol == "admin" && !empty($grupo['empresa_nombre'])): ?>
                            <span> - <?= $grupo['empresa_nombre'] ?> </span>
                        <?php endif; ?>
                    </h3>
                    
                    <table class="tablaSolicitudes">
                        <thead>
                            <tr>
                                <th>Alumno</th>
                                <th>DNI</th>
                                <th>Email</th>
                                <th>Fecha Solicitud</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grupo['solicitudes'] as $sol): ?>
                                <tr>
                                    <td><?= $sol['alumno_nombre'] . ' ' . $sol['alumno_ape1'] ?></td>
                                    <td><?= $sol['alumno_dni'] ?></td>
                                    <td><?= $sol['alumno_email'] ?></td>
                                    <td><?= $sol['fecha_solicitud'] ?></td>
                                    <td>
                                        <!--Le pone como clase badge-(el estado en el que está, apra cambiarle el color)-->
                                        <span class="badge badge-<?= $sol['estado'] ?>">
                                            <?= ucfirst($sol['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="tdAccion">
                                        <input type="hidden" class="idSolicitud" value="<?= $sol['id'] ?>">
                                        <input type="hidden" class="idAlumno" value="<?= $sol['id_alumno_fk'] ?>">
                                        <button class="btnVerCV btn btnAzul">Ver CV</button>
                                        <!--Si el estado no es pendiente se le pone disabled al button-->
                                        <button class="btnAceptar btnAgregar btn btnVerde" <?= $sol['estado'] !== 'pendiente' ? 'disabled' : '' ?>> Aceptar </button>
                                        <button class="btnRechazar btnEliminar btn btnRojo" <?= $sol['estado'] !== 'pendiente' ? 'disabled' : '' ?>> Rechazar </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <!--Alumno-->
    <?php elseif ($rol == "alumno"): ?>
        <h1>Mis Solicitudes</h1>
        
        <?php if (empty($solicitudes)): ?>
            <div class="empty-state">
                <h3>No has realizado solicitudes todavía</h3>
                <p>Explora las ofertas disponibles y postúlate a las que te interesen</p>
            </div>
        <?php else: ?>
            <div class="oferta-seccion">
                <table class="tablaSolicitudes" id="tablaSolicitudesAlumno">
                    <thead>
                        <tr>
                            <th>♥</th>
                            <th>Oferta</th>
                            <th>Empresa</th>
                            <th>Fecha Solicitud</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $sol): ?>
                            <tr>
                                <td>
                                    <span class="corazon <?= $sol['favorito'] == 1 ? 'favorito' : '' ?>" 
                                          data-id="<?= $sol['id'] ?>">
                                        <?= $sol['favorito'] == 1 ? '♥' : '♡' ?>
                                    </span>
                                </td>
                                <td><?= $sol['oferta_titulo'] ?></td>
                                <td><?= $sol['empresa_nombre'] ?></td>
                                <td><?= $sol['fecha_solicitud'] ?></td>
                                <td>
                                    <span class="badge badge-<?= $sol['estado'] ?>">
                                        <?= ucfirst($sol['estado']); ?>
                                    </span>
                                </td>
                                <td class="tdAccion">
                                    <input type="hidden" class="idSolicitud" value="<?= $sol['id'] ?>">
                                    <button class="btnEliminarSolicitud btnEliminar">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>


<?php $this->stop() ?>