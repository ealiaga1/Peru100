<?php // app/views/inventario/ingredientes/edit.php ?>

<div class="form-container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>inventario/updateIngrediente" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($ingrediente['id_ingrediente']); ?>">
        
        <div class="form-group">
            <label for="nombre_ingrediente">Nombre del Ingrediente:</label>
            <input type="text" id="nombre_ingrediente" name="nombre_ingrediente" value="<?php echo htmlspecialchars($ingrediente['nombre_ingrediente']); ?>" required>
        </div>
        <div class="form-group">
            <label for="costo_unitario">Costo Unitario (S/):</label>
            <input type="number" id="costo_unitario" name="costo_unitario" step="0.01" min="0" value="<?php echo htmlspecialchars($ingrediente['costo_unitario']); ?>" required>
        </div>
        <div class="form-group">
            <label for="id_unidad_medida">Unidad de Medida:</label>
            <select id="id_unidad_medida" name="id_unidad_medida" required>
                <option value="">Seleccione una unidad</option>
                <?php foreach ($unidades_medida as $unidad): ?>
                    <option value="<?php echo htmlspecialchars($unidad['id_unidad']); ?>" <?php echo ($unidad['id_unidad'] == $ingrediente['id_unidad_medida']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($unidad['nombre_unidad']); ?> (<?php echo htmlspecialchars($unidad['abreviatura']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="stock_actual">Stock Actual:</label>
            <input type="number" id="stock_actual" name="stock_actual" step="0.01" min="0" value="<?php echo htmlspecialchars($ingrediente['stock_actual']); ?>" required>
        </div>
        <div class="form-group">
            <label for="stock_minimo">Stock Mínimo de Alerta:</label>
            <input type="number" id="stock_minimo" name="stock_minimo" step="0.01" min="0" value="<?php echo htmlspecialchars($ingrediente['stock_minimo']); ?>" required>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="activo" <?php echo $ingrediente['activo'] ? 'checked' : ''; ?>>
                Activo
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Ingrediente</button>
        <a href="<?php echo BASE_URL; ?>inventario/ingredientes" class="btn btn-secondary">Cancelar</a>
    </form>
</div>