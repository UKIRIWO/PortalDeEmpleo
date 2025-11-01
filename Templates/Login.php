<?php $this->layout('Layout_simple'); ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="css/login.css">
<link rel="stylesheet" href="css/registroAlumno.css">
<link rel="stylesheet" href="css/modal.css">
<link rel="stylesheet" href="css/camara.css">
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
            <p id="btnPrueba">¿No tienes cuenta?</p>
            <div class="register-buttons" >
                <span class="btn-register" id="AlumnRegisBtn">Registrarse como Alumno</span>
                <a href="?menu=Registro" class="btn-register">Registrarse como Empresa</a>
            </div>
        </div>
    </div>
    <div id="modalAlumno"></div>
    <div id="modalCamara"></div>
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="js/Modal.js"></script>
<script src="js/registroAlumno.js"></script>
<?php $this->stop() ?>