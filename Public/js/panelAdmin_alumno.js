window.addEventListener("load", function () {
    cargarAlumnos();

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
                        <td>
                            <button class="editar">Editar</button>
                            <button class="eliminar">Eliminar</button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                });
            })
            .catch(err => console.error("Error cargando alumnos:", err));
    }

    // function cargarAlumnos() {
    //     console.log('Iniciando carga de alumnos...');

    //     fetch('/portalDeEmpleo/api/ApiAlumno.php')
    //         .then(res => {
    //             console.log('Respuesta HTTP:', res.status);
    //             return res.json();
    //         })
    //         .then(alumnos => {
    //             console.log('Datos recibidos:', alumnos);
    //             console.log('Número de alumnos:', alumnos.length);

    //             const tbody = document.querySelector("#tablaAlumnos tbody");
    //             console.log('tbody encontrado:', tbody);

    //             tbody.innerHTML = "";

    //             if (alumnos.length === 0) {
    //                 console.log('No hay alumnos para mostrar');
    //                 return;
    //             }

    //             alumnos.forEach((alumno, index) => {
    //                 console.log(`Procesando alumno ${index}:`, alumno);

    //                 const fila = document.createElement("tr");
    //                 fila.innerHTML = `
    //                 <td>${alumno.id}</td>
    //                 <td>${alumno.id_user_fk}</td>
    //                 <td>${alumno.dni}</td>
    //                 <td>${alumno.nombre}</td>
    //                 <td>${alumno.email}</td>
    //                 <td>
    //                     <button class="editar">Editar</button>
    //                     <button class="eliminar">Eliminar</button>
    //                 </td>
    //             `;
    //                 tbody.appendChild(fila);
    //             });

    //             console.log('Filas agregadas:', tbody.children.length);
    //         })
    //         .catch(err => console.error("Error cargando alumnos:", err));
    // }
});