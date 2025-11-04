<?php $this->layout('layouts/Layout'); ?>

<?php $this->start('contenido') ?>
<h1>Ofertas de trabajo</h1>
<?php
    $rol = Login::getRol();
    switch ($rol) {
        case 'admin':
        case 'empresa':
            $this->insert('partials/_nav_ofertas');
            break;
        default:
            break;
    }
?>
<?php foreach ($ofertas as $oferta): ?>
    <div class="divOferta">
        <p>Empresa: <?= $oferta->getIdEmpresaFk() ?></p>
        <p>Fecha Inicio: <?= $oferta->getFechaInicio() ?></p>
        <p>Fecha Fin: <?= $oferta->getFechaFin() ?></p>
        <p>Titulo: <?= $oferta->getTitulo() ?></p>
        <p>Descripción: <?= $oferta->getDescripcion() ?></p>
    </div>
<?php endforeach; ?>
<?php $this->stop() ?>