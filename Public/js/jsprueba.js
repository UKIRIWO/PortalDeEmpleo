window.addEventListener("load", function () {

    cargarAlumnos();

    // --- MODAL: Añadir alumno ---
    const modalAlumno = Modal.crear("modalAlumno", "html/nuevoAlumno.html", function () {
        const botonAgregar = document.getElementById("btnAgregar");
        if (botonAgregar) botonAgregar.onclick = () => modalAlumno.mostrar();

        const btnGuardar = document.getElementById("btnGuardarNuevo");
        const btnCancelar = document.getElementById("btnCancelarNuevo");

        if (btnGuardar) {
            btnGuardar.onclick = () => {
                const nombre = document.getElementById("nuevoNombre").value;
                const email = document.getElementById("nuevoEmail").value;
                const password = document.getElementById("nuevoPassword").value;
                const curriculum = document.getElementById("nuevoCurriculum").files[0];
                const fechaNacimiento = document.getElementById("nuevaFecha").value;
                const direccion = document.getElementById("nuevaDireccion").value;
                const telefono = document.getElementById("nuevoTelefono").value;
                const fotoPerfil = document.getElementById("nuevaFotoPerfil").files[0];

                if (!nombre || !email || !password) {
                    alert("Completa todos los campos obligatorios");
                    return;
                }

                const formData = new FormData();
                formData.append("nombre", nombre);
                formData.append("email", email);
                formData.append("password", password);
                formData.append("fecha_nacimiento", fechaNacimiento);
                formData.append("direccion", direccion);
                formData.append("telefono", telefono);
                if (curriculum) formData.append("curriculum", curriculum);
                if (fotoPerfil) formData.append("fotoPerfil", fotoPerfil);

                fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(() => {
                    modalAlumno.ocultar();
                    cargarAlumnos();
                })
                .catch(err => console.error("Error POST alumno:", err));
            };
        }

        if (btnCancelar) btnCancelar.onclick = () => modalAlumno.ocultar();
    });

    // --- FUNCIONES AUXILIARES ---
    function asignarEventosFila(fila) {
        const btnEditar = fila.querySelector(".editar");
        const btnEliminar = fila.querySelector(".eliminar");

        // Editar
        btnEditar.onclick = () => {
            const modalEditar = Modal.crear("modalEditar", "html/editarAlumno.html", function () {
                modalEditar.mostrar();
                document.getElementById("editarNombre").value = fila.querySelector("td:nth-child(1)").textContent;
                document.getElementById("editarEmail").value = fila.querySelector("td:nth-child(2)").textContent;

                const btnGuardarEditar = document.getElementById("btnGuardarEditar");
                btnGuardarEditar.onclick = () => {
                    const data = {
                        id: fila.querySelector(".idAlumno").value,
                        nombre: document.getElementById("editarNombre").value,
                        email: document.getElementById("editarEmail").value,
                        fecha_nacimiento: document.getElementById("editarFechaNacimiento").value,
                        direccion: document.getElementById("editarDireccion").value,
                        telefono: document.getElementById("editarTelefono").value
                    };

                    fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    })
                    .then(res => res.json())
                    .then(() => {
                        modalEditar.ocultar();
                        cargarAlumnos();
                    })
                    .catch(err => console.error("Error PUT alumno:", err));
                };
            });
        };

        // Eliminar
        btnEliminar.onclick = () => {
            const modalEliminar = Modal.crear("modalEliminar", "html/eliminarAlumno.html", function () {
                modalEliminar.mostrar();
                document.getElementById("idAlumnoEliminar").value = fila.querySelector(".idAlumno").value;

                const btnConfirmar = document.getElementById("btnConfirmarEliminar");
                btnConfirmar.onclick = () => {
                    fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: fila.querySelector(".idAlumno").value })
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
            });
        };
    }

    function cargarAlumnos() {
        fetch('/portalDeEmpleo/api/ApiAlumno.php')
            .then(res => res.json())
            .then(alumnos => {
                const tbody = document.querySelector("#tablaAlumnos tbody");
                tbody.innerHTML = "";
                alumnos.forEach(alumno => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td>${alumno.nombre}</td>
                        <td>${alumno.email}</td>
                        <td>
                            <input type="hidden" class="idAlumno" value="${alumno.id}">
                            <button class="editar">Editar</button>
                            <button class="eliminar">Eliminar</button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                    asignarEventosFila(fila);
                });
            })
            .catch(err => console.error("Error cargando alumnos:", err));
    }

});