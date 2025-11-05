<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web</title>
    
    <link rel="stylesheet" href="../Public/css/style.css">
    
    <?php $this->section('css') ?>
</head>

<body>
    <header>
        <?= $this->insert('partials/_header');?>
    </header>

    <main>
        <?= $this->section('contenido') ?>
    </main>

    <footer>
        <?= $this->insert('partials/_footer');?>
    </footer>

    <?= $this->section('js') ?>
</body>

</html>