window.addEventListener("load", function () {
    const modalAlumno = Modal.crear("modalAlumno", "html/registroAlumno.html", function () {

        document.getElementById("AlumnRegisBtn").onclick = () => {
            modalAlumno.mostrar();
            cargarFamilias();
        };


        document.getElementById("cerrarModalAlumno").onclick = () => {
            modalAlumno.ocultar();
        };


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



        // Variable global para guardar la foto de la cámara
        let fotoCapturada = null;

        // Modal de cámara
        const modalCamara = Modal.crear("modalCamara", "html/camara.html", function () {
            document.getElementById("btnCamara").onclick = () => {
                modalCamara.mostrar();
                setTimeout(() => conectarCamara(), 100); // Espero un poco para que el DOM esté listo
            };

            const cerrarCamara = document.querySelector("#veloCamara .cerrarModal");
            if (cerrarCamara) {
                cerrarCamara.onclick = () => modalCamara.ocultar();
            }
        });

        // Función para conectar cámara y echar foto
        function conectarCamara() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const captura = document.getElementById('captura');

            if (!video || !canvas || !captura) {
                console.error("Elementos de cámara no encontrados");
                return;
            }

            const constraints = {
                audio: false,
                video: { width: 600, height: 450 }
            };

            // Iniciar cámara
            async function init() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia(constraints);
                    video.srcObject = stream;
                } catch (e) {
                    console.error("Error al acceder a la cámara:", e);
                }
            }

            init();

            // Capturar foto
            captura.onclick = async function () {
                const context = canvas.getContext('2d');
                canvas.width = 300;
                canvas.height = 300;
                context.drawImage(video, 150, 75, 300, 300, 0, 0, 300, 300);

                // Pasamos de Canvas a Blob
                fotoCapturada = await new Promise(resolve => canvas.toBlob(resolve, "image/png"));

                // Creamos File desde Blob para poder asignarlo al input
                const file = new File([fotoCapturada], "foto_camara.png", { type: "image/png" });

                // Asignar al input file del formulario principal
                const inputFoto = document.getElementById("nuevaFotoPerfil");
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                inputFoto.files = dataTransfer.files;
            };
        }

        

        // Submit del formulario
        const form = document.getElementById("formAlumno");
        form.onsubmit = async function (e) {
            e.preventDefault();

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

            // Opcionales
            const ape2 = document.getElementById("nuevoApe2").value.trim();
            if (ape2) formData.append("ape2", ape2);

            const fechaNacimiento = document.getElementById("nuevaFecha").value;
            if (fechaNacimiento) formData.append("fecha_nacimiento", fechaNacimiento);

            const direccion = document.getElementById("nuevaDireccion").value.trim();
            if (direccion) formData.append("direccion", direccion);

            // Estudios (obligatorios)
            const cicloId = document.getElementById("selectCiclo").value;
            formData.append("ciclo_id", cicloId);

            const fechaInicio = document.getElementById("fechaInicio").value;
            if (fechaInicio) formData.append("fecha_inicio", fechaInicio);

            const fechaFin = document.getElementById("fechaFin").value;
            if (fechaFin) formData.append("fecha_fin", fechaFin);

            // Archivos
            const curriculum = document.getElementById("nuevoCurriculum").files[0];
            if (curriculum) formData.append("curriculum", curriculum);

            const fotoPerfil = document.getElementById("nuevaFotoPerfil").files[0];
            if (fotoPerfil) formData.append("fotoPerfil", fotoPerfil);

            // Enviar a la API
            fetch('/API/ApiAlumno.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "index.php?menu=Login";
                    }
                })
                .catch(err => console.error("Error al registrar alumno:", err));

        };
    });
});