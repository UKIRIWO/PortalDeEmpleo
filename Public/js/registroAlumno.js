window.addEventListener("load", function(){
    const modalAlumno = Modal.crear("modalAlumno", "html/registroAlumno.html", function() {
        document.getElementById("AlumnRegisBtn").onclick = () => {
            this.mostrar();
        };
        const modalCamara = Modal.crear("modalCamara", "html/camara.html", function() {
            document.getElementById("btnCamara").onclick = () => {
                this.mostrar();
                conectarCamara();
            }; 
        }); 
    });
})




function conectarCamara() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const snap = document.getElementById('snap');
    const form = document.forms[0];

    const constraints = {
        audio: false,
        video: { width: 600, height: 450 }
    };

    async function init() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = stream;
        } catch (e) {
            console.error("Error al acceder a la cámara:", e);
        }
    }

    init();


    snap.addEventListener('click', async function () {
        const context = canvas.getContext('2d');
        const canva = document.getElementById("canvas");
        canva.width = 300;
        canva.height = 300;
        context.drawImage(video, 150, 75, 300, 300, 0, 0, 300, 300);

        const imageBlob = await new Promise(resolve => canvas.toBlob(resolve, "image/png"));

        const datos = new FormData(form);
        datos.append("foto", imageBlob, "fotico.png");

        fetch("php/guardarImagen.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.text())
            .then(texto => console.log(texto));
    });
}













// window.addEventListener("load", function () {
//     cargarContenido("html/registroAlumno.html");

//     function cargarContenido(url) {
//         descargaGET(url, function (ajx) {
//             contenido.innerHTML = ajx.responseText;
            
//         });
//     }

//     function descargaGET(url, accion) {
//         const ajx = new XMLHttpRequest();
//         ajx.open("GET", url, true);
//         ajx.onreadystatechange = function () {
//             if (this.readyState === 4 && this.status === 200) {
//                 accion(ajx);
//                 cargarModal();
//             }
//         };
//         ajx.send();
//     }

//     function cargarModal() {
//         const registerButton = document.getElementById("regisBtn");
//         const cerrarModBtn = document.getElementById("cerrarModalAlumno");
//         const velo = document.getElementById("veloRegistroAlumno");
//         registerButton.onclick = function () {
//             velo.style.display = "flex";
//         }

//         cerrarModBtn.onclick = function () {
//             velo.style.display = "none";
//         }
//     }
// })