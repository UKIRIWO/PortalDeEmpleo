<a href="?menu=Inicio">
    <img src="img/logoWorkSphere-sinFondo.png" alt="Logo WorkSphere" class="logoHead">
</a>
<nav>
    <ul>
        <li><a href="?menu=Inicio">Inicio</a></li>
        <li><a href="?menu=Ofertas">Ofertas</a></li>

        <?php
        use Helpers\Login;
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
    </ul>
</nav>

<a href="index.php?menu=Logout"><button class="btnLoginCabecera">Logout</button></a>