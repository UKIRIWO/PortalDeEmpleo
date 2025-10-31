window.addEventListener("load", function(){
    const modalAlumno = Modal.crear("modalAlumno", "html/registroAlumno.html", function() {
        document.getElementById("AlumnRegisBtn").onclick = () => {
            this.mostrar();
        }; 
    });

    const modalPrueba = Modal.crear("modalPrueba", "html/registroPrueba.html", function() {
        document.getElementById("btnPrueba").onclick = () => {
            this.mostrar();
        }; 
    });

})


















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