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
    const modalCrearAlumno = Modal.crear("modalCrearAlumno", "html/nuevoAlumno.html", function () {
        const botonAgregarAlumno = document.getElementById("btnAgregarAlumno");
        botonAgregarAlumno.onclick = () => modalCrearAlumno.mostrar();

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
                    modalCrearAlumno.ocultar();
                    cargarAlumnos();
                })
                .catch(err => console.error("Error POST alumno:", err));
        };
        btnCancelarAlumno.onclick = () => modalCrearAlumno.ocultar();
    });


    // --- FUNCIONES AUXILIARES ---
    function asignarEventosFila(fila) {
        const btnEditar = fila.querySelector(".editar");
        const btnEliminar = fila.querySelector(".eliminar");

        // Editar
        btnEditar.onclick = () => {
            const modalEditar = Modal.crear("modalEditar", "html/editarAlumno.html", function () {
                modalEditar.mostrar();
                // document.getElementById("editarIdAlumno").value = fila.querySelector(".idAlumnoEditar").textContent;
                // document.getElementById("editarIdUser").value = fila.querySelector(".id_userEditar").textContent;
                // document.getElementById("editarDniAlumno").value = fila.querySelector(".dniEditar").textContent;
                // document.getElementById("editarNombreAlumno").value = fila.querySelector(".nombreEditar").textContent;
                // document.getElementById("editarEmailAlumno").value = fila.querySelector(".emailEditar").textContent;
                

                const btnGuardarEditar = document.getElementById("btnGuardarEditar");
                btnGuardarEditar.onclick = () => {
                    const data = {
                        id: fila.querySelector(".idAlumno").value,
                        username: document.getElementById("editarUsername").value,
                        password: document.getElementById("editarPassword").value,
                        nombre: document.getElementById("editarNombre").value,
                        ape1: document.getElementById("editarApe1").value,
                        ape2: document.getElementById("editarAp2").value,
                        email: document.getElementById("editarEmail").value,
                        fecha_nacimiento: document.getElementById("editarFechaNacimiento").value,
                        direccion: document.getElementById("editarDireccion").value,
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
            const idAlumno = fila.querySelector(".idAlumno").value;
            console.log(idAlumno);
            const modalEliminar = Modal.crear("modalEliminar", "html/eliminarAlumno.html", function () {
                modalEliminar.mostrar();

                const btnConfirmar = document.getElementById("btnConfirmarEliminar");
                btnConfirmar.onclick = () => {
                    fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: idAlumno})
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
                btnCancelar.onclick = function(){modalEliminar.ocultar()};
            });
            
        };
    }
});