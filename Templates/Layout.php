<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Proyecto</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <!--Así se define una seccion (header, content, footer)-->

    <!--Manolo quiere que esto tenga css y js dentro de la propia plantilla (es decir, hereda css y js)-->
    <?= $this->section('css') ?>
    <?= $this->section('js') ?>
    <?= $this->section('header') ?>
    <?= $this->section('content') ?>
    <?= $this->section('footer') ?>

</body>

</html>