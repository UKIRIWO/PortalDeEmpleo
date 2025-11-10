<?php $this->layout('layouts/Layout') ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/styles.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
    <h1>Detalles de la Empresa</h1>
    
    <div class="empresa-card">
        <?php if ($empresa->getLogo()): ?>
            <div class="logo-container">
                <img src="../.imagenes/empresa/<?= $this->e($empresa->getLogo()) ?>" 
                     alt="Logo de <?= $this->e($empresa->getNombre()) ?>" 
                     class="empresa-logo">
            </div>
        <?php endif; ?>
        
        <div class="info-empresa">
            <h2><?= $this->e($empresa->getNombre()) ?></h2>
            
            <div class="detalles-grid">
                <div class="detalle-item">
                    <strong>ID:</strong>
                    <span><?= $this->e($empresa->getId()) ?></span>
                </div>
                
                <div class="detalle-item">
                    <strong>ID Usuario:</strong>
                    <span><?= $this->e($empresa->getIdUserFk()) ?></span>
                </div>
                
                <div class="detalle-item">
                    <strong>Usuario:</strong>
                    <span><?= $this->e($user->getNombreUsuario()) ?></span>
                </div>
                
                <div class="detalle-item">
                    <strong>Dirección:</strong>
                    <span><?= $this->e($empresa->getDireccion()) ?></span>
                </div>
                
                <div class="detalle-item">
                    <strong>Persona de Contacto:</strong>
                    <span><?= $this->e($empresa->getPersonaDeContacto()) ?></span>
                </div>
                
                <div class="detalle-item">
                    <strong>Correo de Contacto:</strong>
                    <span><?= $this->e($empresa->getCorreoDeContacto()) ?></span>
                </div>
                
                <div class="detalle-item">
                    <strong>Teléfono de Contacto:</strong>
                    <span><?= $this->e($empresa->getTelefonoDeContacto()) ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="acciones">
        <a href="index.php?menu=PanelAdmin">
            ← Volver al Panel
        </a>
    </div>
<?php $this->stop() ?>