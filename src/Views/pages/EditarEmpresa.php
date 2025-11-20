<?php $this->layout('layouts/Layout_simple', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>

<div class="bodyForm">


<form action="index.php?menu=EditarEmpresa" method="POST" enctype="multipart/form-data" class="form-container" id="formEditarEmpresa">
    <h1 class="titulo-principal">Editar Empresa</h1>
    <input type="hidden" name="id_empresa" value="<?= $empresa->getId() ?>">

    <h3 class="subtitulo">Datos de Usuario</h3>
    <div class="form-row">
        <div class="inptLabe">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username"
                value="<?= $user->getNombreUsuario() ?>" required>
        </div>

        <div class="inptLabe">
            <label for="password">Cambiar Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Dejar vacío para mantener actual">
        </div>
    </div>

    <h3 class="subtitulo">Datos de la Empresa</h3>
    <div class="inptLabe">
        <label for="nombre">Nombre de la Empresa:</label>
        <input type="text" id="nombre" name="nombre"
            value="<?= $empresa->getNombre() ?>" required>
    </div>

    <div class="inptLabe">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion"
            value="<?= $empresa->getDireccion() ?>" required>
    </div>

    <div class="form-row">
        <div class="inptLabe">
            <label for="persona_de_contacto">Persona de Contacto:</label>
            <input type="text" id="persona_de_contacto" name="persona_de_contacto"
                value="<?= $empresa->getPersonaDeContacto() ?>" required>
        </div>

        <div class="inptLabe">
            <label for="correo_de_contacto">Correo de Contacto:</label>
            <input type="email" id="correo_de_contacto" name="correo_de_contacto"
                value="<?= $empresa->getCorreoDeContacto() ?>" required>
        </div>
    </div>

    <div class="inptLabe">
        <label for="telefono_de_contacto">Teléfono de Contacto:</label>
        <input type="tel" id="telefono_de_contacto" name="telefono_de_contacto"
            value="<?= $empresa->getTelefonoDeContacto() ?>" required>
    </div>

    <div class="inptLabe">

        <label for="logo">Logo de la Empresa:</label>

        <?php
        $logo = $empresa->getLogo();
        $rutaBase = "../.imagenes/empresa/";

        //si la imagen de la empresa existe se guarda esa ruta si no, se guarda la ruta de la imagen predeterminada
        if ($logo && file_exists($rutaBase . $logo)) {
            $ruta = $rutaBase . $logo;
        } else {
            $ruta = $rutaBase . "predeterminada.png";
        }
        ?>

        <div class="logo-actual">
            <p>Logo actual:</p>
            <img src="<?= $ruta ?>"
                alt="Logo actual" class="logo-preview">
        </div>

        <input type="file" id="logo" name="logo" accept="image/*">
    </div>

    <div class="acciones-formulario">
        <a href="index.php?menu=PanelAdmin" class="btnAzul volver-btn">Volver</a>
        <button type="submit" class="btn btnVerde">Guardar Cambios</button>
    </div>
</form>
</div>
<?php $this->stop() ?>