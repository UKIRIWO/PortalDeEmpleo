<?php $this->layout('layouts/Layout') ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/eliminar-empresa.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<main class="eliminar-empresa-container">
    <div class="confirmacion-card">
        <div class="icon-warning">⚠️</div>
        
        <h1>Confirmar Eliminación</h1>
        
        <p class="mensaje-advertencia">
            ¿Estás seguro de que deseas eliminar la empresa 
            <strong>"<?= $this->e($empresa->getNombre()) ?>"</strong> 
            y todas sus ofertas?
        </p>
        
        <div class="info-eliminacion">
            <p>Esta acción eliminará:</p>
            <ul>
                <li>✗ La empresa y su cuenta de usuario</li>
                <li>✗ Todas las ofertas publicadas por esta empresa</li>
                <li>✗ Todas las relaciones con ciclos formativos</li>
                <li>✗ Todas las solicitudes de alumnos a estas ofertas</li>
            </ul>
            <p class="texto-peligro">⚠️ Esta acción NO se puede deshacer</p>
        </div>
        
        <form action="index.php?menu=ConfirmarEliminarEmpresa" method="POST" class="form-eliminar">
            <input type="hidden" name="id_empresa" value="<?= $empresa->getId() ?>">
            
            <div class="form-actions">
                <a href="index.php?menu=PanelAdmin" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-danger">
                    Eliminar Empresa
                </button>
            </div>
        </form>
    </div>
</main>
<?php $this->stop() ?>