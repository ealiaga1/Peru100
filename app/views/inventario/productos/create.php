<?php // app/views/inventario/productos/create.php ?>

<div class="form-container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>inventario/storeProducto" method="POST">
        <div class="form-group">
            <label for="nombre_producto">Nombre del Producto:</label>
            <input type="text" id="nombre_producto" name="nombre_producto" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción (Opcional):</label>
            <textarea id="descripcion" name="descripcion" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="precio_venta">Precio de Venta (S/):</label>
            <input type="number" id="precio_venta" name="precio_venta" step="0.01" min="0" required>
        </div>
        <div class="form-group">
            <label for="id_categoria">Categoría:</label>
            <select id="id_categoria" name="id_categoria" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo htmlspecialchars($categoria['id_categoria']); ?>">
                        <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tipo_producto">Tipo de Producto (para Comandas):</label>
            <select id="tipo_producto" name="tipo_producto" required>
                <option value="">Seleccione un tipo</option>
                <option value="Plato">Plato (para Cocina)</option>
                <option value="Bebida">Bebida (para Bar)</option>
                <option value="Postre">Postre</option>
                <option value="Otro">Otro</option>
            </select>
        </div>
        <div class="form-group">
            <label for="stock_actual">Stock Inicial (para ventas directas):</label>
            <input type="number" id="stock_actual" name="stock_actual" step="0.01" min="0" value="0" required>
        </div>
        <div class="form-group">
            <label for="id_unidad_medida">Unidad de Venta:</label>
            <select id="id_unidad_medida" name="id_unidad_medida" required>
                <option value="">Seleccione una unidad</option>
                <?php foreach ($unidades_medida as $unidad): ?>
                    <option value="<?php echo htmlspecialchars($unidad['id_unidad']); ?>">
                        <?php echo htmlspecialchars($unidad['nombre_unidad']); ?> (<?php echo htmlspecialchars($unidad['abreviatura']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Producto</button>
        <a href="<?php echo BASE_URL; ?>inventario/productos" class="btn btn-secondary">Cancelar</a>
    </form>
</div>