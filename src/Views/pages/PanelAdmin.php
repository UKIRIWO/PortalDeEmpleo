<?php $this->layout('layouts/Layout', ['titulo' => $titulo]); ?>

<?php $this->start('js') ?>
<script src="js/Modal.js"></script>
<script src="js/panelAdmin_alumno.js"></script>
<script src="js/panelAdmin.js"></script>
<script src="js/validator.js"></script>
<?php $this->stop() ?>

<?php $this->start('css') ?>
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<div id="PanelAdmin">
    <!-- Menú lateral -->
    <label for="checkboxAlumnos" class="labelNavPanelAdmin">Alumnos</label>
    <input type="radio" name="tablas" id="checkboxAlumnos" checked>
    <label for="checkboxEmpresas" class="labelNavPanelAdmin">Empresas</label>
    <input type="radio" name="tablas" id="checkboxEmpresas">
    <label for="checkboxEmpresasCandidatas" class="labelNavPanelAdmin">Empresas Candidatas</label>
    <input type="radio" name="tablas" id="checkboxEmpresasCandidatas">

    <!-- Contenido -->
    <section class="gestion-alumnos tabla-alumnosPanel">

        <!-- Alumnos -->
        <h1>Gestión de Alumnos</h1>
        <button id="btnAgregarAlumno" class="btn btnVerde">Añadir Alumno</button>
        <button id="btnAgregarVariosAlumnos" class="btn btnVerde">Añadir Varios Alumnos</button>
        <input type="text" id="bucarAlumno" placeholder="Buscar por nombre...">

        <table id="tablaAlumnos">
            <thead>
                <tr>
                    <th data-campo="id">ID</th>
                    <th data-campo="id_user">ID_user</th>
                    <th data-campo="dni">DNI</th>
                    <th data-campo="nombre">Nombre</th>
                    <th data-campo="email">Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Se rellenará con JS -->
            </tbody>
        </table>
    </section>

    <!-- Sección Empresas -->
    <section class="gestion-empresas">

        <div class="tabla-empresas">
            <h1>Empresas Aprobadas</h1>
            <a href="index.php?menu=RegistroEmpresa"><button class="btn btnVerde">Añadir Empresa</button></a>
            <a href="index.php?menu=PdfEmpresas" target="_blank"><button class="btn btnVerde">Generar PDF de Empresas</button></a>

            <!-- Formulario de búsqueda -->
            <form method="GET" action="index.php" class="form-busqueda">
                <input type="hidden" name="menu" value="PanelAdmin">
                <input type="text" name="busqueda" placeholder="Buscar por nombre de empresa..."
                    value="<?= $_GET['busqueda'] ?? '' ?>">
                <button type="submit" class="btn btnAzul">Buscar</button>
                <?php if (isset($_GET['busqueda']) && !empty($_GET['busqueda'])): ?>
                    <a href="index.php?menu=PanelAdmin" class="btn btnGris">Limpiar búsqueda</a>
                <?php endif; ?>
            </form>

            <!-- Paginación -->
            <div class="paginacion">

                <label for="selectSize"> Mostrar:
                    <select id="selectSize">
                        <option value="2" <?= $size == 2 ? 'selected' : '' ?>>2</option>
                        <option value="5" <?= $size == 5 ? 'selected' : '' ?>>5</option>
                        <option value="10" <?= $size == 10 ? 'selected' : '' ?>>10</option>
                        <option value="20" <?= $size == 20 ? 'selected' : '' ?>>20</option>
                        <option value="50" <?= $size == 50 ? 'selected' : '' ?>>50</option>
                    </select>
                </label>
            </div>

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
                    <?php if (empty($empresas)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #6c757d;">
                                No hay empresas en esta página
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($empresas as $empresa): ?>
                            <tr>
                                <td><?= $empresa->getId() ?></td>
                                <td><?= $empresa->getIdUserFk() ?></td>
                                <td><?= $empresa->getNombre() ?></td>
                                <td><?= $empresa->getCorreoDeContacto() ?></td>
                                <td>
                                    <form method="POST" action="index.php?menu=PanelAdmin">
                                        <input type="hidden" name="id_empresa" value="<?= $empresa->getId() ?>">
                                        <button type="submit" name="accion" value="detalles" class="btn btnVerde btnDetalles">Detalles</button>
                                        <button type="submit" name="accion" value="editar" class="btn btnAmarillo btnEditar">Editar</button>
                                        <button type="submit" name="accion" value="eliminar" class="btn btnRojo btnEliminar">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="paginacion">
                <?php
                $parametrosBusqueda = isset($_GET['busqueda']) ? '&busqueda=' . urlencode($_GET['busqueda']) : '';
                ?>

                <a href="index.php?menu=PanelAdmin&page=<?= max(1, $paginaActual - 1) ?>&size=<?= $size ?><?= $parametrosBusqueda ?>"
                    class="<?= $paginaActual <= 1 ? 'disabled' : '' ?>"
                    <?= $paginaActual <= 1 ? 'onclick="return false;"' : '' ?>>
                    Anterior
                </a>

                <span class="info-pagina">
                    Página <?= $paginaActual ?> de <?= max(1, $totalPaginas) ?>
                </span>

                <a href="index.php?menu=PanelAdmin&page=<?= min($totalPaginas, $paginaActual + 1) ?>&size=<?= $size ?><?= $parametrosBusqueda ?>"
                    class="<?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>"
                    <?= $paginaActual >= $totalPaginas ? 'onclick="return false;"' : '' ?>>
                    Siguiente
                </a>
            </div>
        </div>

        <div class="tabla-empresas-candidatas">
            <!-- Empresas Candidatas -->
            <?php if ($empresasC): ?>
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
                                        <input type="hidden" name="id_empresaC" value="<?= $empresaC->getId() ?>">
                                        <input type="hidden" name="id_user" value="<?= $empresaC->getIdUserFk() ?>">
                                        <button type="submit" name="accion" value="aprobar" class="btn btnVerde">Aprobar</button>
                                        <button type="submit" name="accion" value="rechazar" class="btn btnRojo">Rechazar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <h2>No quedan empresas candidatas</h2>
                <p>¡Vuelve más tarde!</p>
            <?php endif; ?>
        </div>

    </section>
</div>
<?php $this->stop() ?>