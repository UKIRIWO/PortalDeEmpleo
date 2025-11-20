<?php $this->layout('layouts/Layout', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>
<!-- Si eres empresa aparece el botón añadir oferta -->
<?php if ($rol == 'empresa'): ?>
    <div class="cabeceraOfertas">
        <a href="index.php?menu=CrearOferta" class="btn btnVerde">Nueva Oferta</a>
    </div>
<?php endif; ?>


<div class="ofertas-container">
    <?php foreach ($ofertas as $oferta): ?>
        <div class="oferta-card">
            <h3><?= $oferta->getTitulo() ?></h3>

            <p><strong>Fecha inicio:</strong> <?= $oferta->getFechaInicio() ?></p>
            <p><strong>Fecha fin:</strong> <?= $oferta->getFechaFin() ?></p>
            <p><?= $oferta->getDescripcion() ?></p>

            <div class="acciones-oferta">

                <!-- Accion admin/empresa (eliminar) -->
                <?php if ($rol === 'admin' || $rol === 'empresa'): ?>
                    <form action="index.php?menu=Ofertas" method="POST">
                        <input type="hidden" name="id_oferta" value="<?= $oferta->getId() ?>">
                        <button type="submit" name="accion" value="eliminar" class="btn btnRojo"> Eliminar </button>
                    </form>

                    <!-- Accion alumno (solicitar) -->
                <?php elseif ($rol === 'alumno'): ?>
                    <form method="POST" action="index.php?menu=SolicitarOferta">
                        <input type="hidden" name="id_oferta" value="<?= $oferta->getId() ?>">
                        <input type="hidden" name="id_alumno" value="<?= $idAlumno ?>">
                        <button type="submit" name="accion" value="solicitar" class="btn btnVerde"> Solicitar </button>
                    </form>
                <?php endif; ?>

            </div>
        </div>
    <?php endforeach; ?>

</div>

<?php $this->stop() ?>

