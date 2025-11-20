window.addEventListener("load", function () {
    cargarAlumnos();

    const inputBuscar = document.getElementById("bucarAlumno");
    inputBuscar.addEventListener("input", () => {
        buscarAlumnos(inputBuscar.value);
    });

    // ORDENAR COLUMNAS
    document.querySelectorAll("#tablaAlumnos thead th[data-campo]")
        .forEach(th => {
            th.addEventListener("click", () => {
                ordenarPor(th.dataset.campo);
            });
        });
});

// Get
function cargarAlumnos() {
    fetch('/API/ApiAlumno.php', {
        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
    })
        .then(res => res.json())
        .then(alumnos => {
            alumnosCargados = alumnos; // GUARDAR
            pintarTabla(alumnos);
        })
        .catch(err => console.error("Error cargando alumnos:", err));
}

let alumnosCargados = []; // almacenar alumnos actuales
let ordenActual = {};     // guardar si está asc o desc cada th

function pintarTabla(alumnos) {
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
                <button class="detalles btn btnVerde btnDetalles">Detalles</button>
                <button class="editar btn btnAmarillo btnEditar">Editar</button>
                <button class="eliminar btn btnRojo btnEliminar">Eliminar</button>
            </td>
        `;
        tbody.appendChild(fila);
        asignarEventosFila(fila);
    });
}


function ordenarPor(campo) {
    // Alternar asc/desc
    if (!ordenActual[campo] || ordenActual[campo] === "desc") {
        ordenActual[campo] = "asc";
    } else {
        ordenActual[campo] = "desc";
    }

    // Clonar array
    const alumnosOrdenados = [...alumnosCargados];

    alumnosOrdenados.sort((a, b) => {
        let v1 = a[campo];
        let v2 = b[campo];

        // para strings, ordenar sin may/min
        if (typeof v1 === "string" && typeof v2 === "string") {
            v1 = v1.toLowerCase();
            v2 = v2.toLowerCase();
        }

        if (ordenActual[campo] === "asc") {
            return v1 > v2 ? 1 : v1 < v2 ? -1 : 0;
        } else {
            return v1 < v2 ? 1 : v1 > v2 ? -1 : 0;
        }
    });

    // volver a pintar
    pintarTabla(alumnosOrdenados);
}




// Post
const modalCrear = Modal.crear("modalCrear", "html/nuevoAlumno.html", function () {
    const botonAgregarAlumno = document.getElementById("btnAgregarAlumno");
    botonAgregarAlumno.onclick = () => {
        modalCrear.mostrar()

        cargarFamilias();
        // Función para cargar familias
        function cargarFamilias() {
            fetch('/API/AlumnoCargaMasiva.php?action=familias')
                .then(res => res.json())
                .then(familias => {
                    const selectFamilia = document.getElementById("selectFamilia");
                    selectFamilia.innerHTML = '<option value="">-- Selecciona una familia --</option>';

                    familias.forEach(familia => {
                        const option = document.createElement("option");
                        option.value = familia.id;
                        option.textContent = familia.nombre;
                        selectFamilia.appendChild(option);
                    });
                })
                .catch(err => console.error("Error cargando familias:", err));
        }

        // Cuando se selecciona una familia, cargar sus ciclos
        document.getElementById("selectFamilia").addEventListener("change", function () {
            const familiaId = this.value;
            const selectCiclo = document.getElementById("selectCiclo");

            if (!familiaId) {
                selectCiclo.innerHTML = '<option value="">-- Primero selecciona familia --</option>';
                selectCiclo.disabled = true;
                return;
            }

            fetch(`/API/AlumnoCargaMasiva.php?action=ciclos&familia_id=${familiaId}`)
                .then(res => res.json())
                .then(ciclos => {
                    selectCiclo.innerHTML = '<option value="">-- Selecciona un ciclo --</option>';

                    ciclos.forEach(ciclo => {
                        const option = document.createElement("option");
                        option.value = ciclo.id;
                        option.textContent = `${ciclo.nombre} (${ciclo.nivel})`;
                        selectCiclo.appendChild(option);
                    });

                    selectCiclo.disabled = false;
                })
                .catch(err => console.error("Error cargando ciclos:", err));
        });
    };

    const btnGuardarAlumno = document.getElementById("btnGuardarNuevoAlumno");
    const btnCancelarAlumno = document.getElementById("btnCancelarNuevoAlumno");

    btnGuardarAlumno.onclick = async () => {

        // Validar formulario
        if (!Validator.validarFormulario()) {
            return;
        }

        const formData = new FormData();
        formData.append("username", document.getElementById("nuevoUsername").value.trim());
        formData.append("password", document.getElementById("nuevoPassword").value);
        formData.append("dni", document.getElementById("nuevoDni").value.trim().toUpperCase());
        formData.append("email", document.getElementById("nuevoEmail").value.trim());
        formData.append("nombre", document.getElementById("nuevoNombre").value.trim());
        formData.append("ape1", document.getElementById("nuevoApe1").value.trim());

        const ape2 = document.getElementById("nuevoApe2").value.trim();
        if (ape2) formData.append("ape2", ape2);

        const fechaNacimiento = document.getElementById("nuevaFecha").value;
        if (fechaNacimiento) formData.append("fecha_nacimiento", fechaNacimiento);

        const direccion = document.getElementById("nuevaDireccion").value.trim();
        if (direccion) formData.append("direccion", direccion);

        // Estudios
        const cicloId = document.getElementById("selectCiclo").value;
        formData.append("ciclo_id", cicloId);

        const fechaInicio = document.getElementById("fechaInicio").value;
        if (fechaInicio) formData.append("fecha_inicio", fechaInicio);

        const fechaFin = document.getElementById("fechaFin").value;
        if (fechaFin) formData.append("fecha_fin", fechaFin);

        const curriculum = document.getElementById("nuevoCurriculum").files[0];
        if (curriculum) formData.append("curriculum", curriculum);

        const fotoPerfil = document.getElementById("nuevaFotoPerfil").files[0];
        if (fotoPerfil) formData.append("fotoPerfil", fotoPerfil);

        // Enviar

        fetch('/API/ApiAlumno.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(() => {
                modalCrear.ocultar();
                cargarAlumnos();
            })
            .catch(err => console.error("Error al registrar alumno:", err));
    };

    btnCancelarAlumno.onclick = () => modalCrear.ocultar();
});


// --- EDITAR/ELIMINAR/DETALLES ---
function asignarEventosFila(fila) {
    const btnEditar = fila.querySelector(".editar");
    const btnEliminar = fila.querySelector(".eliminar");
    const btnDetalles = fila.querySelector(".detalles");

    // EDITAR (put)
    btnEditar.onclick = () => {
        const idAlumno = fila.querySelector(".idAlumno").value;

        fetch(`API/ApiAlumno.php?id=${idAlumno}`, {
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
        })
            .then(res => res.json())
            .then(alumno => {
                const modalEditar = Modal.crear("modalEditar", "html/editarAlumno.html", function () {

                    modalEditar.mostrar();

                    document.getElementById("editarUsername").value = alumno.username || "";
                    document.getElementById("editarDni").value = alumno.dni || "";
                    document.getElementById("editarNombre").value = alumno.nombre || "";
                    document.getElementById("editarApe1").value = alumno.ape1 || "";
                    document.getElementById("editarApe2").value = alumno.ape2 || "";
                    document.getElementById("editarEmail").value = alumno.email || "";
                    document.getElementById("editarFechaNacimiento").value = alumno.fecha_nacimiento || "";
                    document.getElementById("editarDireccion").value = alumno.direccion || "";



                    const btnGuardar = document.getElementById("btnGuardarEditar");
                    btnGuardar.onclick = async () => {
                        try {

                            const datosActualizados = {
                                id: alumno.id,
                                username: document.getElementById("editarUsername").value,
                                dni: document.getElementById("editarDni").value,
                                nombre: document.getElementById("editarNombre").value,
                                ape1: document.getElementById("editarApe1").value,
                                ape2: document.getElementById("editarApe2").value,
                                email: document.getElementById("editarEmail").value,
                                fecha_nacimiento: document.getElementById("editarFechaNacimiento").value,
                                direccion: document.getElementById("editarDireccion").value
                            };


                            const password = document.getElementById("editarPassword").value;
                            if (password) {
                                datosActualizados.password = password;
                            }


                            const curriculumFile = document.getElementById("editarCurriculum").files[0];
                            if (curriculumFile) {
                                datosActualizados.curriculum = await fileToBase64(curriculumFile);
                            }


                            const fotoFile = document.getElementById("editarFoto").files[0];
                            if (fotoFile) {
                                datosActualizados.foto = await fileToBase64(fotoFile);
                            }


                            const response = await fetch('/API/ApiAlumno.php', {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                                },
                                body: JSON.stringify(datosActualizados)
                            });

                            const data = await response.json();

                            if (data.success) {
                                modalEditar.ocultar();
                                cargarAlumnos();
                            } else {
                                alert("Error: " + (data.error || "Error desconocido"));
                            }

                        } catch (err) {
                            console.error("Error al actualizar alumno:", err);
                            alert("Error al actualizar el alumno");
                        }
                    };
                });
            })
            .catch(err => console.error("Error al obtener alumno:", err));
    };


    function fileToBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
        });
    }



    // Eliminar (DELETE)
    btnEliminar.onclick = () => {
        const idAlumno = fila.querySelector(".idAlumno").value;
        const modalEliminar = Modal.crear("modalEliminar", "html/eliminarAlumno.html", function () {
            modalEliminar.mostrar();

            const btnConfirmar = document.getElementById("btnConfirmarEliminar");
            btnConfirmar.onclick = () => {
                fetch('/API/ApiAlumno.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    },
                    body: JSON.stringify({ id: idAlumno })
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
            const btnCancelar = document.getElementById("btnCancelarEliminar");
            btnCancelar.onclick = function () { modalEliminar.ocultar() };
        });

    };


    // Detalles (GET con ID)
    btnDetalles.onclick = () => {
        const idAlumno = fila.querySelector(".idAlumno").value;

        fetch(`API/ApiAlumno.php?id=${idAlumno}`, {
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
        })
            .then(res => res.json())
            .then(alumno => {
                const modalDetalles = Modal.crear("modalDetalles", "html/detallesAlumno.html", function () {
                    modalDetalles.mostrar();

                    let fotoPerfil = document.getElementById("detalleFoto");
                    let rutaFoto = '';

                    if (alumno.foto && alumno.foto.trim() !== '') {
                        rutaFoto = "/.imagenes/alumno/" + alumno.foto;
                    } else {
                        rutaFoto = "/.imagenes/alumno/predeterminada.png";
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


                    document.getElementById("btnVerCurriculum").onclick = () => {
                        const modalCurriculum = Modal.crear("modalVerCurriculum", "html/curriculumAlumno.html", function () {
                            modalCurriculum.mostrar();

                            // Cojo el curriculum (viene en la respuesta del GET)
                            const curriculumBlob = alumno.curriculum;

                            if (!curriculumBlob || curriculumBlob === null) {
                                alert('Este alumno no tiene curriculum cargado');
                                modalCurriculum.ocultar();
                                return;
                            }

                            // Convierto el BLOB a Base64 y lo muestro en el iframe
                            const iframe = document.getElementById("iframeCurriculum");
                            iframe.src = "data:application/pdf;base64," + curriculumBlob;
                        });
                    };

                    // Cerrar
                    document.getElementById("btnCerrarDetalles").onclick = () => modalDetalles.ocultar();
                });
            })
            .catch(err => console.error("Error al obtener alumno:", err));
    };
}

function buscarAlumnos(nombre) {
    fetch(`API/ApiAlumno.php?nombre=${nombre}`, {
        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
    })
        .then(res => res.json())
        .then(alumnos => {
            const tbody = document.querySelector("#tablaAlumnos tbody");
            tbody.innerHTML = "";

            if (!alumnos || alumnos.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3">No se encontraron alumnos</td></tr>';
                return;
            }

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
        .catch(err => console.error("Error buscando alumnos:", err));
}


// ============================================
// CARGA MASIVA DE ALUMNOS
// ============================================

const modalCargaMasiva = Modal.crear("modalCargaMasiva", "html/cargaMasivaAlumnos.html", function () {
    const btnAgregarVariosAlumnos = document.getElementById("btnAgregarVariosAlumnos");
    btnAgregarVariosAlumnos.onclick = () => {
        modalCargaMasiva.mostrar();
        cargarFamilias();
    };

    let alumnosCSV = [];

    function cargarFamilias() {

        fetch('/API/AlumnoCargaMasiva.php?action=familias', {
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
        })
            .then(res => res.json())
            .then(familias => {
                const selectFamilia = modalCargaMasiva.contenedor.querySelector("#selectFamilia");
                if (!selectFamilia) return console.error("No se encontró el selectFamilia en el modal");
                selectFamilia.innerHTML = '<option value="">-- Selecciona una familia --</option>';

                familias.forEach(familia => {
                    const option = document.createElement("option");
                    option.value = familia.id;
                    option.textContent = familia.nombre;
                    selectFamilia.appendChild(option);
                });
            })
            .catch(err => console.error("Error cargando familias:", err));
    }

    // Cuando se selecciona una familia, cargar sus ciclos
    const selectFamilia = modalCargaMasiva.contenedor.querySelector("#selectFamilia");
    const selectCiclo = modalCargaMasiva.contenedor.querySelector("#selectCiclo");

    selectFamilia.addEventListener("change", function () {
        const familiaId = this.value;

        if (!familiaId) {
            selectCiclo.innerHTML = '<option value="">-- Primero selecciona familia --</option>';
            selectCiclo.disabled = true;
            return;
        }

        fetch(`API/AlumnoCargaMasiva.php?action=ciclos&familia_id=${familiaId}`, {
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
        })
            .then(res => res.json())
            .then(ciclos => {
                selectCiclo.innerHTML = '<option value="">-- Selecciona un ciclo --</option>';

                ciclos.forEach(ciclo => {
                    const option = document.createElement("option");
                    option.value = ciclo.id;
                    option.textContent = `${ciclo.nombre} (${ciclo.nivel})`;
                    selectCiclo.appendChild(option);
                });

                selectCiclo.disabled = false; // ahora sí se habilita
            })
            .catch(err => console.error("Error cargando ciclos:", err));
    });


    // Botón PINTAR ALUMNOS
    const btnPintarAlumnos = modalCargaMasiva.contenedor.querySelector("#btnPintarAlumnos");
    btnPintarAlumnos.addEventListener("click", function () {
        const inputCSV = document.getElementById("inputCSV");
        const file = inputCSV.files[0];

        if (!file) {
            alert("Por favor, selecciona un archivo CSV");
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const contenido = e.target.result;
            alumnosCSV = parsearCSV(contenido);

            if (alumnosCSV.length === 0) {
                alert("El archivo CSV está vacío o mal formateado");
                return;
            }

            pintarTablaAlumnos(alumnosCSV);
        };
        reader.readAsText(file);
    });

    // Parsear CSV (formato: dni,nombre,ape1,correo)
    function parsearCSV(contenido) {
        const lineas = contenido.split('\n').filter(linea => linea.trim() !== '');
        const alumnos = [];

        lineas.forEach((linea, index) => {
            const campos = linea.split(',').map(campo => campo.trim());

            if (campos.length >= 4) {
                alumnos.push({
                    dni: campos[0],
                    nombre: campos[1],
                    ape1: campos[2],
                    correo: campos[3],
                    index: index
                });
            }
        });

        return alumnos;
    }


    function pintarTablaAlumnos(alumnos) {
        const tbody = modalCargaMasiva.contenedor.querySelector("#tbodyAlumnos");
        const container = modalCargaMasiva.contenedor.querySelector("#tablaAlumnosContainer");

        tbody.innerHTML = "";

        alumnos.forEach((alumno, i) => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td style="text-align: center;">
                    <input type="checkbox" class="check-alumno" data-index="${i}" checked>
                </td>
                <td><input type="text" class="input-dni" value="${alumno.dni}" data-index="${i}"></td>
                <td><input type="text" class="input-nombre" value="${alumno.nombre}" data-index="${i}"></td>
                <td><input type="text" class="input-ape1" value="${alumno.ape1}" data-index="${i}"></td>
                <td><input type="text" class="input-correo" value="${alumno.correo}" data-index="${i}"></td>
            `;
            tbody.appendChild(fila);
        });

        container.style.display = "block";
        configurarCheckBox();
    }

    function configurarCheckBox() {
        // Checkbox en header para marcar/desmarcar todos
        const checkTodos = document.getElementById("checkTodos");
        checkTodos.addEventListener("change", function () {
            const checkboxes = document.querySelectorAll(".check-alumno");
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    }

    // Botón LIMPIAR TABLA
    document.getElementById("btnLimpiarTabla").addEventListener("click", function () {
        document.getElementById("tbodyAlumnos").innerHTML = "";
    });

    // Botón SUBIR DATOS
    document.getElementById("btnSubirDatos").addEventListener("click", function () {
        const cicloId = modalCargaMasiva.contenedor.querySelector("#selectCiclo").value;
        const fechaInicio = modalCargaMasiva.contenedor.querySelector("#fechaInicio").value || null;
        const fechaFin = modalCargaMasiva.contenedor.querySelector("#fechaFin").value || null;


        if (!cicloId) {
            alert("Por favor, selecciona un ciclo formativo");
            return;
        }

        // Solo los alumnos marcados
        const alumnosSeleccionados = [];
        const checkboxes = document.querySelectorAll(".check-alumno");

        checkboxes.forEach((checkBox) => {
            if (checkBox.checked) {
                const fila = checkBox.closest("tr");
                alumnosSeleccionados.push({
                    dni: fila.querySelector(".input-dni").value.trim(),
                    nombre: fila.querySelector(".input-nombre").value.trim(),
                    ape1: fila.querySelector(".input-ape1").value.trim(),
                    correo: fila.querySelector(".input-correo").value.trim()
                });
            }
        });

        if (alumnosSeleccionados.length === 0) {
            alert("No hay alumnos seleccionados para subir");
            return;
        }

        subirAlumnos(alumnosSeleccionados, cicloId, fechaInicio, fechaFin);
    });

    function subirAlumnos(alumnos, cicloId, fechaInicio, fechaFin) {
        const datos = {
            alumnos: alumnos,
            ciclo_id: cicloId,
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin
        };

        fetch('/API/AlumnoCargaMasiva.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            body: JSON.stringify(datos)
        })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    if (response.errores.length === 0) {
                        mostrarModalExito(response);
                    } else {
                        mostrarModalErrores(response);
                    }
                } else {
                    alert("Error: " + response.error);
                }
            })
            .catch(err => {
                console.error("Error al subir alumnos:", err);
                alert("Error al procesar la solicitud: " + err.message);
            });
    }

    // Modal de éxito
    function mostrarModalExito(response) {
        const modalExito = Modal.crear("modalExito", "html/exitoCargaMasiva.html", function () {
            modalExito.mostrar();

            document.getElementById("mensajeExito").textContent =
                `Se han guardado correctamente ${response.exitosos} de ${response.total} alumnos`;

            // Enseño los usuarios y contraseñas creadas
            const tablaCredenciales = document.getElementById("tablaCredenciales");
            const tbody = tablaCredenciales.querySelector("tbody");
            tbody.innerHTML = "";

            response.credenciales.forEach(credencial => {
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${credencial.alumno.nombre} ${credencial.alumno.ape1}</td>
                    <td>${credencial.username}</td>
                    <td>${credencial.password}</td>
                `;
                tbody.appendChild(fila);
            });

            // Botón "Subir más alumnos"
            document.getElementById("btnSubirMas").onclick = () => {
                modalExito.ocultar();
                reiniciarFormulario();
                cargarAlumnos();
            };

            // Botón "Volver al panel"
            document.getElementById("btnVolverPanel").onclick = () => {
                modalExito.ocultar();
                modalCargaMasiva.ocultar();
                cargarAlumnos(); // Recargar tabla principal
            };
        });
    }


    function mostrarModalErrores(response) {
        const modalErrores = Modal.crear("modalErrores", "html/erroresCargaMasiva.html", function () {
            modalErrores.mostrar();

            document.getElementById("mensajeErrores").textContent =
                `Se han subido ${response.exitosos} de ${response.total} alumnos correctamente`;

            // Pinto solo los alumnos con errores
            pintarTablaErrores(response.errores);

            // Botón "Reintentar"
            document.getElementById("btnReintentar").onclick = () => {
                const alumnosReintento = obtenerAlumnosEditadosErrores();
                const cicloId = modalCargaMasiva.contenedor.querySelector("#selectCiclo").value;
                const fechaInicio = modalCargaMasiva.contenedor.querySelector("#fechaInicio").value || null;
                const fechaFin = modalCargaMasiva.contenedor.querySelector("#fechaFin").value || null;

                modalErrores.ocultar();
                subirAlumnos(alumnosReintento, cicloId, fechaInicio, fechaFin);
            };

            // Botón "Ignorar errores"
            document.getElementById("btnIgnorarErrores").onclick = () => {
                modalErrores.ocultar();
                modalCargaMasiva.ocultar();
                cargarAlumnos();
            };
        });
    }

    function pintarTablaErrores(errores) {
        const tbody = document.getElementById("tbodyAlumnosErrores");
        tbody.innerHTML = "";

        errores.forEach((error, i) => {
            const fila = document.createElement("tr");
            fila.classList.add("error-row");
            fila.innerHTML = `
                <td style="text-align: center;">
                    <input type="checkbox" class="check-error" data-index="${i}" checked>
                </td>
                <td><input type="text" class="input-dni-error" value="${error.alumno.dni}" data-index="${i}"></td>
                <td><input type="text" class="input-nombre-error" value="${error.alumno.nombre}" data-index="${i}"></td>
                <td><input type="text" class="input-ape1-error" value="${error.alumno.ape1}" data-index="${i}"></td>
                <td><input type="text" class="input-correo-error" value="${error.alumno.correo}" data-index="${i}"></td>
                <td style="color: red; font-size: 12px;">${error.error}</td>
            `;
            tbody.appendChild(fila);
        });

        configurarCheckBoxErorres();
    }

    function configurarCheckBoxErorres() {
        document.getElementById("checkTodosErrores").addEventListener("change", function () {
            const checks = document.querySelectorAll(".check-error");
            checks.forEach(checkBox => checkBox.checked = this.checked);
        });
    }

    // Obtenengo los alumnos editados de la tabla de errores
    function obtenerAlumnosEditadosErrores() {
        const alumnosEditados = [];
        const checkboxes = document.querySelectorAll(".check-error");

        checkboxes.forEach(checkBox => {
            if (checkBox.checked) {
                const fila = checkBox.closest("tr");
                alumnosEditados.push({
                    dni: fila.querySelector(".input-dni-error").value.trim(),
                    nombre: fila.querySelector(".input-nombre-error").value.trim(),
                    ape1: fila.querySelector(".input-ape1-error").value.trim(),
                    correo: fila.querySelector(".input-correo-error").value.trim()
                });
            }
        });

        return alumnosEditados;
    }

    // Reinicio formulario (mantener CSV y tabla)
    function reiniciarFormulario() {
        document.getElementById("selectFamilia").value = "";
        document.getElementById("selectCiclo").innerHTML = '<option value="">-- Primero selecciona familia --</option>';
        document.getElementById("selectCiclo").disabled = true;
        document.getElementById("fechaInicio").value = "";
        document.getElementById("fechaFin").value = "";
    }
});
