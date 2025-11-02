<?php $this->layout('Layout_simple'); ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="css/registroEmpresa.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<div class="form-container">
    <h2>Registro de Empresa</h2>

    <form action="registrar_empresa.php" method="POST" enctype="multipart/form-data">
        <h3>Datos de usuario</h3>

        <div class="form-row">
            <div class="inptLabe">
                <label for="username">Nombre de usuario</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="inptLabe">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
        </div>

        <div class="form-row">
            <div class="inptLabe">
                <label for="password2">Repetir contraseña</label>
                <input type="password" id="password2" name="password2" required>
            </div>
        </div>

        <h3>Datos de la empresa</h3>

        <div class="form-row">
            <div class="inptLabe">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" required>
            </div>
            <div class="inptLabe">
                <label for="persona_de_contacto">Persona de contacto</label>
                <input type="text" id="persona_de_contacto" name="persona_de_contacto" required>
            </div>
        </div>

        <div class="form-row">
            <div class="inptLabe">
                <label for="correo_de_contacto">Correo de contacto</label>
                <input type="email" id="correo_de_contacto" name="correo_de_contacto" required>
            </div>
            <div class="inptLabe">
                <label for="telefono_de_contacto">Teléfono de contacto</label>
                <input type="tel" id="telefono_de_contacto" name="telefono_de_contacto" required>
            </div>
        </div>

        <div class="form-row">
            <div class="inptLabe">
                <label for="logo">Logo de la empresa</label>
                <input type="file" id="logo" name="logo" accept="image/*">
            </div>
        </div>

        <input type="submit" value="Registrarse">
    </form>
    <a href="?menu=Login" class="volver-btn">Iniciar de sesión</a>
</div>
<?php $this->stop() ?>