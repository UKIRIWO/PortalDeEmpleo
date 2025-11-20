document.addEventListener('DOMContentLoaded', () => {

    fetch('/API/ApiEstadisticas.php', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(res => res.json())
    .then(data => {

        // === TARJETAS ===
        document.getElementById("cardTotalOfertas").textContent = data.totalOfertas ?? 0;
        document.getElementById("cardOfertasActivas").textContent = data.ofertasActivas ?? 0;
        document.getElementById("cardTotalSolicitudes").textContent = data.totalSolicitudes ?? 0;

        // === GRAFICO ESTADO ===
        if (data.solicitudesPorEstado) {
            new Chart(document.getElementById('graficoEstado'), {
                type: 'pie',
                data: {
                    labels: data.solicitudesPorEstado.labels,
                    datasets: [{
                        data: data.solicitudesPorEstado.data
                    }]
                }
            });
        }

        // === GRAFICO OFERTA ===
        if (data.solicitudesPorOferta) {
            new Chart(document.getElementById('graficoOferta'), {
                type: 'bar',
                data: {
                    labels: data.solicitudesPorOferta.labels,
                    datasets: [{
                        label: 'Solicitudes',
                        data: data.solicitudesPorOferta.data
                    }]
                }
            });
        }

        // === GRAFICO TOTALES ===
        if (data.totalOfertas !== undefined && data.totalSolicitudes !== undefined) {
            new Chart(document.getElementById('graficoTotales'), {
                type: 'doughnut',
                data: {
                    labels: ['Total Ofertas', 'Total Solicitudes'],
                    datasets: [{
                        data: [data.totalOfertas, data.totalSolicitudes]
                    }]
                }
            });
        }

    })
    .catch(err => console.error("Error cargando estadísticas:", err));
});
