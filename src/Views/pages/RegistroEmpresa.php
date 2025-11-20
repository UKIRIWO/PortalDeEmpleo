<?php $this->layout('layouts/Layout_simple', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>
<div class="bodyForm" id="bodyRegistroEmpresa">
    <div class="form-container">
        <h2>Registro de Empresa</h2>

        <?php if (isset($exito)): ?>
            <div class="mensaje-exito">
                <?= $exito ?>
            </div>
        <?php endif; ?>

        <form action="index.php?menu=RegistroEmpresa" method="POST" enctype="multipart/form-data">
            <h3>Datos de usuario</h3>

            <div class="form-row">
                <div class="inptLabe">
                    <label for="username">Nombre de usuario</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($datos_anteriores['username'] ?? '') ?>" required>
                    <?php if (isset($errores['username'])): ?>
                        <span class="error"><?= $errores['username'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="inptLabe">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                    <?php if (isset($errores['password'])): ?>
                        <span class="error"><?= $errores['password'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="inptLabe">
                    <label for="password2">Repetir contraseña</label>
                    <input type="password" id="password2" name="password2" required>
                </div>
            </div>

            <h3>Datos de la empresa</h3>

            <div class="form-row">
                <div class="inptLabe">
                    <label for="nombreEmpresa">Nombre de la empresa</label>
                    <input type="text" id="nombreEmpresa" name="nombreEmpresa" value="<?= htmlspecialchars($datos_anteriores['nombreEmpresa'] ?? '') ?>" required>
                    <?php if (isset($errores['nombreEmpresa'])): ?>
                        <span class="error"><?= $errores['nombreEmpresa'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="inptLabe">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($datos_anteriores['direccion'] ?? '') ?>" required>
                    <?php if (isset($errores['direccion'])): ?>
                        <span class="error"><?= $errores['direccion'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="inptLabe">
                    <label for="persona_de_contacto">Persona de contacto</label>
                    <input type="text" id="persona_de_contacto" name="persona_de_contacto" value="<?= htmlspecialchars($datos_anteriores['persona_de_contacto'] ?? '') ?>" required>
                    <?php if (isset($errores['persona_de_contacto'])): ?>
                        <span class="error"><?= $errores['persona_de_contacto'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="inptLabe">
                    <label for="correo_de_contacto">Correo de contacto</label>
                    <input type="email" id="correo_de_contacto" name="correo_de_contacto" value="<?= htmlspecialchars($datos_anteriores['correo_de_contacto'] ?? '') ?>" required>
                    <?php if (isset($errores['correo_de_contacto'])): ?>
                        <span class="error"><?= $errores['correo_de_contacto'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="inptLabe">
                    <label for="telefono_de_contacto">Teléfono de contacto</label>
                    <input type="tel" id="telefono_de_contacto" name="telefono_de_contacto" value="<?= htmlspecialchars($datos_anteriores['telefono_de_contacto'] ?? '') ?>" required>
                    <?php if (isset($errores['telefono_de_contacto'])): ?>
                        <span class="error"><?= $errores['telefono_de_contacto'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="inptLabe">
                    <label for="logo">Logo de la empresa</label>
                    <input type="file" id="logo" name="logo" accept="image/*">
                    <?php if (isset($errores['logo'])): ?>
                        <span class="error"><?= $errores['logo'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <input type="submit" value="Registrarse">
        </form>

        <div id="btnVolverEmpresa">
            <?php
            // si no tienes rol se pinta un botón para volver a la página de inicio
            // si eres admin significa que estás accediendo desde panel de administración por lo que el botón es para volver al panelAdmin
            use Helpers\Login;

            $rol = Login::getRol();
            if ($rol == 'admin') { ?>
                <a href="?menu=PanelAdmin" class="volver-btn">Volver al panel de administración</a>
            <?php } else { ?>
                <a href="?menu=Login" class="volver-btn">Iniciar de sesión</a>
            <?php } ?>
        </div>
    </div>
</div>

<?php $this->stop() ?>