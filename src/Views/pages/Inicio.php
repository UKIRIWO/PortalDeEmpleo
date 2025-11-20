<?php $this->layout('layouts/Layout', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>
<section class="seccionPrincipal">
    <div class="contenidoPrincipal">
        <h1 class="tituloPrincipal">Conecta Talento, Impulsa Carreras</h1>
        <p class="mensajeAdvertencia">
            WorkSphere es el portal de empleo definitivo donde las mejores empresas encuentran a los alumnos más prometedores. Tu futuro comienza aquí.
        </p>


        <div class="acciones">

            <a href="index.php?men=Login" class="btn btnAzul botonLlamada">Buscar Ofertas Ahora</a>


            <a href="index.php?menu=RegistroEmpresa" class="btn btnVerde botonLlamada">Registrar mi Empresa</a>
        </div>
    </div>
    <div class="imagenPrincipal">

        <img src="/img/fondoInicio.png" alt="FotoInicio" class="imagenPrincipalBloque" />
    </div>
</section>


<section class="propuestaValor">
    <div class="tituloSeccion">
        <h2 class="nombreEmpresa">La Ventaja WorkSphere</h2>
        <p class="infoBusqueda">Un portal hecho a medida para el sector educativo y laboral.</p>
    </div>

    <div class="rejillaDetalles">

        <div class="tarjeta">
            <strong class="iconoAdvertencia"></strong>
            <h3 class="nombreEmpresa">Oportunidades Exclusivas</h3>
            <p>Accede a ofertas de empleo y prácticas validadas por empresas líderes, enfocadas en tu perfil académico.</p>
        </div>


        <div class="tarjeta">
            <strong class="iconoAdvertencia"></strong>
            <h3 class="nombreEmpresa">Talento Filtrado</h3>
            <p>Encuentra perfiles de alumnos listos para empezar, con habilidades y conocimientos frescos.</p>
        </div>

        <div class="tarjeta">
            <strong class="iconoAdvertencia"></strong>
            <h3 class="nombreEmpresa">Gestión Simple</h3>
            <p>Publica ofertas, gestiona solicitudes y contacta candidatos desde un panel de administración intuitivo.</p>
        </div>
    </div>
</section>


<section class="estadisticasConfianza">
    <div class="tituloSeccion">
        <h2 class="nombreEmpresa">Nuestros Resultados Hablan</h2>
    </div>
    <div class="resumenEstadisticas">
        <div class="tarjeta">
            <h3 class="nombreEmpresa">1.200+</h3>
            <p>Alumnos Registrados</p>
        </div>
        <div class="tarjeta">
            <h3 class="nombreEmpresa">250+</h3>
            <p>Empresas Colaboradoras</p>
        </div>
        <div class="tarjeta">
            <h3 class="nombreEmpresa">4.000+</h3>
            <p>Ofertas Publicadas</p>
        </div>
    </div>
</section>

<?php $this->stop() ?>