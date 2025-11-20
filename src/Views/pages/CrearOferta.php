<?php $this->layout('layouts/Layout_simple', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>
<div class="bodyForm">




<form action="index.php?menu=CrearOferta" method="POST" class="form-container">
    <h1>Crear Nueva Oferta</h1>

    <label>Título</label>
    <input type="text" name="titulo" required>

    <label>Descripción</label>
    <textarea name="descripcion" required></textarea>

    <label>Fecha Inicio</label>
    <input type="date" name="fecha_inicio" required>

    <label>Fecha Fin</label>
    <input type="date" name="fecha_fin" required>

    <label>Familia</label>
    <select id="selectFamilia" name="familia" required>
        <option value="">Cargando familias...</option>
    </select>

    <label>Ciclo</label>
    <select id="selectCiclo" name="ciclo" disabled required>
        <option value="">Selecciona una familia primero</option>
    </select>

    <button type="submit" class="btn btnVerde">Crear Oferta</button>
    <a href="index.php?menu=Ofertas" class="btn btnRojo">Cancelar</a>
</form>
</div>
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="js/ofertaCiclo.js"></script>
<?php $this->stop() ?>