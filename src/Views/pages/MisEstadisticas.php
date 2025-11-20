<?php $this->layout('layouts/Layout', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>
<div class="estadisticas-container">

    <h1 class="titulo-estadisticas">Mis Estadísticas</h1>

    <!-- Tarjetas resumen -->
    <section class="resumen-estadisticas">
        <div class="card">
            <h3>Total Ofertas</h3>
            <p id="cardTotalOfertas">...</p>
        </div>

        <div class="card">
            <h3>Ofertas Activas</h3>
            <p id="cardOfertasActivas">...</p>
        </div>

        <div class="card">
            <h3>Total Solicitudes</h3>
            <p id="cardTotalSolicitudes">...</p>
        </div>
    </section>

    <!-- Gráficos -->
    <section class="grafica-section">
        <h2>Solicitudes por Estado</h2>
        <canvas id="graficoEstado"></canvas>
    </section>

    <section class="grafica-section">
        <h2>Solicitudes por Oferta</h2>
        <canvas id="graficoOferta"></canvas>
    </section>

    <section class="grafica-section">
        <h2>Totales Empresa</h2>
        <canvas id="graficoTotales"></canvas>
    </section>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/misEstadisticas.js"></script>

<?php $this->stop() ?>
