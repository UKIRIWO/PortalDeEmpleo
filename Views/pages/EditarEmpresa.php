<?php $this->layout('layouts/Layout') ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/editar-empresa.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<main class="editar-empresa-container">
    <h1>✏️ Editar Empresa</h1>
    
    <form action="index.php?menu=ActualizarEmpresa" method="POST" enctype="multipart/form-data" class="form-empresa">
        <input type="hidden" name="id_empresa" value="<?= $empresa->getId() ?>">
        
        <h3>Datos de Usuario</h3>
        <div class="form-row">
            <div class="form-group">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" 
                       value="<?= $this->e($user->getNombreUsuario()) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Cambiar Contraseña:</label>
                <input type="password" id="password" name="password" 
                       placeholder="Dejar vacío para mantener actual">
                <small>Dejar en blanco si no deseas cambiarla</small>
            </div>
        </div>
        
        <h3>Datos de la Empresa</h3>
        <div class="form-group">
            <label for="nombre">Nombre de la Empresa: *</label>
            <input type="text" id="nombre" name="nombre" 
                   value="<?= $this->e($empresa->getNombre()) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="direccion">Dirección: *</label>
            <input type="text" id="direccion" name="direccion" 
                   value="<?= $this->e($empresa->getDireccion()) ?>" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="persona_contacto">Persona de Contacto: *</label>
                <input type="text" id="persona_contacto" name="persona_contacto" 
                       value="<?= $this->e($empresa->getPersonaDeContacto()) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="correo_contacto">Correo de Contacto: *</label>
                <input type="email" id="correo_contacto" name="correo_contacto" 
                       value="<?= $this->e($empresa->getCorreoDeContacto()) ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="telefono_contacto">Teléfono de Contacto: *</label>
            <input type="tel" id="telefono_contacto" name="telefono_contacto" 
                   value="<?= $this->e($empresa->getTelefonoDeContacto()) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="logo">Logo de la Empresa:</label>
            <?php if ($empresa->getLogo()): ?>
                <div class="logo-actual">
                    <p>Logo actual:</p>
                    <img src="../.imagenes/empresas/<?= $this->e($empresa->getLogo()) ?>" 
                         alt="Logo actual" class="logo-preview">
                </div>
            <?php endif; ?>
            <input type="file" id="logo" name="logo" accept="image/*">
            <small>Formatos aceptados: JPG, PNG, GIF (Dejar vacío para mantener el actual)</small>
        </div>
        
        <div class="form-actions">
            <a href="index.php?menu=PanelAdmin" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
    </form>
</main>
<?php $this->stop() ?>