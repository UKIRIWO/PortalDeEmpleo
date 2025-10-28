window.addEventListener("load", function () {
    const boton = document.getElementById("boton");
    const contenedor = document.querySelector("#contenedor");

    function descargarGET(url, accion) {
        const ajx = new XMLHttpRequest();
        ajx.open("GET", url, true);
        ajx.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                accion(this);
            }
        };
        ajx.send();
    }

    boton.onclick = function () {
        descargarGET("datos/alumnos.json", mostrarAlumnos);
    };

    function mostrarAlumnos(ajx) {
        const alumnos = JSON.parse(ajx.responseText);
        const tabla = document.createElement("table"); 
        contenedor.appendChild(tabla);

        const encabezado = document.createElement("tr");
        ["ID", "Nombre", "Apellido", "Email"].forEach(titulo => {
            const th = document.createElement("th");
            th.textContent = titulo;
            encabezado.appendChild(th);
        });

        tabla.appendChild(encabezado);

        alumnos.forEach(alumno => {
            const fila = tabla.insertRow();

            const celdaId = fila.insertCell();
            celdaId.textContent = alumno.id;

            const celdaNombre = fila.insertCell();
            celdaNombre.textContent = alumno.nombre;

            const celdaApellido = fila.insertCell();
            celdaApellido.textContent = alumno.apellido;

            const celdaEmail =  fila.insertCell();
            celdaEmail.textContent = alumno.email;

            tabla.appendChild(fila);
        });
    }
});