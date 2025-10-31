<?php $this->layout('Layout_simple'); ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="css/registroEmpresa.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<div class="form-container">
        <h2>Registro de Alumno</h2>

        <form action="registrar_alumno.php" method="POST">

            <h3>Datos de usuario</h3>

            <label for="username">Nombre de usuario</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <label for="password2">Repetir contraseña</label>
            <input type="password" id="password2" name="password2" required>

            <h3>Datos personales</h3>

            <label for="dni">DNI</label>
            <input type="text" id="dni" name="dni" maxlength="9" required>

            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="ape1">Primer apellido</label>
            <input type="text" id="ape1" name="ape1" required>

            <label for="ape2">Segundo apellido</label>
            <input type="text" id="ape2" name="ape2">

            <label for="fecha_nacimiento">Fecha de nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

            <label for="direccion">Dirección</label>
            <input type="text" id="direccion" name="direccion" required>

            <label for="curriculum">Currículum (URL o ruta)</label>
            <input type="text" id="curriculum" name="curriculum">

            <input type="submit" value="Registrarse">
        </form>
    </div>
<?php $this->stop() ?>