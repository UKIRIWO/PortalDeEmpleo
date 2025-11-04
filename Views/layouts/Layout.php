<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web</title>
    
    <link rel="stylesheet" href="../Public/css/cabecera.css">
    <link rel="stylesheet" href="../Public/css/pie.css">
    
    <?php $this->section('css') ?>
</head>

<body>
    <header>
        <a href="?menu=Inicio">
            <img src="img/logoWorkSphere-sinFondo.png" alt="Logo WorkSphere" class="logoHead">
        </a>
        <nav>
            <?php
            $rol = Login::getRol();
            switch ($rol) {
                case 'admin':
                    $this->insert('partials/_nav_admin');
                    break;
                case 'empresa':
                    $this->insert('partials/_nav_empresa');
                    break;
                case 'alumno':
                    $this->insert('partials/_nav_alumno');
                    break;
                
                default:
                    break;
            }
            ?>
        </nav>
        
        <a href="index.php?menu=Logout"><button class="btnLoginCabecera">Logout</button></a>
    </header>

    <main>
        <?= $this->section('contenido') ?>
    </main>

    <footer>
        <div id="footer-left">
            <div id="foot-L-container-more-links">
                <a href="?menu=Inicio">
                    <img src="img/logoWorkSphere-sinFondo.png" alt="Logo WorkSphere" id="footer-logo">
                </a>
                <ul id="foot-L-container-links">
                    <li><a href="?menu=Contacto" class="footer-link">Contacto</a></li>
                    <li><a href="?menu=Terminos" class="footer-link">Términos de uso</a></li>
                    <li><a href="?menu=Privacidad" class="footer-link">Política de privacidad</a></li>
                </ul>
            </div>
        </div>
        <div id="footer-right">
            <div id="foot-R-container-social">
                <h2>Redes sociales</h2>
                <div id="foot-R-container-social-icons">
                    <a href="https://facebook.com" target="_blank">
                        <img src="img/facebook_logo.png" alt="Facebook" class="social-icon">
                    </a>
                    <a href="https://instagram.com" target="_blank">
                        <img src="img/instagram_logo.png" alt="Instagram" class="social-icon">
                    </a>
                    <a href="https://twitter.com" target="_blank">
                        <img src="img/twitter_logo.png" alt="Twitter" class="social-icon">
                    </a>
                    <a href="https://youtube.com" target="_blank">
                        <img src="img/youtube_logo.png" alt="YouTube" class="social-icon">
                    </a>
                </div>
            </div>
        </div>
    </footer>


    <?php $this->section('js') ?>
</body>

</html>