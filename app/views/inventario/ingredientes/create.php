<?php // app/views/inventario/ingredientes/create.php ?>

<div class="form-container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>inventario/storeIngrediente" method="POST">
        <div class="form-group">
            <label for="nombre_ingrediente">Nombre del Ingrediente:</label>
            <input type="text" id="nombre_ingrediente" name="nombre_ingrediente" required>
        </div>
        <div class="form-group">
            <label for="costo_unitario">Costo Unitario (S/):</label>
            <input type="number" id="costo_unitario" name="costo_unitario" step="0.01" min="0" required>
        </div>
        <div class="form-group">
            <label for="id_unidad_medida">Unidad de Medida:</label>
            <select id="id_unidad_medida" name="id_unidad_medida" required>
                <option value="">Seleccione una unidad</option>
                <?php foreach ($unidades_medida as $unidad): ?>
                    <option value="<?php echo htmlspecialchars($unidad['id_unidad']); ?>">
                        <?php echo htmlspecialchars($unidad['nombre_unidad']); ?> (<?php echo htmlspecialchars($unidad['abreviatura']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="stock_actual">Stock Inicial:</label>
            <input type="number" id="stock_actual" name="stock_actual" step="0.01" min="0" value="0" required>
        </div>
        <div class="form-group">
            <label for="stock_minimo">Stock Mínimo de Alerta:</label>
            <input type="number" id="stock_minimo" name="stock_minimo" step="0.01" min="0" value="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Ingrediente</button>
        <a href="<?php echo BASE_URL; ?>inventario/ingredientes" class="btn btn-secondary">Cancelar</a>
    </form>
</div>