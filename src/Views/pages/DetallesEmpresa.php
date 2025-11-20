<?php $this->layout('layouts/Layout_simple', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>
<section id="detalles-empresa" class="detalle-empresa-container">
    <h1 class="titulo-principal">Detalles de la Empresa</h1>

    <div class="tarjeta-empresa">
        <?php
        $logo = $empresa->getLogo();

        $rutaBase = $_SERVER['DOCUMENT_ROOT'] . '/.imagenes/empresa/';


        //si la imagen de la empresa existe se guarda esa ruta si no, se guarda la ruta de la imagen predeterminada
        if ($logo && file_exists($rutaBase . $logo)) {
            $ruta = '/.imagenes/empresa/' . $logo;
        } else {
            $ruta = '/.imagenes/empresa/predeterminada.png';
        }
        ?>

        <div class="contenedor-logo">
            <img src="<?= $ruta ?>"
                alt="Logo de <?= $this->e($empresa->getNombre()) ?>"
                class="logo-empresa">
        </div>

        <div class="info-empresa">
            <h2 class="nombre-empresa"><?= $this->e($empresa->getNombre()) ?></h2>

            <div class="grid-detalles">
                <div class="detalle">
                    <strong>ID Empresa:</strong>
                    <span><?= $this->e($empresa->getId()) ?></span>
                </div>
                <div class="detalle">
                    <strong>Usuario (ID):</strong>
                    <span><?= $this->e($empresa->getIdUserFk()) ?></span>
                </div>
                <div class="detalle">
                    <strong>Nombre de Usuario:</strong>
                    <span><?= $this->e($user->getNombreUsuario()) ?></span>
                </div>
                <div class="detalle">
                    <strong>Dirección:</strong>
                    <span><?= $this->e($empresa->getDireccion()) ?></span>
                </div>
                <div class="detalle">
                    <strong>Persona de Contacto:</strong>
                    <span><?= $this->e($empresa->getPersonaDeContacto()) ?></span>
                </div>
                <div class="detalle">
                    <strong>Correo de Contacto:</strong>
                    <span><?= $this->e($empresa->getCorreoDeContacto()) ?></span>
                </div>
                <div class="detalle">
                    <strong>Teléfono de Contacto:</strong>
                    <span><?= $this->e($empresa->getTelefonoDeContacto()) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="acciones">
        <a href="index.php?menu=PanelAdmin">
            <button class="btnAzul">Volver al Panel</button>
        </a>
    </div>
</section>
<?php $this->stop() ?>