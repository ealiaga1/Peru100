<?php // app/views/inventario/unidades_medida/create.php ?>

<div class="form-container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>inventario/storeUnidadMedida" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre de Unidad:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="abreviatura">Abreviatura:</label>
            <input type="text" id="abreviatura" name="abreviatura" required maxlength="10">
        </div>
        <button type="submit" class="btn btn-primary">Guardar Unidad</button>
        <a href="<?php echo BASE_URL; ?>inventario/unidadesMedida" class="btn btn-secondary">Cancelar</a>
    </form>
</div>