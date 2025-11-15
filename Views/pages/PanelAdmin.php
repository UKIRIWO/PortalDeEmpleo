<?php $this->layout('layouts/Layout', ['titulo' => $titulo]); ?>

<?php $this->start('js') ?>
<script src="js/Modal.js"></script>
<script src="js/panelAdmin_alumno.js"></script>
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<div id="PanelAdmin">
    <section class="gestion-alumnos">
        <h1>Gestión de Alumnos</h1>
        <button id="btnAgregarAlumno" class="btnAgregar">Añadir Alumno</button>
        <button id="btnAgregarVariosAlumnos" class="btnAgregar">Añadir Varios Alumnos</button>

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

    <!-- Sección Empresas -->
    <section class="gestion-empresas">
        <h1>Empresas Aprobadas</h1>
        <button class="btnAgregar"><a href="index.php?menu=RegistroEmpresa">Añadir Empresa</a></button>
        <table id="tablaEmpresas">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID_user</th>
                    <th>Nombre de la empresa</th>
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
                            <form method="POST" action="index.php?menu=PanelAdmin">
                                <input type="hidden" name="id_empresa" value="<?= $empresa->getId() ?>">
                                <button type="submit" name="accion" value="detalles" class="btnDetalles">Detalles</button>
                                <button type="submit" name="accion" value="editar" class="btnEditar">Editar</button>
                                <button type="submit" name="accion" value="eliminar" class="btnEliminar">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        if ($empresasC) {
        ?>
            <h1>Empresas Pendientes de Aprobación</h1>
            <table id="tablaEmpresasPendientes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID_user</th>
                        <th>Nombre de la empresa</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empresasC as $empresaC): ?>
                        <tr>
                            <td><?= $empresaC->getId() ?></td>
                            <td><?= $empresaC->getIdUserFk() ?></td>
                            <td><?= $empresaC->getNombre() ?></td>
                            <td><?= $empresaC->getCorreoDeContacto() ?></td>
                            <td>
                                <form method="POST" action="index.php?menu=PanelAdmin">
                                    <input type="hidden" name="id_empresa" value="<?= $empresaC->getId() ?>">
                                    <input type="hidden" name="id_user" value="<?= $empresaC->getIdUserFk() ?>">
                                    <button type="submit" name="accion" value="aprobar" class="btnAgregar">Aprobar</button>
                                    <button type="submit" name="accion" value="rechazar" class="btnEliminar">Rechazar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php } ?>
    </section>
</div>
<?php $this->stop() ?>