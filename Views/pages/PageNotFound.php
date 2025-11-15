<?php $this->layout('layouts/Layout_simple', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>
<div class="error-content">
    <h1>¡Vaya! Página No Encontrada</h1>
    <p>Parece que la página que estás buscando no existe o se ha movido.</p>
    <p>No te preocupes, siempre puedes volver a la página de inicio y comenzar de nuevo.</p>
    <br>
    <a href="Index.php?menu=Inicio" class="btn-submit">Ir a la Página de Inicio</a>
</div>

<?php $this->stop() ?>