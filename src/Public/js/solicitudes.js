window.addEventListener("load", function () {
    const rol = getRolFromHTML();
    const tablaSolicitudes = document.querySelectorAll('.tablaSolicitudes tbody');

    if (!tablaSolicitudes) return;

    tablaSolicitudes.forEach(tbody => {
        const filas = tbody.querySelectorAll('tr');

        filas.forEach(fila => {

            if (rol === 'admin' || rol === 'empresa') { // Admin o Empresa
                asignarEventosFilaEmpresa(fila);
            } else if (rol === 'alumno') { // Alumno
                asignarEventosFilaAlumno(fila);
            }
        });
    })

    function getRolFromHTML() {
        // Cojo el rol según se cargue la página
        if (document.querySelector('.btnAceptar')) return 'empresa'; // Empresa o Admin
        if (document.querySelector('.corazon')) return 'alumno'; // Alumno
        return null;
    }

    // Empresa: Eventos
    function asignarEventosFilaEmpresa(fila) {
        const idSolicitud = fila.querySelector(".idSolicitud")?.value;
        const idAlumno = fila.querySelector(".idAlumno")?.value;

        // Ver CV
        const btnVerCV = fila.querySelector(".btnVerCV");
        if (btnVerCV) {
            btnVerCV.onclick = () => {
                fetch(`API/ApiAlumno.php?id=${idAlumno}`, {
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.curriculum) {
                            alert("Este alumno no tiene curriculum cargado");
                            return;
                        }

                        const modalId = "modalVerCV_" + idSolicitud;
                        const modalCV = Modal.crear(modalId, "html/verCVSolicitud.html", function () {

                            const modalCVHTML = document.getElementById(modalId);

                            modalCV.mostrar();

                            modalCVHTML.querySelector(".iframeCurriculum").src =
                                "data:application/pdf;base64," + data.curriculum;
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Error al cargar el curriculum");
                    });
            };
        }


        // Aceptar
        const btnAceptar = fila.querySelector(".btnAceptar");
        if (btnAceptar && !btnAceptar.disabled) {

            const modalId = "modalAceptar_" + idSolicitud;

            const modalAceptar = Modal.crear(modalId, "html/aceptarSolicitud.html", function () {

                const modalAceptarHTML = document.getElementById(modalId);

                // abrir modal al pulsar el botón
                btnAceptar.onclick = () => {
                    modalAceptar.mostrar();
                };

                // confirmar
                modalAceptarHTML.querySelector(".btnConfirmarAceptar").onclick = () => {
                    cambiarEstadoSolicitud(idSolicitud, 'aceptada', () => {
                        modalAceptar.ocultar();
                        location.reload();
                    });
                };

                // cancelar
                modalAceptarHTML.querySelector(".btnCancelarAceptar").onclick = () => {
                    modalAceptar.ocultar();
                };
            });
        }


        // Rechazar
        const btnRechazar = fila.querySelector(".btnRechazar");
        if (btnRechazar && !btnRechazar.disabled) {

            const modalId = "modalRechazar_" + idSolicitud;

            const modalRechazar = Modal.crear(modalId, "html/rechazarSolicitud.html", function () {

                const modalRechazarHTML = document.getElementById(modalId);

                // abrir modal al pulsar
                btnRechazar.onclick = () => {
                    modalRechazar.mostrar();
                };

                // confirmar
                modalRechazarHTML.querySelector(".btnConfirmarRechazar").onclick = () => {
                    cambiarEstadoSolicitud(idSolicitud, 'rechazada', () => {
                        modalRechazar.ocultar();
                        location.reload();
                    });
                };

                // cancelar
                modalRechazarHTML.querySelector(".btnCancelarRechazar").onclick = () => {
                    modalRechazar.ocultar();
                };
            });
        }

    }

    function cambiarEstadoSolicitud(id, estado, callback) {
        fetch('/API/ApiSolicitud.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            body: JSON.stringify({ id, estado })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    callback();
                } else {
                    alert("Error: " + (data.error || 'Error desconocido'));
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("Error al procesar la solicitud");
            });
    }

    // Alumno: Eventos
    function asignarEventosFilaAlumno(fila) {
        const idSolicitud = fila.querySelector(".idSolicitud")?.value;

        // Corazón
        const corazon = fila.querySelector(".corazon");
        if (corazon) {
            corazon.onclick = () => {
                const tieneFavorito = corazon.classList.contains('favorito') ? 0 : 1;

                fetch('/API/ApiSolicitud.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    },
                    body: JSON.stringify({ id: idSolicitud, favorito: tieneFavorito })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {

                            // Cambiar visualmente antes de recargar la página
                            corazon.classList.toggle('favorito');
                            corazon.textContent = tieneFavorito ? '♥' : '♡';
                            location.reload();
                        } else {

                            alert("Error: " + (data.error || 'Error desconocido'));
                        }
                    })
                    .catch(err => {
                        console.error("Error al actualizar favorito:", err);
                        alert("Error de red al actualizar favorito");
                    });
            };
        }

        // Eliminar
        const modalEliminar = Modal.crear("modalEliminarSolicitud_" + idSolicitud, "html/eliminarSolicitud.html", function () {
            const modalEliminarHTML = document.getElementById("modalEliminarSolicitud_" + idSolicitud);
            const btnEliminar = fila.querySelector(".btnEliminarSolicitud");

            btnEliminar.onclick = () => {
                modalEliminar.mostrar();
            };

            modalEliminarHTML.querySelector(".btnConfirmarEliminarSolicitud").onclick = () => {
                fetch('/API/ApiSolicitud.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    },
                    body: JSON.stringify({ id: idSolicitud })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            modalEliminar.ocultar();
                            fila.remove(); // Elimino la fila
                            // Si no quedan filas
                            const tbody = document.querySelector('.tablaSolicitudes tbody');
                            if (tbody.children.length === 0) {
                                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:40px;">No tienes solicitudes realizadas</td></tr>';
                            }
                        } else {
                            alert("Error: " + (data.error || 'Error desconocido'));
                        }
                    })
                    .catch(err => {
                        console.error("Error al eliminar:", err);
                        alert("Error de red al eliminar solicitud");
                    });
            };

            modalEliminarHTML.querySelector(".btnCancelarEliminarSolicitud").onclick = () => {
                modalEliminar.ocultar();
            };
        });
    }
});