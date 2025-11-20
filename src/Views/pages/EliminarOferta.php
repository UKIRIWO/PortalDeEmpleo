<?php $this->layout('layouts/Layout_simple', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>

<div class="confirmacion-card">
    <div class="icon-warning">!</div>

    <h1>Confirmar Eliminación</h1>

    <p class="mensaje-advertencia">
        ¿Estás seguro de que deseas eliminar la oferta
        <strong>"<?= $this->e($oferta->getTitulo()) ?>"</strong>?
    </p>

    <div class="info-eliminacion">
        <p>Esta acción eliminará también:</p>
        <ul>
            <li>Las solicitudes de los alumnos asociadas.</li>
        </ul>
        <p class="texto-peligro">Esta acción no se puede deshacer.</p>
    </div>

    <form action="index.php?menu=EliminarOferta" method="POST" class="form-eliminar">
        <input type="hidden" name="id_oferta" value="<?= $oferta->getId() ?>">
        <div>
            <a href="index.php?menu=Ofertas" class="btnGris btnDecorado">Cancelar</a>
            <button type="submit" class="btnRojo btnDecorado">Eliminar Oferta</button>
        </div>
    </form>
</div>

<?php $this->stop() ?>