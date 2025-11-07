window.addEventListener("load", function () {
    cargarAlumnos();


    // Get
    function cargarAlumnos() {
        fetch('/portalDeEmpleo/api/ApiAlumno.php')
            .then(res => res.json())
            .then(alumnos => {
                const tbody = document.querySelector("#tablaAlumnos tbody");
                tbody.innerHTML = "";
                alumnos.forEach(alumno => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td>${alumno.id}</td>
                        <td>${alumno.id_user}</td>
                        <td>${alumno.dni}</td>
                        <td>${alumno.nombre}</td>
                        <td>${alumno.email}</td>
                        <td class="tdAccion">
                            <input type="hidden" class="idAlumno" value="${alumno.id}">
                            <button class="detalles btnDetalles">Detalles</button>
                            <button class="editar btnEditar">Editar</button>
                            <button class="eliminar btnEliminar">Eliminar</button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                    asignarEventosFila(fila);
                });
            })
            .catch(err => console.error("Error cargando alumnos:", err));
    }








    // Post
    const modalCrear = Modal.crear("modalCrear", "html/nuevoAlumno.html", function () {
        const botonAgregarAlumno = document.getElementById("btnAgregarAlumno");
        botonAgregarAlumno.onclick = () => modalCrear.mostrar();

        const btnGuardarAlumno = document.getElementById("btnGuardarNuevoAlumno");
        const btnCancelarAlumno = document.getElementById("btnCancelarNuevoAlumno");

        btnGuardarAlumno.onclick = () => {

            const username = document.getElementById("nuevoUsername").value;
            const password = document.getElementById("nuevoPassword").value;
            const dni = document.getElementById("nuevoDni").value;
            const email = document.getElementById("nuevoEmail").value;
            const nombre = document.getElementById("nuevoNombre").value;
            const ape1 = document.getElementById("nuevoApe1").value;
            const ape2 = document.getElementById("nuevoApe2").value;
            const curriculum = document.getElementById("nuevoCurriculum").files[0];
            const fechaNacimiento = document.getElementById("nuevaFecha").value;
            const direccion = document.getElementById("nuevaDireccion").value;
            const fotoPerfil = document.getElementById("nuevaFotoPerfil").files[0];

            if (!dni || !nombre || !ape1 || !email || !password) {
                alert("Completa todos los campos obligatorios");
                return;
            }

            const formData = new FormData();
            if (username) formData.append("username", username);
            formData.append("password", password);
            formData.append("dni", dni);
            formData.append("email", email);
            formData.append("nombre", nombre);
            formData.append("ape1", ape1);
            if (ape2) formData.append("ape2", ape2);
            if (curriculum) formData.append("curriculum", curriculum);
            if (fechaNacimiento) formData.append("fecha_nacimiento", fechaNacimiento);
            if (direccion) formData.append("direccion", direccion);
            if (fotoPerfil) formData.append("fotoPerfil", fotoPerfil);

            fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(() => {
                    modalCrear.ocultar();
                    cargarAlumnos();
                })
                .catch(err => console.error("Error POST alumno:", err));
        };
        btnCancelarAlumno.onclick = () => modalCrear.ocultar();
    });


    // --- FUNCIONES AUXILIARES ---
    function asignarEventosFila(fila) {
        const btnEditar = fila.querySelector(".editar");
        const btnEliminar = fila.querySelector(".eliminar");
        const btnDetalles = fila.querySelector(".detalles");

        // EDITAR
        btnEditar.onclick = () => {
            const idAlumno = fila.querySelector(".idAlumno").value;

            fetch(`/portalDeEmpleo/api/ApiAlumno.php?id=${idAlumno}`)
                .then(res => res.json())
                .then(alumno => {
                    const modalEditar = Modal.crear("modalEditar", "html/editarAlumno.html", function () {

                        modalEditar.mostrar();

                        // Rellenar los campos con los datos obtenidos
                        document.getElementById("editarUsername").value = alumno.username || "";
                        document.getElementById("editarDni").value = alumno.dni || "";
                        document.getElementById("editarNombre").value = alumno.nombre || "";
                        document.getElementById("editarApe1").value = alumno.ape1 || "";
                        document.getElementById("editarApe2").value = alumno.ape2 || "";
                        document.getElementById("editarEmail").value = alumno.email || "";
                        document.getElementById("editarFechaNacimiento").value = alumno.fecha_nacimiento || "";
                        document.getElementById("editarDireccion").value = alumno.direccion || "";
                        

                        // Evento del botón Guardar (mantienes tu lógica original)
                        const btnGuardar = document.getElementById("btnGuardarEditar");
                        btnGuardar.onclick = () => {
                            const datosActualizados = {
                                id: alumno.id,
                                password: document.getElementById("editarPassword").value,
                                dni: document.getElementById("editarDni").value,
                                nombre: document.getElementById("editarNombre").value,
                                ape1: document.getElementById("editarApe1").value,
                                ape2: document.getElementById("editarApe2").value,
                                email: document.getElementById("editarEmail").value,
                                fecha_nacimiento: document.getElementById("editarFechaNacimiento").value,
                                direccion: document.getElementById("editarDireccion").value

                            };

                            fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                                method: 'PUT',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify(datosActualizados)
                            })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        alert("Alumno actualizado correctamente");
                                        modalEditar.ocultar();
                                        cargarAlumnos();
                                    } else {
                                        alert("Error: " + (data.error || "Error desconocido"));
                                    }
                                })
                                .catch(err => console.error("Error al actualizar alumno:", err));
                        };
                    });
                })
                .catch(err => console.error("Error al obtener alumno:", err));
        };


        // Eliminar
        btnEliminar.onclick = () => {
            const idAlumno = fila.querySelector(".idAlumno").value;
            const modalEliminar = Modal.crear("modalEliminar", "html/eliminarAlumno.html", function () {
                modalEliminar.mostrar();

                const btnConfirmar = document.getElementById("btnConfirmarEliminar");
                btnConfirmar.onclick = () => {
                    fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: idAlumno })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.mensaje) {
                                alert(data.mensaje);
                                modalEliminar.ocultar();
                                fila.remove();
                            } else if (data.error) {
                                alert("Error: " + data.error);
                            }
                        })
                        .catch(err => console.error("Error DELETE alumno:", err));
                };
                const btnCancelar = document.getElementById("btnCancelarEliminar");
                btnCancelar.onclick = function () { modalEliminar.ocultar() };
            });

        };

        btnDetalles.onclick = () => {
            const idAlumno = fila.querySelector(".idAlumno").value;

            fetch(`/portalDeEmpleo/api/ApiAlumno.php?id=${idAlumno}`)
                .then(res => res.json())
                .then(alumno => {
                    const modalDetalles = Modal.crear("modalDetalles", "html/detallesAlumno.html", function () {
                        modalDetalles.mostrar();

                        // Mostrar los datos del alumno en el HTML
                        let fotoPerfil = document.getElementById("detalleFoto");
                        let rutaFoto = '';

                        if (alumno.foto && alumno.foto.trim() !== '') {
                            rutaFoto = "../.imagenes/alumno/" + alumno.foto;
                        } else {
                            rutaFoto = "../.imagenes/alumno/predeterminada.png";
                        }

                        fotoPerfil.src = rutaFoto;
                        document.getElementById("detalleUsername").textContent = alumno.username || "—";
                        document.getElementById("detalleDni").textContent = alumno.dni || "—";
                        document.getElementById("detalleNombre").textContent = alumno.nombre || "—";
                        document.getElementById("detalleApe1").textContent = alumno.ape1 || "—";
                        document.getElementById("detalleApe2").textContent = alumno.ape2 || "—";
                        document.getElementById("detalleEmail").textContent = alumno.email || "—";
                        document.getElementById("detalleFechaNacimiento").textContent = alumno.fecha_nacimiento || "—";
                        document.getElementById("detalleDireccion").textContent = alumno.direccion || "—";

                        // Botón cerrar
                        document.getElementById("btnCerrarDetalles").onclick = () => modalDetalles.ocultar();
                    });
                })
                .catch(err => console.error("Error al obtener alumno:", err));
        };
    }
});