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
                $this->insert('partials/_nav_invitado');
                break;
        }
        ?>
    </ul>
</nav>
<?php if (Login::estaLogeado()): ?>
    <a href="index.php?menu=Logout" class="btnAzul btn">Logout</a>
<?php else: ?>
    <a href="index.php?menu=Login" class="btnAzul btn">Iniciar sesión</a>
<?php endif; ?>


