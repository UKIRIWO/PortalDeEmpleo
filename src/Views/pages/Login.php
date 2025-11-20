<?php $this->layout('layouts/Layout_simple', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>
<div class="bodyForm">
    <div class="form-container">
        <h2>Iniciar Sesión</h2>
        <form action="index.php" method="POST">
            <label for="username">Nombre:</label>
            <input type="text" name="username" id="username" autocomplete="username" required>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" name="accion" value="Login" class="btn-submit">
        </form>

        <div class="register-options">
            <p id="btnPrueba">¿No tienes cuenta?</p>
            <div class="register-buttons">
                <span class="btn-register" id="AlumnRegisBtn">Registrarse como Alumno</span>
                <a href="?menu=RegistroEmpresa" class="btn-register">Registrarse como Empresa</a>
                <a href="?menu=Inicio" class="btn-register">Inicio</a>
            </div>
        </div>
    </div>
</div>
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="js/Modal.js"></script>
<script src="js/login.js"></script>
<script src="js/validator.js"></script>
<script src="js/registroAlumno.js"></script>

<?php $this->stop() ?>