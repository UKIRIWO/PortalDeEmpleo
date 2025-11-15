<?php $this->layout('layouts/Layout_simple', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>

<div class="confirmacion-card">
    <div class="icon-warning">!</div>

    <h1>Confirmar Eliminación</h1>

    <p class="mensaje-advertencia">
        ¿Estás seguro de que deseas eliminar la empresa
        <strong>"<?= $this->e($empresa->getNombre()) ?>"</strong>
        y todas sus ofertas?
    </p>

    <div class="info-eliminacion">
        <p>Esta acción eliminará:</p>
        <ul>
            <li>La empresa y su cuenta de usuario</li>
            <li>Todas las ofertas publicadas por esta empresa</li>
            <li>Todas las solicitudes de los alumnos recibidas</li>
        </ul>
        <p class="texto-peligro">Esta acción no se puede deshacer.</p>
    </div>

    <form action="index.php?menu=EliminarEmpresa" method="POST" class="form-eliminar">
        <input type="hidden" name="id_empresa" value="<?= $empresa->getId() ?>">
        <div>
            <a href="index.php?menu=PanelAdmin" class="btnGris btnDecorado">Cancelar</a>
            <button type="submit" class="btnRojo btnDecorado">Eliminar Empresa</button>
        </div>
    </form>
</div>

<?php $this->stop() ?>