<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Proyecto</title>
    <link rel="stylesheet" href="../Public/css/cabecera.css">
    <link rel="stylesheet" href="../Public/css/pie.css">
</head>

<body>
    <header>
        <a href="?menu=Inicio"><img src="img/logoWorkSphere-sinFondo.png" alt="Logo" class="logoHead"></a>
        <nav>
            <nav>
                <ul>
                    <li><a href="?menu=Inicio">Inicio</a></li>
                    <li><a href="?menu=OfertaAlumno">OfertaAlumno</a></li>
                </ul>
            </nav>
        </nav>
        <a href="index.php?menu=Logout"><button class="btnLoginCabecera">Logout</button></a>
    </header>

    <?= $this->section('css') ?>
    <?= $this->section('js') ?>
    <?= $this->section('contenido') ?>

    
    <footer>
        <div id="footer-left">
            <div id="foot-L-container-more-links">
                <a href="?menu=Inicio"><img src="img/logoWorkSphere-sinFondo.png" alt="" id="footer-logo"></a>
                <ul id="foot-L-container-links">
                    <li><a href="" class="footer-link">Contacto</a></li>
                    <li><a href="" class="footer-link">Términos de uso</a></li>
                    <li><a href="" class="footer-link">Política de privacidad</a></li>
                </ul>
            </div>
        </div>
        <div id="footer-right">
            <div id="foot-R-container-social">
                <h2>Redes sociales</h2>
                <div id="foot-R-container-social-icons">
                    <a href=""><img src="img/facebook_logo.png" alt="" class="social-icon"></a>
                    <a href=""><img src="img/instagram_logo.png" alt="" class="social-icon"></a>
                    <a href=""><img src="img/twitter_logo.png" alt="" class="social-icon"></a>
                    <a href=""><img src="img/youtube_logo.png" alt="" class="social-icon"></a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>