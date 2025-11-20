<?php $this->layout('layouts/Layout', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>
<h1>Solicitudes de las empresas</h1>
<?php foreach ($solicitudes as $solicitud): ?>
    <div class="solicitud">
        <p>ID: <?= $solicitud->getId() ?></p>
        <p>Oferta: <?= $solicitud->getIdOfertaFk() ?></p>
        <p>ID Alumno: <?= $solicitud->getIdAlumnoFk() ?></p>
        <p>Fecha de creación: <?= $solicitud->getFechaSolicitud() ?></p>
        <p>Estado: <?= $solicitud->getEstado() ?></p>
    </div>
<?php endforeach; ?>

<?php $this->stop() ?>