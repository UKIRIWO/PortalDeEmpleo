<?php $this->layout('layouts/Layout', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>
<section class="contenedorContacto">
    <div class="tarjetaContacto">
        <h1 class="tituloPrincipal">Contáctanos</h1>
        <p class="mensajeAdvertencia">
            ¿Tienes alguna pregunta, sugerencia o necesitas soporte? Rellena el formulario y nos pondremos en contacto contigo lo antes posible.
        </p>

        <form class="formularioContacto">
            <div class="inptLabe">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" placeholder="Tu nombre y apellidos" required>
            </div>
            
            <div class="inptLabe">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
            </div>

            <div class="inptLabe">
                <label for="asunto">Asunto</label>
                <input type="text" id="asunto" name="asunto" placeholder="Motivo de la consulta" required>
            </div>

            <div class="inptLabe">
                <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" rows="6" placeholder="Escribe tu mensaje aquí..." required></textarea>
            </div>
            
            <input type="submit" value="Enviar Mensaje" class="btn btnAzul">
        </form>

        <div class="infoContactoAdicional">
            <h3 class="subtitulo">Información Adicional</h3>
            <p><strong>Soporte General:</strong> soporte@worksphere.es</p>
            <p><strong>Atención Empresas:</strong> empresas@worksphere.es</p>
            <p><strong>Teléfono:</strong> +34 900 123 456 (Lunes a Viernes, 9:00 - 18:00)</p>
        </div>
    </div>
</section>
<?php $this->stop() ?>