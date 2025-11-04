window.addEventListener("load", function () {


    // Modal para añadir alumno
    const modalAlumno = Modal.crear("modalAlumno", "html/nuevoAlumno.html", function () {

        const botonAgregar = document.getElementById("btnAgregar");
        if (botonAgregar) {
            botonAgregar.onclick = () => modalAlumno.mostrar();
        }


        const btnGuardar = document.getElementById("btnGuardarNuevo");
        const btnCancelar = document.getElementById("btnCancelarNuevo");

        if (btnGuardar) {
            btnGuardar.onclick = () => {
                const nombre = document.getElementById("nuevoNombre").value;
                const email = document.getElementById("nuevoEmail").value;
                const password = document.getElementById("nuevoPassword").value;
                const curriculum = document.getElementById("nuevoCurriculum").files[0] || null;

                if (!nombre || !email || !password) {
                    alert("Completa todos los campos obligatorios");
                    return;
                }

                const formData = new FormData();
                formData.append("nombre", nombre);
                formData.append("email", email);
                formData.append("password", password);
                if (curriculum) formData.append("curriculum", curriculum);

                fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                    method: 'POST',
                    body: formData
                })

                    .then(res => res.json())
                    .then(data => {
                        console.log("Respuesta API POST:", data);
                        modalAlumno.ocultar();

                        const tbody = document.querySelector("#tablaAlumnos tbody");

                        // Crear nueva fila
                        const fila = document.createElement("tr");

                        // Insertar los datos en la fila
                        fila.innerHTML = `
                                <td>${nombre}</td>
                                <td>${email}</td>
                                    <td>
                                        <input type="hidden" class="idAlumno" value="${data.id}">
                                        <button class="editar">Editar</button>
                                        <button class="eliminar">Eliminar</button>
                                    </td>`;
                        tbody.appendChild(fila);
                        tbody.appendChild(fila);
                        asignarEventosFila(fila, nombre, email);


                    })
                    .catch(err => console.error("Error POST alumno:", err));
            };
        }

        if (btnCancelar) {
            btnCancelar.onclick = () => modalAlumno.ocultar();
        }
    });



    // Modal para editar alumno
    const modalEditar = Modal.crear("modalEditar", "html/editarAlumno.html", function () {
        const botonesEditar = document.getElementsByClassName("editar");
        for (let boton of botonesEditar) {
            boton.onclick = () => {

                modalEditar.mostrar();

                const btnGuardarEditar = document.getElementById("btnGuardarEditar");
                if (btnGuardarEditar) {
                    btnGuardarEditar.onclick = () => {
                        const fila = boton.closest("tr");
                        const id = fila.querySelector(".idAlumno").value;

                        const nombre = document.getElementById("editarNombre").value;
                        const email = document.getElementById("editarEmail").value;
                        //Validar los campos
                        

                        const data = {
                            id,
                            nombre,
                            email
                        };

                        fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(data)
                        })

                            .then(res => res.json())
                            .then(data => {
                                console.log("Respuesta API PUT:", data);
                                modalEditar.ocultar();
                            })
                            .catch(err => console.error("Error PUT alumno:", err));
                    };
                }
            };
        }
    });


    // Modal para eliminar alumno
    const modalEliminar = Modal.crear("modalEliminar", "html/eliminarAlumno.html", function () {
        const botonesEliminar = document.getElementsByClassName("eliminar");

        for (let boton of botonesEliminar) {
            boton.onclick = () => {
                const fila = boton.closest("tr");
                const id = fila.querySelector(".idAlumno").value;

                // Coloca el id en un input hidden dentro del modal
                const inputModal = document.getElementById("idAlumnoEliminar");
                if (inputModal) inputModal.value = id;

                modalEliminar.mostrar();

                const btnConfirmar = document.getElementById("btnConfirmarEliminar");
                if (btnConfirmar) {
                    btnConfirmar.onclick = () => {
                        const idEliminar = inputModal.value;

                        fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                            method: 'DELETE',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: idEliminar })
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.mensaje) {
                                    modalEliminar.ocultar();
                                    fila.remove();
                                } else if (data.error) {
                                    alert("Error: " + data.error);
                                }
                            })
                            .catch(err => console.error("Error DELETE alumno:", err));
                    };
                }
            };
        }
    });



    function asignarEventosFila(fila, nombre, email) {
        const btnEditar = fila.querySelector(".editar");
        const btnEliminar = fila.querySelector(".eliminar");

        // Editar
        btnEditar.onclick = () => {
            modalEditar.mostrar();
            const id = fila.querySelector(".idAlumno").value;
            document.getElementById("idAlumnoEditar").value = id;
            document.getElementById("editarNombre").value = nombre;
            document.getElementById("editarEmail").value = email;
        };

        // Eliminar
        btnEliminar.onclick = () => {
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
        };
    }
});