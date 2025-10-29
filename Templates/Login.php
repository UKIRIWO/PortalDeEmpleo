<?php $this->layout('Layout_simple'); ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="css/login.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form action="index.php?menu=Inicio" method="POST">
            <label for="username">Nombre:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" name="accion" value="Login" class="btn-login">
        </form>

        <div class="register-options">
            <p>¿No tienes cuenta?</p>
            <div class="register-buttons">
                <a href="#" class="btn-register">Registrarse como Alumno</a>
                <a href="#" class="btn-register">Registrarse como Empresa</a>
            </div>
        </div>
    </div>
<?php $this->stop() ?>