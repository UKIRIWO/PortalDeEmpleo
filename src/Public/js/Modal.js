class Modal {
    constructor(contenedorId, urlContenido, funcionJS) {
        this.contenedorId = contenedorId;
        this.urlContenido = urlContenido;
        this.funcionJS = funcionJS;
        
        this.crearContenedor();
        this.contenedor = document.getElementById(contenedorId);
        this.cargarContenido();
    }

    crearContenedor() {
        const contenedor = document.createElement('div');
        contenedor.id = this.contenedorId;
        document.body.appendChild(contenedor);
    }

    cargarContenido() {
        this.descargaGET(this.urlContenido, (ajx) => {
            this.contenedor.innerHTML = ajx.responseText;
            this.configurarEventosBasicos();
            this.funcionJS();
        });
    }

    descargaGET(url, accion) {
        const ajx = new XMLHttpRequest();
        ajx.open("GET", url, true);
        ajx.onreadystatechange = () => {
            if (ajx.readyState === 4 && ajx.status === 200) {
                accion(ajx);
            }
        };
        ajx.send();
    }

    configurarEventosBasicos() {
        const cerrarBtn = this.contenedor.querySelector('.cerrarModal');
        if (cerrarBtn) {
            cerrarBtn.onclick = () => {
                this.ocultar();
            };
        }
    }

    mostrar() {
        const velo = this.contenedor.querySelector('.velo');
        if (velo) {
            velo.style.display = "flex";
        }
    }

    ocultar() {
        const velo = this.contenedor.querySelector('.velo');
        if (velo) {
            velo.style.display = "none";
        }
    }

    static crear(contenedorId, urlContenido, funcionJS) {
        return new Modal(contenedorId, urlContenido, funcionJS);
    }
}