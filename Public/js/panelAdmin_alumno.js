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


    // --- EDITAR/ELIMINAR/DETALLES ---
    function asignarEventosFila(fila) {
        const btnEditar = fila.querySelector(".editar");
        const btnEliminar = fila.querySelector(".eliminar");
        const btnDetalles = fila.querySelector(".detalles");

        // EDITAR (put)
        btnEditar.onclick = () => {
            const idAlumno = fila.querySelector(".idAlumno").value;

            fetch(`/portalDeEmpleo/api/ApiAlumno.php?id=${idAlumno}`)
                .then(res => res.json())
                .then(alumno => {
                    const modalEditar = Modal.crear("modalEditar", "html/editarAlumno.html", function () {

                        modalEditar.mostrar();

                        // Relleno los input con los datos del alumno
                        document.getElementById("editarUsername").value = alumno.username || "";
                        document.getElementById("editarDni").value = alumno.dni || "";
                        document.getElementById("editarNombre").value = alumno.nombre || "";
                        document.getElementById("editarApe1").value = alumno.ape1 || "";
                        document.getElementById("editarApe2").value = alumno.ape2 || "";
                        document.getElementById("editarEmail").value = alumno.email || "";
                        document.getElementById("editarFechaNacimiento").value = alumno.fecha_nacimiento || "";
                        document.getElementById("editarDireccion").value = alumno.direccion || "";


                        // Botón guardar
                        const btnGuardar = document.getElementById("btnGuardarEditar");
                        btnGuardar.onclick = async () => {
                            try {
                                // JSON con los datos
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

                                // Password
                                const password = document.getElementById("editarPassword").value;
                                if (password) {
                                    datosActualizados.password = password;
                                }

                                // Curriculum (Base64)
                                const curriculumFile = document.getElementById("editarCurriculum").files[0];
                                if (curriculumFile) {
                                    datosActualizados.curriculum = await fileToBase64(curriculumFile);
                                }

                                // Foto (Base64)
                                const fotoFile = document.getElementById("editarFoto").files[0];
                                if (fotoFile) {
                                    datosActualizados.foto = await fileToBase64(fotoFile);
                                }

                                // Enviar con PUT
                                const response = await fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                                    method: 'PUT',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify(datosActualizados)
                                });

                                const data = await response.json();

                                if (data.success) {
                                    // alert(data.mensaje);
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

        // Función auxiliar para convertir archivo a Base64
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
                    fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: idAlumno })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.mensaje) {
                                // alert(data.mensaje);
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


                        document.getElementById("btnVerCurriculum").onclick = () => {
                            const modalCurriculum = Modal.crear("modalVerCurriculum", "html/curriculumAlumno.html", function () {
                                modalCurriculum.mostrar();

                                // Obtener el curriculum del alumno (viene en la respuesta del GET)
                                const curriculumBlob = alumno.curriculum;

                                if (!curriculumBlob || curriculumBlob === null) {
                                    alert('Este alumno no tiene curriculum cargado');
                                    modalCurriculum.ocultar();
                                    return;
                                }

                                // Convertir el BLOB a Base64 y mostrarlo en el iframe
                                const iframe = document.getElementById("iframeCurriculum");
                                iframe.src = "data:application/pdf;base64," + curriculumBlob;
                            });
                        };

                        // Botón cerrar
                        document.getElementById("btnCerrarDetalles").onclick = () => modalDetalles.ocultar();
                    });
                })
                .catch(err => console.error("Error al obtener alumno:", err));
        };
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

        // Cargar familias al abrir el modal
        function cargarFamilias() {
            fetch('/portalDeEmpleo/api/AlumnoCargaMasiva.php?action=familias')
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

            fetch(`/portalDeEmpleo/api/AlumnoCargaMasiva.php?action=ciclos&familia_id=${familiaId}`)
                .then(res => res.json())
                .then(ciclos => {
                    selectCiclo.innerHTML = '<option value="">-- Selecciona un ciclo --</option>';

                    ciclos.forEach(ciclo => {
                        const option = document.createElement("option");
                        option.value = ciclo.id;
                        option.textContent = '${ciclo.nombre} (${ciclo.nivel})';
                        selectCiclo.appendChild(option);
                    });

                    selectCiclo.disabled = false;
                })
                .catch(err => console.error("Error cargando ciclos:", err));
        });

        // Botón PINTAR ALUMNOS
        document.getElementById("btnPintarAlumnos").addEventListener("click", function () {
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
                        ape2: campos[4] || null,
                        index: index
                    });
                }
            });

            return alumnos;
        }

        // Pintar tabla de alumnos
        function pintarTablaAlumnos(alumnos) {
            const tbody = document.getElementById("tbodyAlumnos");
            const container = document.getElementById("tablaAlumnosContainer");

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
            asignarEventosTabla();
        }

        // Eventos de la tabla
        function asignarEventosTabla() {
            // Checkbox en header para marcar/desmarcar todos
            const checkTodos = document.getElementById("checkTodos");
            checkTodos.addEventListener("change", function () {
                const checkboxes = document.querySelectorAll(".check-alumno");
                checkboxes.forEach(cb => cb.checked = this.checked);
            });

            // Sincronizar datos editados con el array alumnosCSV
            const inputs = document.querySelectorAll("#tbodyAlumnos input[type='text']");
            inputs.forEach(input => {
                input.addEventListener("blur", function () {
                    const index = parseInt(this.dataset.index);
                    const field = this.className.replace('input-', '');
                    alumnosCSV[index][field] = this.value.trim();
                });
            });
        }

        // Botón LIMPIAR TABLA
        document.getElementById("btnLimpiarTabla").addEventListener("click", function () {
            document.getElementById("tbodyAlumnos").innerHTML = "";
            document.getElementById("tablaAlumnosContainer").style.display = "none";
            alumnosCSV = [];
        });

        // Botón SUBIR DATOS
        document.getElementById("btnSubirDatos").addEventListener("click", function () {
            const cicloId = document.getElementById("selectCiclo").value;
            const fechaInicio = document.getElementById("fechaInicio").value || null;
            const fechaFin = document.getElementById("fechaFin").value || null;

            // Validaciones
            if (!cicloId) {
                alert("Por favor, selecciona un ciclo formativo");
                return;
            }

            // Obtener solo los alumnos marcados
            const alumnosSeleccionados = [];
            const checkboxes = document.querySelectorAll(".check-alumno");

            checkboxes.forEach((cb, i) => {
                if (cb.checked) {
                    // Obtener datos actualizados de los inputs
                    const fila = cb.closest("tr");
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

            // Enviar datos a la API
            subirAlumnos(alumnosSeleccionados, cicloId, fechaInicio, fechaFin);
        });

        // Función para subir alumnos
        function subirAlumnos(alumnos, cicloId, fechaInicio, fechaFin) {
            const datos = {
                alumnos: alumnos,
                ciclo_id: cicloId,
                fecha_inicio: fechaInicio,
                fecha_fin: fechaFin
            };

            fetch('/portalDeEmpleo/api/AlumnoCargaMasiva.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            })
                .then(res => {
                    // Verificar si la respuesta es JSON válida
                    const contentType = res.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") !== -1) {
                        return res.json();
                    } else {
                        // Si no es JSON, leer como texto para debug
                        return res.text().then(text => {
                            console.error("Respuesta no JSON:", text);
                            throw new Error("La API no devolvió JSON válido");
                        });
                    }
                })
                .then(response => {
                    if (response.success) {
                        if (response.errores.length === 0) {
                            // Todos se subieron correctamente
                            mostrarModalExito(response);
                        } else {
                            // Algunos con errores
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

                // Mostrar credenciales generadas
                const tablaCredenciales = document.getElementById("tablaCredenciales");
                const tbody = tablaCredenciales.querySelector("tbody");
                tbody.innerHTML = "";

                response.credenciales.forEach(cred => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                    <td>${cred.alumno.nombre} ${cred.alumno.ape1}</td>
                    <td>${cred.username}</td>
                    <td>${cred.password}</td>
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

        // Modal de errores
        function mostrarModalErrores(response) {
            const modalErrores = Modal.crear("modalErrores", "html/erroresCargaMasiva.html", function () {
                modalErrores.mostrar();

                document.getElementById("mensajeErrores").textContent =
                    `Se han subido ${response.exitosos} de ${response.total} alumnos correctamente`;

                // Pintar solo los alumnos con errores
                pintarTablaErrores(response.errores);

                // Botón "Reintentar" - vuelve a intentar subir solo los erróneos
                document.getElementById("btnReintentar").onclick = () => {
                    const alumnosReintento = obtenerAlumnosEditadosErrores();
                    const cicloId = document.getElementById("selectCiclo").value;
                    const fechaInicio = document.getElementById("fechaInicio").value || null;
                    const fechaFin = document.getElementById("fechaFin").value || null;

                    modalErrores.ocultar();
                    subirAlumnos(alumnosReintento, cicloId, fechaInicio, fechaFin);
                };

                // Botón "Ignorar errores"
                document.getElementById("btnIgnorarErrores").onclick = () => {
                    modalErrores.ocultar();
                    modalCargaMasiva.ocultar();
                    cargarAlumnos(); // Recargar tabla principal
                };
            });
        }

        // Pintar tabla de errores
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

            // Checkbox todos en errores
            document.getElementById("checkTodosErrores").addEventListener("change", function () {
                const checks = document.querySelectorAll(".check-error");
                checks.forEach(cb => cb.checked = this.checked);
            });
        }

        // Obtener alumnos editados de la tabla de errores
        function obtenerAlumnosEditadosErrores() {
            const alumnosEditados = [];
            const checkboxes = document.querySelectorAll(".check-error");

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const fila = cb.closest("tr");
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

        // Reiniciar formulario (mantener CSV y tabla)
        function reiniciarFormulario() {
            document.getElementById("selectFamilia").value = "";
            document.getElementById("selectCiclo").innerHTML = '<option value="">-- Primero selecciona familia --</option>';
            document.getElementById("selectCiclo").disabled = true;
            document.getElementById("fechaInicio").value = "";
            document.getElementById("fechaFin").value = "";
            // NO limpiar el CSV ni la tabla
        }
    });

});