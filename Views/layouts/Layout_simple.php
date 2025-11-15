<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $this->e($titulo ?? 'Portal de Empleo') ?></title>
    <link rel="stylesheet" href="../Public/css/style.css">
    <?= $this->section('css') ?>
</head>

<body>
    <main>
        <?= $this->section('contenido') ?>
    </main>
    <?= $this->section('js') ?>
</body>

</html>