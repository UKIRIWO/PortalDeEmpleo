<?php $this->layout('Layout_simple'); ?>

<?php $this->start('contenido') ?>
<form action='index.php?menu=Inicio' method='POST'>
    <label for='username'>Nombre: </label>
    <input type='text' name='username'>
    <br>
    <label for='password'>Contraseña: </label>
    <input type='text' name='password'>
    <input type='submit' name='accion' value='Login'>
</form>
<?php $this->stop() ?>