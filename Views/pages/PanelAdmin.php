<?php $this->layout('layouts/Layout', ['titulo' => 'Gestión de Empresas y Alumnos']) ?>

<?php $this->start('css') ?>
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<main>
    <!-- Sección Alumnos -->
    <section class="gestion-alumnos">
        <h1>Gestión de Alumnos</h1>
        <button id="btnAgregar">Añadir alumno</button>
        <button id="btnAgregarVarios">Añadir varios alumnos</button>

        <table id="tablaAlumnos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID_user</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Se rellenará vía JS -->
            </tbody>
        </table>

    </section>
    <!-- Contenedor del modal -->
    <div id="modalDetallesAlumnos"></div>
    <div id="modalEditar"></div>
    <div id="modalEliminar"></div>

    <!-- Sección Empresas -->
    <section class="gestion-empresas">
        <h1>Empresas Aprobadas</h1>
        <button><a href="index.php?menu=RegistroEmpresa">Añadir Empresa</a></button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID_user</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empresas as $empresa): ?>
                    <tr>
                        <td><?= $empresa->getId() ?></td>
                        <td><?= $empresa->getIdUserFk() ?></td>
                        <td><?= $empresa->getNombre() ?></td>
                        <td><?= $empresa->getCorreoDeContacto() ?></td>
                        <td>
                            <button>Editar</button>
                            <button>Eliminar</button>
                            <button>Detalles</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h1>Empresas Pendientes de Aprobación</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID_user</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empresasC as $empresaC): ?>
                    <tr>
                        <td><?= $empresa->getId() ?></td>
                        <td><?= $empresa->getIdUserFk() ?></td>
                        <td><?= $empresa->getNombre() ?></td>
                        <td><?= $empresa->getCorreoDeContacto() ?></td>
                        <td>
                            <form method="post" action="index.php?menu=PanelAdmin">
                                <input type="hidden" name="aprobarEmpresa" value="<?= $empresaC->getIdUserFk() ?>">
                                <button type="submit" name="accion" value="aprobar">Aprobar</button>
                                <button type="submit" name="accion" value="rechazar">Rechazar</button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>


</main>
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="../Public/js/Modal.js"></script>
<script src="../Public/js/logicaAlumno.js"></script>


<?php $this->stop() ?>