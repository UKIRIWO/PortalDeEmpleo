<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $this->e($titulo ?? 'Portal de Empleo') ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="img/logoWorkSphere.png">
    <?= $this->section('css') ?>
</head>

<body>
    <main>
        <?= $this->section('contenido') ?>
    </main>
    <?= $this->section('js') ?>
</body>

</html>