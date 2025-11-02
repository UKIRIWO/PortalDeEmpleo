window.addEventListener("load", function(){
    const modalAlumno = Modal.crear("modalAlumno", "html/registroAlumno.html", function() {
        document.getElementById("AlumnRegisBtn").onclick = () => {
            modalAlumno.mostrar();
        };
        const modalCamara = Modal.crear("modalCamara", "html/camara.html", function() {
            document.getElementById("btnCamara").onclick = () => {
                modalCamara.mostrar();
                conectarCamara();
            }; 
        }); 
    });
})




function conectarCamara() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captura = document.getElementById('captura');
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


    captura.addEventListener('click', async function () {
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