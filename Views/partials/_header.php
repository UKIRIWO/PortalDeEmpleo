<a href="?menu=Inicio">
    <img src="img/logoWorkSphere-sinFondo.png" alt="Logo WorkSphere" class="logoHead">
</a>
<nav>
    <ul>
        

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
<?php if (Login::estaLogeado()): ?>
    <a href="index.php?menu=Logout"><button class="btnLoginCabecera">Logout</button></a>
<?php else: ?>
    <a href="index.php?menu=Login"><button class="btnLoginCabecera">Iniciar sesión</button></a>
<?php endif; ?>


