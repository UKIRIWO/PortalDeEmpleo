// este fragmento va en la plantilla base
fetch("url/obtenerToken.php").
    then((response)=>response.json()).
    then((json)=>{
        sessionStorage.setItem("token", json.token)
    })



//------------------------------------------

let f=new FormData(formulario);

fetch("url",
    {
        headers:{Authorization: 'Bearer { '+ sessionStorage.getItem("token") +'}'},
        method:post,
        body:f
    }
)


//el token se genera cuando te logeas
//ahora en los fetch hay que meter el headers ese de arriba
//no puede haber un mismo token en 2 usuarios
//en todas las apis hay que poner leerToken
//en php una vez estés logeado y todo esté correcto entonces se creará el token antes des de hacer el header
//el token se guardará en la BD
//el token se guardará en sessionStorage
//class Security, tiene los metodos generarToken y verificarToken

//Login => CRUD => Ofertas(con php) => Solicitudes(js) => Gestion de las solicitudes (por parte de empresa con js)