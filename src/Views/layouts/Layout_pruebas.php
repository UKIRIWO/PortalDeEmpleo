<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/logoWorkSphere.png">
    <title><?= $this->e($titulo ?? 'Portal de Empleo') ?></title>
    
    <link rel="stylesheet" href="css/style2.css">
    
    <?= $this->section('css') ?>
</head>

<body>
    <main>
        <?= $this->section('contenido') ?>
    </main>
    <?= $this->section('js') ?>
</body>

</html>